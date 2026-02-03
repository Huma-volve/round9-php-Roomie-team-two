<?php

use App\Services\InstallmentReminderService;
use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

Schedule::call(function () {
    app(InstallmentReminderService::class)->sendUpcomingReminders();
})->everyMinute();

Schedule::call(function () {
    app(InstallmentReminderService::class)->handleOverdueInstallments();
})->everyTenSeconds();


// Process Webhook Payments in case of webhook failure
Schedule::command('payments:process-webhook')->everyFiveMinutes()->withoutOverlapping();
