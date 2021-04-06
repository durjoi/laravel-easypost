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
use App\Repositories\Customer\StateRepositoryEloquent as State;
use App\Repositories\Admin\ProductPhotoRepositoryEloquent as ProductPhoto;
use App\Repositories\Admin\NetworkRepositoryEloquent as Network;
use App\Repositories\Admin\ProductNetworkEloquentRepository as ProductNetwork;
use App\Repositories\Admin\ProductStorageEloquentRepository as ProductStorage;
use App\Repositories\Customer\CustomerSellRepositoryEloquent as CustomerSell;
use App\Repositories\Customer\CustomerRepositoryEloquent as Customer;
use App\Repositories\Customer\CustomerTransactionRepositoryEloquent as CustomerTransaction;
use App\Repositories\Admin\UserRepositoryEloquent as User;
use App\Repositories\Admin\MenuRepositoryEloquent as Menu;

class SettingsController extends Controller
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
    protected $stateRepo;
    protected $userRepo;
    protected $menuRepo;

    function __construct(
                        Brand $brandRepo, 
                        Product $productRepo, 
                        ProductPhoto $productPhotoRepo, 
                        Config $configRepo, 
                        Network $networkRepo, 
                        ProductNetwork $productNetworkRepo, 
                        ProductStorage $productStorageRepo, 
                        CustomerSell $customerSellRepo, 
                        Customer $customerRepo, 
                        CustomerTransaction $customerTransactionRepo, 
                        State $stateRepo, 
                        User $userRepo, 
                        Menu $menuRepo
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
        $this->stateRepo = $stateRepo;
        $this->userRepo = $userRepo;
        $this->menuRepo = $menuRepo;
    }

    public function config()
    {
        $data['config'] = $this->configRepo->find(1);
        $data['tvsettings'] = true;
        $data['stateList'] = $this->stateRepo->selectlist('name', 'abbr');
        $data['module'] = 'config';
        $data['is_dark_mode'] = ($data['config']['is_dark_mode'] == 1) ? true : false;
        return view('admin.settings.config.index', $data);
    }
    
    public function status () 
    {
        $data['module'] = 'status';
        $data['tvsettings'] = true;
        $data['config'] = $this->configRepo->find(1);
        $data['is_dark_mode'] = ($data['config']['is_dark_mode'] == 1) ? true : false;
        return view('admin.settings.status.index', $data);
    }
    
    public function categories () 
    {
        $data['module'] = 'category';
        $data['tvsettings'] = true;
        $data['config'] = $this->configRepo->find(1);
        $data['is_dark_mode'] = ($data['config']['is_dark_mode'] == 1) ? true : false;
        return view('admin.settings.categories.index', $data);
    }

    public function Menus()
    {
        $data['menus'] = $this->menuRepo->all();
        $data['module'] = 'menu';
        $data['tvsettings'] = true;
        $config = $this->configRepo->find(1);
        $data['is_dark_mode'] = ($config['is_dark_mode'] == 1) ? true : false;
        return view('admin.settings.menus.index', $data);
    }
    
    public function Users () 
    {
        $data['module'] = 'user';
        $data['tvsettings'] = true;
        $config = $this->configRepo->find(1);
        $data['is_dark_mode'] = ($config['is_dark_mode'] == 1) ? true : false;
        return view('admin.settings.users.index', $data);
    }

    public function CreateUser()
    {
        $data['module'] = 'user';
        $data['tvsettings'] = true;
        $config = $this->configRepo->find(1);
        $data['is_dark_mode'] = ($config['is_dark_mode'] == 1) ? true : false;
        return view('admin.settings.users.create', $data);
    }

    public function EditUser($hashedId)
    {
        $data['module'] = 'user';
        $data['tvsettings'] = true;
        $id = app('App\Http\Controllers\GlobalFunctionController')->decodeHashid($hashedId);
        $data['user'] = $this->userRepo->find($id);
        $config = $this->configRepo->find(1);
        $data['is_dark_mode'] = ($config['is_dark_mode'] == 1) ? true : false;
        return view('admin.settings.users.edit', $data);
    }
}
