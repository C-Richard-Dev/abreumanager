<?php

namespace App\Http\Controllers\Workspaces;

use App\Http\Controllers\Controller;
use Inertia\Response;

class ListWorkspaceController extends Controller
{
    public function __invoke(): Response
    {
        return inertia('Workspace/Index', [
            'workspaces' => auth()->user()
                ->workspaces()
                ->with('creator:id,name')
                ->orderBy('created_at')
                ->get(),
        ]);
    }
}
