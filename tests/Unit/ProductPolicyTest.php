<?php

use App\Policies\ProductPolicy;
use App\Models\AccessLevel;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(Tests\TestCase::class, RefreshDatabase::class);

it('productpolicy control uses access_levels config', function () {
    // Config disponible porque estamos usando TestCase (aplicación bootstrapped)
    config(['access_levels.admin' => [42]]);

    $user = new User();
    $user->access_level_id = 42;

    $policy = new ProductPolicy();

    expect($policy->control($user))->toBeTrue();
});
