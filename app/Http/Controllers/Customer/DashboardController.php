<?php

namespace App\Http\Controllers\Customer;

use Illuminate\Http\Request;
use EasyPost\Tracker;
use EasyPost\EasyPost;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\Facades\DataTables;
use App\Repositories\Customer\CustomerRepositoryEloquent as Customer;
use App\Repositories\Admin\OrderRepositoryEloquent as Order;
use App\Repositories\Customer\DeviceRepositoryEloquent as Device;

class DashboardController extends Controller
{
    protected $customerRepo;
    protected $orderRepo;
    protected $deviceRepo;

    function __construct(Customer $customerRepo, Order $orderRepo, Device $deviceRepo)
    {
        $this->customerRepo = $customerRepo;
        $this->orderRepo = $orderRepo;
        $this->deviceRepo = $deviceRepo;
    }

    public function index()
    {
        $customer_id = Auth::guard('customer')->user()->id;
        $data['ordercount'] = $this->orderRepo->rawCount("customer_id = ?", $customer_id);
        return view('customer.dashboard', $data);
    }

    public function getdevices()
    {
        $customer_id = Auth::guard('customer')->user()->id;
        $devices = $this->deviceRepo->rawWith(['product'], "customer_id = ?", [$customer_id]);
        return Datatables::of($devices)
        ->editColumn('device', function($devices) {
            return $devices->product->model;
        })
        ->editColumn('carrier', function($devices) {
            return $devices->product->network;
        })
        ->editColumn('storage', function($devices) {
            return $devices->product->storage;
        })
        ->editColumn('amount', function ($devices) {
            return number_format($devices->amount, 2).' USD';
        })
        ->editColumn('status', function ($devices) {
            return 'Pending';
        })
        ->make(true);
    }
}
