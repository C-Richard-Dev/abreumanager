<?php

namespace App\Actions\Workspaces;

use App\Models\Workspace;

class DestroyWorkspace
{
    public function execute(Workspace $workspace): void
    {
        $workspace->delete();
    }
}