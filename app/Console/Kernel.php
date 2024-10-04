<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     */
    protected function schedule(Schedule $schedule): void
    {
        // $schedule->command('inspire')->hourly();
        $schedule->call(function () {
            $inmunizaciones = \App\Models\Inmunizacion::whereDate('fecha_aplicacion', now()->addDays(2))->get();
    
            foreach ($inmunizaciones as $inmunizacion) {
                $user = User::find($inmunizacion->user_id); // Asumiendo que cada inmunización tiene un user_id
                $user->notify(new InmunizacionProxima($inmunizacion));
            }
        })->daily();
    }
        // $schedule->command('inspire')->hourly();
       

    /**
     * Register the commands for the application.
     */
    protected function commands(): void
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
