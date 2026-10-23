<?php
namespace App\Http\Controllers\ResetPassword;

use Inertia\Inertia;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ShowResetPasswordFormController
{
    public function __invoke(string $token)
    {
        $resetRecord = DB::table('password_reset_tokens')
            ->where('token', $token)
            ->first();

        if (!$resetRecord) {
            abort(404)
                ->with('error', 'Link inválido.');
        }

        return Inertia::render('reset-password/Form', [
            'token' => $token
        ]);
    }
}