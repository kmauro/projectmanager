<?php

use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\AccessLevel;
use App\Models\User;
use App\Models\Product;

uses(RefreshDatabase::class);

it('guest cannot access stock control (redirect or forbidden)', function () {
    $response = $this->get('/stock/control');

    // Aceptamos redirección a login o 403 si se llegó a la autorización antes
    $this->assertTrue(in_array($response->status(), [302, 403]));
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

    // Omitir protección CSRF en este test (se comprueba la lógica del controller)
    $this->withoutMiddleware(\Illuminate\Foundation\Http\Middleware\VerifyCsrfToken::class);

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
