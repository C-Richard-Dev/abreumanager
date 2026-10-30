<?php

namespace App\Http\Controllers\Users;

use App\Enums\UserType;
use App\Http\Controllers\Controller;
use App\Models\User;
use Inertia\Response;

class FormEditUserController extends Controller
{
    public function __invoke(string $userUuid): Response
    {
        $user = User::query()
            ->select(['uuid', 'name', 'email', 'type'])
            ->where('uuid', $userUuid)
            ->firstOrFail();

        return inertia('User/Edit', [
            'user' => $user,
            'types' => array_column(UserType::cases(), 'value'),
        ]);
    }
}
