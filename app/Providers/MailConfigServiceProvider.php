<?php

namespace App\Providers; 

use Config;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\ServiceProvider;
use Crypt;
use Illuminate\Contracts\Encryption\DecryptException;

class MailConfigServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {

        
        if (\Schema::hasTable('mails')) {
            $mail = DB::table('mails') ->orderBy('updated_at', 'desc')->first();
            if ($mail) //checking if table is not empty
            {  
                $config = array(
                    'mailer'     => $mail->mailer,
                    'host'       => $mail->host,
                    'port'       => $mail->port,
                    'from'       => array('address' => $mail->from_address, 'name' => $mail->from_name),
                    'encryption' => $mail->encrption,
                    'username'   => $mail->username,
                    'password'   =>($mail->password),
                    'sendmail'   => '/usr/sbin/sendmail -bs',
                    'pretend'    => false,
                    //Crypt::decryptString
                );
                Config::set('mail', $config);
            }
        }
    } 
    

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
