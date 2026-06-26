<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\JsonResponse;

class GraphQLWrapperMiddleware
{
    public function handle($request, Closure $next)
    {
        $response = $next($request);

        if ($response instanceof JsonResponse) {
            $original = $response->getData(true);
            
            // If the response already has 'status', it was probably handled by errors_handler
            if (!isset($original['status'])) {
                if (isset($original['errors'])) {
                    $response->setData([
                        'status' => 'error',
                        'message' => 'GraphQL Error',
                        'data' => $original['data'] ?? null,
                        'errors' => $original['errors'],
                        'meta' => [
                            'service_name' => 'Listing-Service',
                            'api_version' => 'v1'
                        ]
                    ]);
                } else {
                    $response->setData([
                        'status' => 'success',
                        'message' => 'GraphQL Success',
                        'data' => $original['data'] ?? null,
                        'meta' => [
                            'service_name' => 'Listing-Service',
                            'api_version' => 'v1'
                        ]
                    ]);
                }
            }
        }

        return $response;
    }
}
