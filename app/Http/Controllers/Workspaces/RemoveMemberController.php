<?php

namespace App\Http\Controllers\Workspaces;

use App\Actions\Workspaces\RemoveMember;
use App\Http\Controllers\Controller;
use App\Http\Requests\Workspaces\RemoveMemberRequest;
use App\Models\Workspace;
use Illuminate\Http\RedirectResponse;

class RemoveMemberController extends Controller
{
    public function __invoke(
        RemoveMemberRequest $request,
        string $workspaceUuid,
        string $userUuid,
        RemoveMember $removeMember
    ): RedirectResponse {
        $workspace = Workspace::where('uuid', $workspaceUuid)->firstOrFail();

        $removeMember->execute($workspace, $userUuid);

        return back();
    }
}
