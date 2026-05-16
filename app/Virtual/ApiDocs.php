<?php

namespace App\Virtual;

use OpenApi\Attributes as OA;

#[OA\Info(
    version: "1.0.0",
    title: "Listing Property API",
    description: "API untuk Service Listing Rumah"
)]

#[OA\SecurityScheme(
    securityScheme: "ApiKeyAuth",
    type: "apiKey",
    in: "header",
    name: "X-IAE-KEY"
)]

class ApiDocs
{
}