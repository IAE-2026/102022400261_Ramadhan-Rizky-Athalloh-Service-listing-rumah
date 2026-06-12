<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use OpenApi\Attributes as OA;
use App\Services\SoapAuditService;
use App\Services\RabbitMQPublisher;
use App\Models\Listing;

class ListingController extends Controller
{



    #[OA\Get(
        path: "/api/v1/listings",
        summary: "Get all listings",
        security: [["ApiKeyAuth" => []]],
        tags: ["Listings"]
    )]

    #[OA\Response(
        response: 200,
        description: "Success"
    )]

    public function index()
    {
        return response()->json([
            'status' => 'success',
            'message' => 'Data retrieved successfully',
            'data' => [
                [
                    'id' => 1,
                    'title' => 'Rumah Minimalis',
                    'location' => 'Bandung',
                    'price' => 1500000,
                    'status' => 'available'
                ]
            ],
            'meta' => [
                'service_name' => 'Listing-Service',
                'api_version' => 'v1'
            ]
        ]);
    }


    #[OA\Get(
        path: "/api/v1/listings/{id}",
        summary: "Get detail listing",
        security: [["ApiKeyAuth" => []]],
        tags: ["Listings"]
    )]

    #[OA\Parameter(
        name: "id",
        in: "path",
        required: true
    )]

    #[OA\Response(
        response: 200,
        description: "Success"
    )]

    public function show($id)
    {
        if ($id != 1) {
            return response()->json([
                'status' => 'error',
                'message' => 'Listing not found',
                'errors' => null
            ], 404);
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Detail listing',
            'data' => [
                'id' => $id,
                'title' => 'Rumah Minimalis',
                'location' => 'Bandung',
                'price' => 1500000,
                'status' => 'available'
            ]
        ]);
    }


    #[OA\Post(
        path: "/api/v1/listings",
        summary: "Create new listing",
        security: [["ApiKeyAuth" => []]],
        tags: ["Listings"]
    )]

    #[OA\RequestBody(
        required: true
    )]

    #[OA\Response(
        response: 201,
        description: "Created"
    )]

    public function store(
    Request $request,
    SoapAuditService $soapService,
    RabbitMQPublisher $rabbitService
)
{
    $validated = $request->validate([
        'title' => 'required|string|max:255',
        'description' => 'nullable|string',
        'location' => 'required|string|max:255',
        'price' => 'required|numeric'
    ]);

    $listing = Listing::create([
        'title' => $validated['title'],
        'description' => $validated['description'] ?? null,
        'location' => $validated['location'],
        'price' => $validated['price'],
        'status' => 'available'
    ]);

    $audit = $soapService->sendAudit([
        'listing_id' => $listing->id,
        'title' => $listing->title,
        'price' => $listing->price
    ]);

    $listing->receipt_number = 
        $audit['receipt_number'];
    $listing->save();

    $rabbitService->publish([
        'event' => 'listing.created',
        'listing_id' => $listing->id,
        'title' => $listing->title,
        'price' => $listing->price
    ]);

    return response()->json([
    'status' => 'success',
    'message' => 'Property created successfully',
    'receipt_number' => $listing->receipt_number,
    'data' => $listing
], 201);
}


    #[OA\Get(
        path: "/api/v1/listings/{id}/availability",
        summary: "Check listing availability",
        security: [["ApiKeyAuth" => []]],
        tags: ["Listings"]
    )]

    #[OA\Parameter(
        name: "id",
        in: "path",
        required: true
    )]

    #[OA\Response(
        response: 200,
        description: "Success"
    )]

    public function availability($id)
    {
        return response()->json([
            'status' => 'success',
            'message' => 'Availability checked',
            'data' => [
                'listing_id' => $id,
                'status' => 'available'
            ]
        ]);
    }
       #[OA\Get(
        path: "/api/v1/contracts",
        summary: "Get contracts",
        security: [["ApiKeyAuth" => []]],
        tags: ["Contracts"]
    )]

    #[OA\Response(
        response: 200,
        description: "Success"
    )]

    public function contracts()
    {
        return response()->json([
            'status' => 'success',
            'message' => 'Contracts retrieved successfully',
            'data' => [
                [
                    'contract_id' => 1,
                    'tenant_name' => 'Rama',
                    'property' => 'Rumah Minimalis',
                    'duration' => '12 bulan'
                ]
            ]
        ]);
    }
}