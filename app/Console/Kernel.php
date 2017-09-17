<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use Psy\Command\Command;

class Kernel extends ConsoleKernel
{

    protected $commands = [
        Commands\Affiliate\Affilinet::class,
        Commands\Affiliate\Daisycon::class,
        Commands\Affiliate\Duplicates::class,
        Commands\Affiliate\FamilyBlend::class,
        Commands\Affiliate\Remove::class,
        Commands\Affiliate\Tradetracker::class,
        Commands\Affiliate\Zanox::class,
   	    Commands\Affiliate\Tradedoubler::class,
        Commands\Appointment\Reminder::class,
        Commands\Barcode\Expired::class,
        Commands\Guest\Couverts::class,
        Commands\Guest\EetNu::class,
        Commands\Guest\SeatMe::class,
        Commands\Guest\Wifi::class,
        Commands\Guest\Regio::class,
        Commands\Invoice\DirectDebit::class,
        Commands\Invoice\Product::class,
        Commands\Invoice\Reminder::class,
        Commands\Invoice\Reservation::class,
        Commands\Invoice\Mollie::class,
        Commands\Other\EetNu::class,
        Commands\Other\Sitemap::class,
        Commands\Other\AffTargetLink::class,
        Commands\Payment\Validate::class,
        Commands\Reservation\Pay::class,
        Commands\Reservation\Reminder::class,
        Commands\Reservation\ThirdParty::class,
        Commands\Reservation\Today::class,
        Commands\Review\Reminder::class,
        Commands\Transaction\Affilinet::class,
        Commands\Transaction\Daisycon::class,
        Commands\Transaction\Expired::class,
        Commands\Transaction\Tradetracker::class,
        Commands\Transaction\Tradedoubler::class,
        Commands\Transaction\Zanox::class,
        Commands\Transaction\Que::class,
        Commands\Newsletter\NewsletterEmail::class,
        Commands\Payment\UpdatePaymentTypes::class,
    ];

    protected function schedule(Schedule $schedule)
    {
        // affiliate
        $schedule
            ->command('daisycon:affiliate')
            ->daily()
        ;

        $schedule
            ->command('tradetracker:affiliate')
            ->daily()
        ;
        
        $schedule
            ->command('dulicates:affiliate')
            ->daily()
        ;

        $schedule
            ->command('afftargetlink:other')
            ->daily()
        ;

        // Barcode
        $schedule
            ->command('expired:barcode')
            ->hourly()
        ;

        // Guest
        $schedule
            ->command('wifi:guest')
            ->everyFiveMinutes()
        ;

        $schedule
            ->command('regio:guest')
            ->everyFiveMinutes()
        ;

        $schedule
            ->command('couverts:guest')
            ->everyFiveMinutes()
        ;

        $schedule
            ->command('eetnu:guest')
            ->everyFiveMinutes()
        ;

        $schedule
            ->command('seatme:guest')
            ->everyFiveMinutes()
        ;

        // Invoice
        $schedule
            ->command('debit:invoice')
            ->everyMinute()
        ;

        $schedule
            ->command('product:invoice')
            ->everyMinute()
        ;

        $schedule
            ->command('reminder:invoice')
            ->everyMinute()
        ;

        $schedule
            ->command('reservation:invoice')
            ->everyMinute()
        ;

        // Other
        $schedule
            ->command('sitemap:other')
            ->weekly()
        ;

        // Payment
        $schedule
            ->command('validate:payment')
            ->everyMinute()
        ;

        // Reservation
        $schedule
            ->command('pay:reservation')
            ->everyMinute()
        ;

        $schedule
            ->command('thirdparty:reservation')
            ->everyMinute()
        ;

        $schedule
            ->command('reminder:reservation')
            ->everyMinute()
        ;

        $schedule
            ->command('today:reservation')
            ->daily()
        ;

        // Transaction
        $schedule
            ->command('daisycon:transaction')
            ->everyFiveMinutes()
        ;

        $schedule
            ->command('tradetracker:transaction')
            ->everyFiveMinutes()
        ;

        $schedule
            ->command('tradedoubler:transaction')
            ->everyFiveMinutes()
        ;

        $schedule
            ->command('affilinet:affiliate')
            ->daily()
        ;

        $schedule
            ->command('affilinet:transaction')
            ->everyFiveMinutes()
        ;

         $schedule
            ->command('zanox:affiliate')
            ->daily()
        ;

        $schedule
            ->command('zanox:transaction')
            ->everyFiveMinutes()
        ;

        $schedule
            ->command('expired:transaction')
            ->daily()
        ;

        $schedule
            ->command('que:transaction')
            ->everyMinute()
        ;

        // Appointment
        $schedule
            ->command('reminder:appointment')
            ->everyMinute()
        ;

        // Others
        $schedule
            ->command('eetnu:other')
            ->hourly()
        ;

        //Newsletter Deals
        $schedule
          ->command('newsletter:dealmail')
          ->hourly()
        ;

        $schedule
            ->command('payments:update')
            ->daily()
        ;
    }
}
