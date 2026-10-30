<?php

namespace App\Http\Controllers\Workspaces;

use App\Http\Controllers\Controller;
use App\Models\Workspace;

class ListUsersOfWorkspace extends Controller
{
    public function __invoke(string $workspaceUuid)
    {
        $workspace = Workspace::where('uuid', $workspaceUuid)->firstOrFail();

        return response()->json($workspace->users->makeHidden('id'));
    }
}
