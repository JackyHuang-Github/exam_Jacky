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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::apiResource('articles','App\Http\Controllers\Api\ArticleController');

Route::namespace('App\Http\Controllers\Api')->group(function() {
    Route::get('querySelect', 'ArticleController@querySelect');
    Route::get('querySpecific', 'ArticleController@querySpecific');
    Route::get('queryPagination', 'ArticleController@queryPagination');
    Route::get('queryRange/{min?}/{max?}', 'ArticleController@queryRange');
    Route::get('ByCgy/{cgy_id?}', 'ArticleController@queryByCgy');
    Route::get('queryPluck', 'ArticleController@queryPluck');
    Route::get('enabledCount', 'ArticleController@enabledCount');
});