<?php

use App\Modules\V1\Billing\Application\Jobs\DailyBillingCheckJob;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('billing:daily-check', function () {
    dispatch(new DailyBillingCheckJob());
})->purpose('Run daily billing checks for platforms');

Schedule::job(DailyBillingCheckJob::class)->dailyAt('00:01');
