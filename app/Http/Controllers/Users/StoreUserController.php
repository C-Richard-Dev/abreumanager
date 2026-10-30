<?php

namespace App\Http\Controllers\Users;

use App\Actions\Users\CreateUser;
use App\Http\Controllers\Controller;
use App\Http\Requests\Users\StoreUserRequest;
use Illuminate\Http\RedirectResponse;

class StoreUserController extends Controller
{
    public function __invoke(
        StoreUserRequest $request,
        CreateUser $createUser
    ): RedirectResponse {
        $createUser->execute($request->safe()->only(['name', 'email', 'type', 'password']));

        return redirect()->route('users.index');
    }
}
