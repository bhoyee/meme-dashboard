<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class FetchAlphaTokens extends Command
{
    protected $signature = 'alpha:fetch';
    protected $description = 'Simulate fetching alpha meme tokens';

    public function handle()
    {
        Log::info('Alpha token fetch simulated at ' . now());
        $this->info('Alpha token fetch simulated.');
    }
}
