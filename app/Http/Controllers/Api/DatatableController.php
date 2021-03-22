<?php

namespace App\Http\Controllers\API;

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
use App\Repositories\Admin\SettingsCategoryEloquentRepository as SettingsCategory;

class DatatableController extends Controller
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
    protected $settingsCategoryRepo;

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
                        SettingsCategory $settingsCategoryRepo
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
        $this->settingsCategoryRepo = $settingsCategoryRepo;
    }
    


    public function GetBrands()
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

    public function GetStatuses()
    {
        $status = $this->settingsStatusRepo->all();
        return Datatables::of($status)
        ->editColumn('name', function($status) {
            return $status->name;
        })
        ->editColumn('module', function($status) {
            return $status->module;
        })
        ->editColumn('email_sending', function($status) {
            return $status->email_sending.'d';
        })
        ->editColumn('default', function($status) {
            return ($status->default == 1) ? '<span class="label label-info">Default</span>' : '';
        })
        ->addColumn('action', function ($status) {
            $html_out  = '';
            $html_out .= '<a href="javascript:void(0)" onclick="editStatus(\''.$status->hashedid.'\')" class="btn btn-primary btn-xs btn-flat"><i class="fa fa-pencil-alt fa-fw"></i> &nbsp;Edit</a>&nbsp;&nbsp;';
            $html_out .= '<a href="javascript:void(0)" onclick="deleteStatus(\''.$status->hashedid.'\')" class="btn btn-danger btn-xs btn-flat"><i class="fa fa-trash-alt fa-fw"></i> &nbsp;Delete</a>';
            return $html_out;
        })
        ->rawColumns(['name', 'module', 'email_sending', 'default', 'action'])
        ->make(true);
    }

    public function getOrders (Request $request) 
    {
        $orders = $this->orderRepo->rawWith([
                                                            'customer',
                                                            'order_item', 
                                                            'order_item.customer',
                                                            'order_item.product', 
                                                            'order_item.product.brand', 
                                                            'order_item.product.photo', 
                                                            'order_item.network', 
                                                        ], 
                                                        "1 = ?", 
                                                        [1]);
                                                        
        return Datatables::of($orders)
        ->editColumn('seller_name', function($orders) {
            $html  = $orders['customer']['fullname'];
            return $html;
        })
        ->editColumn('order_no', function($orders) {
            $html  = $orders['order_no'];
            return $html;
        })
        ->editColumn('status', function($orders) {
            $html  = $orders['status']['name'];
            return $html;
        })
        ->editColumn('transaction_date', function($orders) {
            $html  = $orders['display_transaction_date'];
            return $html;
        })
        ->editColumn('delivery_due', function($orders) {
            $html  = $orders['display_delivery_due'];
            return $html;
        })
        ->addColumn('action', function ($orders) {
            $html_out  = '';
            $html_out .= '<div class="dropdown">';
                $html_out .= '<button class="btn btn-primary dropdown-toggle btn-xs" type="button" id="action-btn" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Action</button>';
                $html_out .= '<div class="dropdown-menu dropdown-menu-right" aria-labelledby="action-btn">';
                    $html_out .= '<a class="dropdown-item" href="'.url('admin/orders/'.$orders['hashedid']).'/edit">Edit</a>';
                    $html_out .= '<a class="dropdown-item" href="javascript:void(0)" onclick="deleteproduct(\''.$orders['hashedid'].'\')">Delete</a>';
                $html_out .= '</div>';
            $html_out .= '</div>';
            return $html_out;
        })
        // ->rawColumns(['photo', 'action', 'model','brand','amount'])
        ->rawColumns(['order_no', 'seller_name', 'status', 'transaction_date', 'delivery_due', 'action'])
        ->make(true);
    }
    

    public function GetCategories()
    {
        $category = $this->settingsCategoryRepo->all();
        return Datatables::of($category)
        ->editColumn('name', function($category) {
            return $category->name;
        })
        ->addColumn('action', function ($category) {
            $html_out  = '';
            $html_out .= '<a href="javascript:void(0)" onclick="editCategory(\''.$category->hashedid.'\')" class="btn btn-primary btn-xs btn-flat"><i class="fa fa-pencil-alt fa-fw"></i> &nbsp;Edit</a>&nbsp;&nbsp;';
            $html_out .= '<a href="javascript:void(0)" onclick="deleteCategory(\''.$category->hashedid.'\')" class="btn btn-danger btn-xs btn-flat"><i class="fa fa-trash-alt fa-fw"></i> &nbsp;Delete</a>';
            return $html_out;
        })
        ->rawColumns(['name', 'action'])
        ->make(true);
    }
    
    
}
