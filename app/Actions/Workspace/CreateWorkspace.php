<?php

namespace App\Actions\Workspace;

use App\Models\User;
use App\Models\Workspace;
use App\Models\Pivot\UserWorkspace;

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