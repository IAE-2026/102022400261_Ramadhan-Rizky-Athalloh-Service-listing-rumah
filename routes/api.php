<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\ListingController;
use OpenApi\Annotations as OA;

/**
 * @OA\Info(
 *     title="Listing Property API",
 *     version="1.0.0",
 *     description="API untuk Service Listing Rumah dan Property"
 * )
 * 
 * @OA\SecurityScheme(
 *     securityScheme="ApiKeyAuth",
 *     type="apiKey",
 *     in="header",
 *     name="X-IAE-KEY"
 * )
 */

Route::middleware(['apikey'])->group(function () {

    Route::get('/v1/listings', [ListingController::class, 'index']);

    Route::get('/v1/listings/{id}', [ListingController::class, 'show']);

    Route::post('/v1/listings', [ListingController::class, 'store']);

    Route::get('/v1/listings/{id}/availability', [ListingController::class, 'availability']);

   
});