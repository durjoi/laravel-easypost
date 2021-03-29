<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Yajra\DataTables\Facades\DataTables;
use App\Http\Requests\Admin\ProductRequest;
use App\Http\Requests\Admin\ProductDupRequest;
use App\Repositories\Admin\SettingsBrandRepositoryEloquent as Brand;
use App\Repositories\Admin\ConfigRepositoryEloquent as Config;
use App\Repositories\Admin\ProductRepositoryEloquent as Product;
use App\Repositories\Admin\ProductPhotoRepositoryEloquent as ProductPhoto;
use App\Repositories\Admin\NetworkRepositoryEloquent as NetworkRepo;
use App\Repositories\Admin\ProductNetworkEloquentRepository as ProductNetwork;
use App\Repositories\Admin\ProductStorageEloquentRepository as ProductStorage;
use App\Repositories\Admin\SettingsCategoryEloquentRepository as SettingsCategory;
use App\Repositories\Admin\ProductCategoryEloquentRepository as ProductCategory;
use App\Models\TableList as Tablelist;

class ProductController extends Controller
{
    protected $brandRepo;
    protected $productRepo;
    protected $productPhotoRepo;
    protected $configRepo;
    protected $networkRepo;
    protected $productNetworkRepo;
    protected $productStorageRepo;
    protected $settingsCategoryRepo;
    protected $productCategoryRepo;
    protected $tablelist;

    function __construct(
                        Brand $brandRepo, 
                        Product $productRepo, 
                        ProductPhoto $productPhotoRepo, 
                        Config $configRepo, 
                        NetworkRepo $networkRepo, 
                        ProductNetwork $productNetworkRepo, 
                        ProductStorage $productStorageRepo, 
                        SettingsCategory $settingsCategoryRepo,
                        ProductCategory $productCategoryRepo,
                        Tablelist $tablelist
                        )
    {
        $this->brandRepo = $brandRepo;
        $this->productRepo = $productRepo;
        $this->productPhotoRepo = $productPhotoRepo;
        $this->configRepo = $configRepo;
        $this->networkRepo = $networkRepo;
        $this->productNetworkRepo = $productNetworkRepo;
        $this->productStorageRepo = $productStorageRepo;
        $this->settingsCategoryRepo = $settingsCategoryRepo;
        $this->productCategoryRepo = $productCategoryRepo;
        $this->tablelist = $tablelist;
    }

    public function index()
    {
        // $data['storageList'] = $this->tablelist->storageList;
        $data['storageList'] = [''=>'--','32GB', '64GB','128GB','256GB','512GB'];
        // $data['networkList'] = $this->tablelist->networkList;
        $data['networkList'] = [''=>'--','AT&T'=>'AT&T','Sprint'=>'Sprint','T-Mobile'=>'T-Mobile','Verizon'=>'Verizon','Unlocked'=>'Unlocked'];
        $data['config'] = $this->configRepo->find(1);
        $data['module'] = 'product';
        return view('admin.products.index', $data);
    }

