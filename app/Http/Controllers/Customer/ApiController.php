<?php

namespace App\Http\Controllers\Customer;

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
use App\Repositories\Admin\NetworkRepositoryEloquent as Network;
use App\Repositories\Admin\ProductNetworkEloquentRepository as ProductNetwork;
use App\Repositories\Admin\ProductStorageEloquentRepository as ProductStorage;
use App\Repositories\Customer\CustomerSellRepositoryEloquent as CustomerSell;
use App\Repositories\Admin\OrderRepositoryEloquent as Order;
use App\Repositories\Admin\OrderItemRepositoryEloquent as OrderItem;
use App\Repositories\Admin\SettingsStatusEloquentRepository as SettingsStatus;
use App\Repositories\Customer\CustomerRepositoryEloquent as Customer;

class ApiController extends Controller
{
    protected $brandRepo;
    protected $productRepo;
    protected $productPhotoRepo;
    protected $configRepo;
    protected $networkRepo;
    protected $productNetworkRepo;
    protected $productStorageRepo;
    protected $customerSellRepo;
    protected $orderRepo;
    protected $orderItemRepo;
    protected $settingsStatusRepo;
    protected $customerRepo;

    function __construct(
                        Brand $brandRepo, 
                        Product $productRepo, 
                        ProductPhoto $productPhotoRepo, 
                        Config $configRepo, 
                        Network $networkRepo, 
                        ProductNetwork $productNetworkRepo, 
                        ProductStorage $productStorageRepo, 
                        CustomerSell $customerSellRepo, 
                        Order $orderRepo, 
                        OrderItem $orderItemRepo, 
                        SettingsStatus $settingsStatusRepo, 
                        Customer $customerRepo
                        )
    {
        $this->brandRepo = $brandRepo;
        $this->productRepo = $productRepo;
        $this->productPhotoRepo = $productPhotoRepo;
        $this->configRepo = $configRepo;
        $this->networkRepo = $networkRepo;
        $this->productNetworkRepo = $productNetworkRepo;
        $this->productStorageRepo = $productStorageRepo;
        $this->customerSellRepo = $customerSellRepo;
        $this->orderRepo = $orderRepo;
        $this->orderItemRepo = $orderItemRepo;
        $this->settingsStatusRepo = $settingsStatusRepo;
        $this->customerRepo = $customerRepo;
    }
    
    public function ChangePassword (Request $request) 
    {
        if ($request['id'] == '') {
            $response['status'] = 400;
            $response['error'] = "Id missing";
        } else if ($request['password'] == '') {
            $response['status'] = 400;
            $response['error'] = "New Password is required";
        } else if (strlen($request['password']) <= 5) {
            $response['status'] = 400;
            $response['error'] = "New password must be atleast 6 characters";
        } else if ($request['retype-password'] == '') {
            $response['status'] = 400;
            $response['error'] = "Re-type Password is required";
        } else if ($request['password'] != $request['retype-password']) {
            $response['status'] = 400;
            $response['error'] = "New Password and Re-type Password not matched";
        } else {
            $id = app('App\Http\Controllers\GlobalFunctionController')->decodeHashid($request['id']);
            $customer = $this->customerRepo->find($id);
            $makeRequest = [
                'password' => bcrypt($request['password']), 
                'authpw' => $request['password']
            ];
            $this->customerRepo->update($makeRequest, $id);

            $response['status'] = 200;
            $response['message'] = "Password has been successfully updated";
        }
        return response()->json($response);
    }   


    public function GetOrderItem ($hashedId) 
    {
        $id = app('App\Http\Controllers\GlobalFunctionController')->decodeHashid($hashedId);
        $data['customerSell'] = $this->orderItemRepo->rawByWithField(['product_storage'], 'id = ?', [$id]);
        $data['productDetails'] = $this->productRepo->rawByWithField(['networks.network'], 'id = ?', [$data['customerSell']['product_id']]);
        $data['productDetails']['storages'] = $data['productDetails']->storagesForBuying()->get();
        return $data;
    } 

    public function verification (Request $request) 
    {
        if ($request['input1'] == '' || $request['input2'] == '' || $request['input3'] == '' || $request['input4'] == '') 
        {
            $response['status'] = 400;
            $response['message'] = "Please enter valid verification code";
            return response()->json($response);
        }

        $merge_code = $request['input1'].''.$request['input2'].''.$request['input3'].''.$request['input4']; 
        // return Auth::guard('customer')->user()->verification_code .' - '. $merge_code;
        if (Auth::guard('customer')->user()->verification_code != $merge_code) 
        {
            $response['status'] = 400;
            $response['message'] = "Verification code not matched";
            return response()->json($response);  
        }
        
        $makeRequest = [
            'status' => 'Active', 
            'is_verified' => 1
        ];
        $this->customerRepo->update($makeRequest, Auth::guard('customer')->user()->id);
        $response['status'] = 200;
        $response['message'] = "Account verified";
        return response()->json($response);  
    }
    
}
