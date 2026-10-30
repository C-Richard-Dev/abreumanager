<?php

namespace App\Http\Controllers\Workspaces;

use App\Actions\Workspaces\SendInviteEmail;
use App\Http\Controllers\Controller;
use App\Http\Requests\Workspaces\SendInviteEmailRequest;
use App\Models\User;
use App\Models\Workspace;
use Illuminate\Http\RedirectResponse;

class SendInviteEmailController extends Controller
{
    public function __invoke(
        SendInviteEmailRequest $request,
        string $workspaceUuid,
        SendInviteEmail $sendInviteEmail
    ): RedirectResponse {
        $workspace = Workspace::where('uuid', $workspaceUuid)->firstOrFail();
        $sender = $request->user();

        abort_unless($sender instanceof User, 401);

        $sendInviteEmail->execute($workspace, $sender, $request->string('email')->toString());

        return back()->with('status', 'Convite enviado.');
    }
}