    public function store(ProductRequest $request)
    {
        // return $request->all();
        $device_type = $request['device_type'];
        $user_id = Auth::user()->id;
        // $chkdup = $this->productRepo->rawCount("brand_id = ? and device_type = ? and model = ? and color = ?", [$request['brand_id'], $request['device_type'], $request['name'], $request['color']]);
        $chkdup = $this->productRepo->rawCount("brand_id = ? and model = ? and color = ?", [$request['brand_id'], $request['name'], $request['color']]);
        
        $makeRequest = $this->makeProductRequest($request);
        
        if($chkdup == 0){
            $hasfile = $request->hasFile('photo');    
            if ($hasfile) 
            {
                $product = $this->productRepo->create($makeRequest);
                if(isset($request['network']) && $request['network'] != null) {
                    foreach ($request['network'] as $nKey => $nVal) {
                        $this->createProductNetwork($product->id, $nVal);
                    }
                }

                if(isset($request['categories']) && $request['categories'] != null) {
                    foreach ($request['categories'] as $cKey => $cVal) {
                        $this->createProductCategory($product->id, $cVal);
                    }
                }
                if (isset($request['storage']) && $request['storage'] != null) {
                    $this->createProductStorageList($request, $product->id);
                }
                $hashedProductId = $product->hashedid;
                $path = 'uploads/products/'.$hashedProductId;
                $pathThumb = 'uploads/products/'.$hashedProductId.'/thumb';
                File::makeDirectory($path, 0777, true, true);
                File::makeDirectory($pathThumb, 0777, true, true);
                $field = $request->file('photo');    
                $photo = productFileUpload($path, $field, $hasfile, 250);
                $makeRequest = [
                    'product_id' => $product->id,
                    'photo' => $photo['small'],
                    'full_size' => $photo['full'],
                    'user_create' => $user_id,
                    'user_update' => $user_id
                ];
                $this->productPhotoRepo->create($makeRequest);
            }
            $data['response'] = 1;
            return redirect()->to('admin/products')->with('msg', 'New device has been added!');
        }
        return redirect()->back()->with('errormsg', 'This device is already exists!');
        // return redirect()->to('admin/products/create')->with('errormsg', 'This device is already exists!');



        // $device_type = $request['device_type'];
        // $user_id = Auth::user()->id;
        // $chkdup = $this->productRepo->rawCount("brand_id = ? and network = ? and storage = ? and model = ?", [$request['brand_id'], $request['network'], $request['storage'], $request['name']]);
        // $makeRequest = [
        //     'brand_id' => $request['brand_id'],
        //     'model' => $request['name'],
        //     'color' => $request['color'],
        //     'height' => $request['height'],
        //     'width' => $request['width'],
        //     'weight' => $request['weight'],
        //     'length' => $request['length'],
        //     'status' => 'Active',
        //     'device_type' => $device_type,
        //     'amount' => $request['amount'],
        //     'excellent_offer' => $request['excellent_offer'],
        //     'good_offer' => $request['good_offer'],
        //     'fair_offer' => $request['fair_offer'],
        //     'poor_offer' => $request['poor_offer'],
        //     'offer_type' => $request['offer_type'],
        //     'sku' => ($request['device_type'] != 'Buy') ? $request['sku'] : '',
        //     'description' => $request['description'],
        //     'storage' => $request['storage'],
        //     'network' => $request['network'],
        //     'user_create' => $user_id,
        //     'user_update' => $user_id
        // ];
        // if($chkdup == 0){
        //     $hasfile = $request->hasFile('photo');    
        //     if ($hasfile) 
        //     {
        //         $product = $this->productRepo->create($makeRequest);
        //         // $hashedProductId = app('App\Http\Controllers\GlobalFunctionController')->encodeHashid($product->id);
        //         $hashedProductId = $product->hashedid;
        //         $path = 'uploads/products/'.$hashedProductId;
        //         $pathThumb = 'uploads/products/'.$hashedProductId.'/thumb';
        //         File::makeDirectory($path, 0777, true, true);
        //         File::makeDirectory($pathThumb, 0777, true, true);
        //         $field = $request->file('photo');    
        //         $photo = productFileUpload($path, $field, $hasfile, 250);
        //         $makeRequest = [
        //             'product_id' => $product->id,
        //             'photo' => $photo['small'],
        //             'full_size' => $photo['full'],
        //             'user_create' => $user_id,
        //             'user_update' => $user_id
        //         ];
        //         $this->productPhotoRepo->create($makeRequest);
        //     }
        //     $data['response'] = 1;
        //     return redirect()->to('admin/products')->with('msg', 'New device has been added!');
        // }
        // return redirect()->back()->with('errormsg', 'This device is already exists!');
    }

    public function create()
    {
        $data['brandList'] = $this->brandRepo->selectlist('name', 'id');
        $data['statusList'] = [''=>'Choose Status', 'Active'=>'Active', 'Draft'=>'Draft', 'Inactive'=>'Inactive'];
        $data['typeList'] = [''=>'--', 'Sell'=>'I want to sell this device', 'Buy'=>'I want to buy this kind of device', 'Both'=>'I want to buy and sell this device'];
        // $data['networkList'] = [''=>'--','AT&T'=>'AT&T','Sprint'=>'Sprint','T-Mobile'=>'T-Mobile','Verizon'=>'Verizon','Unlocked'=>'Unlocked'];
        $data['storageList'] = $this->tablelist->storageList;
        $data['categoryList'] = $this->settingsCategoryRepo->all();
        $data['networkList'] = $this->networkRepo->all();
        $data['config'] = $this->configRepo->find(1);
        $data['module'] = 'product';
        return view('admin.products.create', $data);
    }

    // ProductRequest

