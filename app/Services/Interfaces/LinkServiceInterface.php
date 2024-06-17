<?php

namespace App\Services\Interfaces;

use App\Models\Link;
use Illuminate\Database\Eloquent\Collection;

interface LinkServiceInterface
{
    public function createLink(int $userId,string $originalUrl):Link;

    public function getTopLinks(int $count):Collection;

    public function getUserLinks(int $userId):Collection;

    public function searchLinks(string $query):Collection;

    public function redirectToOriginalUrl(string $shortenedUrl);
}
