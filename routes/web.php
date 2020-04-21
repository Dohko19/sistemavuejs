<?php
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');
Route::get('categoria', 'CategoriaController@index');
Route::post('categoria/registrar', 'CategoriaController@store')->name('categoria.store');
Route::put('categoria/actualizar', 'CategoriaController@update')->name('categoria.update');
Route::put('categoria/desactivar', 'CategoriaController@desactivar')->name('categoria.desactivar');
Route::put('categoria/activar', 'CategoriaController@activar')->name('categoria.activar');