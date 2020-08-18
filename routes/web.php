<?php

use Illuminate\Support\Facades\Route;
use App\Http\Middleware\AjaxOnly;
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
    return view('home');
});

Route::middleware([AjaxOnly::class])->group(function () {
    Auth::routes();
    Route::get('/home', 'HomeController@index')->name('home');
    Route::get('login/{provider}', 'Auth\LoginController@redirectToProvider')->withoutMiddleware([AjaxOnly::class]);
    Route::get('login/{provider}/callback', 'Auth\LoginController@handleProviderCallback')->withoutMiddleware([AjaxOnly::class]);
    Route::get('comments', 'CommentsController@getComments')->name('getComments');
    Route::post('comment', 'CommentsController@store')->name('saveComment');
    Route::delete('comment/{comment}', 'CommentsController@destroy')->name('deleteComment');
});
Route::fallback(function() {
    return abort(404);
});