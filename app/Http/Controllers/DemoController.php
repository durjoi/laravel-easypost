<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Repositories\Admin\OrderRepositoryEloquent as Order;
use Illuminate\Support\Facades\Crypt;

class DemoController extends Controller
{
    protected $orderRepo;
    
    function __construct(Order $orderRepo) 
    {
        $this->orderRepo = $orderRepo;
    }

    public function VerificationPage () 
    {
        
        // return $order = $this->orderRepo->rawByWithField(['customer.bill', 'status_details'], "id = ?", [1]);
        return view('demo.verification.index');
    }
    public function EmailTemplateIndex () 
    {
        
        // return $order = $this->orderRepo->rawByWithField(['customer.bill', 'status_details'], "id = ?", [1]);
        return view('demo.emailtemplate.index');
    }

    public function EmailTemplateCreate () 
    {
        return view('demo.emailtemplate.create');
    }

    public function EmailTemplateEdit () 
    {
        return view('demo.emailtemplate.edit');
    }
}
