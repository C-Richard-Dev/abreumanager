<?php

namespace App\Http\Controllers\Users;

use App\Http\Requests\Users\ResetPasswordOfUserRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use App\Models\User;
use App\Actions\Users\ResetPasswordOfUser;

class ResetPasswordOfUserController
{
    public function __invoke(
        ResetPasswordOfUserRequest $request,
        ResetPasswordOfUser $resetPassword,
        string $token
    ): RedirectResponse
    {
        $resetPassword->execute($request->email, $request->password, $request->token);

        return redirect()
            ->route('login')
            ->with('status', 'Senha redefinida com sucesso.');
    }
}