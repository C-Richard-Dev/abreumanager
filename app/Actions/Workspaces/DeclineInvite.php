<?php

namespace App\Actions\Workspaces;

use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class DeclineInvite
{
    public function execute(User $user, string $token): void
    {
        $inviteWasDeleted = DB::table('invite_tokens')
            ->where('token', $token)
            ->where('email', Str::lower($user->email))
            ->delete();

        if ($inviteWasDeleted !== 1) {
            throw ValidationException::withMessages([
                'token' => 'O convite é inválido ou expirou.',
            ]);
        }
    }
}
