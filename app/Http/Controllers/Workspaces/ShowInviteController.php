<?php

namespace App\Http\Controllers\Workspaces;

use App\Actions\Workspaces\AcceptInvite;
use App\Http\Controllers\Controller;
use App\Http\Requests\Workspaces\AcceptInviteRequest;
use App\Models\User;
use Inertia\Inertia;
use Inertia\Response;

class ShowInviteController extends Controller
{
    public function __invoke(AcceptInviteRequest $request, string $token, AcceptInvite $acceptInvite): Response
    {
        $user = $request->user();

        abort_unless($user instanceof User, 401);

        $invite = $acceptInvite->findValid($token, $user);

        abort_unless($invite, 404);

        return Inertia::render('invite/Accept', [
            'token' => $token,
            'workspaceName' => $invite->workspace_name,
            'senderName' => $invite->sender_name,
        ]);
    }
}
