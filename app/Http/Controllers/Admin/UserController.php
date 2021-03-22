<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\UserRequest;
use Yajra\DataTables\Facades\DataTables;
use App\Http\Requests\Admin\UpdateUserRequest;
use App\Repositories\Admin\UserRepositoryEloquent as User;

class UserController extends Controller
{
    protected $userRepo;

    function __construct(User $userRepo)
    {
        $this->userRepo = $userRepo;
    }

    public function index()
    {
        $data['module'] = 'user';
        return view('admin.settings.users.index', $data);
    }

    public function create()
    {
        $data['module'] = 'user';
        return view('admin.settings.users.create', $data);
    }

    public function edit($hashedId)
    {
        $data['module'] = 'user';
        $id = app('App\Http\Controllers\GlobalFunctionController')->decodeHashid($hashedId);
        $data['user'] = $this->userRepo->find($id);
        return view('admin.settings.users.edit', $data);
    }

    public function store(UserRequest $request)
    {
        $makeRequest = [
            'name' => $request['name'],
            'email' => $request['email'],
            'password' => bcrypt($request['password']),
            'status' => 'Active'
        ];
        $this->userRepo->create($makeRequest);
        return redirect()->to('admin/settings/users');
    }

    public function update(UpdateUserRequest $request, $hashedId)
    {
        $id = app('App\Http\Controllers\GlobalFunctionController')->decodeHashid($hashedId);
        if($request['password']){
            $makeRequest = [
                'name' => $request['name'],
                'email' => $request['email'],
                'password' => bcrypt($request['password']),
                'status' => 'Active'
            ];
        } else {
            $makeRequest = [
                'name' => $request['name'],
                'email' => $request['email'],
                'status' => 'Active'
            ];
        }
        $this->userRepo->update($makeRequest, $id);
        return redirect()->to('admin/settings/users');
    }

    public function destroy($hashedId)
    {
        $id = app('App\Http\Controllers\GlobalFunctionController')->decodeHashid($hashedId);
        $this->userRepo->update(['status' => 'Inactive'], $id);
        $data['response'] = 1;
        return response()->json($data);
    }

    public function getuser()
    {
        $users = $this->userRepo->rawAll("status = ?", ['Active']);
        return Datatables::of($users)
        ->addColumn('action', function ($users) {
            $html_out  = '';
            $html_out .= '<a href="'.url('admin/settings/users',$users->hashedid).'/edit" class="btn btn-success btn-xs btn-flat"><i class="fa fa-pencil-alt fa-fw"></i> &nbsp;Edit</a>&nbsp;&nbsp;';
            $html_out .= '<a href="javascript:void(0)" onclick="deleteuser(\''.$users->hashedid.'\')" class="btn btn-danger btn-xs btn-flat"><i class="fa fa-trash-alt fa-fw"></i> &nbsp;Delete</a>';
            return $html_out;
        })->make(true);
    }
}
