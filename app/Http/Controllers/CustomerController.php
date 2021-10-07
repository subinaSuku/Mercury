<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Customer;
use App\Models\ServiceType;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Models\CustomerService;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class CustomerController extends Controller
{
  var $filter = [
      "searchText" => "",
      "itemPerPage" => 15
  ];

  public function __construct()
  {

  }

  public function index()
  {
  }

  public function list(Request $request)
    {
        try {
            $filter = $request->all();  
            $page = $filter['page'] ?? 1;   

            $customers = Customer::where('is_deleted' , false);

            if(isset($filter['searchText'])){
                $customers =  $customers->where(function($query) use($filter){
                                            $query->where('first_name' , 'LIKE' , '%'.$filter['searchText'].'%')
                                                    ->orWhere('last_name' , 'LIKE' , '%'.$filter['searchText'].'%')
                                                    ->orWhere('email' , 'LIKE' , '%'.$filter['searchText'].'%')
                                                    ->orWhere('customer_no' , 'LIKE' , '%'.$filter['searchText'].'%')
                                                    ->orWhere('mobile' , 'LIKE' , '%'.$filter['searchText'].'%');
                                        });
            }

            $this->response["totalItems"] = $customers->count();

            $customers = $customers->offset(isset($filter['limit']) ? $filter['limit'] * ( $page - 1 ) : 15 * ( $page - 1 ))
                                    ->limit(isset($filter['limit']) ? $filter['limit'] : 15)
                                    ->orderBy(isset($filter['order_column']) ? $filter['order_column'] : 'updated_at', isset($filter['order_by']) ? $filter['order_by'] : 'desc')
                                    // ->withCount(['maxxcoins as maxxcoins' => function($query){
                                    //     $query->whereDate('expiry_date' , '>=' ,  Carbon::now());
                                    // }])
                                    ->with(['user'])
                                    ->latest()
                                    ->get();

            $this->response["currentPage"] = $page;
            $this->response["filter"] = [];
            $this->response['data'] = $customers;
            return $this->successResponse($this->response);
        } catch (\Exception $e){
            $this->response['error_message'] = $e->getMessage();
            return $this->errorResponse($this->response);
        }
    }

    public function bills(Request $request)
    {
        try {
            $filter = $request->all();  
            $page = $filter['page'] ?? 1;   
            

            $services = CustomerService::where('is_deleted' , false);

            if(isset($filter['searchText'])){
                $services =  $services->where(function($query) use($filter){
                                            $query->where('account_name' , 'LIKE' , '%'.$filter['searchText'].'%')
                                                    ->orWhere('bill_number' , 'LIKE' , '%'.$filter['searchText'].'%')
                                                    ->orWhereHas('customer', function($query) use ($filter) {
                                                        $query->where('first_name' , 'LIKE' , '%'.$filter['searchText'].'%')
                                                        ->orWhere('last_name' , 'LIKE' , '%'.$filter['searchText'].'%')
                                                         ->orWhere('email' , 'LIKE' , '%'.$filter['searchText'].'%')
                                                        ->orWhere('customer_no' , 'LIKE' , '%'.$filter['searchText'].'%')
                                                        ->orWhere('mobile' , 'LIKE' , '%'.$filter['searchText'].'%');
                                                    })->orWhereHas('type', function($query) use ($filter) {
                                                        $query->where('name' , 'LIKE' , '%'.$filter['searchText'].'%');
                                                    });
                                        });
            }
            //return $filter;

            $is_expired = $filter['is_expired'] ?? 0;
            $expire_in = $filter['expire_in'] ?? '';

            if($is_expired == 1){
                $services =  $services->whereDate('expires_at' , '<', Carbon::now());
            }

            if($is_expired == 0){
                if($expire_in != 0){
                    $services =  $services->whereDate('expires_at' , '>=', Carbon::now())
                                    ->whereDate('expires_at' , '<', Carbon::now()->addDays($filter['expire_in']));
                }
            }          

            $this->response["totalItems"] = $services->count();

            $services = $services->offset(isset($filter['limit']) ? $filter['limit'] * ( $page - 1 ) : 15 * ( $page - 1 ))
                                    ->limit(isset($filter['limit']) ? $filter['limit'] : 15)
                                    ->orderBy(isset($filter['order_column']) ? $filter['order_column'] : 'updated_at', isset($filter['order_by']) ? $filter['order_by'] : 'desc')
                                    // ->withCount(['maxxcoins as maxxcoins' => function($query){
                                    //     $query->whereDate('expiry_date' , '>=' ,  Carbon::now());
                                    // }])
                                    ->with(['user', 'type', 'customer'])
                                    ->get();

            $this->response["currentPage"] = $page;
            $this->response["filter"] = [];
            $this->response['data'] = $services;
            return $this->successResponse($this->response);
        } catch (\Exception $e){
            $this->response['error_message'] = $e->getMessage();
            return $this->errorResponse($this->response);
        }
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'first_name' => 'required|max:255',
            //'last_name' => 'required|max:255',
            'email' => 'nullable|email|max:255|unique:customers,email',
            'mobile' => 'required|unique:customers,mobile',
            'std_code' => 'required',
            'email' => 'regex:/^([a-z0-9\+_\-]+)(\.[a-z0-9\+_\-]+)*@([a-z0-9\-]+\.)+[a-z]{2,6}$/ix',
        ]);


        try {
            $this->response["title"] = Lang::get('');

            if ($validator->fails()) {
                $this->response["errors"] = $validator->errors();
                $this->response["old_data"] = $request->all();
                $this->response["message"] = Lang::get('');
                return $this->errorResponse($this->response);
            } else {
                $latest = Customer::latest()->first();
                $user = new Customer();
                $user->first_name = $request->input('first_name');
                $user->last_name = $request->input('last_name') ?? "";
                $user->email = $request->input('email') ?? null;
                $user->mobile = $request->input('mobile');
                $user->std_code = $request->input('std_code');
                $user->country_code = $request->input('country_code');
                $user->password = Hash::make(Str::random(8));
                $user->user_id = Auth::id();
                $user->customer_no = isset($latest) ? $latest->customer_no + 1 : 1000001; 
                $user->save();
                

                $this->response["message"] = Lang::get('');
                $this->response["data"] = $user;
                return $this->successResponse($this->response);
            }
        } catch (\Exception $e) {
            $this->response['error_message'] = $e->getMessage();
            return $this->errorResponse($this->response);
        }
    }

    public function show($id)
    {

    }

    public function fetch($id)
    {
        try{
            $user = Customer::with(['services', 'services.type', 'services.user', 'user'])->find($id);

            $this->response["data"] = $user;
            return $this->successResponse($this->response);
        }catch (\Exception $e) {
            $this->response['error_message'] = $e->getMessage();
            return $this->errorResponse($this->response);
        }
    }

    public function update(Request $request , $id)
    {
        $validator = Validator::make($request->all(), [
            'first_name' => 'required|max:255',
            //'last_name' => 'required|max:255',
            'email' => 'nullable|max:255|unique:customers,email,'.$id,
            'mobile' => 'required|unique:customers,mobile,'.$id,
            'std_code' => 'required',
            'email' => 'regex:/^([a-z0-9\+_\-]+)(\.[a-z0-9\+_\-]+)*@([a-z0-9\-]+\.)+[a-z]{2,6}$/ix',
        ]);

        try {
            $this->response["title"] = Lang::get('');

            if ($validator->fails()) {
                $this->response["errors"] = $validator->errors();
                $this->response["old_data"] = $request->all();
                $this->response["message"] = Lang::get('');
                return $this->errorResponse($this->response);
            } else {
                $user = Customer::find($id);
                $user->first_name = $request->input('first_name');
                $user->last_name = $request->input('last_name') ?? "";
                $user->email = $request->input('email') ?? null;
                $user->mobile = $request->input('mobile');
                $user->std_code = $request->input('std_code');
                $user->country_code = $request->input('country_code');
                $user->save();

                $this->response["message"] = Lang::get('');
                $this->response["data"] = $user;
                return $this->successResponse($this->response);
            }
        } catch (\Exception $e) {
            $this->response['error_message'] = $e->getMessage();
            return $this->errorResponse($this->response);
        }
    }
   

    public static function  get_rand_token($length = 10){
        $token = "";
        $codeAlphabets = "ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
        $alphaMax = strlen($codeAlphabets);

        for ($i= 0; $i < $length ; $i++) {
            $token .= $codeAlphabets[random_int( 0 , $alphaMax - 1 )];
        }

        $user = Customer::where("referral_code", $token)->first();

        if(isset($user)){
            return self::get_rand_token(8);
        }

        return $token;
    }

    public function update_service(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'bill_number' => 'required|max:255',
            'service_type_id' => 'required|max:255',
        ]);

        try {
            $this->response["title"] = Lang::get('');

            if ($validator->fails()) {
                $this->response["errors"] = $validator->errors();
                $this->response["old_data"] = $request->all();
                $this->response["message"] = Lang::get('');
                return $this->errorResponse($this->response);
            } else {

                if($request->has('id')){
                   $service = CustomerService::find($request->id);
                }

                if($request->has('id') && !isset($service)){
                    $service = CustomerService::where('bill_number', $request->input('bill_number'))->where('id', $request->id)->first();
                }

                if(!isset($service)){
                    $service = new CustomerService();
                    $service->user_id = Auth::id();
                    $service->customer_id = $id;
                }

                $service->service_type_id = $request->input('service_type_id');
                $service->bill_number = $request->input('bill_number');
                $service->account_name = $request->input('account_name');
                if($request->has('expired_at')){
                    $service->expires_at = $request->input('expired_at') != null ? Carbon::createFromFormat('d-m-Y', $request->input('expired_at'))->format('Y-m-d') : null;
                }
                $service->is_active = true;
                $service->is_deleted = false; 
                $service->save();

                $this->response["message"] = Lang::get('');
                $this->response["data"] = $service;
                return $this->successResponse($this->response);
            }
        } catch (\Exception $e) {
            $this->response['error_message'] = $e->getMessage();
            return $this->errorResponse($this->response);
        }
    }
    
    public function destroy($id)
    {
        try {
            $item = Customer::find($id);
            $item->update(['is_deleted' => 1]);
            $this->response["data"] = $item;
            return $this->successResponse($this->response);
        } catch (\Exception $e) {
            $this->response['error_message'] = $e->getMessage();
            return $this->errorResponse($this->response);
        }
    }



    public function service_type_destroy($id)
    {
        try {
            $item = CustomerService::find($id);
            $item->update(['is_deleted' => 1]);
            $this->response["data"] = $item;
            return $this->successResponse($this->response);
        } catch (\Exception $e) {
            $this->response['error_message'] = $e->getMessage();
            return $this->errorResponse($this->response);
        }
    }

}