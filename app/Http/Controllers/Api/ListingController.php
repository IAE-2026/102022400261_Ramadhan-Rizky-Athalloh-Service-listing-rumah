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
        description: "Success",
        content: new OA\JsonContent()
    )]

    public function index()
    {
        $listings = Listing::all();

        return response()->json([
            'status' => 'success',
            'message' => 'Data retrieved successfully',
            'data' => $listings,
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
        description: "Success",
        content: new OA\JsonContent()
    )]

    public function show($id)
    {
        $listing = Listing::find($id);

        if (!$listing) {
            return response()->json([
                'status' => 'error',
                'message' => 'Listing not found',
                'errors' => null
            ], 404);
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Detail listing',
            'data' => $listing,
            'meta' => [
                'service_name' => 'Listing-Service',
                'api_version' => 'v1'
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
        required: true,
        content: new OA\JsonContent(
            required: ["title", "location", "price"],
            properties: [
                new OA\Property(property: "title", type: "string", example: "Rumah Minimalis"),
                new OA\Property(property: "description", type: "string", example: "Deskripsi properti"),
                new OA\Property(property: "location", type: "string", example: "Bandung"),
                new OA\Property(property: "price", type: "integer", example: 1500000)
            ]
        )
    )]

    #[OA\Response(
        response: 201,
        description: "Created",
        content: new OA\JsonContent()
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
        'data' => $listing,
        'meta' => [
            'service_name' => 'Listing-Service',
            'api_version' => 'v1'
        ]
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
        description: "Success",
        content: new OA\JsonContent()
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
        description: "Success",
        content: new OA\JsonContent()
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