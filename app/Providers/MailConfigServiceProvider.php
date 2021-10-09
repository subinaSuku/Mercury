<?php

namespace App\Providers; 

use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Contracts\Encryption\DecryptException;
use Illuminate\Support\Facades\Schema;

class MailConfigServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {

        
        
            $mail = DB::table('mail_configs') ->orderBy('updated_at', 'desc')->first();
            if ($mail) //checking if table is not empty
            {  
                $config = array(
                    'mailer'     => $mail->mailer,
                    'host'       => $mail->host,
                    'port'       => $mail->port,
                    'from'       => array('address' => $mail->from_address, 'name' => $mail->from_name),
                    'encryption' => $mail->encryption,
                    'username'   => $mail->username,
                    'password'   =>Crypt::decryptString($mail->password),
                    'sendmail'   => '/usr/sbin/sendmail -bs',
                    'pretend'    => false,
                    //Crypt::decryptString
                );
                Config::set('mail', $config);
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
