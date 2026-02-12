<?php

use App\Policies\ProductPolicy;
use App\Models\AccessLevel;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('productpolicy control uses access_levels config', function () {
    $level = AccessLevel::create(['name' => 'Admin']);
    config(['access_levels.admin' => [$level->id]]);

    $user = User::factory()->create(['access_level_id' => $level->id]);

    $policy = new ProductPolicy();

    expect($policy->control($user))->toBeTrue();
});
