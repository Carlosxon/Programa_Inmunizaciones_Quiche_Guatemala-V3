<?php

use App\Http\Controllers\InventoryController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\StockAdjustmentController;
use App\Http\Controllers\StockTransferController;
use App\Http\Controllers\SystemSettingController;
use App\Http\Controllers\WarehouseController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminController;

use App\Http\Controllers\UserController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\BrandController;
use App\Http\Controllers\UnitController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ReportController;

use App\Http\Controllers\TransferController;
use App\Http\Controllers\TransferenciasController;






/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

//Route::get('/dashboard', function () {
 //   return view('dashboard');
//})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

//Route::middleware(['auth'])->group(function () {
    //Route::get('/admin', [AdminController::class, 'index'])->name('admin.dashboard');
//});
Route::middleware('auth')->group(function () {
    Route::resource('stock_adjustments', StockAdjustmentController::class);
});
Route::group(['middleware' => ['role:Administrador']], function () {
    // Rutas accesibles solo para el rol 'Administrador'
});


Route::middleware(['auth'])->group(function () {
    Route::resource('warehouses', WarehouseController::class);
    // Otras rutas relacionadas con bodegas
});
Route::middleware(['auth', 'role:Encargado de Sucursal'])->group(function () {
    Route::get('/warehouses', [WarehouseController::class, 'index'])->name('warehouses.index');
    // Otras rutas relacionadas con bodegas
});
Route::group(['middleware' => ['role:Encargado de Sucursal']], function () {
    // Rutas accesibles solo para el rol 'Encargado de Sucursal'
});
Route::middleware('auth')->group(function () {
    Route::resource('warehouses', WarehouseController::class);
});
// dasboard

//Route::group(['middleware' => ['role:Administrador']], function () {
    //Route::get('/admin/dashboard', function () {
        //return view('admin.dashboard');
    //})->name('admin.dashboard');
//});

Route::group(['middleware' => ['role:Encargado de Sucursal']], function () {
    Route::get('/encargado/dashboard', function () {
        return view('encargado.dashboard');
    })->name('encargado.dashboard');
});


Route::get('/inicio', [AdminController::class, 'index'])->name('admin.index');
Route::resource('users', UserController::class);
Route::resource('products', ProductController::class);
Route::resource('categories', CategoryController::class);
Route::resource('brands', BrandController::class);
Route::resource('units', UnitController::class);
Route::get('products/{product}/printBarcode', [ProductController::class, 'printBarcode'])->name('products.printBarcode');
Route::get('products/{product}/barcode', [ProductController::class, 'generateBarcode'])->name('products.generateBarcode');

Route::resource('stock-adjustments', StockAdjustmentController::class);
Route::resource('stock-transfers', StockTransferController::class);
Route::get('stock-transfers/create', [StockTransferController::class, 'create'])->name('stock-transfers.create');
Route::get('warehouses/{warehouseId}/inventory', [InventoryController::class, 'index'])->name('inventories.index');


// Ruta adicional para marcar como recibida
Route::patch('stock-transfers/{stockTransfer}/receive', [StockTransferController::class, 'receive'])->name('stock-transfers.receive');
Route::resource('warehouses', WarehouseController::class);
Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
Route::resource('system-settings', SystemSettingController::class);
// rutas para roles
Route::get('users/{user}/roles', [UserController::class, 'manageRoles'])->name('users.roles');
Route::put('users/{user}/roles', [UserController::class, 'updateRoles'])->name('users.updateRoles');
// ruta para actualizar roles y permisos
Route::put('/users/{user}/roles-permissions', [UserController::class, 'updateRolesAndPermissions'])->name('users.updateRolesAndPermissions');
//vistas para roles y permisos
// Rutas para Roles
Route::prefix('roles')->name('roles.')->group(function () {
    Route::get('/', [UserController::class, 'indexRoles'])->name('index'); // Método GET para listar roles
    Route::get('/create', [UserController::class, 'createRole'])->name('create'); // Método GET para mostrar formulario de creación
    Route::post('/store', [UserController::class, 'storeRole'])->name('store'); // Método POST para almacenar un nuevo rol
    Route::get('/{role}/edit', [UserController::class, 'editRole'])->name('edit'); // Método GET para mostrar formulario de edición
    Route::put('/{role}', [UserController::class, 'updateRole'])->name('update'); // Método PUT para actualizar un rol
    Route::delete('/{role}', [UserController::class, 'destroyRole'])->name('destroy'); // Método DELETE para eliminar un rol
});

