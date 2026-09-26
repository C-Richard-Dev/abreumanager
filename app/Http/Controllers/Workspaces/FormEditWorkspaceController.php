<?php

namespace App\Http\Controllers\Workspaces;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Workspace;

class FormEditWorkspaceController extends Controller
{
    public function __invoke(string $workspaceUuid)
    {
        $workspace = Workspace::where('uuid', $workspaceUuid)->firstOrFail();

        return inertia('Workspaces/Edit', [
            'workspace' => $workspace,
        ]);
    }
}