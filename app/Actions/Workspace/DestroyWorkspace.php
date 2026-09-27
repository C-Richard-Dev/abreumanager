<?php

namespace App\Actions\Workspace;

use App\Models\Workspace;

class DestroyWorkspace
{
    public function execute(Workspace $workspace): void
    {
        $workspace->delete();
    }
}