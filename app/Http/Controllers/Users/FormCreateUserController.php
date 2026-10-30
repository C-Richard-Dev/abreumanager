<?php

namespace App\Http\Controllers\Users;

use App\Enums\UserType;
use App\Http\Controllers\Controller;
use Inertia\Response;

class FormCreateUserController extends Controller
{
    public function __invoke(): Response
    {
        return inertia('User/Create', [
            'types' => array_column(UserType::cases(), 'value'),
        ]);
    }
}
