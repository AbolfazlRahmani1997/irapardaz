<?php

namespace App\Repositories;

use App\Models\Link;
use App\Repositories\Interfaces\LinkRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;

class LinkRepository implements LinkRepositoryInterface
{
    public function all(): Collection
    {
        return Link::all();
    }

    public function find(int $id): Link
    {
        return Link::find($id);
    }

    public function create(array $data): Link
    {
        return Link::create($data);
    }

    public function update($id, array $data): bool
    {
        return Link::where('id', $id)->update($data);
    }

    public function delete(int $id): bool
    {
        return Link::destroy($id);
    }

    public function findByShortenedUrl($shortenedUrl): Link
    {
        return Link::where('shortened_url', $shortenedUrl)->first();
    }


    public function getUserLinks(int $userId): Collection
    {
        return Link::where('user_id', $userId)->get();
    }

    public function incrementClicks(int $id): Link
    {
        $link = Link::find($id);
        $link->increment('clicks');
        return $link;
    }

    public function getTopLinks(int $count):Collection
    {
        return Link::orderBy('clicks', 'desc')->take($count)->get();
    }

    public function search($query):Collection
    {
        return Link::where('original_url', 'LIKE', '%' . $query . '%')->get();
    }
}
