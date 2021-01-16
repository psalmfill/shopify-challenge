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

Route::get('/', function () {
    $search = request()->query('search');
    
    $images = App\Models\Image::when($search, function ($query, $search) {
        return $query->where('caption', 'like', "%$search%");
    })->paginate();
    return view('welcome', compact('images'));
});

Auth::routes();

Route::group(['middleware' => 'auth'], function () {
    Route::get('/home', 'HomeController@index')->name('home');
    Route::get('/configurations', 'HomeController@configurations')->name('configurations');
    Route::post('/configurations/reset-api-keys', 'HomeController@resetApiKey')->name('configurations.rest_api_keys');
    Route::resources(['folders' => 'FolderController', 'images' => 'ImageController']);
});
