<?php

namespace App\Http\Controllers\Users;

use App\Http\Controllers\Controller;
use App\Models\User;
use Inertia\Response;

class ListUsersController extends Controller
{
    public function __invoke(): Response
    {
        return inertia('User/Index', [
            'users' => User::query()
                ->select(['uuid', 'name', 'email', 'type', 'created_at'])
                ->orderBy('name')
                ->get(),
        ]);
    }
}
