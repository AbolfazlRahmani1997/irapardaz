<?php

namespace Tests\Unit;

use App\Enums\LinkStatus;
use App\Models\Link;
use App\Models\User;
use App\Repositories\LinkRepository;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LinkRepositoryTest extends TestCase
{
    use RefreshDatabase;

    protected $linkRepository;

    protected function setUp(): void
    {
        parent::setUp();
        $this->linkRepository = new LinkRepository();
    }

    public function testCreateLink()
    {
        $user = User::factory()->create();
        $data = [
            'user_id' => $user->id,
            'original_url' => 'http://example.com',
            'shortened_url' => 'abc12',
            'clicks' => 0,
        ];

        $link = $this->linkRepository->create($data);

        $this->assertDatabaseHas('links', $data);
        $this->assertEquals('http://example.com', $link->original_url);
    }

    public function testGetTopLinks()
    {
        Link::factory()->create(['clicks' => 10,'status'=>LinkStatus::ACTIVE->value]);
        Link::factory()->create(['clicks' => 8,'status'=>LinkStatus::ACTIVE->value]);

        $topLinks = $this->linkRepository->getTopLinks(1);
        $this->assertCount(1, $topLinks);
        $this->assertEquals(10, $topLinks[0]->clicks);
    }

    public function testGetUserLinks()
    {
        $user = User::factory()->create();
        Link::factory()->create(['user_id' => $user->id]);
        Link::factory()->create(['user_id' => $user->id]);;

        $userLinks = $this->linkRepository->getUserLinks($user->id);

        $this->assertCount(2, $userLinks);
        $this->assertEquals($user->id, $userLinks[0]->user_id);
    }

    public function testSearchLinks()
    {
        Link::factory()->create(['original_url' => 'http://example.com']);
        Link::factory()->create(['original_url' => 'http://test.com']);

        $searchResults = $this->linkRepository->search('example');

        $this->assertCount(1, $searchResults);
        $this->assertEquals('http://example.com', $searchResults[0]->original_url);
    }

    public function testFindByShortenedUrl()
    {
        $shortenedUrl = 'abc12';
        Link::factory()->create(['shortened_url' => $shortenedUrl]);

        $link = $this->linkRepository->findByShortenedUrl($shortenedUrl);

        $this->assertNotNull($link);
        $this->assertEquals($shortenedUrl, $link->shortened_url);
    }

    public function testUpdateLink()
    {
        $link = Link::factory()->create(['clicks' => 0]);
        $this->linkRepository->update($link->id, ['clicks' => 5]);

        $updatedLink = Link::find($link->id);

        $this->assertEquals(5, $updatedLink->clicks);
    }
}
