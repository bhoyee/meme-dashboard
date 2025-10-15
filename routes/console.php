<?php

use Illuminate\Console\Scheduling\Schedule;

app()->booted(function () {
    $schedule = app(Schedule::class);
    $schedule->command('alpha:fetch')->everyMinute(); // Use everyMinute for testing
      $schedule->command('alpha:scrape')->everyFiveMinutes();
});
