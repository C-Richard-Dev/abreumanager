<?php

namespace App\Http\Controllers\Workspaces;

use App\Actions\Workspaces\UpdateWorkspace;
use App\Http\Controllers\Controller;
use App\Http\Requests\Workspaces\UpdateWorkspaceRequest;
use App\Models\Workspace;
use Illuminate\Http\RedirectResponse;

class UpdateWorkspaceController extends Controller
{
    public function __invoke(
        UpdateWorkspaceRequest $request,
        string $workspaceUuid,
        UpdateWorkspace $updateWorkspace
    ): RedirectResponse {
        $workspace = Workspace::where('uuid', $workspaceUuid)->firstOrFail();
        $input = ['name' => $request['name']];

        $updateWorkspace->execute($workspace, $input);

        return redirect()
            ->route('workspaces.show', [
                'workspaceUuid' => $workspace->uuid,
            ]);
    }
}