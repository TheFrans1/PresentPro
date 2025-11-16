<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Console\Scheduling\Schedule;
use App\Console\Commands\AutoAbsenPulang;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {

    // Daftarkan 'role' sebagai shortcut untuk CekRole::class
    $middleware->alias([
        'role' => \App\Http\Middleware\CekRole::class,
    ]);

    // Pastikan middleware 'auth' juga ter-redirect ke login
    $middleware->redirectGuestsTo('/login');
})
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })

    ->withSchedule(function (Schedule $schedule) {
        // Menjalankan command 'absensi:auto-pulang' setiap hari jam 9 malam (21:00)
        $schedule->command(AutoAbsenPulang::class)->dailyAt('21:00')->timezone('Asia/Jakarta');
    })
    ->create();
