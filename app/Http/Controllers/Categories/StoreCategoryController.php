<?php

namespace App\Http\Controllers\Categories;

use App\Actions\Categories\CreateCategory;
use App\Http\Controllers\Controller;
use App\Http\Requests\Categories\StoreCategoryRequest;
use App\Models\Category;
use Illuminate\Http\JsonResponse;

class StoreCategoryController extends Controller
{
    public function __invoke(
        StoreCategoryRequest $request,
        string $workspaceUuid,
        CreateCategory $createCategory
    ): JsonResponse {
        $workspace = Category::where('workspace_id', $workspaceUuid)
            ->first();

        $inputs = [
            'name' => $request->input('name'),
            'description' => $request->input('description'),
            'workspace_id' => $workspace->id,
        ];

        $category = $createCategory->execute($inputs);

        return response()->json($category, 201);
    }
}
