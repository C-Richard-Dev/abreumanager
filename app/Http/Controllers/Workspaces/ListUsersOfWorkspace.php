<?php

namespace App\Http\Controllers\Workspaces;

use App\Http\Controllers\Controller;
use App\Models\Workspace;
use Illuminate\Http\Request;

class ListUsersOfWorkspace extends Controller
{
    public function __invoke(Request $request, string $workspaceUuid)
    {
        $workspace = Workspace::where('uuid', $workspaceUuid)->firstOrFail();
        return response()->json($workspace->users);
    }
}