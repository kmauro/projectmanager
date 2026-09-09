<?php

test('guests are redirected to login from the home page', function () {
    $this->get('/')
        ->assertRedirectToRoute('login');
});
