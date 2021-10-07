<?php 

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Spatie\Permission\Models\Role;

class UserController extends Controller 
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

            $users = User::with('roles:id,name')->where('is_deleted' , false);

            if(isset($filter['searchText'])){
                $users =  $users->where(function($query) use($filter){
                                            $query->where('first_name' , 'LIKE' , '%'.$filter['searchText'].'%')
                                                    ->orWhere('last_name' , 'LIKE' , '%'.$filter['searchText'].'%')
                                                    ->orWhere('email' , 'LIKE' , '%'.$filter['searchText'].'%');
                                        });
            }

            $this->response["currentPage"] = $page;
            $this->response["filter"] = [];
            $this->response["totalItems"] = $users->count();

            $this->response['data'] = $users->offset(isset($filter['limit']) ? $filter['limit'] * ( $page - 1 ) : 15 * ( $page - 1 ))
                                            ->limit(isset($filter['limit']) ? $filter['limit'] : 15)
                                            ->orderBy(isset($filter['order_column']) ? $filter['order_column'] : 'id', isset($filter['order_by']) ? $filter['order_by'] : 'desc')
                                            ->latest()
                                            ->get();

            return $this->successResponse($this->response);
        } catch (\Exception $e){
            $this->response['error_message'] = $e->getMessage();
            return $this->errorResponse($this->response);
        }
    }

    public function search(Request $request , $page = 1)
    {
        try {      
            $filter = $request->all(); 
            $user = User::where('status' , true);

            if(isset($filter['searchText'])){
                $user = $user->where('first_name' , 'LIKE' , '%'.$filter['searchText'].'%')
                             ->orWhere('last_name' , 'LIKE' , '%'.$filter['searchText'].'%');
            }    

            $this->response['data'] = $user->get();
            $this->response["currentPage"] = $page;
            $this->response["filter"] = [];
            $this->response["totalItems"] = $user->count();
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
            'last_name' => 'required|max:255',
            'email' => 'required|email|max:255|unique:users,email',
            'role_id' => 'required',
            'email' => 'regex:/^([a-z0-9\+_\-]+)(\.[a-z0-9\+_\-]+)*@([a-z0-9\-]+\.)+[a-z]{2,6}$/ix',
            //'password' => 'required|min:6',
        ]);

       
        try {
            $this->response["title"] = Lang::get('');
        
            if ($validator->fails()) {
                $this->response["errors"] = $validator->errors();
                $this->response["old_data"] = $request->all();
                $this->response["message"] = Lang::get('');
                return $this->errorResponse($this->response);
            } else {
                $user = new User();
                $user->first_name = $request->input('first_name');
                $user->last_name = $request->input('last_name');
                $user->email = $request->input('email');
                $user->password = $request->has('password') ? Hash::make($request->input('password')) : Hash::make('12345678');;//Hash::make(str_random(8));
                $user->save();

                $user->assignRole($request->role_id);

                //$file = $user->uploadFile($request , 'profile_image' , 'user/profile');

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
            $user = User::find($id);
            $user->load('roles');

            $role = $user->roles()->first();

           

            $user['role_id'] = $role->id ?? null;

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
            'last_name' => 'required|max:255',
            'email' => 'required|email|max:255|unique:users,email,' .$id,
            'role_id' => 'required',
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
                $user = User::find($id);
                $user->first_name = $request->input('first_name');
                $user->last_name = $request->input('last_name');
                $user->email = $request->input('email');
                $user->is_active = $request->is_active ?? false;

                if($request->has('password')){
                    $user->password = Hash::make($request->input('password'));                    
                }
                
                $user->save();
                $user->syncRoles([$request->role_id]);

                //$user->file = $user->uploadFile($request , 'profileImage' , 'user/profile');

                $this->response["message"] = Lang::get('');
                $this->response["data"] = $user;
                return $this->successResponse($this->response);
            }
        } catch (\Exception $e) {
            $this->response['error_message'] = $e->getMessage();
            return $this->errorResponse($this->response);
        }
    }

    public function update_status($id)
    {
        try {
            $item = User::find($id);
            $item->update(['is_active' => !$item->is_active]);
            $this->response["data"] = $item;
            return $this->successResponse($this->response);
        } catch (\Exception $e) {
            $this->response['error_message'] = $e->getMessage();
            return $this->errorResponse($this->response);
        }
    }

    public function fetch_roles()
    {
        $this->response["data"] = Role::get(['name', 'id']);
        return $this->successResponse($this->response);
    }
    

    public function destroy($id)
    {
    }

}