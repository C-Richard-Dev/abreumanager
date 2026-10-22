<?php

namespace App\Actions\Workspaces;

use App\Models\Workspace;

class UpdateWorkspace
{
    public function execute(
        Workspace $workspace,
        array $input
    ): Workspace {
        $workspace->update(['name' => $input['name']]);

        return $workspace;
    }
}