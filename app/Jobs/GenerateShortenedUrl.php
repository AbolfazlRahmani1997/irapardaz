<?php

namespace App\Jobs;

use App\Services\Interfaces\LinkServiceInterface;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class GenerateShortenedUrl implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private LinkServiceInterface $linkService;

    /**
     * Create a new job instance.
     */
    public function __construct(private int $linkId)
    {
        /** @var LinkServiceInterface $linkService */
        $this->linkService = app()->make(LinkServiceInterface::class);
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $this->linkService->generateShortenedUrlForLink($this->linkId);
    }
}
