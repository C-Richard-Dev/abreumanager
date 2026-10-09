<?php

namespace App\Http\Controllers\Links;

use App\Actions\Links\CreateLink;
use App\Http\Controllers\Controller;
use App\Http\Requests\Links\StoreLinkRequest;
use App\Models\Workspace;
use Illuminate\Http\JsonResponse;

class StoreLinkController extends Controller
{
    public function __invoke(
        StoreLinkRequest $request,
        string $workspaceUuid,
        CreateLink $createLink
    ): JsonResponse {
        $workspace = Workspace::where('uuid', $workspaceUuid)->firstOrFail();

        $inputs = [
            'name' => $request->input('name'),
            'url' => $request->input('url'),
            'workspace_id' => $workspace->id,
            'category_id' => $request->input('category_id'),
        ];

        $link = $createLink->handle($inputs);

        return response()->json($link, 201);
    }
}
