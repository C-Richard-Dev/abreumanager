<?php

namespace App\Actions\Workspaces;

use App\Models\Pivot\UserWorkspace;
use App\Models\User;
use App\Models\Workspace;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class AcceptInvite
{
    public const EXPIRES_IN_DAYS = 7;

    public function findValid(string $token, User $user): ?object
    {
        return DB::table('invite_tokens')
            ->join('workspaces', 'workspaces.id', '=', 'invite_tokens.workspace_id')
            ->join('users as senders', 'senders.id', '=', 'invite_tokens.sender_id')
            ->where('invite_tokens.token', $token)
            ->where('invite_tokens.email', Str::lower($user->email))
            ->where('invite_tokens.created_at', '>=', now()->subDays(self::EXPIRES_IN_DAYS))
            ->select([
                'invite_tokens.email',
                'workspaces.uuid as workspace_uuid',
                'workspaces.name as workspace_name',
                'senders.name as sender_name',
            ])
            ->first();
    }

    public function execute(User $user, string $token): Workspace
    {
        return DB::transaction(function () use ($user, $token): Workspace {
            $invite = DB::table('invite_tokens')
                ->where('token', $token)
                ->where('email', Str::lower($user->email))
                ->where('created_at', '>=', now()->subDays(self::EXPIRES_IN_DAYS))
                ->lockForUpdate()
                ->first();

            if (! $invite) {
                throw ValidationException::withMessages([
                    'token' => 'O convite é inválido ou expirou.',
                ]);
            }

            $inviteWasConsumed = DB::table('invite_tokens')
                ->where('email', $invite->email)
                ->where('token', $token)
                ->delete();

            if ($inviteWasConsumed !== 1) {
                throw ValidationException::withMessages([
                    'token' => 'O convite é inválido ou expirou.',
                ]);
            }

            $workspace = Workspace::findOrFail($invite->workspace_id);

            UserWorkspace::firstOrCreate([
                'user_id' => $user->getKey(),
                'workspace_id' => $workspace->getKey(),
            ]);

            return $workspace;
        });
    }
}
