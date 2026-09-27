<?php

namespace App\Http\Controllers\Workspaces;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use App\Http\Requests\Workspace\UpdateWorkspaceRequest;
use App\Models\Workspace;
use App\Actions\Workspace\UpdateWorkspace;

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

        return redirect()->route('workspaces.show', [
                'workspaceUuid' => $workspace->uuid
            ]
        );
    }
}