<?php

namespace App\Http\Controllers\Users;

use App\Actions\Users\DestroyUser;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class DeleteUserController extends Controller
{
    public function __invoke(
        Request $request,
        string $userUuid,
        DestroyUser $destroyUser
    ): RedirectResponse {
        $user = User::where('uuid', $userUuid)->firstOrFail();
        $authenticatedUser = $request->user();

        abort_unless($authenticatedUser instanceof User, 401);

        $destroyUser->execute($user, $authenticatedUser);

        return redirect()->route('users.index');
    }
}
