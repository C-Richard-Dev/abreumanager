<?php

namespace App\Http\Controllers\Links;

use App\Actions\Links\UpdateLink;
use App\Http\Controllers\Controller;
use App\Http\Requests\Links\UpdateLinkRequest;
use App\Models\Link;
use Illuminate\Http\JsonResponse;

class UpdateLinkController extends Controller
{
    public function __invoke(
        UpdateLinkRequest $request,
        string $linkUuid,
        UpdateLink $updateLink
    ): JsonResponse {
        $link = Link::where('uuid', $linkUuid)->firstOrFail();

        $inputs = [
            'name' => $request->input('name'),
            'url' => $request->input('url'),
            'workspace_id' => $link->workspace_id,
            'category_id' => $request->input('category_id', $link->category_id),
        ];

        $updatedLink = $updateLink->handle($link, $inputs);

        return response()->json($updatedLink);
    }
}
