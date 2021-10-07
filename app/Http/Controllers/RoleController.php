<?php

namespace App\Http\Controllers;

use App;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Facades\Gate;
use Spatie\Permission\Models\Permission;
use Illuminate\Support\Facades\Validator;

class RoleController extends Controller
{
    /**
     * Display a listing of Role.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (! Gate::allows('users_manage')) {
            return abort(401);
        }

        $roles = Role::all();

        return view('admin.roles.index', compact('roles'));
    }

    public function list(Request $request)
    {
        try {
            $filter = $request->all();            
            $page = isset($filter['page']) ? $filter['page'] : 1;

            $roles = new Role();

            if(isset($filter['searchText'])){
                $roles = $roles->where('name' , 'LIKE' , '%'.$filter['searchText'].'%');
            }  


            $this->response["totalItems"] = $roles->count();

            $roles = $roles->offset(isset($filter['limit']) ? $filter['limit'] * ( $page - 1 ) : 15 * ( $page - 1 ))
                                        ->limit(isset($filter['limit']) ? $filter['limit'] : 15)
                                        ->orderBy(isset($filter['order_column']) ? $filter['order_column'] : 'id', isset($filter['order_by']) ? $filter['order_by'] : 'desc')
                                        ->get();

            $this->response['data'] = $roles;
            $this->response["currentPage"] = $page;
            $this->response["filter"] = [];
            return $this->successResponse($this->response);
        } catch (\Exception $e){
            $this->response['error_message'] = $e->getMessage();
            return $this->errorResponse($this->response);
        }
    }

    public function fetchPermissions()
    {
        try {            
            $this->response['data'] = Permission::get()->pluck('name', 'name');
            return $this->successResponse($this->response);
        } catch (\Exception $e){
            $this->response['error_message'] = $e->getMessage();
            return $this->errorResponse($this->response);
        }
    }

    public function update_permissions(Request $request ,$id)
    {
        try {          
            $permissions = $request->all();
            $role = Role::find($id);
            $role->syncPermissions($permissions);

            $permissions = $role->permissions;

            $permissions = $permissions->map(function($permission) {
                return $permission->name;
            });

            $role['role_permissions'] = $permissions;

            $this->response['data'] = $role;

            return $this->successResponse($this->response);
        } catch (\Exception $e){
            $this->response['error_message'] = $e->getMessage();
            return $this->errorResponse($this->response);
        }
        
    }

    /**
     * Show the form for creating new Role.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if (! Gate::allows('users_manage')) {
            return abort(401);
        }
        $permissions = Permission::get()->pluck('name', 'id');

        return view('admin.roles.create', compact('permissions'));
    }

    /**
     * Store a newly created Role in storage.
     *
     * @param  \App\Http\Requests\StoreRolesRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|max:255|unique:roles,name',
            'guard_name' => 'sometimes|required',
        ]);

       
        try {
            $this->response["title"] = Lang::get('');

            // if (! Gate::allows('users_manage')) {
            //     return abort(401);
            // }
        
            if ($validator->fails()) {
                $this->response["errors"] = $validator->errors();
                $this->response["old_data"] = $request->all();
                $this->response["message"] = Lang::get('');
                return $this->errorResponse($this->response);
            } else {

                $role = Role::create($request->only(['name' , 'guard_name']));

                //$permissions = $request->input('permission') ? $request->input('permission') : [];
                //$role->givePermissionTo($permissions);

                $this->response["message"] = Lang::get('');
                $this->response["data"] = $role;
                return $this->successResponse($this->response);
            }
        } catch (\Exception $e) {
            $this->response['error_message'] = $e->getMessage();
            return $this->errorResponse($this->response);
        }
    }


    /**
     * Show the form for editing Role.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Role $role)
    {
        if (! Gate::allows('users_manage')) {
            return abort(401);
        }
        $permissions = Permission::get()->pluck('name', 'name');

        return view('admin.roles.edit', compact('role', 'permissions'));
    }

    /**
     * Update Role in storage.
     *
     * @param  \App\Http\Requests\UpdateRolesRequest  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $role_id)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|max:255|unique:roles,name,' . $role_id,
            'guard_name' => 'sometimes|required',
        ]);

       
        try {
            $this->response["title"] = Lang::get('');

            // if (! Gate::allows('users_manage')) {
            //     return abort(401);
            // }
        
            if ($validator->fails()) {
                $this->response["errors"] = $validator->errors();
                $this->response["old_data"] = $request->all();
                $this->response["message"] = Lang::get('');
                return $this->errorResponse($this->response);
            } else {

                $role = Role::find($role_id);

                $role = $role->update($request->only(['name' , 'guard_name']));

                //$permissions = $request->input('permission') ? $request->input('permission') : [];
                //$role->givePermissionTo($permissions);

                $this->response["message"] = Lang::get('');
                $this->response["data"] = $role;
                return $this->successResponse($this->response);
            }
        } catch (\Exception $e) {
            $this->response['error_message'] = $e->getMessage();
            return $this->errorResponse($this->response);
        }
    }

    public function fetch($id)
    {
        try{
            $role = Role::find($id);

            $role->load('permissions');

            $permissions = $role->permissions;

            $permissions = $permissions->map(function($permission) {
                return $permission->name;
            });

            $role['role_permissions'] = $permissions;

            $this->response["data"] = $role;
            return $this->successResponse($this->response);
        }catch (\Exception $e) {
            $this->response['error_message'] = $e->getMessage();
            return $this->errorResponse($this->response);
        }
    }

    public function show(Role $role)
    {
        if (! Gate::allows('users_manage')) {
            return abort(401);
        }

        $role->load('permissions');

        return view('admin.roles.show', compact('role'));
    }


    /**
     * Remove Role from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    // public function destroy(Role $role)
    // {
    //     if (! Gate::allows('users_manage')) {
    //         return abort(401);
    //     }

    //     $role->delete();

    //     return redirect()->route('admin.roles.index');
    // }

    /**
     * Delete all selected Role at once.
     *
     * @param Request $request
     */
    public function massDestroy(Request $request)
    {
        Role::whereIn('id', request('ids'))->delete();

        return response(null, 204);
    }

    public function destroy($id)
    {
        try {
            $item = Role::find($id);
            $item->delete();
            $this->response["data"] = $item;
            return $this->successResponse($this->response);
        } catch (\Exception $e) {
            $this->response['error_message'] = $e->getMessage();
            return $this->errorResponse($this->response);
        }
    }

}