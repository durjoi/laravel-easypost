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
use App\Repositories\Admin\NetworkRepositoryEloquent as NetworkRepo;
use App\Repositories\Admin\ProductNetworkEloquentRepository as ProductNetworkRepo;
use App\Repositories\Admin\ProductStorageEloquentRepository as ProductStorageRepo;
use App\Repositories\Customer\CustomerSellRepositoryEloquent as CustomerSellRepo;
use App\Repositories\Customer\CustomerRepositoryEloquent as CustomerRepo;
use App\Repositories\Customer\CustomerTransactionRepositoryEloquent as CustomerTransactionRepo;

class SettingsStatusController extends Controller
{
    protected $brandRepo;
    protected $productRepo;
    protected $productPhotoRepo;
    protected $configRepo;
    protected $networkRepo;
    protected $productNetworkRepo;
    protected $productStorageRepo;
    protected $customerSellRepo;
    protected $customerRepo;
    protected $customerTransactionRepo;

    function __construct(
                        Brand $brandRepo, 
                        Product $productRepo, 
                        ProductPhoto $productPhotoRepo, 
                        Config $configRepo, 
                        NetworkRepo $networkRepo, 
                        ProductNetworkRepo $productNetworkRepo, 
                        ProductStorageRepo $productStorageRepo, 
                        CustomerSellRepo $customerSellRepo, 
                        CustomerRepo $customerRepo, 
                        CustomerTransactionRepo $customerTransactionRepo
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
        $this->customerRepo = $customerRepo;
        $this->customerTransactionRepo = $customerTransactionRepo;
    }
    
    public function index () 
    {
        $data['module'] = 'status';
        $data['config'] = $this->configRepo->find(1);
        return view('admin.settings.status.index', $data);
    }
}
