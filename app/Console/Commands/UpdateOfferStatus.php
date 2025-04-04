<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Offer;
use Illuminate\Console\Scheduling\Schedule;

class UpdateOfferStatus extends Command
{
    protected $signature = 'offers:update-status';
    protected $description = 'Actualizar el estado de las ofertas automáticamente según las fechas';

    public function handle()
    {
        Offer::where('start_date', '<=', now())
            ->where('end_date', '>=', now())
            ->update(['is_active' => true]);

        Offer::where('end_date', '<', now())
            ->update(['is_active' => false]);

        $this->info('Ofertas actualizadas correctamente.');
    }

    // 📌 Agregar esto para programar el comando
    public function schedule(Schedule $schedule): void
    {
        $schedule->command('offers:update-status')->everyMinute();
    }
}
