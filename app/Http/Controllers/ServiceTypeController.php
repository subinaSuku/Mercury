<?php

namespace App\Http\Controllers;

use Faker\Provider\Uuid;
use App\Models\ServiceType;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Facades\Validator;

class ServiceTypeController extends Controller
{
    public $filter = [
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
            $serviceTypes = ServiceType::where('is_deleted', 0);

            if(isset($filter['searchText'])){
                $serviceTypes =  $serviceTypes->where('name' , 'LIKE' , '%'.$filter['searchText'].'%');
            }

            $this->response["totalItems"] = $serviceTypes->count();

            $this->response['data'] = $serviceTypes->offset(isset($filter['limit']) ? $filter['limit'] * ( $page - 1 ) : 15 * ( $page - 1 ))
                                                    ->limit(isset($filter['limit']) ? $filter['limit'] : 15)
                                                    ->orderBy(isset($filter['order_column']) ? $filter['order_column'] : 'id', isset($filter['order_by']) ? $filter['order_by'] : 'desc')
                                                    // ->withCount(['maxxcoins as maxxcoins' => function($query){
                                                    //     $query->whereDate('expiry_date' , '>=' ,  Carbon::now());
                                                    // }])
                                                    ->latest()
                                                    ->get();
            $this->response["currentPage"] = $page;
            $this->response["filter"] = [];
            return $this->successResponse($this->response);
        } catch (\Exception $e) {
            $this->response['error_message'] = $e->getMessage();
            return $this->errorResponse($this->response);
        }
    }

    public function search(Request $request, $page = 1)
    {
        try {
            $filter = $request->all();
            $serviceTypes = ServiceType::where('status', true);

            if (isset($filter['searchText'])) {
                $serviceTypes = $serviceTypes->where('first_name', 'LIKE', '%'.$filter['searchText'].'%')
                             ->orWhere('last_name', 'LIKE', '%'.$filter['searchText'].'%');
            }

            $this->response['data'] = $serviceTypes->get();
            $this->response["currentPage"] = $page;
            $this->response["filter"] = [];
            $this->response["totalItems"] = $serviceTypes->count();
            return $this->successResponse($this->response);
        } catch (\Exception $e) {
            $this->response['error_message'] = $e->getMessage();
            return $this->errorResponse($this->response);
        }
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|max:255',
        ]);

       
        try {
            $this->response["title"] = Lang::get('');
        
            if ($validator->fails()) {
                $this->response["errors"] = $validator->errors();
                $this->response["old_data"] = $request->all();
                $this->response["message"] = Lang::get('');
                return $this->errorResponse($this->response);
            } else {
                $serviceType = new ServiceType();
                $serviceType->name = $request->input('name');
                $serviceType->is_active = $request->is_active ?? false;
                $serviceType->save();

                $this->response["message"] = Lang::get('');
                $this->response["data"] = $serviceType;
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
        try {
            $serviceType = ServiceType::find($id);

            $this->response["data"] = $serviceType;
            return $this->successResponse($this->response);
        } catch (\Exception $e) {
            $this->response['error_message'] = $e->getMessage();
            return $this->errorResponse($this->response);
        }
    }

    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|max:255',
        ]);
        
        try {
            $this->response["title"] = Lang::get('');
        
            if ($validator->fails()) {
                $this->response["errors"] = $validator->errors();
                $this->response["old_data"] = $request->all();
                $this->response["message"] = Lang::get('');
                return $this->errorResponse($this->response);
            } else {
                $serviceType = ServiceType::find($id);                
                $serviceType->name = $request->input('name');
                $serviceType->is_active = $request->is_active ?? false;
                $serviceType->save();

                $this->response["message"] = Lang::get('');
                $this->response["data"] = $serviceType;
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
            $item = ServiceType::find($id);
            $item->update(['is_deleted' => 1]);
            $this->response["data"] = $item;
            return $this->successResponse($this->response);
        } catch (\Exception $e) {
            $this->response['error_message'] = $e->getMessage();
            return $this->errorResponse($this->response);
        }
    }
}