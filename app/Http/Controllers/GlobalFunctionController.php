<?php

namespace App\Http\Controllers;

use Vinkla\Hashids\HashidsManager;
use App\Repositories\Customer\CustomerRepositoryEloquent as Customer;
use Illuminate\Support\Facades\Crypt;

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

    public function encrypt ($str) 
    {
        return Crypt::encryptString($str);
    }

    public function decrypt ($str) 
    {
        return $encrypted = Crypt::decryptString($str);
    }

    public function generateUUID () 
    {
        $bytes = random_bytes(5);
        return strtoupper(bin2hex($bytes));
    }
}