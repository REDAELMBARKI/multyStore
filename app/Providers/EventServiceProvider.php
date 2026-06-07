<?php

namespace App\Providers;

use App\Events\OrderConfirmed;
use App\Events\UserLogin;
use App\Events\NewStoreCreation;

use App\Listeners\SeedStoreDefaults;
use App\Listeners\WelcomeBack;
use App\Listeners\DecrementStock;
use App\Listeners\HandleUserRegister;
use App\Listeners\HandleUserWelcomming;
use App\Listeners\NotifyAdmin;
use App\Listeners\NotifyAdmins;
use App\Listeners\SendInvoice;
use Illuminate\Auth\Events\Login;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\ServiceProvider;

class EventServiceProvider extends ServiceProvider
{

    protected $listen = [
            NewStoreCreation::class => [
                  SeedStoreDefaults::class
            ],
            OrderConfirmed::class => [
                DecrementStock::class ,
                NotifyAdmins::class ,
                SendInvoice::class
            ] ,
            UserLogin::class => [
                HandleUserWelcomming::class

            ] ,
            Registered::class => [
                HandleUserRegister::class
            ]
            ];
    

}
