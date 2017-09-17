<?php

namespace App\Console\Commands\Reservation;

use App\Models\Reservation;
use Illuminate\Console\Command;

class CancelReservations extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'cancel:payments';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Cancel pending payments which are not completed';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $reservations = Reservation::where('');
    }
}
