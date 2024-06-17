<?php

namespace App\Services;

use App\Repositories\Interfaces\LinkRepositoryInterface;
use App\Services\Interfaces\LinkServiceInterface;
use Illuminate\Support\Facades\Redis;

class LinkService implements LinkServiceInterface
{
    protected LinkRepositoryInterface $linkRepository;

    public function __construct(LinkRepositoryInterface $linkRepository)
    {
        $this->linkRepository = $linkRepository;
    }

    public function createLink(int $userId, string $originalUrl): \App\Models\Link
    {
        $shortenedUrl = $this->generateShortenedUrl();
        $link = $this->linkRepository->create(data:[
            'user_id' => $userId,
            'original_url' => $originalUrl,
            'shortened_url' => $shortenedUrl,
            'clicks' => 0,
        ]);

        Redis::set("link:{$shortenedUrl}", json_encode($link));
        return $link;
    }

    public function getTopLinks(int $count): \Illuminate\Database\Eloquent\Collection
    {
        return $this->linkRepository->getTopLinks(count:$count);
    }

    public function getUserLinks(int $userId): \Illuminate\Database\Eloquent\Collection
    {
        return $this->linkRepository->getUserLinks(userId:$userId);
    }

    public function searchLinks(string $query): \Illuminate\Database\Eloquent\Collection
    {
        return $this->linkRepository->search(query: $query);
    }

    public function redirectToOriginalUrl(string $shortenedUrl)
    {
        $cachedLink = Redis::get("link:{$shortenedUrl}");

        if ($cachedLink) {
            $link = json_decode($cachedLink, true);
            $link['clicks'] += 1;
            $this->linkRepository->incrementClicks($link['id']);

            Redis::set("link:{$shortenedUrl}", json_encode($link));
        } else {
            $link = $this->linkRepository->findByShortenedUrl($shortenedUrl);
            if ($link) {
                $link->clicks += 1;
                $this->linkRepository->update($link->id, ['clicks' => $link->clicks]);
                Redis::set("link:{$shortenedUrl}", json_encode($link));
            }
        }

        return $link ? $link['original_url'] : null;
    }

    private function generateShortenedUrl(): string
    {
        return substr(str_shuffle(config('link.character_set')), 0, 5);
    }
}
