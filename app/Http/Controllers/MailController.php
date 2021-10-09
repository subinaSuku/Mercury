<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\MailConfig;
use App\Models\MailSchedule;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;
use Illuminate\Support\Facades\Crypt;
use DB;


class MailController extends Controller
{

    public function getMail(){
      try{
        $mail= MailConfig::orderBy('updated_at','desc')->first();
         $password = Crypt::decryptString($mail->password);
       
         $this->response["data"] = $mail;
        $this->response["password"] = $password;
       
       return $this->successResponse($this->response);
   }
    catch (\Exception $e) {
        $this->response['error_message'] = $e->getMessage();
        return $this->errorResponse($this->response);
    }
}

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'mailer' => 'required',
            'host' => 'required',
            'port' => 'required',
            //'last_name' => 'required|max:255',
               'from_address' => 'nullable|email|max:255',
                'from_name' => 'required',
                'encryption' => 'required',
                'username' => 'nullable|email',
                'password' => 'required',
            'from_address' => 'regex:/^([a-z0-9\+_\-]+)(\.[a-z0-9\+_\-]+)*@([a-z0-9\-]+\.)+[a-z]{2,6}$/ix',
            'username' => 'regex:/^([a-z0-9\+_\-]+)(\.[a-z0-9\+_\-]+)*@([a-z0-9\-]+\.)+[a-z]{2,6}$/ix',
        ]);

    try {
        $this->response["title"] = Lang::get('');

        if ($validator->fails()) {
            $this->response["errors"] = $validator->errors();
            $this->response["old_data"] = $request->all();
            $this->response["message"] = Lang::get('');
            return $this->errorResponse($this->response);
        } else {
            
           
            $mailer = $request->input('mailer');
			$host = $request->input('host');
			$port = $request->input('port');
			$from_address = $request->input('from_address');
			$from_name = $request->input('from_name');
			$encryption = $request->input('encryption');
			$username = $request->input('username');
			$password =  Crypt::encryptString($request->input('password'));
		
			// Hash::check($request->newPasswordAtLogin, $hashedPassword)

				$mail = new MailConfig();
				$mail->mailer = $mailer;
				$mail->host = $host;
				$mail->port = $port;
				$mail->from_address = $from_address;
				$mail->from_name = $from_name;
				$mail->encryption = $encryption;
				$mail->username = $username;
				$mail->password = $password;
                $mail->created_at = Carbon::now();
                $mail->updated_at = Carbon::now();

				$mail->save();
                

            

            $this->response["message"] = Lang::get('');
            $this->response["data"] = $mail;
            return $this->successResponse($this->response);
        }
        } catch (\Exception $e) {
        $this->response['error_message'] = $e->getMessage();
        return $this->errorResponse($this->response);
         }
    }


    public function getMailSchedule(){
        try{
          $mail= MailSchedule::orderBy('updated_at','desc')->first();
           
           $this->response["data"] = $mail;
           
         
         return $this->successResponse($this->response);
     }
      catch (\Exception $e) {
          $this->response['error_message'] = $e->getMessage();
          return $this->errorResponse($this->response);
      }
  }


    public function store_schedule(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'days' => 'required',
            
        ]);

    try {
        $this->response["title"] = Lang::get('');

        if ($validator->fails()) {
            $this->response["errors"] = $validator->errors();
            $this->response["old_data"] = $request->all();
            $this->response["message"] = Lang::get('');
            return $this->errorResponse($this->response);
        } else {
            
           
            $mailer = $request->input('days');
			
		
			

				$mail = new MailSchedule();
				$mail->days = $mailer;
				

				$mail->save();
                

            

            $this->response["message"] = Lang::get('');
            $this->response["data"] = $mail;
            return $this->successResponse($this->response);
        }
        } catch (\Exception $e) {
        $this->response['error_message'] = $e->getMessage();
        return $this->errorResponse($this->response);
         }
    }

    
 }