    public function update(Request $request, $hashedId)
    {
        $id = app('App\Http\Controllers\GlobalFunctionController')->decodeHashid($hashedId);
        $device_type = $request['device_type'];
        $user_id = Auth::user()->id;
        $product = $this->productRepo->find($id);
        $chkdup = $this->productRepo->rawCount("brand_id = ? and color = ? and model = ? and id != ?", [$request['brand_id'], $request['color'], $request['name'], $id]);
        if ($request['device_type'] != "Both") {
            if ($request['device_type'] != $product['device_type']) {
                $this->productStorageRepo->deleteRaw("product_id = ?", [$id]);
            }
        }
        $makeRequest = $this->makeProductRequest($request);
        // return $request['network'];
        // return $this->productNetworkRepo->findByFieldAll('product_id', $id);
        if($chkdup == 0){

            if(isset($request['network']) && $request['network'] != null) {
                foreach ($request['network'] as $nKey => $nVal) {
                    if ($this->productNetworkRepo->rawCount("product_id = ? and network_id = ?", [$id, $nVal]) == 0){
                        $this->createProductNetwork($product->id, $nVal);
                    }
                }
            }

            if(isset($request['categories']) && $request['categories'] != null) {
                foreach ($request['categories'] as $cKey => $cVal) {
                    if ($this->productCategoryRepo->rawCount("product_id = ? and category_id = ?", [$id, $cVal]) == 0){
                        $this->createProductCategory($product->id, $cVal);
                    }
                }
            }

            if (isset($request['storage']) && $request['storage'] != null) {
                $this->createProductStorageList($request, $product->id);
            }

            // foreach ($this->productStorageRepo->findByFieldAll('product_id', $id) as $psrKey => $psrVal) {
            //     if (!in_array($psrVal['title'], $request['storage'])) {
            //         $this->productStorageRepo->delete($psrVal['id']);
            //     }
            // }
            foreach ($this->productNetworkRepo->findByFieldAll('product_id', $id) as $pnrKey => $pnrVal) {
                if (!in_array($pnrVal['network_id'], $request['network'])) {
                    $this->productNetworkRepo->delete($pnrVal['id']);
                }
            }
            foreach ($this->productCategoryRepo->findByFieldAll('product_id', $id) as $pcrKey => $pcrVal) {
                if (!in_array($pcrVal['category_id'], $request['categories'])) {
                    $this->productCategoryRepo->delete($pcrVal['id']);
                }
            }

            $this->productRepo->update($makeRequest, $id);
            $path = 'uploads/products/'.$hashedId;
            $pathThumb = 'uploads/products/'.$hashedId.'/thumb';
            File::makeDirectory($path, 0777, true, true);
            File::makeDirectory($pathThumb, 0777, true, true);
            $field = $request->file('photo');
            $hasfile = $request->hasFile('photo');  
            if($hasfile){      
                $photo = productFileUpload($path, $field, $hasfile, 250);
                $makeRequest = [
                    'product_id' => $id,
                    'photo' => $photo['small'],
                    'full_size' => $photo['full'],
                    'user_create' => $user_id,
                    'user_update' => $user_id
                ];
                $this->productPhotoRepo->create($makeRequest);
            }
            return redirect()->back()->with('msg', 'Device has been updated!');
        }
        return redirect()->back()->with('errormsg', 'This device is already exists!');
        
        // return $request->all();
        // $id = app('App\Http\Controllers\GlobalFunctionController')->decodeHashid($hashedId);
        // $device_type = $request['device_type'];
        // $user_id = Auth::user()->id;
        // $chkdup = $this->productRepo->rawCount("brand_id = ? and network = ? and storage = ? and model = ? and id != ?", [$request['brand_id'], $request['network'], $request['storage'], $request['name'], $id]);
        // $makeRequest = [
        //     'brand_id' => $request['brand_id'],
        //     'model' => $request['name'],
        //     'color' => $request['color'],
        //     'height' => $request['height'],
        //     'width' => $request['width'],
        //     'weight' => $request['weight'],
        //     'length' => $request['length'],
        //     'status' => 'Active',
        //     'device_type' => $device_type,
        //     'amount' => ($request['device_type'] == 'Buy') ? $request['amount'] : $request['amount'],
        //     'excellent_offer' => ($request['device_type'] == 'Buy') ? $request['excellent_offer'] : '',
        //     'good_offer' => ($request['device_type'] == 'Buy') ? $request['good_offer'] : '',
        //     'fair_offer' => ($request['device_type'] == 'Buy') ? $request['fair_offer'] : '',
        //     'poor_offer' => ($request['device_type'] == 'Buy') ? $request['poor_offer'] : '',
        //     'offer_type' => $request['offer_type'],
        //     'sku' => ($request['device_type'] != 'Buy') ? $request['sku'] : '',
        //     'description' => $request['description'],
        //     'storage' => $request['storage'],
        //     'network' => $request['network'],
        //     'user_update' => $user_id
        // ];
        
        // if($chkdup == 0){
        //     $this->productRepo->update($makeRequest, $id);
        //     $path = 'uploads/products/'.$hashedId;
        //     $pathThumb = 'uploads/products/'.$hashedId.'/thumb';
        //     File::makeDirectory($path, 0777, true, true);
        //     File::makeDirectory($pathThumb, 0777, true, true);
        //     $field = $request->file('photo');
        //     $hasfile = $request->hasFile('photo');  
        //     if($hasfile){      
        //         $photo = productFileUpload($path, $field, $hasfile, 250);
        //         $makeRequest = [
        //             'product_id' => $id,
        //             'photo' => $photo['small'],
        //             'full_size' => $photo['full'],
        //             'user_create' => $user_id,
        //             'user_update' => $user_id
        //         ];
        //         $this->productPhotoRepo->create($makeRequest);
        //     }
        //     return redirect()->back()->with('msg', 'Device has been updated!');
        // }
        // return redirect()->back()->with('errormsg', 'This device is already exists!');
    }

