<?php

namespace App\Http\Controllers;

use Milon\Barcode\DNS1D;
use App\Models\Product;
use App\Models\Category;
use App\Models\Brand;
use App\Models\Unit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProductController extends Controller
{


    public function __construct()
    {
        $this->middleware('auth'); // Asegura que el usuario esté autenticado
    
        // Verificar que el usuario tenga el rol adecuado
        $this->middleware(function ($request, $next) {
            if (!Auth::user()->hasRole('Administrador')) {
                // Redirigir a la ruta dashboard si el usuario no tiene el rol adecuado
                return redirect()->route('dashboard')->with('error', 'No tienes permisos para acceder a esta sección.');
            }
    
            return $next($request);
        });
    }


    public function index(Request $request)
{
    $query = Product::with(['category', 'brand', 'unit']);

    if ($request->filled('name')) {
        $query->where('name', 'like', '%' . $request->name . '%');
    }

    if ($request->filled('category_id')) {
        $query->where('category_id', $request->category_id);
    }

    if ($request->filled('brand_id')) {
        $query->where('brand_id', $request->brand_id);
    }

    if ($request->filled('min_price')) {
        $query->where('price', '>=', $request->min_price);
    }

    if ($request->filled('max_price')) {
        $query->where('price', '<=', $request->max_price);
    }

    $products = $query->paginate(10)->appends($request->all());
    $categories = Category::all();
    $brands = Brand::all();

    return view('products.index', compact('products', 'categories', 'brands'));
}
   
    public function create()
    {
        $categories = Category::all();
        $brands = Brand::all();
        $units = Unit::all();
        return view('products.create', compact('categories', 'brands', 'units'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'description' => 'nullable',
            'price' => 'required|numeric',
            'stock' => 'required|integer',
            'barcode' => 'required|unique:products',
            'category_id' => 'required|exists:categories,id',
            'brand_id' => 'required|exists:brands,id',
            'unit_id' => 'required|exists:units,id',
        ]);

        Product::create($request->all());

        return redirect()->route('products.index')->with('success', 'Product created successfully.');
    }

    public function show(Product $product)
    {
        return view('products.show', compact('product'));
    }

    public function edit(Product $product)
    {
        $categories = Category::all();
        $brands = Brand::all();
        $units = Unit::all();
        return view('products.edit', compact('product', 'categories', 'brands', 'units'));
    }

    public function update(Request $request, Product $product)
    {
        $request->validate([
            'name' => 'required',
            'description' => 'nullable',
            'price' => 'required|numeric',
            'stock' => 'required|integer',
            'barcode' => 'required|unique:products,barcode,' . $product->id,
            'category_id' => 'required|exists:categories,id',
            'brand_id' => 'required|exists:brands,id',
            'unit_id' => 'required|exists:units,id',
        ]);

        $product->update($request->all());

        return redirect()->route('products.index')->with('success', 'Product updated successfully.');
    }

    public function destroy(Product $product)
    {
        $product->delete();

        return redirect()->route('products.index')->with('success', 'Product deleted successfully.');
    }

    public function printBarcode(Product $product)
    {
        return view('products.printBarcode', compact('product'));
    }

    public function generateBarcode($barcode)
    {
        $barcodeGenerator = new DNS1D();
        return $barcodeGenerator->getBarcodeHTML($barcode, 'C39');
    }

    public function search(Request $request)
    {
        $query = $request->get('query');
        $products = Product::where('name', 'like', "%{$query}%")->get();
        return response()->json($products);
    }
}





