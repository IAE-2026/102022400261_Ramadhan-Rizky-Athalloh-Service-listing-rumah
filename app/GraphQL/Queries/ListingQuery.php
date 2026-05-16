<?php

namespace App\GraphQL\Queries;

use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Query;

class ListingQuery extends Query
{
    protected $attributes = [
        'name' => 'listings'
    ];

    public function type(): Type
    {
        return Type::listOf(Type::string());
    }

    public function resolve($root, $args)
    {
        return [
            'Rumah Minimalis',
            'Apartemen Bandung',
            'Kos Exclusive'
        ];
    }
}