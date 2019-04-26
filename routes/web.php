<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/


Auth::routes();

Route::get('/','Auth\LoginController@showLoginForm');
Route::get('take-db-snapshot', 'HomeController@index')->name('home');
Route::post('db-snapshot-create', 'HomeController@CreateDBSnapshot')->name('CreateDBSnapshot');
Route::get('db-snapshot-list', 'HomeController@DBSnapshotList')->name('DBSnapshotList');
Route::get('db-snapshot-list-datatable', 'HomeController@DBSnapshotDatatable')->name('DBSnapshotDatatable');
Route::get('db-snapshot-list-delete/{name}', 'HomeController@DBSnapshotDelete')->name('DBSnapshotDelete');
Route::get('db-snapshot-list-download/{name}', 'HomeController@DBSnapshotDownload')->name('DBSnapshotDownload');
