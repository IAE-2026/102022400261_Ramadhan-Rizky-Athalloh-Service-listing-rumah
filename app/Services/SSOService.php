<?php

namespace App\Services;

use App\Models\UserRole;
use Illuminate\Support\Facades\Http;

class SSOService
{
    public function getToken()
    {
        $response = Http::post(
            'https://iae-sso.virtualfri.id/api/v1/auth/token',
            [
                'email' => env('SSO_EMAIL'),
                'password' => env('SSO_PASSWORD')
            ]
        );

        $data = $response->json();

        return $data['token'];
    }

    public function getM2MToken()
    {
        $response = Http::post(
            'https://iae-sso.virtualfri.id/api/v1/auth/token',
            [
                'api_key' => env('IAE_API_KEY'),
                'nim' => env('NIM')
            ]
        );

        $data = $response->json();

        return $data['token'];
    }

    // method decodePayload()
    // method getLocalRole()
}