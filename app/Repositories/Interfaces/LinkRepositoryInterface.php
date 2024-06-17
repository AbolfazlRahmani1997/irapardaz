<?php

namespace App\Repositories\Interfaces;

use App\Models\Link;
use Illuminate\Database\Eloquent\Collection;

interface LinkRepositoryInterface
{
    public function all():Collection;

    public function find(int $id):Link;

    public function create(array $data):Link;

    public function update(int $id, array $data):bool;

    public function delete(int $id):bool;

    public function findByShortenedUrl(string $shortenedUrl):Link|null;

    public function getUserLinks(int $userId):Collection;

    public function incrementClicks(int $id):Link;

    public function getTopLinks(int $count):Collection;

    public function search(string $query):Collection;
}
