<?php

namespace App\Http\Controllers\Users;

use App\Actions\Users\UpdateUser;
use App\Http\Controllers\Controller;
use App\Http\Requests\Users\UpdateUserRequest;
use App\Models\User;
use Illuminate\Http\RedirectResponse;

class UpdateUserController extends Controller
{
    public function __invoke(
        UpdateUserRequest $request,
        string $userUuid,
        UpdateUser $updateUser
    ): RedirectResponse {
        $user = User::where('uuid', $userUuid)->firstOrFail();
        $authenticatedUser = $request->user();

        abort_unless($authenticatedUser instanceof User, 401);

        $updateUser->execute(
            $user,
            $authenticatedUser,
            $request->safe()->only(['name', 'email', 'type', 'password'])
        );

        return redirect()->route('users.index');
    }
}
