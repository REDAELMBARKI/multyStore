<?php

namespace App\Listeners;

use App\Events\OrderConfirmed;
use App\Mail\OrderConfirmedMail;
use App\Models\User;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Mail;

class NotifyAdmins implements ShouldQueue
{
    use InteractsWithQueue, Queueable;
    public function handle(OrderConfirmed $event): void
    {
        $admins = $this->getRecipients();
        
        foreach ($admins as $adminEmail) {
            Mail::to($adminEmail)
                ->queue(new OrderConfirmedMail($event->order));
        }
    }

    public function getRecipients() : array {
         $admins  = User::whereHas("roles" , function($q){
               $q->whereIn("name" , ["super_admin" , "manager"]);
         })->pluck('email');

         if($admins->isNotEmpty() ){
            return [
                ...$admins->toArray() ,

            ] ;
         }
         return [] ;
                    
    }
}