// Rutas para Permisos
Route::prefix('permissions')->name('permissions.')->group(function () {
    Route::get('/', [UserController::class, 'indexPermissions'])->name('index'); // Método GET para listar permisos
    Route::get('/create', [UserController::class, 'createPermission'])->name('create'); // Método GET para mostrar formulario de creación
    Route::post('/store', [UserController::class, 'storePermission'])->name('store'); // Método POST para almacenar un nuevo permiso
    Route::get('/{permission}/edit', [UserController::class, 'editPermission'])->name('edit'); // Método GET para mostrar formulario de edición
    Route::put('/{permission}', [UserController::class, 'updatePermission'])->name('update'); // Método PUT para actualizar un permiso
    Route::delete('/{permission}', [UserController::class, 'destroyPermission'])->name('destroy'); // Método DELETE para eliminar un permiso
});
//rutas de trasferencias
Route::resource('transfers', StockTransferController::class);
Route::get('transfers/accept/{transfer}', [StockTransferController::class, 'accept'])->name('transfers.accept');
Route::get('/products/search', [ProductController::class, 'search'])->name('products.search');
Route::get('/transferencias', [TransferController::class, 'listarTransferencia'])->name('transferencias.listar');
//rutas para ver inventarios
// trasferencias pendientes
Route::get('/bodegas/{warehouse}/transferencias', [App\Http\Controllers\TransferController::class, 'listarTransferenciasPendientes'])
     ->name('trasferencias.pendientes');

Route::get('/warehouses/{warehouse}/transferencias', [TransferController::class, 'listarTransferenciasPendientes'])
->name('warehouses.transferencias');
 // aceptar la trasferencia
 Route::patch('/transferencias/{transferencia}/aceptar', [StockTransferController::class, 'aceptarTransferencia'])->name('transferencias.aceptar');

    // segunda ruta para trasferencias
Route::get('stock-transfers/{stockTransfer}/pdf', [StockTransferController::class, 'generatePdf'])->name('stock-transfers.pdf');
Route::get('transferencias/pendientes/{warehouseId}', [StockTransferController::class, 'listarTransferenciasPendientes'])->name('transferencias.listar');
Route::match(['post', 'patch'], 'transferencias/aceptar/{id}', 'StockTransferController@accept')->name('stock-transfers.accept');

require __DIR__.'/auth.php';
//visualizacion del stock existenteS
Route::get('/get-warehouse-stock', [StockTransferController::class, 'getWarehouseStock'])->name('get-warehouse-stock');
Route::get('/get-warehouse-inventory', [StockTransferController::class, 'getWarehouseInventory'])->name('get-warehouse-inventory');

// visualizacion de reportes
Route::prefix('reports')->group(function () {
    Route::get('inventory', [ReportController::class, 'inventoryReport'])->name('reports.inventory');
    Route::get('transfers', [ReportController::class, 'transfersReport'])->name('reports.transfers');
    Route::get('adjustments', [ReportController::class, 'adjustmentsReport'])->name('reports.adjustments');
    Route::get('users', [ReportController::class, 'usersReport'])->name('reports.users');
    Route::get('consolidated', [ReportController::class, 'consolidatedReport'])->name('reports.consolidated');
    Route::get('reports/products', [ReportController::class, 'productsReport'])->name('reports.products');
    Route::get('reports/warehouses', [ReportController::class, 'warehousesReport'])->name('reports.warehouses');
    Route::get('reports/kardex', [ReportController::class, 'kardexReport'])->name('reports.kardex');
});

Route::post('inventory/exit-form', [InventoryController::class, 'exitForm'])->name('inventory.exit-form');
Route::post('inventory/process-exit', [InventoryController::class, 'processExit'])->name('inventory.process-exit');
Route::get('inventory/exit-pdf/{id}', [InventoryController::class, 'exitPdf'])->name('inventory.exit-pdf');
Route::post('/inventory/exit', [App\Http\Controllers\InventoryController::class, 'storeExit'])->name('inventory.exit.store');

Route::get('inventory/exit-form', function() {
    return redirect()->route('transferencias.pendientes');
})->name('inventory.exit-form.get');




Route::post('inventory/realizar-salida', [InventoryController::class, 'realizarSalida'])->name('inventory.realizar-salida');
Route::post('inventory/realizar-salida', [InventoryController::class, 'realizarSalida'])->name('inventory.realizar-salida');
Route::put('/stock-transfers/{stockTransfer}/cancel', [StockTransferController::class, 'cancelTransfer'])->name('stock-transfers.cancel');
Route::get('/inventory/exit/{id}', [InventoryController::class, 'showExit'])->name('inventory.show-exit');
Route::get('/transferencias/pendientes/{warehouseId?}', [TransferController::class, 'pendientes'])
     ->name('transferencias.pendientes');
     Route::get('/inventory/salidas', [InventoryController::class, 'getSalidas'])->name('inventory.salidas');
     Route::get('/inventory/salidas/{warehouseId}', [InventoryController::class, 'salidas'])->name('inventory.salidas');
     Route::get('/inventory/filtrar-salidas/{warehouseId}', [InventoryController::class, 'filtrarSalidas'])->name('inventory.filtrarSalidas');
     Route::get('/inventory/filtrar-salidas', [InventoryController::class, 'filtrarSalidas'])->name('inventory.filtrarSalidas');
     Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');