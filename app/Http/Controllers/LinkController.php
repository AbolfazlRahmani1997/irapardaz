<?php

// app/Http/Controllers/LinkController.php
namespace App\Http\Controllers;

use App\Http\Requests\StoreLinkRequest;
use App\Http\Resources\LinkResource;
use App\Services\Interfaces\LinkServiceInterface;
use Illuminate\Http\Request;

class LinkController extends Controller
{
    protected LinkServiceInterface $linkService;

    public function __construct(LinkServiceInterface $linkService)
    {
        $this->linkService = $linkService;
    }

    public function create(StoreLinkRequest $request)
    {
        $link = $this->linkService->createLink($request->user_id, $request->original_url);
        return new LinkResource($link);
    }

    public function topLinks(Request $request)
    {
        $count = $request->get('count', 5);
        $links = $this->linkService->getTopLinks($count);
        return LinkResource::collection($links);
    }

    public function userLinks($userId)
    {
        $links = $this->linkService->getUserLinks($userId);
        return LinkResource::collection($links);
    }

    public function search(Request $request)
    {
        $query = $request->get('query', '');
        $links = $this->linkService->searchLinks($query);
        return LinkResource::collection($links);
    }

    public function redirect($shortenedUrl)
    {
        $originalUrl = $this->linkService->redirectToOriginalUrl($shortenedUrl);
        if ($originalUrl) {
            return redirect($originalUrl);
        }
        return abort(404);
    }
}
