<?php

namespace App\Actions\Category;

use App\Models\Category;

class CreateCategory
{
    public function execute(array $inputs): Category
    {
        return Category::create($inputs);
    }
}