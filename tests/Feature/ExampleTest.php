<?php
/** @var \Tests\TestCase $this */

test('the application returns a successful response', function () {
    $response = $this->get('/');

    // La ruta raíz redirige al login cuando no hay sesión
    $response->assertRedirect('/login');
});
