<?php

namespace App\Http\Controllers\Category;

use App\Http\Controllers\Controller;
use App\Http\Requests\Category\StoreCategoryRequest;
use App\Models\Category;
use App\Actions\Category\CreateCategory;
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
