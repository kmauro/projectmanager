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

    // Incluir token CSRF explícito en el payload para evitar 419 en este entorno de tests
    // Llamamos al controller directamente para evitar problemas de CSRF en el entorno de tests
    $request = \Illuminate\Http\Request::create('/stock/control', 'POST', $payload);
    $request->setUserResolver(fn() => $admin);

    // Establecer el usuario autenticado para que Gate/authorize lo reconozca
    $this->be($admin);

    $controller = new \App\Http\Controllers\StockController();
    $response = $controller->updateStock($request);

    $this->assertInstanceOf(\Illuminate\Http\RedirectResponse::class, $response);

    foreach ($products as $i => $p) {
        $this->assertDatabaseHas('products', ['id' => $p->id, 'stock' => $payload['quantity'][$i]]);
    }

    // payload inválido -> error en validación (usamos el helper de request para validar)
    $bad = [
        'productid' => ['999999'],
        'quantity' => [-5],
    ];

    $badRequest = \Illuminate\Http\Request::create('/stock/control', 'POST', $bad);
    $badRequest->setUserResolver(fn() => $admin);

    try {
        $controller->updateStock($badRequest);
        $this->fail('Expected ValidationException not thrown');
    } catch (\Illuminate\Validation\ValidationException $e) {
        $this->assertArrayHasKey('productid.0', $e->errors());
        $this->assertArrayHasKey('quantity.0', $e->errors());
    }
});
