<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Yajra\DataTables\Facades\DataTables;
use App\Http\Requests\Admin\ProductRequest;
use App\Http\Requests\Admin\ProductDupRequest;
use App\Repositories\Admin\BrandRepositoryEloquent as Brand;
use App\Repositories\Admin\ConfigRepositoryEloquent as Config;
use App\Repositories\Admin\ProductRepositoryEloquent as Product;
use App\Repositories\Admin\ProductPhotoRepositoryEloquent as ProductPhoto;

class ProductController extends Controller
{
    protected $brandRepo;
    protected $productRepo;
    protected $productPhotoRepo;
    protected $configRepo;

    function __construct(Brand $brandRepo, Product $productRepo, ProductPhoto $productPhotoRepo, Config $configRepo)
    {
        $this->brandRepo = $brandRepo;
        $this->productRepo = $productRepo;
        $this->productPhotoRepo = $productPhotoRepo;
        $this->configRepo = $configRepo;
    }

    public function index()
    {
        $data['storageList'] = [''=>'--','32GB'=>'32GB','64GB'=>'64GB','128GB'=>'128GB','256GB'=>'256GB'];
        $data['networkList'] = [''=>'--','AT&T'=>'AT&T','Sprint'=>'Sprint','T-Mobile'=>'T-Mobile','Verizon'=>'Verizon','Unlocked'=>'Unlocked','Others'=>'Others'];
        $data['config'] = $this->configRepo->find(1);
        return view('admin.products.index', $data);
    }

    public function store(ProductRequest $request)
    {
        $device_type = $request['device_type'];
        $user_id = Auth::user()->id;
        $chkdup = $this->productRepo->rawCount("brand_id = ? and network = ? and storage = ? and model = ?", [$request['brand_id'], $request['network'], $request['storage'], $request['name']]);
        $makeRequest = [
            'brand_id' => $request['brand_id'],
            'model' => $request['name'],
            'color' => $request['color'],
            'height' => $request['height'],
            'width' => $request['width'],
            'weight' => $request['weight'],
            'length' => $request['length'],
            'status' => 'Active',
            'device_type' => $device_type,
            'amount' => $request['amount'],
            'excellent_offer' => $request['excellent_offer'],
            'good_offer' => $request['good_offer'],
            'fair_offer' => $request['fair_offer'],
            'poor_offer' => $request['poor_offer'],
            'offer_type' => $request['offer_type'],
            'sku' => ($request['device_type'] != 'Buy') ? $request['sku'] : '',
            'description' => $request['description'],
            'storage' => $request['storage'],
            'network' => $request['network'],
            'user_create' => $user_id,
            'user_update' => $user_id
        ];
        
        if($chkdup == 0){
            $product = $this->productRepo->create($makeRequest);
            $path = 'uploads/products/'.$product->id;
            File::makeDirectory($path, 0777, true, true);
            $field = $request->file('photo');
            $hasfile = $request->hasFile('photo');        
            $photo = productFileUpload($path, $field, $hasfile, 250);
            $makeRequest = [
                'product_id' => $product->id,
                'photo' => $photo['small'],
                'full_size' => $photo['full'],
                'user_create' => $user_id,
                'user_update' => $user_id
            ];
            $this->productPhotoRepo->create($makeRequest);
            $data['response'] = 1;
            return redirect()->to('admin/products')->with('msg', 'New device has been added!');
        }
        return redirect()->back()->with('errormsg', 'This device is already exists!');
    }

    public function create()
    {
        $data['brandList'] = $this->brandRepo->selectlist('name', 'id');
        $data['statusList'] = [''=>'Choose Status', 'Active'=>'Active', 'Draft'=>'Draft', 'Inactive'=>'Inactive'];
        $data['typeList'] = [''=>'--', 'Sell'=>'I want to sell this device', 'Buy'=>'I want to buy this kind of device', 'Both'=>'I want to buy and sell this device'];
        $data['storageList'] = [''=>'--','32GB'=>'32GB','64GB'=>'64GB','128GB'=>'128GB','256GB'=>'256GB','512GB'=>'512GB'];
        $data['networkList'] = [''=>'--','AT&T'=>'AT&T','Sprint'=>'Sprint','T-Mobile'=>'T-Mobile','Verizon'=>'Verizon','Unlocked'=>'Unlocked'];
        $data['config'] = $this->configRepo->find(1);
        return view('admin.products.create', $data);
    }

