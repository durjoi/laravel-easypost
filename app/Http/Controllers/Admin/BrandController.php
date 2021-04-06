<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\Facades\DataTables;
use App\Http\Requests\Admin\SettingsBrandRequest;
use App\Repositories\Admin\SettingsBrandRepositoryEloquent as Brand;
use App\Repositories\Admin\ConfigRepositoryEloquent as Config;

class BrandController extends Controller
{
    protected $brandRepo;
    protected $configRepo;

    function __construct(Brand $brandRepo, Config $configRepo)
    {
        $this->brandRepo = $brandRepo;
        $this->configRepo = $configRepo;
    }

    public function index()
    {
        $data['module'] = 'brand';
        $data['tvsettings'] = true;
        $data['types'] = [''=>'Choose Device', 'Mobile'=>'Mobile Device', 'Other'=>'Other Devices'];
        $data['featureList'] = [''=>'No', 1=>'Yes at Row 1', 2=>'Yes at Row 2', 3=>'Yes at Row 3'];
        $data['config'] = $this->configRepo->find(1);
        $data['is_dark_mode'] = ($data['config']['is_dark_mode'] == 1) ? true : false;
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
}
