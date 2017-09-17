<?php

namespace App\Console\Commands\Payment;

use App\Models\Payment;
use Illuminate\Console\Command;

class UpdatePaymentTypes extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'payments:update';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update all `open` (in behandeling) to `cancelled` (geannuleerd) every night';

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
        Payment::where("status", "open")->update(['status' => 'cancelled']);
    }
}