    public function edit($hashedId)
    {
        $id = app('App\Http\Controllers\GlobalFunctionController')->decodeHashid($hashedId);
        $data['product'] = $this->productRepo->findWith($id, ['photo', 'networks.network', 'storages']);
        $data['brandList'] = $this->brandRepo->selectlist('name', 'id');
        $data['statusList'] = [''=>'Choose Status', 'Active'=>'Active', 'Draft'=>'Draft', 'Inactive'=>'Inactive'];
        $data['typeList'] = [''=>'--', 'Sell'=>'I want to sell this device', 'Buy'=>'I want to buy this kind of device', 'Both'=>'I want to buy and sell this device'];
        // $data['storageList'] = $this->tablelist->storageList;
        // $data['networkList'] = [''=>'--','AT&T'=>'AT&T','Sprint'=>'Sprint','T-Mobile'=>'T-Mobile','Verizon'=>'Verizon','Unlocked'=>'Unlocked'];
        $data['storageList'] = $this->tablelist->storageList;
        $data['categoryList'] = $this->settingsCategoryRepo->all();
        $data['networkList'] = $this->networkRepo->all();
        $data['config'] = $this->configRepo->find(1);
        $data['hashedId'] = $hashedId;
        $data['module'] = 'product';
        // return $data['assignedNetworks'] = $data['product']['networks'];
        return view('admin.products.edit', $data);
    }

    public function destroy($hashedId)
    {
        $id = app('App\Http\Controllers\GlobalFunctionController')->decodeHashid($hashedId);
        $this->productRepo->update(['status' => 'Inactive'], $id);
        $data['response'] = 1;
        $data['status'] = 200;
        return response()->json($data);
    }

