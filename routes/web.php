<?php
//Route::get('/', function () {
//    return view('welcome');
//});
Route::group([
    'middleware' => ['auth','Almacenero']
], function (){
    Route::get('/', 'HomeController@index')->name('home');

    Route::get('categoria', 'CategoriaController@index');
    Route::post('categoria/registrar', 'CategoriaController@store')->name('categoria.store');
    Route::put('categoria/actualizar', 'CategoriaController@update')->name('categoria.update');
    Route::put('categoria/desactivar', 'CategoriaController@desactivar')->name('categoria.desactivar');
    Route::put('categoria/activar', 'CategoriaController@activar')->name('categoria.activar');
    Route::get('categoria/selectCategoria', 'CategoriaController@selectCategoria');

    Route::get('articulo', 'ArticuloController@index');
    Route::post('articulo/registrar', 'ArticuloController@store')->name('articulo.store');
    Route::put('articulo/actualizar', 'ArticuloController@update')->name('articulo.update');
    Route::put('articulo/desactivar', 'ArticuloController@desactivar')->name('articulo.desactivar');
    Route::put('articulo/activar', 'ArticuloController@activar')->name('articulo.activar');
    Route::get('articulo/buscarArticulo', 'ArticuloController@buscarArticulo');
    Route::get('articulo/listarArticulo', 'ArticuloController@listarArticulo');
    Route::get('articulo/listarPdf', 'ArticuloController@listarPdf')->name('articulo.pdf');


    Route::get('proveedor', 'ProveedorController@index');
    Route::post('proveedor/registrar', 'ProveedorController@store')->name('proveedor.store');
    Route::put('proveedor/actualizar', 'ProveedorController@update')->name('proveedor.update');
    Route::get('proveedor/selectProveedor', 'ProveedorController@selectProveedor')->name('proveedor.selectProveedor');

    Route::get('ingreso', 'IngresoController@index')->name('ingreso.index');
    Route::post('ingreso/registrar', 'IngresoController@store')->name('ingreso.store');
    Route::put('ingreso/desactivar', 'IngresoController@desactivar')->name('ingreso.desactivar');
    Route::get('ingreso/obtenerCabezera', 'IngresoController@obtenerCabezera')->name('ingreso.cabecera');
    Route::get('ingreso/obtenerDetalles', 'IngresoController@obtenerDetalles')->name('ingreso.detalles');

});

Route::group([
    'middleware' => ['auth','Vendedor']
], function (){
    Route::get('/', 'HomeController@index')->name('home');

    Route::get('cliente', 'ClienteController@index');
    Route::post('cliente/registrar', 'ClienteController@store')->name('cliente.store');
    Route::put('cliente/actualizar', 'ClienteController@update')->name('cliente.update');
    Route::get('cliente/selectCliente', 'ClienteController@selectCliente')->name('cliente.selectcliente');

    Route::get('venta', 'VentaController@index');
    Route::get('venta', 'VentaController@index');
    Route::post('venta/registrar', 'VentaController@store')->name('venta.store');
    Route::put('venta/desactivar', 'VentaController@desactivar')->name('venta.desactivar');
    Route::get('venta/obtenerCabezera', 'VentaController@obtenerCabezera')->name('venta.cabecera');
    Route::get('venta/obtenerDetalles', 'VentaController@obtenerDetalles')->name('venta.detalles');
    Route::get('venta/pdf/{id}', 'VentaController@pdf')->name('venta.pdf');

    Route::get('articulo/buscarArticuloVenta', 'ArticuloController@buscarArticuloVenta');
    Route::get('articulo/listarArticuloVenta', 'ArticuloController@listarArticuloVenta');


});
Route::group([
    'middleware' => ['auth', 'Administrador']
],
        function () {

            Route::get('/', 'HomeController@index')->name('home');

            Route::get('categoria', 'CategoriaController@index');
            Route::post('categoria/registrar', 'CategoriaController@store')->name('categoria.store');
            Route::put('categoria/actualizar', 'CategoriaController@update')->name('categoria.update');
            Route::put('categoria/desactivar', 'CategoriaController@desactivar')->name('categoria.desactivar');
            Route::put('categoria/activar', 'CategoriaController@activar')->name('categoria.activar');
            Route::get('categoria/selectCategoria', 'CategoriaController@selectCategoria');


            Route::get('articulo', 'ArticuloController@index');
            Route::post('articulo/registrar', 'ArticuloController@store')->name('articulo.store');
            Route::put('articulo/actualizar', 'ArticuloController@update')->name('articulo.update');
            Route::put('articulo/desactivar', 'ArticuloController@desactivar')->name('articulo.desactivar');
            Route::put('articulo/activar', 'ArticuloController@activar')->name('articulo.activar');
            Route::get('articulo/buscarArticulo', 'ArticuloController@buscarArticulo');
            Route::get('articulo/listarArticulo', 'ArticuloController@listarArticulo');
            Route::get('articulo/buscarArticuloVenta', 'ArticuloController@buscarArticuloVenta');
            Route::get('articulo/listarArticuloVenta', 'ArticuloController@listarArticuloVenta');
            Route::get('articulo/listarPdf', 'ArticuloController@listarPdf')->name('articulo.pdf');

            Route::get('cliente', 'ClienteController@index');
            Route::post('cliente/registrar', 'ClienteController@store')->name('cliente.store');
            Route::put('cliente/actualizar', 'ClienteController@update')->name('cliente.update');
            Route::get('cliente/selectCliente', 'ClienteController@selectCliente')->name('cliente.selectcliente');


            Route::get('venta', 'VentaController@index');
            Route::post('venta/registrar', 'VentaController@store')->name('venta.store');
            Route::put('venta/desactivar', 'VentaController@desactivar')->name('venta.desactivar');
            Route::get('venta/obtenerCabezera', 'VentaController@obtenerCabezera')->name('venta.cabecera');
            Route::get('venta/obtenerDetalles', 'VentaController@obtenerDetalles')->name('venta.detalles');
            Route::get('venta/pdf/{id}', 'VentaController@pdf')->name('venta.pdf');

            Route::get('proveedor', 'ProveedorController@index');
            Route::post('proveedor/registrar', 'ProveedorController@store')->name('proveedor.store');
            Route::put('proveedor/actualizar', 'ProveedorController@update')->name('proveedor.update');
            Route::get('proveedor/selectProveedor', 'ProveedorController@selectProveedor')->name('proveedor.selectProveedor');

            Route::get('rol', 'RoleController@index');
            Route::get('rol/selectrol', 'RoleController@selectRol');

            Route::get('user', 'UserController@index');
            Route::post('user/registrar', 'UserController@store')->name('user.store');
            Route::put('user/actualizar', 'UserController@update')->name('user.update');
            Route::put('user/desactivar', 'UserController@desactivar')->name('user.desactivar');
            Route::put('user/activar', 'UserController@activar')->name('user.activar');

            Route::get('ingreso', 'IngresoController@index')->name('ingreso.index');
            Route::post('ingreso/registrar', 'IngresoController@store')->name('ingreso.store');
            Route::put('ingreso/desactivar', 'IngresoController@desactivar')->name('ingreso.desactivar');
            Route::get('ingreso/obtenerCabezera', 'IngresoController@obtenerCabezera')->name('ingreso.cabecera');
            Route::get('ingreso/obtenerDetalles', 'IngresoController@obtenerDetalles')->name('ingreso.detalles');
        });

Auth::routes(['register' => false]);
