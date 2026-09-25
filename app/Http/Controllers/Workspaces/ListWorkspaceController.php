<?php

namespace App\Http\Controllers\Workspaces;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ListWorkspaceController extends Controller
{
    public function __invoke(): \Inertia\Response
    {
        return inertia('Workspace/Index', [
            'workspaces' => auth()->user()->workspaces
        ]);
    }
}
