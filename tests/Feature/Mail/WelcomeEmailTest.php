<?php

use App\Mail\WelcomeEmail;
use App\Models\User;

test('welcome email greets the new user and links to the login page', function () {
    $welcomeEmail = new WelcomeEmail(new User(['name' => 'Test User']));

    $welcomeEmail->assertSeeInHtml('Olá, Test User!');
    $welcomeEmail->assertSeeInHtml('Acessar minha conta');
    $welcomeEmail->assertSeeInHtml(route('login'));
    $welcomeEmail->assertSeeInText('Test User');
});
