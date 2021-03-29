<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use App\Repositories\Customer\CustomerRepositoryEloquent as Customer;

class CustomerController extends Controller
{
    protected $customerRepo;

    function __construct(Customer $customerRepo)
    {
        $this->customerRepo = $customerRepo;
    }

    public function index()
    {
        $data['module'] = 'customer';
        return view('admin.customers.index', $data);
    }
}
