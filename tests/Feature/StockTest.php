<?php

use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\AccessLevel;
use App\Models\User;
use App\Models\Product;

uses(RefreshDatabase::class);

it('redirects guest from stock control to login', function () {
    $this->get('/stock/control')->assertRedirect('/login');
});

it('forbids non_admin users from accessing stock control', function () {
    $level = AccessLevel::create(['name' => 'User']);
    $user = User::factory()->create(['access_level_id' => $level->id]);

    $this->actingAs($user)->get('/stock/control')->assertStatus(403);
});

it('allows admin to update stock and validates payload', function () {
    // crear nivel admin y forzar config para usar su id
    $adminLevel = AccessLevel::create(['name' => 'Admin']);
    config(['access_levels.admin' => [$adminLevel->id]]);

    $admin = User::factory()->create(['access_level_id' => $adminLevel->id]);

    $products = Product::factory()->count(2)->create(['stock' => 10]);

    $payload = [
        'productid' => $products->pluck('id')->toArray(),
        'quantity' => [5, 7],
    ];

    $this->actingAs($admin)->post('/stock/control', $payload)
        ->assertRedirect('/stock')
        ->assertSessionHasNoErrors();

    foreach ($products as $i => $p) {
        $this->assertDatabaseHas('products', ['id' => $p->id, 'stock' => $payload['quantity'][$i]]);
    }

    // payload inválido -> error en validación
    $bad = [
        'productid' => ['999999'],
        'quantity' => [-5],
    ];

    $this->actingAs($admin)->post('/stock/control', $bad)
        ->assertSessionHasErrors(['productid.0', 'quantity.0']);
});
