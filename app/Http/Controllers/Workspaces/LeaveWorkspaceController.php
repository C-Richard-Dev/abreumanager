<?php

namespace App\Http\Controllers\Workspaces;

use App\Actions\Workspaces\LeaveWorkspace;
use App\Http\Controllers\Controller;
use App\Http\Requests\Workspaces\LeaveWorkspaceRequest;
use App\Models\User;
use App\Models\Workspace;
use Illuminate\Http\RedirectResponse;

class LeaveWorkspaceController extends Controller
{
    public function __invoke(
        LeaveWorkspaceRequest $request,
        string $workspaceUuid,
        LeaveWorkspace $leaveWorkspace
    ): RedirectResponse {
        $workspace = Workspace::where('uuid', $workspaceUuid)->firstOrFail();
        $user = $request->user();

        abort_unless($user instanceof User, 401);

        $leaveWorkspace->execute($workspace, $user);

        return redirect()->route('workspaces.index');
    }
}
