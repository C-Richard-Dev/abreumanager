<?php

namespace App\Http\Controllers\Users;

use App\Actions\Users\ResetPasswordOfUser;
use App\Http\Requests\Users\ResetPasswordOfUserRequest;
use Illuminate\Http\RedirectResponse;

class ResetPasswordOfUserController
{
    public function __invoke(
        ResetPasswordOfUserRequest $request,
        ResetPasswordOfUser $resetPassword
    ): RedirectResponse {
        $resetPassword->execute(
            $request->string('password')->toString(),
            $request->string('token')->toString()
        );

        return redirect()
            ->route('login')
            ->with('status', 'Senha redefinida com sucesso.');
    }
}
