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

class ProductMappingController extends Controller
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
        return 'asd';
        $data['storageList'] = [''=>'--','32GB', '64GB','128GB','256GB','512GB'];
        $data['networkList'] = [''=>'--','AT&T'=>'AT&T','Sprint'=>'Sprint','T-Mobile'=>'T-Mobile','Verizon'=>'Verizon','Unlocked'=>'Unlocked'];
        $data['config'] = $this->configRepo->find(1);
        $data['module'] = 'product';
        $data['is_dark_mode'] = ($data['config']['is_dark_mode'] == 1) ? true : false;
        $data['tvproducts'] = true;
        return view('admin.products.index', $data);
    }
}


