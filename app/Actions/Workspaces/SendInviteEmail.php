<?php

namespace App\Actions\Workspaces;

use App\Models\User;
use App\Models\Workspace;
use Illuminate\Mail\Message;
use Illuminate\Mail\SentMessage;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class SendInviteEmail
{
    public function execute(Workspace $workspace, User $sender, string $recipient): ?SentMessage
    {
        $recipient = Str::lower(trim($recipient));

        $user = User::where('email', $recipient)->first();

        if (! $user) {
            throw ValidationException::withMessages([
                'email' => 'Não existe um usuário com este e-mail.',
            ]);
        }

        $isAlreadyMember = $workspace->users()->where('email', $user->email)->first();

        if ($isAlreadyMember) {
            throw ValidationException::withMessages([
                'email' => 'Este usuário já é membro do workspace.',
            ]);
        }

        $cacheKey = 'workspace-invite-email:'.hash('sha256', $workspace->uuid.'|'.$recipient);

        if (! Cache::add($cacheKey, true, 60)) {
            throw ValidationException::withMessages([
                'email' => 'Aguarde 1 minuto antes de enviar outro convite para este e-mail.',
            ]);
        }

        try {
            $token = Str::random(60);

            DB::table('invite_tokens')->updateOrInsert(
                ['email' => $recipient],
                [
                    'token' => $token,
                    'workspace_id' => $workspace->getKey(),
                    'sender_id' => $sender->getKey(),
                    'created_at' => now(),
                    'updated_at' => now(),
                ]
            );

            $linkToAccept = url('/invite/'.$token);

            $sentMessage = Mail::raw(
                "{$sender->name} convidou você para ser membro do workspace \"{$workspace->name}\". Clique no link a seguir para aceitar o convite: ".$linkToAccept,
                function (Message $message) use ($recipient, $workspace): void {
                    $message
                        ->to($recipient)
                        ->subject('Convite para o workspace '.$workspace->name);
                },
            );
        } catch (\Throwable $exception) {
            Cache::forget($cacheKey);

            throw $exception;
        }

        if ($sentMessage === null) {
            Cache::forget($cacheKey);
        }

        return $sentMessage;
    }
}
