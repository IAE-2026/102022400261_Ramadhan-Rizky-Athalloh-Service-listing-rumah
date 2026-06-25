<?php

namespace App\GraphQL\Queries;

use App\Models\Listing;
use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Query;
use Rebing\GraphQL\Support\Facades\GraphQL;

class ListingQuery extends Query
{
    protected $attributes = [
        'name' => 'listings'
    ];

    public function type(): Type
    {
        return Type::listOf(GraphQL::type('Listing'));
    }

    public function resolve($root, $args)
    {
        return Listing::all();
    }
}