    public function getproduct()
    {
        $products = $this->productRepo->rawWith(['brand','photo','networks.network','storages'], "status = ?", ['Active']);
        $products;
        return Datatables::of($products)
        ->editColumn('photo', function($products) {
            if(!empty($products->photo)){
                return '<img src="'.url($products->photo->photo).'" style="width: auto; height: 80px">';
            }
            return '';
        })
        ->editColumn('model', function($products) {
            $html  = $products->model;
            return $html;
        })
        ->editColumn('brand', function($products) {
            $html = '';
            if($products->brand_id){
                $html .= (!empty($products->brand)) ? $products->brand->name.'<br>' : '';
            }
            
            if($products->device_type == 'Buy' || $products->device_type == 'Both'){
                $storages = '';
            // if($products->device_type == 'Buy'){
            //     $storages = '';
            //     $networks = '';
            //     if ($products->storages != null) {
            //         foreach ($products->storages as $psKey => $psVal) {
            //             $storages .= $psVal['title'].', ';
            //         }
            //         $storages = substr($storages, 0, -2);
            //     }
            //     if ($products->networks != null) {
            //         foreach ($products->networks as $pnKey => $pnVal) {
            //             $networks .= $pnVal['network']['title'].', ';
            //         }
            //         $networks = substr($networks, 0, -2);
            //     }
                // if ($products->storages != null) {
                //     foreach ($products->storages as $psKey => $psVal) {
                //         $storages .= $psVal['title'].', ';
                //     }
                //     $storages = substr($storages, 0, -2);
                // }
                // $html .= '<small><b>Storage:</b> <br />'.$storages.'</small><br>';
            //     $html .= '<small><b>Carrier:</b> '.$networks.'</small>';
            }
            if($products->sku) $html .= '<small><b>SKU:</b> '.$products->sku.'</small>';
            return $html;
        })
        ->editColumn('color', function ($products) {
            return $products->color;
        })
        ->editColumn('otherInfo', function ($products) {
            // if($products->device_type == 'Sell'){
            //     return number_format($products->amount, 2).' USD';
            // } else if($products->device_type == 'Buy'){
            //     return number_format($products->excellent_offer, 2).' USD';
            // } else {
            //     $html  = '<b>Price: </b>'.number_format($products->amount, 2).' USD<br>';
            //     $html .= '<b>Offer: </b>'.number_format($products->excellent_offer, 2).' USD';
            //     return $html;
            // }
            
            $html = '<small><b>Dimensions:</b> '.$products->height.' in x '.$products->width.' in x '.$products->length.' in</small><br>';
            $html .= '<small><b>Weight:</b> '.$products->weight.' ounces</small><br>'; 
            if($products->device_type == 'Buy' || $products->device_type == 'Both'){
                $storages = '';
                $networks = '';
                if ($products->networks != null) {
                    foreach ($products->networks as $pnKey => $pnVal) {
                        $networks .= $pnVal['network']['title'].', ';
                    }
                    $networks = substr($networks, 0, -2);
                }
                $html .= '<small><b>Carrier:</b> '.$networks.'</small>';
            }
            return $html;
        })
        ->addColumn('action', function ($products) {
            $html_out  = '';
            $html_out .= '<div class="dropdown">';
                $html_out .= '<button class="btn btn-primary dropdown-toggle btn-xs" type="button" id="action-btn" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Action</button>';
                $html_out .= '<div class="dropdown-menu dropdown-menu-right" aria-labelledby="action-btn">';
                    $html_out .= '<a class="dropdown-item" href="javascript:void(0)" onclick="duplicate(\''.$products->hashedid.'\')">Create Duplicate</a>';
                    $html_out .= '<a class="dropdown-item" href="'.url('admin/products', $products->hashedid).'/edit">Edit</a>';
                    $html_out .= '<a class="dropdown-item" href="javascript:void(0)" onclick="deleteproduct(\''.$products->hashedid.'\')">Delete</a>';
                $html_out .= '</div>';
            $html_out .= '</div>';
            return $html_out;
        })
        // ->rawColumns(['photo', 'action', 'model','brand','amount'])
        ->rawColumns(['photo', 'action', 'model', 'brand', 'color', 'otherInfo'])
        ->make(true);
    }

    public function storephoto(Request $request)
    {
        $user_id = Auth::user()->id;
        $product_id = $request['product_id'];
        $path = 'uploads/products/'.$product_id;
        File::makeDirectory($path, 0777, true, true);
        $field = $request->file('file');
        $hasfile = $request->hasFile('file');        
        $photo = productFileUpload($path, $field, $hasfile, 250);
        $makeRequest = [
            'product_id' => $product_id,
            'photo' => $photo['small'],
            'full_size' => $photo['full'],
            'user_create' => $user_id,
            'user_update' => $user_id
        ];
        $photoproduct = $this->productPhotoRepo->create($makeRequest);
        $data['response'] = 1;
        $data['success'] = $photoproduct->id;
        return response()->json($data);
    }

    public function deletephoto(Request $request)
    {
        $product_id = $request['id'];
        $path = 'uploads/products/'.$product_id;
        $photo = $this->productPhotoRepo->findByField('product_id', $product_id);
        if(File::delete([$path.'/'.$photo->small, $path.'/'.$photo->full])){
            $this->productPhotoRepo->delete($photo->id);
        }
        $data['response'] = 1;
        return response()->json($data);
    }

    public function listfile($id)
    {
        $photos = $this->productPhotoRepo->rawAll("product_id = ?", [$id]);
        return response()->json(['photos' => $photos], 200);
    }

