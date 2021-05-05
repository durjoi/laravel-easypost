<?php

namespace App\Http\Controllers;

use Vinkla\Hashids\HashidsManager;
use App\Repositories\Customer\CustomerRepositoryEloquent as Customer;
use App\Repositories\Admin\ConfigRepositoryEloquent as Config;
use Illuminate\Support\Facades\Crypt;
use App\Models\TableList as Tablelist;

// For Plivio
require __DIR__ . '/../../../vendor/autoload.php';
use Plivo\RestClient;
use Plivo\Exceptions\PlivoAuthenticationException;
use Plivo\Exceptions\PlivoRestException;


class GlobalFunctionController extends Controller 
{
    protected $hashids;
    protected $configRepo;
    protected $customerRepo;
    protected $tablelist;

    public function __construct (HashidsManager $hashids, Config $configRepo, Customer $customerRepo, TableList $tablelist) 
    {
        $this->hashids = $hashids;
        $this->configRepo = $configRepo;
        $this->customerRepo = $customerRepo;
        $this->tablelist = $tablelist;
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

    public function verificationCode () 
    {
        return mt_rand(1000, 9999);
    }

    public function checkSMSFeatureIfActive () 
    {
        $config = $this->configRepo->find(1);    
        return ($config->is_sms_feature_active == 1) ? true : false;
    }
    
    public function doSmsSending($phone, $message) 
    {
        if ($this->checkSMSFeatureIfActive() == false) return false;

        $plivo_credentials = $this->tablelist->plivo_client_credentials;

        $client = new RestClient($plivo_credentials['auth_id'], $plivo_credentials['auth_token']); 

        $message_created = $client->messages->create(
            $plivo_credentials['sender'],
            [$phone],
            // ['+971503361319'],
            $message
        );
        return ($message_created) ? true : false;
    }
}