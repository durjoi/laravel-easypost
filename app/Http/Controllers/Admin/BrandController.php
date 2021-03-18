<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\Facades\DataTables;
use App\Http\Requests\Admin\BrandRequest;
use App\Repositories\Admin\BrandRepositoryEloquent as Brand;

class BrandController extends Controller
{
    protected $brandRepo;

    function __construct(Brand $brandRepo)
    {
        $this->brandRepo = $brandRepo;
    }

    public function index()
    {
        $data['module'] = 'brand';
        $data['types'] = [''=>'Choose Device', 'Mobile'=>'Mobile Device', 'Other'=>'Other Devices'];
        $data['featureList'] = [''=>'No', 1=>'Yes at Row 1', 2=>'Yes at Row 2', 3=>'Yes at Row 3'];
        return view('admin.settings.brands.index', $data);
    }

    public function store(BrandRequest $request)
    {
        $id = $request['id'];
        $user_id = Auth::user()->id;
        $path = 'uploads/brands';
        $field = $request->file('photo');
        $hasfile = $request->hasFile('photo');

        if($id){
            $brand = $this->brandRepo->find($id);
            $photo = resizeFileUpload($path, $field, $hasfile, 250, $brand->photo, $brand->full_size);
            $makeRequest = [
                'name' => $request['name'],
                'device_type' => $request['device_type'],
                'photo' => $photo['small'],
                'full_size' => $photo['full'],
                'feature' => $request['feature'],
                'user_create' => $user_id,
                'user_update' => $user_id
            ];
            $this->brandRepo->update($makeRequest, $id);
            $data['response'] = 1;
            return response()->json($data);
        }

        $photo = resizeFileUpload($path, $field, $hasfile, 250);
        $makeRequest = [
            'name' => $request['name'],
            'device_type' => $request['device_type'],
            'photo' => $photo['small'],
            'full_size' => $photo['full'],
            'feature' => $request['feature'],
            'user_create' => $user_id,
            'user_update' => $user_id
        ];
        $this->brandRepo->create($makeRequest);
        $data['response'] = 1;
        return response()->json($data);
    }

    public function edit($id)
    {
        $data['brand'] = $this->brandRepo->find($id);
        return response()->json($data);
    }

    public function getbrand()
    {
        $brands = $this->brandRepo->all();
        return Datatables::of($brands)
        ->editColumn('photo', function($brands) {
            if($brands->photo){
                return '<img src="'.url($brands->photo).'" style="width: 80px; height: auto">';
            }
            return '';
        })
        ->editColumn('updated_at', function($brands) {
            return $brands->updated_at_display;
        })
        ->addColumn('action', function ($brands) {
            $html_out  = '';
            $html_out .= '<a href="javascript:void(0)" onclick="updatebrand(\''.$brands->id.'\')" class="btn btn-success btn-xs btn-flat"><i class="fa fa-pencil-alt fa-fw"></i> &nbsp;Edit</a>&nbsp;&nbsp;';
            $html_out .= '<a href="javascript:void(0)" onclick="deletebrand(\''.$brands->id.'\')" class="btn btn-danger btn-xs btn-flat"><i class="fa fa-trash-alt fa-fw"></i> &nbsp;Delete</a>';
            return $html_out;
        })
        ->rawColumns(['photo', 'action'])
        ->make(true);
    }
}