    public function postproduct(Request $request)
    {
        $device_type = $request['device_type'];
        if($device_type == 'Both'){
            $products = $this->productRepo->rawWith(['brand','photo'], "status = ?", ['Active']);
        } elseif($device_type == 'None'){
            $products = [];
        } else {
            $products = $this->productRepo->rawWith(['brand','photo'], "status = ? and (device_type = ? or device_type = ?)", ['Active', $device_type, 'Both']);
        }
        
        return Datatables::of($products)
        ->editColumn('photo', function($products) {
            if(!empty($products->photo)){
                return '<img src="'.url($products->photo->photo).'" style="width: auto; height: 80px">';
            }
            return '';
        })
        ->editColumn('model', function($products) {
            $html  = $products->model.'<br>';
            $html .= '<small><b>Dimensions:</b> '.$products->height.' in x '.$products->width.' in x '.$products->length.' in</small><br>';
            $html .= '<small><b>Weight:</b> '.$products->weight.' ounces</small>';
            return $html;
        })
        ->editColumn('brand', function($products) {
            $html = '';
            if($products->brand_id){
                $html .= (!empty($products->brand)) ? $products->brand->name.'<br>' : '';
            }
            if($products->device_type == 'Buy'){
                $html .= '<small><b>Storage:</b> '.$products->storage.'</small><br>';
                $html .= '<small><b>Carrier:</b> '.$products->network.'</small>';
            }
            if($products->sku) $html .= '<small><b>SKU:</b> '.$products->sku.'</small>';
            return $html;
        })
        ->editColumn('type', function ($products) {
            return $products->device_type;
        })
        ->editColumn('amount', function ($products) {
            if($products->device_type == 'Sell'){
                return number_format($products->amount, 2).' USD';
            } else if($products->device_type == 'Buy'){
                return number_format($products->excellent_offer, 2).' USD';
            } else {
                $html  = '<b>Price: </b>'.number_format($products->amount, 2).' USD<br>';
                $html .= '<b>Offer: </b>'.number_format($products->excellent_offer, 2).' USD';
                return $html;
            }
        })
        ->addColumn('action', function ($products) {
            $html_out  = '';
            $html_out .= '<div class="dropdown">';
                $html_out .= '<button class="btn btn-primary dropdown-toggle btn-xs" type="button" id="action-btn" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Action</button>';
                $html_out .= '<div class="dropdown-menu dropdown-menu-right" aria-labelledby="action-btn">';
                    $html_out .= '<a class="dropdown-item" href="javascript:void(0)" onclick="duplicate(\''.$products->hashedid.'\')">Create Duplicate</a>';
                    $html_out .= '<a class="dropdown-item" href="'.url('admin/products', $products->hashedid).'/edit">Edit</a>';
                    $html_out .= '<a class="dropdown-item" href="javascript:void(0)" onclick="deleteproduct(\''.$products->hashedid.'\')">Delete</a>';
                $html_out .= '</div>';
            $html_out .= '</div>';
            return $html_out;
        })
        ->rawColumns(['photo', 'action', 'model','brand','amount'])
        ->make(true);
    }

    public function checkduplicate(Request $request)
    {
        $model = $request['name'];
        $brand_id = $request['brand_id'];
        $storage = $request['storage'];
        $id = $request['id'];
        if($id){
            $data['duplicate'] = $this->productRepo->rawCount("model = ? and brand_id = ? and storage = ? and id != ?", [$model, $brand_id, $storage, $id]);
            return response()->json($data);
        }
        $data['duplicate'] = $this->productRepo->rawCount("model = ? and brand_id = ? and storage = ?", [$model, $brand_id, $storage]);
        return response()->json($data);
    }

    public function checkduplicatedevice(Request $request)
    {
        // return $request->all();
        $model = $request['model'];
        $brand_id = $request['brand_id'];
        $color = $request['color'];
        $id = $request['product_id'];
        $data['response'] = 200;
        $data['message'] = "";
        $brand = $this->brandRepo->find($brand_id);
        if ($id != 0) {
            $duplicate = $this->productRepo->rawCount("model = ? and brand_id = ? and color = ? and id != ?", [$model, $brand_id, $color, $id]);
        } else {
            $duplicate = $this->productRepo->rawCount("model = ? and brand_id = ? and color = ?", [$model, $brand_id, $color]);
        }
        if ($duplicate > 0) 
        {
            $data['response'] = 400;
            $data['message'] = $brand['name']." ".$model." (".$color.") already exists";
        }
        return response()->json($data);
    }

