<?php

namespace App\Actions\Workspaces;

use App\Models\User;
use App\Models\Workspace;
use Illuminate\Validation\ValidationException;

class LeaveWorkspace
{
    public function execute(Workspace $workspace, User $user): void
    {
        if ($workspace->created_by === $user->getKey()) {
            throw ValidationException::withMessages([
                'workspace' => 'O criador do workspace não pode sair dele.',
            ]);
        }

        $workspace->users()->detach($user->getKey());
    }
}
