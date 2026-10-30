<?php

namespace App\Http\Controllers\Workspaces;

use App\Actions\Workspaces\AcceptInvite;
use App\Http\Controllers\Controller;
use App\Http\Requests\Workspaces\AcceptInviteRequest;
use App\Models\User;
use Illuminate\Http\RedirectResponse;

class AcceptInviteController extends Controller
{
    public function __invoke(AcceptInviteRequest $request, string $token, AcceptInvite $acceptInvite): RedirectResponse
    {
        $user = $request->user();

        abort_unless($user instanceof User, 401);

        $workspace = $acceptInvite->execute($user, $token);

        return redirect()
            ->route('invite.success');
    }
}
