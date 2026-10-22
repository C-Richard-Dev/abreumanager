<?php

namespace App\Actions\Workspaces;

use App\Models\Pivot\UserWorkspace;
use App\Models\Workspace;

class CreateWorkspace
{
    public function execute(array $inputs): Workspace
    {
        $name = rtrim($inputs['name']);

        $alreadyExists = auth()->user()
            ->workspaces()
            ->where('name', 'like', $name . '%')
            ->count();

        if ($alreadyExists > 0) {
            $name = $name . ($alreadyExists + 1);
        }

        $workspace = Workspace::create([
            'name' => $name,
        ]);

        UserWorkspace::create([
            'user_id' => auth()->id(),
            'workspace_id' => $workspace->id,
        ]);

        return $workspace;
    }
}