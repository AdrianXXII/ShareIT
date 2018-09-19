<?php

namespace App\Console\Commands;

use App\Notification;
use Carbon\Carbon;
use Illuminate\Console\Command;

class SendNotifications extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'notification:send';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Sends Notifications';

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
        //
        $notifications = Notification::pending();
        foreach($notifications as $notification)
        {
            $notification->status = Notification::STATUS_SENDING;
            $notification->save();
        }
        foreach($notifications as $notification) {
            if(true){
                $notification->status = Notification::STATUS_SUCCESS;
            }
            elseif($notification->getCreationDate()->diffInDays(Carbon::now) > 30){
                $notification->status = Notification::STATUS_GIVEN_UP;
            }
            else {
                $notification->status = Notification::STATUS_FAILED;
            }
            $notification->save();
        }
    }
}
