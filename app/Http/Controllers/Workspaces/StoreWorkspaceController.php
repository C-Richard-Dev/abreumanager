<?php

namespace App\Http\Controllers\Workspaces;

use App\Http\Controllers\Controller;
use App\Http\Requests\Workspace\StoreWorkspaceRequest;
use App\Actions\Workspace\CreateWorkspaceAction;
use Illuminate\Http\RedirectResponse;

class StoreWorkspaceController extends Controller
{
    public function __invoke(
        StoreWorkspaceRequest $request,
        CreateWorkspaceAction $createWorkspaceAction
    ): RedirectResponse {
        $inputs = [
            'name' => $request->input('name')
        ];
        $createWorkspaceAction->execute($inputs);

        return redirect()
            ->route('workspaces.index');
    }
}