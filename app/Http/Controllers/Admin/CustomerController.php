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

    public function getcustomer()
    {
        $customers = $this->customerRepo->rawWith(['bill'], "status = ?", ['Active']);
        return Datatables::of($customers)
        ->editColumn('fullname', function($customers) {
            return $customers->fname.' '.$customers->lname;
        })
        ->editColumn('address', function($customers) {
            if(!empty($customers->bill)){
                return $customers->bill->street.' '.$customers->bill->city.' '.$customers->bill->state;
            }
            return '';
        })
        ->addColumn('action', function ($customers) {
            $html_out  = '';
            $html_out .= '<div class="dropdown">';
                $html_out .= '<button class="btn btn-primary dropdown-toggle btn-xs" type="button" id="action-btn" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Action</button>';
                $html_out .= '<div class="dropdown-menu" aria-labelledby="action-btn">';
                    $html_out .= '<a class="dropdown-item" href="javascript:void(0)" onclick="updatecustomer(\''.$customers->id.'\')">Edit</a>';
                    $html_out .= '<a class="dropdown-item" href="javascript:void(0)" onclick="deletecustomer(\''.$customers->id.'\')">Delete</a>';
                $html_out .= '</div>';
            $html_out .= '</div>';
            return $html_out;
        })
        ->rawColumns(['photo', 'action'])
        ->make(true);
    }
}
