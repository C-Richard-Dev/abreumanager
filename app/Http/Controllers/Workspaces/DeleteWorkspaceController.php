<?php

namespace App\Http\Controllers\Workspaces;

use App\Actions\Workspaces\DestroyWorkspace;
use App\Http\Controllers\Controller;
use App\Models\Workspace;
use Illuminate\Http\RedirectResponse;

class DeleteWorkspaceController extends Controller
{
    public function __invoke(
        string $workspaceUuid,
        DestroyWorkspace $destroyWorkspace
    ): RedirectResponse {
        $workspace = Workspace::where('uuid', $workspaceUuid)->firstOrFail();
        $destroyWorkspace->execute($workspace);

        return redirect()
            ->route('workspaces.index')
            ->with('success', 'Workspace deleted successfully.');
    }
}