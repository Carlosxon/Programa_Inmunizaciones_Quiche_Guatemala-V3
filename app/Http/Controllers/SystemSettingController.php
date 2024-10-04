<?php

namespace App\Http\Controllers;
use App\Models\SystemSetting;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class SystemSettingController extends Controller
{
    //
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
        $query = SystemSetting::query();

        if ($request->has('key') && $request->key != '') {
            $query->where('key', 'like', '%' . $request->key . '%');
        }

        if ($request->has('value') && $request->value != '') {
            $query->where('value', 'like', '%' . $request->value . '%');
        }

        $settings = $query->latest()->paginate(10);

        return view('system-settings.index', compact('settings'));
    }

    public function edit($id)
    {
        $setting = SystemSetting::findOrFail($id);
        return view('system-settings.edit', compact('setting'));
    }

    public function update(Request $request, $id)
    {
        $setting = SystemSetting::findOrFail($id);
        $setting->update($request->only(['value']));

        return redirect()->route('system-settings.index')->with('success', 'Configuración actualizada correctamente');
    }

    public function create()
    {
        return view('system-settings.create');
    }

    public function store(Request $request)
    {
        SystemSetting::create($request->only(['key', 'value']));

        return redirect()->route('system-settings.index')->with('success', 'Configuración creada correctamente');
    }
}
