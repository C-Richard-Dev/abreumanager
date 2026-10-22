<?php

namespace App\Http\Middleware;

use App\Models\Category;
use App\Models\Link;
use App\Models\User;
use App\Models\Workspace;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureWorkspaceAccess
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $workspaceUuid = $request->route('workspaceUuid');
        $categoryUuid = $request->route('categoryUuid');
        $linkUuid = $request->route('linkUuid');

        $workspace = match (true) {
            is_string($workspaceUuid) => Workspace::where('uuid', $workspaceUuid)->firstOrFail(),
            is_string($categoryUuid) => Category::where('uuid', $categoryUuid)->firstOrFail()->workspace()->firstOrFail(),
            is_string($linkUuid) => Link::where('uuid', $linkUuid)->firstOrFail()->workspace()->firstOrFail(),
            default => abort(Response::HTTP_NOT_FOUND),
        };

        $user = $request->user();

        abort_unless($user instanceof User, Response::HTTP_UNAUTHORIZED);
        abort_unless(
            $user->workspaces()->whereKey($workspace->getKey())->exists(),
            Response::HTTP_NOT_FOUND,
        );

        return $next($request);
    }
}
