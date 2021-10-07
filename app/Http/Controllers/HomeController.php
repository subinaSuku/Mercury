<?php 

namespace App\Http\Controllers;


use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Models\CustomerService;
use Carbon\Carbon;

class HomeController extends Controller 
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
      return view('layouts.app');
  }

  public function dashboardData()
  {
    $this->response["new_users"] = Customer::whereDate('created_at', Carbon::now())->where('is_deleted' , 0)->count();
    $this->response["total_users"] = Customer::where('is_deleted' , 0)->count();
    $this->response["new_bills"] = CustomerService::whereDate('created_at', Carbon::now())->where('is_deleted' , 0)->count();
    $this->response["total_bills"] = CustomerService::where('is_deleted' , 0)->count();
    return $this->successResponse($this->response);
  }

  public function expired_bills()
  {
     try {

            $services = CustomerService::where('is_deleted' , false)
                                      ->whereNotNull('expires_at')
                                      ->whereDate('expires_at' , '<', Carbon::now());

            $this->response["totalItems"] = $services->count();

            $services = $services->limit(5)
                                    ->with(['user', 'type', 'customer'])
                                    ->orderBy('expires_at', 'asc')
                                    ->get();

            $this->response['data'] = $services;

    $this->response["expire_soon"] = CustomerService::whereDate('expires_at', '>=', Carbon::now())->whereDate('expires_at' , '<', Carbon::now()->addDays(15))->where('is_deleted' , 0)->count();
            return $this->successResponse($this->response);
        } catch (\Exception $e){
            $this->response['error_message'] = $e->getMessage();
            return $this->errorResponse($this->response);
        }
  }

  public function recently_added_customers()
  {
    try {
        $customers = Customer::where('is_deleted' , false);

        $this->response["totalItems"] = $customers->count();

        $customers = $customers->limit(5)
                                ->with(['user'])
                                ->latest()
                                ->get();

        $this->response['data'] = $customers;
        return $this->successResponse($this->response);
    } catch (\Exception $e){
        $this->response['error_message'] = $e->getMessage();
        return $this->errorResponse($this->response);
    }
  }
}