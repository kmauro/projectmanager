<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Product;
use App\Policies\ProductPolicy;

class AuthServiceProvider
{
    /**
     * Create a new policy instance.
     */

     protected $policies = [
        Product::class => ProductPolicy::class,
    ];
    

    public function __construct()
    {
        //
    }
}
