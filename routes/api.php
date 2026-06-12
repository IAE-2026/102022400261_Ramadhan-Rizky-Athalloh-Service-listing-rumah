<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\ListingController;
use OpenApi\Annotations as OA;
use Illuminate\Http\Request;
use App\Services\SSOService;

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

    Route::get('/v1/contracts', [ListingController::class, 'contracts']);

    // TEST SSO
    Route::post('/v1/sso-test', function (
        Request $request,
        SSOService $sso
    ) {
        return response()->json(
            $sso->getLocalRole(
                $request->bearerToken()
            )
        );
    });

    Route::get('/v1/token-test', function (
    App\Services\SSOService $sso
) {
    return $sso->getToken();
});

Route::get('/v1/m2m-test', function (
    App\Services\SSOService $sso
) {
    return response()->json([
        'token' => $sso->getM2MToken()
    ]);
});

});