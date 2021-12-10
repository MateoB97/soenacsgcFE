<?php

namespace App\Jobs;

use App\Tools;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class requestApi implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $urlPost;
    public $resolutionData;
    public $authorization;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($urlPost, $resolutionData, $authorization)
    {
        $this->urlPost = $urlPost;
        $this->resolutionData = $resolutionData;
        $this->authorization = $authorization;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        Tools::http_post($this->urlPost, $this->resolutionData, $this->authorization);
    }
}
