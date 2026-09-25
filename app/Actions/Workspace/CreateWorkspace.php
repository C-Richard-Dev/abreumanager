<?php

namespace App\Actions\Workspace;

use App\Models\Workspace;

class CreateWorkspaceAction
{
    public function execute(array $inputs): Workspace
    {
        return Workspace::create(
            [
                'name' => $inputs['name']
            ]
        );
    }
}