    public function update(ProductRequest $request, $id)
    {
        $device_type = $request['device_type'];
        $user_id = Auth::user()->id;
        $chkdup = $this->productRepo->rawCount("brand_id = ? and network = ? and storage = ? and model = ? and id != ?", [$request['brand_id'], $request['network'], $request['storage'], $request['name'], $id]);
        $makeRequest = [
            'brand_id' => $request['brand_id'],
            'model' => $request['name'],
            'color' => $request['color'],
            'height' => $request['height'],
            'width' => $request['width'],
            'weight' => $request['weight'],
            'length' => $request['length'],
            'status' => 'Active',
            'device_type' => $device_type,
            'amount' => ($request['device_type'] == 'Buy') ? $request['amount'] : '',
            'excellent_offer' => ($request['device_type'] == 'Buy') ? $request['excellent_offer'] : '',
            'good_offer' => ($request['device_type'] == 'Buy') ? $request['good_offer'] : '',
            'fair_offer' => ($request['device_type'] == 'Buy') ? $request['fair_offer'] : '',
            'poor_offer' => ($request['device_type'] == 'Buy') ? $request['poor_offer'] : '',
            'offer_type' => $request['offer_type'],
            'sku' => ($request['device_type'] != 'Buy') ? $request['sku'] : '',
            'description' => $request['description'],
            'storage' => $request['storage'],
            'network' => $request['network'],
            'user_update' => $user_id
        ];
        
        if($chkdup == 0){
            $this->productRepo->update($makeRequest, $id);
            $path = 'uploads/products/'.$id;
            File::makeDirectory($path, 0777, true, true);
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
    }

    public function edit($id)
    {
        $data['product'] = $this->productRepo->findWith($id, ['photo']);
        $data['brandList'] = $this->brandRepo->selectlist('name', 'id');
        $data['statusList'] = [''=>'Choose Status', 'Active'=>'Active', 'Draft'=>'Draft', 'Inactive'=>'Inactive'];
        $data['typeList'] = [''=>'--', 'Sell'=>'I want to sell this device', 'Buy'=>'I want to buy this kind of device', 'Both'=>'I want to buy and sell this device'];
        $data['storageList'] = [''=>'--','32GB'=>'32GB','64GB'=>'64GB','128GB'=>'128GB','256GB'=>'256GB','512GB'=>'512GB'];
        $data['networkList'] = [''=>'--','AT&T'=>'AT&T','Sprint'=>'Sprint','T-Mobile'=>'T-Mobile','Verizon'=>'Verizon','Unlocked'=>'Unlocked'];
        $data['config'] = $this->configRepo->find(1);
        return view('admin.products.edit', $data);
    }

    public function destroy($id)
    {
        $this->productRepo->update(['status' => 'Inactive'], $id);
        $data['response'] = 1;
        return response()->json($data);
    }

    public function getproduct()
    {
        $products = $this->productRepo->rawWith(['brand','photo'], "status = ?", ['Active']);
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
                    $html_out .= '<a class="dropdown-item" href="javascript:void(0)" onclick="duplicate(\''.$products->id.'\')">Create Duplicate</a>';
                    $html_out .= '<a class="dropdown-item" href="'.url('admin/products', $products->id).'/edit">Edit</a>';
                    $html_out .= '<a class="dropdown-item" href="javascript:void(0)" onclick="deleteproduct(\''.$products->id.'\')">Delete</a>';
                $html_out .= '</div>';
            $html_out .= '</div>';
            return $html_out;
        })
        ->rawColumns(['photo', 'action', 'model','brand','amount'])
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
                    $html_out .= '<a class="dropdown-item" href="javascript:void(0)" onclick="duplicate(\''.$products->id.'\')">Create Duplicate</a>';
                    $html_out .= '<a class="dropdown-item" href="'.url('admin/products', $products->id).'/edit">Edit</a>';
                    $html_out .= '<a class="dropdown-item" href="javascript:void(0)" onclick="deleteproduct(\''.$products->id.'\')">Delete</a>';
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
        $network = $request['network'];
        $id = $request['id'];
        if($id){
            $data['duplicate'] = $this->productRepo->rawCount("model = ? and brand_id = ? and network = ? and id != ?", [$model, $brand_id, $network, $id]);
            return response()->json($data);
        }
        $data['duplicate'] = $this->productRepo->rawCount("model = ? and brand_id = ? and network = ?", [$model, $brand_id, $network]);
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

}