    public function show($id)
    {
        $data['product'] = $this->productRepo->find($id);
        return response()->json($data);
    }

    public function storeduplicate(ProductDupRequest $request)
    {
        $id = $request['id'];
        $product = $this->productRepo->findWith($id, ['photo']);
        $photo = $this->productPhotoRepo->findByField('product_id', $id);
        $user_id = Auth::user()->id;

        $network = $request['network'];
        $storage = $request['storage'];
        $chkdup = $this->productRepo->rawCount("network = ? and storage = ? and model = ?", [$network, $storage, $product->model]);
        if($chkdup){
            $data['response'] = 2;
            return response()->json($data);
        }

        $makeRequest = [
            'brand_id' => $product->brand_id,
            'model' => $product->model,
            'color' => $product->color,
            'height' => $product->height,
            'width' => $product->width,
            'weight' => $product->weight,
            'length' => $product->length,
            'status' => 'Active',
            'device_type' => 'Buy',
            'excellent_offer' => $request['excellent_offer'],
            'good_offer' => $request['good_offer'],
            'fair_offer' => $request['fair_offer'],
            'poor_offer' => $request['poor_offer'],
            'offer_type' => $request['offer_type'],
            'description' => $product->description,
            'storage' => $storage,
            'network' => $network,
            'user_create' => $user_id,
            'user_update' => $user_id
        ];
        
        $productnew = $this->productRepo->create($makeRequest);
        $makeRequest = [
            'product_id' => $productnew->id,
            'photo' => $photo->photo->small,
            'full_size' => $photo->photo->full,
            'user_create' => $user_id,
            'user_update' => $user_id
        ];
        $this->productPhotoRepo->create($makeRequest);

        $data['response'] = 1;
        return response()->json($data);
    }

    public function findProductStorage ($hashedId) 
    {
        $id = app('App\Http\Controllers\GlobalFunctionController')->decodeHashid($hashedId);
        $data['status'] = 200;
        $data['result'] = $this->productStorageRepo->find($id);
        return response()->json($data);
    }

    private function createProductNetwork($product_id, $network_id) 
    {
        $request = ['product_id' => $product_id, 'network_id' => $network_id];
        $this->productNetworkRepo->create($request);
    }

    private function createProductStorage($product_id, $title) 
    {
        $request = ['product_id' => $product_id, 'title' => $title];
        $this->productStorageRepo->create($request);
    }

    private function createProductCategory($product_id, $category_id) 
    {
        $request = ['product_id' => $product_id, 'category_id' => $category_id];
        $this->productCategoryRepo->create($request);
    }


    private function populateProductBuy($arr_storage, $product_id) 
    {
        if (isset($arr_storage['storage_buy']) && $arr_storage['storage_buy'] != null) 
        {
            foreach ($arr_storage['storage_buy'] as $sKey => $sVal) 
            {
                $storageRequest = [
                    'product_id' => $product_id, 
                    'title' => $sVal,
                    'excellent_offer' => $arr_storage['excellent_offer'][$sKey],
                    'good_offer' => $arr_storage['good_offer'][$sKey],
                    'fair_offer' => $arr_storage['fair_offer'][$sKey],
                    'poor_offer' => $arr_storage['poor_offer'][$sKey]
                ];
                if ($arr_storage['product-storage-id'][$sKey] === "0") 
                {
                    $this->productStorageRepo->create($storageRequest);
                }
                else 
                {
                    $id = app('App\Http\Controllers\GlobalFunctionController')->decodeHashid($arr_storage['product-storage-id'][$sKey]);
                    $this->productStorageRepo->update($storageRequest, $id);
                }
            }
        }
    }

    private function populateProductSell($arr_storage, $product_id) 
    {
        if (isset($arr_storage['storage_sell']) && $arr_storage['storage_sell'] != null) 
        {
            foreach ($arr_storage['storage_sell'] as $sKey => $sVal) 
            {
                $storageRequest = [
                    'product_id' => $product_id, 
                    'title' => $sVal,
                    'amount' => $arr_storage['amount'][$sKey]
                ];
                if ($arr_storage['product-storage-id'][$sKey] === "0") 
                {
                    $this->productStorageRepo->create($storageRequest);
                }
                else 
                {
                    $id = app('App\Http\Controllers\GlobalFunctionController')->decodeHashid($arr_storage['product-storage-id'][$sKey]);
                    $this->productStorageRepo->update($storageRequest, $id);
                }
            }
        }
    }

