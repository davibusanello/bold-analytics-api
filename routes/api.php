<?php

use Illuminate\Http\Request;
use App\Reviews;
use App\Http\Resources\Review as ReviewResource;
use App\Http\Resources\ReviewCollection as ReviewsCollectionResource;

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

// Get all reviews
Route::get('/reviews', function () {
    ReviewsCollectionResource::withoutWrapping();
    return new ReviewsCollectionResource(Reviews::paginate());
});
// Get reviews filtered by app name
Route::get('/app/{name}/reviews', function ($name) {
    ReviewsCollectionResource::withoutWrapping();
    return new ReviewsCollectionResource(Reviews::where('app_name', $name)->paginate());
});
// Get specific review
Route::get('/reviews/{id}', function ($id) {
    ReviewResource::withoutWrapping();
    return new ReviewResource(Reviews::find($id));
});
