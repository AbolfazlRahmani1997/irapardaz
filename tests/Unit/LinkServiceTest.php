<?php

namespace Tests\Unit;

use App\Enums\LinkStatus;
use App\Models\Link;
use App\Models\User;
use App\Repositories\LinkRepository;
use App\Services\LinkService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Redis;
use Tests\TestCase;

class LinkServiceTest extends TestCase
{
    use RefreshDatabase;

    protected $linkService;
    protected $linkRepository;

    protected function setUp(): void
    {
        parent::setUp();

        $this->linkRepository = new LinkRepository();
        $this->linkService = new LinkService($this->linkRepository);
        Redis::shouldReceive('set')->andReturn(true);
        Redis::shouldReceive('get')->andReturn(null);
    }

    public function testCreateLink()
    {
        $user = User::factory()->create();
        $originalUrl = 'http://example.com';

        $link = $this->linkService->createLink($user->id, $originalUrl);

        $this->assertEquals($user->id, $link->user_id);
        $this->assertEquals($originalUrl, $link->original_url);
//        $this->assertNotNull($link->shortened_url);
        $this->assertEquals(0, $link->clicks);
    }

    public function testRedirectToOriginalUrl()
    {
        $link = Link::factory()->create(['shortened_url' => 'abc12']);

        Redis::set("link:{$link->shortened_url}", json_encode([
            'original_url' => $link->original_url,
            'shortened_url' => $link->shortened_url,
            'clicks' => $link->clicks,
        ]));

        $url = $this->linkService->redirectToOriginalUrl($link->shortened_url);
        $this->assertEquals($link->original_url, $url);
    }

    public function testGetTopLinks()
    {
        Link::factory()->create(['clicks' => 10,'status'=>LinkStatus::ACTIVE->value]);
        Link::factory()->create(['clicks' => 8,'status'=>LinkStatus::ACTIVE->value]);

        $result = $this->linkService->getTopLinks(2);

        $this->assertCount(2, $result);
        $this->assertEquals(10, $result[0]->clicks);
        $this->assertEquals(8, $result[1]->clicks);
    }

    public function testGetUserLinks()
    {
        $user = User::factory()->create();
        Link::factory()->create(['user_id' => $user->id]);
        Link::factory()->create(['user_id' => $user->id]);

        $userLinks = $this->linkService->getUserLinks($user->id);

        $this->assertCount(2, $userLinks);
        $this->assertEquals($user->id, $userLinks[0]->user_id);
    }

    public function testSearchLinks()
    {
        Link::factory()->create(['original_url' => 'http://example.com']);
        Link::factory()->create(['original_url' => 'http://test.com']);

        $searchResults = $this->linkService->searchLinks('example');

        $this->assertCount(1, $searchResults);
        $this->assertEquals('http://example.com', $searchResults[0]->original_url);
    }
}