    private function createProductStorageList($arr_storage, $product_id)
    {
        if ($arr_storage['device_type'] == "Buy") {
            $this->populateProductBuy($arr_storage, $product_id);
        } else if ($arr_storage['device_type'] == "Sell") {
            $this->populateProductSell($arr_storage, $product_id);
        } else {
            $this->populateProductBuy($arr_storage, $product_id);
            $this->populateProductSell($arr_storage, $product_id);
            // $storageRequest = [
            //     'product_id' => $product_id, 
            //     'title' => $sVal,
            //     'excellent_offer' => $arr_storage['excellent_offer'][$sKey],
            //     'good_offer' => $arr_storage['good_offer'][$sKey],
            //     'fair_offer' => $arr_storage['fair_offer'][$sKey],
            //     'poor_offer' => $arr_storage['poor_offer'][$sKey],
            //     'amount' => $arr_storage['amount'][$sKey]
            // ];
        }

        // foreach ($arr_storage['storage'] as $sKey => $sVal) 
        // {
        //     if ($arr_storage['device_type'] == "Buy") {
        //         $storageRequest = [
        //             'product_id' => $product_id, 
        //             'title' => $sVal,
        //             'excellent_offer' => $arr_storage['excellent_offer'][$sKey],
        //             'good_offer' => $arr_storage['good_offer'][$sKey],
        //             'fair_offer' => $arr_storage['fair_offer'][$sKey],
        //             'poor_offer' => $arr_storage['poor_offer'][$sKey]
        //         ];
        //     } else if ($arr_storage['device_type'] == "Sell") {
        //         $storageRequest = [
        //             'product_id' => $product_id, 
        //             'title' => $sVal,
        //             'amount' => $arr_storage['amount'][$sKey]
        //         ];
        //     } else {
        //         $storageRequest = [
        //             'product_id' => $product_id, 
        //             'title' => $sVal,
        //             'excellent_offer' => $arr_storage['excellent_offer'][$sKey],
        //             'good_offer' => $arr_storage['good_offer'][$sKey],
        //             'fair_offer' => $arr_storage['fair_offer'][$sKey],
        //             'poor_offer' => $arr_storage['poor_offer'][$sKey],
        //             'amount' => $arr_storage['amount'][$sKey]
        //         ];
        //     }
        //     // return $arr_storage['product-storage-id'][$sKey];
        //     if ($arr_storage['product-storage-id'][$sKey] === "0") 
        //     {
        //         $this->productStorageRepo->create($storageRequest);
        //     }
        //     else 
        //     {
        //         $id = app('App\Http\Controllers\GlobalFunctionController')->decodeHashid($arr_storage['product-storage-id'][$sKey]);
        //         $this->productStorageRepo->update($storageRequest, $id);
        //     }
        // }
        return true;
    }

    private function makeProductRequest($request) 
    {
        return [
            'brand_id' => $request['brand_id'],
            'model' => $request['name'],
            'color' => $request['color'],
            'height' => $request['height'],
            'width' => $request['width'],
            'weight' => $request['weight'],
            'length' => $request['length'],
            'status' => 'Active',
            'device_type' => $request['device_type'],
            // 'amount' => ($request['device_type'] == 'Buy' || $request['device_type'] == 'Both') ? $request['amount'] : $request['amount'],
            // 'excellent_offer' => ($request['device_type'] == 'Buy') ? $request['excellent_offer'] : '',
            // 'good_offer' => ($request['device_type'] == 'Buy' || $request['device_type'] == 'Both') ? $request['good_offer'] : '',
            // 'fair_offer' => ($request['device_type'] == 'Buy' || $request['device_type'] == 'Both') ? $request['fair_offer'] : '',
            // 'poor_offer' => ($request['device_type'] == 'Buy' || $request['device_type'] == 'Both') ? $request['poor_offer'] : '',
            // 'offer_type' => $request['offer_type'],
            'sku' => ($request['device_type'] != 'Buy' || $request['device_type'] == 'Both') ? $request['sku'] : '',
            'description' => $request['description'],
            // 'storage' => $request['storage'],
            // 'network' => $request['network'],
            'user_update' => Auth::user()->id
        ];
    }

}
