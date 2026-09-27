<?php

namespace App\Http\Controllers\Workspaces;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Workspace;

class FormEditWorkspaceController extends Controller
{
    public function __invoke(string $workspaceUuid): \Inertia\Response
    {
        $workspace = Workspace::where('uuid', $workspaceUuid)->firstOrFail();

        return inertia('Workspace/Edit', [
            'workspace' => $workspace,
        ]);
    }
}