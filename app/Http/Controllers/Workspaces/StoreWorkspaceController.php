<?php

namespace App\Http\Controllers\Workspaces;

use App\Http\Controllers\Controller;
use App\Http\Requests\Workspace\StoreWorkspaceRequest;
use App\Actions\Workspace\CreateWorkspace;
use Illuminate\Http\RedirectResponse;

class StoreWorkspaceController extends Controller
{
    public function __invoke(
        StoreWorkspaceRequest $request,
        CreateWorkspace $createWorkspace
    ): RedirectResponse {
        $inputs = [
            'name' => $request->input('name')
        ];
        $createWorkspace->execute($inputs);

        return redirect()
            ->route('workspaces.index');
    }
}