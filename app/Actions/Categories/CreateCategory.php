<?php

namespace App\Actions\Categories;

use App\Models\Category;

class CreateCategory
{
    public function execute(array $inputs): Category
    {
        $name = rtrim($inputs['name']);
        
        $category = Category::create([
            'name' => $name,
            'description' => rtrim($inputs['description'] ?? ''),
            'workspace_id' => $inputs['workspace_id'],
        ]);

        return $category;
    }
}