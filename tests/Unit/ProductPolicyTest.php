<?php

use App\Policies\ProductPolicy;
use App\Models\AccessLevel;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('productpolicy control uses access_levels config', function () {
    // No DB required: comprobamos la lógica del policy sobre un user "fake"
    config(['access_levels.admin' => [42]]);

    $user = new User();
    $user->access_level_id = 42;

    $policy = new ProductPolicy();

    expect($policy->control($user))->toBeTrue();
});
