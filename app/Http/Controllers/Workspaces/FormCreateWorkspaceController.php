<?php

namespace App\Http\Controllers\Workspaces;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class FormCreateWorkspaceController extends Controller
{
    public function __invoke()
    {
        return inertia('Workspace/Create', [
            'workspaces_count' => auth()->user()->workspaces()->count(),
        ]);
    }
}