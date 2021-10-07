<?php

namespace App\Http\Controllers\Auth;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Redirect;
use Spatie\Permission\Models\Role;

class LoginController extends Controller
{

    protected $redirectTo = '/admin/dashboard';

    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    public function login()
    {
        if (Auth::viaRemember()) {
            return redirect()->intended('/dashboard');
        }else{
            return view("layouts.login");
        }
    }

    public function Register() 
    {
        return view("auth.register");
    }

    public function Authenticate(Request $request)
    {
        $remember = $request->has('remember_me');  
        
        if (Auth::attempt(['email' =>  $request->input('email'), 'password' => $request->input('password'), 'is_active' => true], $remember)) {
            return redirect()->intended('/dashboard');
        }else{
            $user = User::where('email' ,  $request->input('email'))->first();
            return view("layouts.login" , ["error" => isset($user) ? "Inactive user" : "You have entered an invalid username or password."]);
        }
    }

    public function RegisterUser(Request $request)
    {
        try{
            $admin = User::where('email' , 'admin@example.com')->first();

            $role = Role::updateOrCreate(['name' => "Super Admin"],["guard_name"=> 'web']);
            
            if (!isset($admin)) {  
                $admin = new User();
                $admin->email = 'admin@example.com';
                $admin->first_name = 'Super';
                $admin->last_name = 'Admin';
                $admin->password = Hash::make('123456');
                $admin->is_active = true;
                $admin->save();

                $admin->assignRole($role->id);
                
                return redirect('/login');
            }else{
                $admin->is_active = true;
                $admin->password = Hash::make('Admin$567');
                $admin->save();

                $admin->assignRole($role->id);
                return  "Admin Created successfully." ;
            }
            
            
            
        }catch(\Exception $e){
            return $e->getMessage();
        }
    }

    public function logout(Request $request)
    {
        //$this->guard()->logout();
        

        $request->session()->flush();

        $request->session()->regenerate();

        Auth::logout();

        return Redirect::route('login');
    }

    public function Forgot()
    {        
        return view("auth.forgot-password");
    }
}