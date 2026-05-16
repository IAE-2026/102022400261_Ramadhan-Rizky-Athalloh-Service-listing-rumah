<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use OpenApi\Attributes as OA;

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

    public function store(Request $request)
    {
        return response()->json([
            'status' => 'success',
            'message' => 'Property created successfully',
            'data' => $request->all()
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


    

}