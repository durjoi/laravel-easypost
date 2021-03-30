<?php

namespace App\Http\Controllers;

use Vinkla\Hashids\HashidsManager;
use App\Repositories\Customer\CustomerRepositoryEloquent as Customer;

class GlobalFunctionController extends Controller 
{
    protected $hashids;
    protected $customerRepo;

    public function __construct (HashidsManager $hashids, Customer $customerRepo) 
    {
        $this->hashids = $hashids;
        $this->customerRepo = $customerRepo;
    }


    public function encodeHashid ($id) 
    {
        return $this->hashids->encode($id);
    }


    public function decodeHashid ($hashid) 
    {
        $decode = $this->hashids->decode($hashid);
        $output = $decode[0];
        return $output;
    }

    public function generateUUID () 
    {
        $bytes = random_bytes(5);
        return strtoupper(bin2hex($bytes));
    }
}