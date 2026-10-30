<?php

namespace App\Actions\Workspaces;

use App\Models\Workspace;
use Illuminate\Validation\ValidationException;

class RemoveMember
{
    public function execute(Workspace $workspace, string $memberUuid): void
    {
        $member = $workspace->users()->where('users.uuid', $memberUuid)->first();

        if (! $member) {
            throw ValidationException::withMessages([
                'member' => 'Membro não encontrado neste workspace.',
            ]);
        }

        if ($member->getKey() === $workspace->created_by) {
            throw ValidationException::withMessages([
                'member' => 'O criador do workspace não pode ser removido.',
            ]);
        }

        $workspace->users()->detach($member->getKey());
    }
}
