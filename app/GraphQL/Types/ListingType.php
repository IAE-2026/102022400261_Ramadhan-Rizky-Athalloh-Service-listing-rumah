<?php

namespace App\GraphQL\Types;

use App\Models\Listing;
use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Type as GraphQLType;

class ListingType extends GraphQLType
{
    protected $attributes = [
        'name' => 'Listing',
        'description' => 'A listing property object',
        'model' => Listing::class,
    ];

    public function fields(): array
    {
        return [
            'id' => [
                'type' => Type::nonNull(Type::int()),
                'description' => 'The id of the listing',
            ],
            'title' => [
                'type' => Type::nonNull(Type::string()),
                'description' => 'The title of the listing',
            ],
            'description' => [
                'type' => Type::string(),
                'description' => 'The description of the listing',
            ],
            'location' => [
                'type' => Type::nonNull(Type::string()),
                'description' => 'The location of the listing',
            ],
            'price' => [
                'type' => Type::nonNull(Type::int()),
                'description' => 'The price of the listing',
            ],
            'status' => [
                'type' => Type::nonNull(Type::string()),
                'description' => 'The status of the listing',
            ],
            'receipt_number' => [
                'type' => Type::string(),
                'description' => 'The receipt number from the audit service',
            ],
            'created_at' => [
                'type' => Type::string(),
                'description' => 'Creation date',
            ],
            'updated_at' => [
                'type' => Type::string(),
                'description' => 'Last updated date',
            ],
        ];
    }
}
