<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class SyncWhaleAlerts extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:sync-whale-alerts';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
public function handle()
{
    require base_path('telegramListener.php');
}

}
