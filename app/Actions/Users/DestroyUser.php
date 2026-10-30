<?php

namespace App\Actions\Users;

use App\Models\User;
use App\Models\Workspace;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class DestroyUser
{
    public function execute(User $user, User $authenticatedUser): void
    {
        if ($user->is($authenticatedUser)) {
            throw ValidationException::withMessages([
                'user' => 'Você não pode excluir o seu próprio usuário.',
            ]);
        }

        if (Workspace::where('created_by', $user->getKey())->exists()) {
            throw ValidationException::withMessages([
                'user' => 'Este usuário é criador de workspaces e não pode ser excluído.',
            ]);
        }

        DB::transaction(function () use ($user): void {
            $user->workspaces()->detach();
            $user->delete();
        });
    }
}
