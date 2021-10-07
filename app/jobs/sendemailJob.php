<?php

namespace App\Jobs;

use App\Mail\MyTestMail;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;
use DB;

class SendEmailJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {

       

        $users = DB::select('SELECT * FROM users1 WHERE DATEDIFF(NOW(),expire_in) = -15;');
        // select * from orders where orderdate > cast(getdate() - 1 as date)
        foreach ($users as $user){
        $email = $user->email;
        $details = [
            'title' => 'Expiry Alert Message - Mercury Insurance',
            'username' => $user->name,
            'userservice' => $user->service,
            'bill' => $user->bill_no
        ];
                Mail::to($email)->send(new MyTestMail($details));
      
    }
    }
}