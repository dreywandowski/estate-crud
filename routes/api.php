<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;


/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});


Route::controller(App\Http\Controllers\BookController::class,)->group(function () {
       Route::get('v1/books/{id}', 'getBook');
       Route::get('v1/books', 'getBooks');
       Route::post('v1/books', 'createBook');
       Route::patch('v1/books/{id}', 'updateBook');
       Route::delete('v1/books/{id}', 'deleteBook');
       Route::get('external-books/{name}', 'getExternalBooks');
});
/*
// create book
Route::post('v1/books', [App\Http\Controllers\BookController::class, 'createBook']);

// get local books
Route::get('v1/books/{book}', [App\Http\Controllers\BookController::class, 'getBooks']);


// get external books
Route::get('external-books/{name}', [App\Http\Controllers\BookController::class, 'getExternalBooks']);

*/