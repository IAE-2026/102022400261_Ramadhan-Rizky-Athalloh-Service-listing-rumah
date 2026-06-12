<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use App\Services\SSOService;

class RabbitMQPublisher
{
    public function publish($message)
    {
        $sso = new SSOService();

        $token = $sso->getM2MToken();

        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . $token,
            'Content-Type' => 'application/json'
        ])->post(
            'https://iae-sso.virtualfri.id/api/v1/messages/publish',
            [
                'exchange' => 'iae.central.exchange',
                'routing_key' => 'listing.created',
                'payload' => $message
            ]
        );

        return $response->json();
    }
}