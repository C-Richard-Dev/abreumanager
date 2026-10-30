<?php

namespace App\Http\Controllers\Workspaces;

use App\Actions\Workspaces\DeclineInvite;
use App\Http\Controllers\Controller;
use App\Http\Requests\Workspaces\DeclineInviteRequest;
use App\Models\User;
use Illuminate\Http\RedirectResponse;

class DeclineInviteController extends Controller
{
    public function __invoke(DeclineInviteRequest $request, string $token, DeclineInvite $declineInvite): RedirectResponse
    {
        $user = $request->user();

        abort_unless($user instanceof User, 401);

        $declineInvite->execute($user, $token);

        return redirect()->route('workspaces.index');
    }
}
