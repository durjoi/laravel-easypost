<?php

namespace App\Http\Controllers;

use EasyPost\Parcel;
use EasyPost\Address;
use EasyPost\Tracker;
use EasyPost\EasyPost;
use EasyPost\Shipment;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Http\Requests\SellRequest;
use Saperemarketing\Phpmailer\Facades\Mailer;
use App\Repositories\Admin\SettingsBrandRepositoryEloquent as Brand;
use App\Repositories\Admin\ConfigRepositoryEloquent as Config;
use App\Repositories\Customer\StateRepositoryEloquent as State;
use App\Repositories\Admin\ProductRepositoryEloquent as Product;
use App\Repositories\Admin\ProductStorageEloquentRepository as ProductStorage;
use App\Repositories\Customer\DeviceRepositoryEloquent as Device;
use App\Repositories\Customer\CustomerRepositoryEloquent as Customer;
use App\Repositories\Customer\CustomerAddressRepositoryEloquent as CustomerAddress;
use App\Repositories\Customer\CustomerOrdersRepositoryEloquent as CustomerOrder;
use App\Repositories\Customer\CustomerSellRepositoryEloquent as CustomerSell;
use App\Repositories\Admin\OrderRepositoryEloquent as Order;
use App\Repositories\Admin\OrderItemRepositoryEloquent as OrderItem;
use App\Repositories\Customer\CustomerTransactionRepositoryEloquent as CustomerTransaction;
use App\Models\Admin\Product as ModelProduct;

class DeviceController extends Controller
{
    protected $brandRepo;
    protected $productRepo;
    protected $stateRepo;
    protected $customerRepo;
    protected $addressRepo;
    protected $deviceRepo;
    protected $configRepo;
    protected $productStorageRepo;
    protected $customerOrderRepo;
    protected $customerSellRepo;
    protected $customerTransactionRepo;
    protected $product;
    protected $orderRepo;
    protected $orderItemRepo;

    function __construct(Brand $brandRepo, 
                        Product $productRepo, 
                        State $stateRepo, 
                        Customer $customerRepo, 
                        CustomerAddress $addressRepo, 
                        Device $deviceRepo, 
                        Config $configRepo, 
                        ProductStorage $productStorageRepo, 
                        CustomerOrder $customerOrderRepo, 
                        CustomerSell $customerSellRepo,
                        CustomerTransaction $customerTransactionRepo, 
                        ModelProduct $product, 
                        Order $orderRepo, 
                        OrderItem $orderItemRepo)
    {
        $this->brandRepo = $brandRepo;
        $this->productRepo = $productRepo;
        $this->stateRepo = $stateRepo;
        $this->customerRepo = $customerRepo;
        $this->addressRepo = $addressRepo;
        $this->deviceRepo = $deviceRepo;
        $this->configRepo = $configRepo;
        $this->productStorageRepo = $productStorageRepo;
        $this->customerOrderRepo = $customerOrderRepo;
        $this->customerSellRepo = $customerSellRepo;
        $this->customerTransactionRepo = $customerTransactionRepo;
        $this->product = $product;
        $this->orderRepo = $orderRepo;
        $this->orderItemRepo = $orderItemRepo;
    }

    public function index()
    {
        return "111";
        if(session()->has('result')){
            return view('front.device.index');
        }
        return redirect()->to('/');
    }

    public function checkout($brand)
    {
        $data['brand'] = $brand;
        $data['stateList'] = $this->stateRepo->selectlist('name', 'abbr');
        $data['paymentList'] = [
            '' => '--',
            'Apple Pay' => 'Apple Pay',
            'Google Pay' => 'Google Pay',
            'Venmo' => 'Venmo',
            'Cash App' => 'Cash App',
            'Paypal' => 'Paypal',
            'Bank Transfer' => 'Bank Transfer'
        ];
        $brandDetails = $this->brandRepo->findByField('name', $brand);
        $data['chkproduct'] = $this->productRepo->rawCount("brand_id = ?", [$brandDetails->id]);
        $data['networks'] =  $this->productRepo->queryTable()->whereRaw("brand_id = ? and device_type IN ('Buy', 'Both')", [$brandDetails->id])->groupBy('network')->get();
        $data['brandDetails'] = $brandDetails;
        $allProducts = $this->productRepo->rawByWithFieldAll(['photo'], "brand_id = ?", [$brandDetails->id], 'model');
        $data['products'] = [];
        foreach ($allProducts as $key => $val) 
        {
            if ($this->product->find($val['id'])->storagesForBuying()->count() >= 1) {
                $data['products'][$key] = $val;
                $data['products'][$key]['storages'] = $this->product->find($val['id'])->storagesForBuying()->get();
            }
        }
        return view('front.device.checkout', $data);
    }

    public function network(Request $request)
    {
        $brand = $this->brandRepo->findByField('name', $request['brand']);
        $products = $this->productRepo->rawWithGroup(['photo'], "brand_id = ? and network = ? and device_type IN ('Both', 'Buy')", [$brand->id, $request['network']], 'model'); 
        $content = '';
        foreach ($products as $product) {
            $content .= '<div class="col-md-3">';
                $content .= '<div class="card tronics">';
                    $content .= '<div class="card-body tronics-wrap">';
                        $content .= '<div class="text-center">';
                            if(!empty($product->photo)){
                            $content .= '<img src="'.url($product->photo->photo).'" class="img-fluid product-photo">';
                            }
                            $content .= '<h3 class="product-name">'.$product->model.'</h3>';
                            $content .= '<div class="tronics-links">';
                                $content .= '<a href="javascript:void(0)" onclick="getoffer('.$product->id.')" class="btn btn-warning btn-sm">Get an Offer</a>';
                            $content .= '</div>';
                        $content .= '</div>';
                    $content .= '</div>';
                $content .= '</div>';
            $content .= '</div>';
        }
        $data['content'] = $content;
        return response()->json($data);
    }

    public function filterByStorageCondition (Request $request) 
    {
        // return $request->all();
        $id = app('App\Http\Controllers\GlobalFunctionController')->decodeHashid($request['id']);
        // $network = $request['network'];
        $device_type = $request['device_type'];
        $result = $this->productRepo->findWith($id, ['photo', 'networks.network']);
        $storagelist = '';
        // $data['productStorage'] = $this->productStorageRepo->rawByFieldAll("product_id = ? and excellent_offer != ''", [$id]);
        
        if (isset($request['storage']) && $request['storage'] != null) {
            $getProductStorage = $this->productStorageRepo->rawByField("product_id = ? and title = ?", [$id, $request['storage']]);
        } else {
            $getProductStorage = $data['productStorage'][0];
        }
        // $product = $this->productRepo->rawByField("brand_id = ? and network = ? and storage = ? and model = ?", [$brand->id, $request['network'], $request['storage'], $request['model']]);
        // return $result;
        $data['selectedProduct'] = $result;
        // foreach ($data['productStorage'] as $psKey => $psVal) {
        //     $active = ($psKey == 0) ? ' active' : '';
        //     $checked = ($psKey == 0) ? ' checked' : '';
        //     $storagelist .= '<label class="btn btn-outline-warning radio-btn'.$active.'" style="margin-right: 4px;">';
        //     $storagelist .= '<input type="radio" class="device-storage" name="storage" value="'.$psVal->title.'" onchange="storage(\''.$psVal->title.'\')" autocomplete="off"'.$checked.'> '.$psVal->title;
        //     $storagelist .= '</label>';
        // }
        $data['storagelist'] = $storagelist;
        $condition = '';
        $amount = '';
        if($device_type == 1){
            $amount = number_format($getProductStorage['excellent_offer'], 2, '.', ',');
            $condition .= '<div class="card-body" style="font-size: 14px;">';
            $condition .= 'If ALL of the following are true:';
            $condition .= '<ul>';
            $condition .= '<li>Fully functional</li>';
            $condition .= '<li>Appears to be brand new</li>';
            $condition .= '<li>No scratches, scuffs or marks</li>';
            $condition .= '<li>Phone has a good ESN /IMEI</li>';
            $condition .= '</ul>';
            $condition .= '</div>';
        }
        if($device_type == 2){
            $amount = number_format($getProductStorage['good_offer'], 2, '.', ',');
            $condition .= '<div class="card-body" style="font-size: 14px;">';
            $condition .= 'If ALL of the following are true:';
            $condition .= '<ul>';
            $condition .= '<li>Fully functional</li>';
            $condition .= '<li>No major scratches, scuffs or nicks</li>';
            $condition .= '<li>No cracks or broken hardware</li>';
            $condition .= '<li>Phone has a good ESN / IMEI</li>';
            $condition .= '</ul>';
            $condition .= '</div>';
        }
        if($device_type == 3){
            $amount = number_format($getProductStorage['fair_offer'], 2, '.', ',');
            $condition .= '<div class="card-body" style="font-size: 14px;">';
            $condition .= 'If ANY of the following are true:';
            $condition .= '<ul>';
            $condition .= '<li>Cracked back</li>';
            $condition .= '<li>Defective buttons</li>';
            $condition .= '<li>Significant wear and tear</li>';
            $condition .= '<li>Housing damage</li>';
            $condition .= '</ul>';
            $condition .= '</div>';
        }
        if($device_type == 4){
            $amount = number_format($getProductStorage['poor_offer'], 2, '.', ',');
            $condition .= '<div class="card-body" style="font-size: 14px;">';
            $condition .= 'If ANY of the following are true:';
            $condition .= '<ul>';
            $condition .= '<li>Does NOT power on</li>';
            $condition .= '<li>Damaged LCD</li>';
            $condition .= '<li>Missing parts or bent frame</li>';
            $condition .= '<li>Any Password lock</li>';
            $condition .= '</ul>';
            $condition .= '</div>';
        }
        $data['condition'] = $condition;
        $data['amount'] = $amount;
        return $data;
        return $request->all();
    }

    public function model(Request $request)
    {
        $id = app('App\Http\Controllers\GlobalFunctionController')->decodeHashid($request['id']);
        $device_type = $request['device_type'];
        $result = $this->productRepo->findWith($id, ['photo', 'networks.network']);
        $storagelist = '';
        $data['productStorage'] = $this->productStorageRepo->rawByFieldAll("product_id = ? and excellent_offer != ''", [$id]);
        
        if (isset($request['storage']) && $request['storage'] != null) {
            $getProductStorage = $this->productStorageRepo->rawByField("product_id = ? and title = ?", [$id, $request['storage']]);
        } else {
            $getProductStorage = $data['productStorage'][0];
        }
        $data['selectedProduct'] = $result;
        foreach ($data['productStorage'] as $psKey => $psVal) {
            $active = ($psKey == 0) ? ' active' : '';
            $checked = ($psKey == 0) ? ' checked' : '';
            $storagelist .= '<label class="btn btn-outline-warning radio-btn'.$active.'" style="margin-right: 4px;">';
            $storagelist .= '<input type="radio" class="device-storage" name="storage" value="'.$psVal->title.'" onchange="storage(\''.$psVal->title.'\')" autocomplete="off"'.$checked.'> '.$psVal->title;
            $storagelist .= '</label>';
        }
        $data['storagelist'] = $storagelist;
        $condition = '';
        $amount = '';
        if($device_type == 1){
            $amount = number_format($getProductStorage['excellent_offer'], 2, '.', ',');
            $condition .= '<div class="card-body" style="font-size: 14px;">';
            $condition .= 'If ALL of the following are true:';
            $condition .= '<ul>';
            $condition .= '<li>Fully functional</li>';
            $condition .= '<li>Appears to be brand new</li>';
            $condition .= '<li>No scratches, scuffs or marks</li>';
            $condition .= '<li>Phone has a good ESN /IMEI</li>';
            $condition .= '</ul>';
            $condition .= '</div>';
        }
        if($device_type == 2){
            $amount = number_format($getProductStorage['good_offer'], 2, '.', ',');
            $condition .= '<div class="card-body" style="font-size: 14px;">';
            $condition .= 'If ALL of the following are true:';
            $condition .= '<ul>';
            $condition .= '<li>Fully functional</li>';
            $condition .= '<li>No major scratches, scuffs or nicks</li>';
            $condition .= '<li>No cracks or broken hardware</li>';
            $condition .= '<li>Phone has a good ESN / IMEI</li>';
            $condition .= '</ul>';
            $condition .= '</div>';
        }
        if($device_type == 3){
            $amount = number_format($getProductStorage['fair_offer'], 2, '.', ',');
            $condition .= '<div class="card-body" style="font-size: 14px;">';
            $condition .= 'If ANY of the following are true:';
            $condition .= '<ul>';
            $condition .= '<li>Cracked back</li>';
            $condition .= '<li>Defective buttons</li>';
            $condition .= '<li>Significant wear and tear</li>';
            $condition .= '<li>Housing damage</li>';
            $condition .= '</ul>';
            $condition .= '</div>';
        }
        if($device_type == 4){
            $amount = number_format($getProductStorage['poor_offer'], 2, '.', ',');
            $condition .= '<div class="card-body" style="font-size: 14px;">';
            $condition .= 'If ANY of the following are true:';
            $condition .= '<ul>';
            $condition .= '<li>Does NOT power on</li>';
            $condition .= '<li>Damaged LCD</li>';
            $condition .= '<li>Missing parts or bent frame</li>';
            $condition .= '<li>Any Password lock</li>';
            $condition .= '</ul>';
            $condition .= '</div>';
        }
        $data['condition'] = $condition;
        $data['amount'] = $amount;

        // $data['carrier'] = '';
        // if ($result && Count($result['networks']) >= 1) 
        // {
        //     $data['carrier'] .= '<div class="row">';
        //     foreach ($result['networks'] as $nKey => $nVal) 
        //     {
        //         $networkImageUrl = url('/').'&#47;assets&#47;images&#47;network&#47;'.$nVal['network']['id'].'.png';
        //         $data['carrier'] .= '<div class="col-md-3><img src="'.$networkImageUrl.'" class="img-fluid"></div>';
        //     }
        //     $data['carrier'] .= '</div>';
        // }

        return $data;




        return $products = $this->productRepo->rawAll("brand_id = ? and (device_type = ? OR device_type = ?)", [$result->brand_id, 'Buy', 'Both']);
        
        foreach ($products as $key => $product) {
            $active = ($key == 0) ? ' active' : '';
            $checked = ($key == 0) ? ' checked' : '';
            $storagelist .= '<label class="btn btn-outline-warning radio-btn'.$active.'" style="margin-right: 4px;">';
            $storagelist .= '<input type="radio" class="device-storage" name="storage" value="'.$product->storage.'" onchange="storage(\''.$product->storage.'\')" autocomplete="off"'.$checked.'> '.$product->storage;
            $storagelist .= '</label>';
        }
        $storage = isset($request['storage']) ? $request['storage'] : $products[0]->storage;
        // return $request->all();
        $data['product'] = $product = $this->productRepo->rawByWithField(['photo'], "brand_id = ? and storage = ? and (device_type = ? OR device_type = ?)", [$result->brand_id, $storage, 'Buy', 'Both']);
        $condition = '';
        if($device_type == 1){
            $amount = $data['product']->excellent_offer;
            $condition .= '<div class="card-body" style="font-size: 14px;">';
            $condition .= 'If ALL of the following are true:';
            $condition .= '<ul>';
            $condition .= '<li>Fully functional</li>';
            $condition .= '<li>Appears to be brand new</li>';
            $condition .= '<li>No scratches, scuffs or marks</li>';
            $condition .= '<li>Phone has a good ESN /IMEI</li>';
            $condition .= '</ul>';
            $condition .= '</div>';
        }
        if($device_type == 2){
            $amount = $data['product']->good_offer;
            $condition .= '<div class="card-body" style="font-size: 14px;">';
            $condition .= 'If ALL of the following are true:';
            $condition .= '<ul>';
            $condition .= '<li>Fully functional</li>';
            $condition .= '<li>No major scratches, scuffs or nicks</li>';
            $condition .= '<li>No cracks or broken hardware</li>';
            $condition .= '<li>Phone has a good ESN / IMEI</li>';
            $condition .= '</ul>';
            $condition .= '</div>';
        }
        if($device_type == 3){
            $amount = $data['product']->fair_offer;
            $condition .= '<div class="card-body" style="font-size: 14px;">';
            $condition .= 'If ANY of the following are true:';
            $condition .= '<ul>';
            $condition .= '<li>Cracked back</li>';
            $condition .= '<li>Defective buttons</li>';
            $condition .= '<li>Significant wear and tear</li>';
            $condition .= '<li>Housing damage</li>';
            $condition .= '</ul>';
            $condition .= '</div>';
        }
        if($device_type == 4){
            $amount = $data['product']->poor_offer;
            $condition .= '<div class="card-body" style="font-size: 14px;">';
            $condition .= 'If ANY of the following are true:';
            $condition .= '<ul>';
            $condition .= '<li>Does NOT power on</li>';
            $condition .= '<li>Damaged LCD</li>';
            $condition .= '<li>Missing parts or bent frame</li>';
            $condition .= '<li>Any Password lock</li>';
            $condition .= '</ul>';
            $condition .= '</div>';
        }
        $data['storagelist'] = $storagelist;
        $data['condition'] = $condition;
        $data['amount'] = $amount;
        $data['photo'] = url($result->photo->photo);
        // return $request->all();
        return response()->json($data);
    }

    // public function store(SellRequest $request)
    public function store(Request $request)
    {
        $response['status'] = 200;
        if ($request['fname'] == null) {
            $response['status'] = 400;
            $response['message'] = "First name field is required";
        } else if ($request['lname'] == null) {
            $response['status'] = 400;
            $response['message'] = "Last name field is required";
        } else if ($request['address1'] == null) {
            $response['status'] = 400;
            $response['message'] = "Address field is required";
        } else if ($request['state_id'] == null) {
            $response['status'] = 400;
            $response['message'] = "State field is required";
        } else if ($request['payment_method'] == null) {
            $response['status'] = 400;
            $response['message'] = "Payment method field is required";
        } else if (!filter_var($request['email'], FILTER_VALIDATE_EMAIL)) {
            $response['status'] = 400;
            $response['message'] = "Invalid Email Address Format";
        } else if ($request['account_username'] != null && !filter_var($request['account_username'], FILTER_VALIDATE_EMAIL)) {
            $response['status'] = 400;
            $response['message'] = "Invalid Payment Email Address format";
        }

        if ($request['payment_method'] == "Bank Transfer") 
        {
            if (!$request['bank']) {
                $response['status'] = 400;
                $response['message'] = "Bank is required";
            } else if (!$request['account_name']) {
                $response['status'] = 400;
                $response['message'] = "Account name is required";
            } else if (!$request['account_number']) {
                $response['status'] = 400;
                $response['message'] = "Account number is required";
            }
        }

        if ($response['status'] == 200) 
        {
            $chkcustomer = $this->customerRepo->findByField('email', $request['email']);
            $config = $this->configRepo->find(1);
            if (!$chkcustomer) 
            {
                $password = Str::random(10);
                $customerRequest = [
                    'fname' => $request['fname'],
                    'lname' => $request['lname'],
                    'email' => $request['email'],
                    'password' => bcrypt($password),
                    'payment_method' => $request['payment_method'],
                    'account_username' => $request['account_username'],
                    'bank' => $request['bank'],
                    'account_name' => $request['account_name'],
                    'account_number' => $request['account_number']
                ];
                $customer = $this->customerRepo->create($customerRequest);
                $addressRequest = [
                    'customer_id' => $customer->id,
                    'address1' => $request['address1'],
                    'address2' => $request['address2'],
                    'city' => $request['city'],
                    'state' => $request['state_id'],
                    'zip' => $request['zip_code'],
                    'phone' => $request['phone']
                ];
                $address = $this->addressRepo->create($addressRequest);
            } else {
                $password = '';
                $address = $this->addressRepo->findByField('customer_id', $chkcustomer['id']);
            }
            
            if ($request['cart'] != null) 
            {
                $makeOrderRequest = [
                    'customer_id' => isset($chkcustomer->id) ? $chkcustomer->id : $customer->id,
                    'order_no' => strtoupper(app('App\Http\Controllers\GlobalFunctionController')->generateUUID()),
                    'status_id' => 1, 
                    'transaction_date' => date("Y-m-d h:i:s"),
                    'delivery_due' => date("Y-m-d", strtotime("+30 day")),
                    'payment_method' => $request['payment_method'],
                    'account_username' => $request['account_username'],
                    'account_name' => $request['account_name'],
                    'account_number' => $request['account_number'],
                    'account_bank' => $request['bank'],
                    'shipping_label' => 'For Approval',
                    'shipping_fee' => '10',
                    'tracking_code' => '1029282812891',
                    'delivery_days' => '5', 
                ];
                $order = $this->orderRepo->create($makeOrderRequest);

                foreach ($request['cart'] as $key => $value) 
                {
                    $brand = $this->brandRepo->findByField('name', $value['brand']);
                    // $product = $this->productRepo->rawByField("brand_id = ? and network = ? and storage = ? and model = ?", [$brand->id, $value['network'], $value['storage'], $value['model']]);
                    $product = $this->productRepo->rawByField("brand_id = ? and model = ?", [$brand->id, $value['model']]);
                    
                    $parcel = $this->parcel($product);

                    $shipment = $this->shipping($address, $parcel);
                    // // $shipment->buy($shipment->lowest_rate());
                    // // $shipment->buy($shipment->lowest_rate(array('USPS'), array('First')));
                    // return $shipment;
                    
                    
                    // $shipment->insure(array('amount' => 100));
                    
                    // $shipment->postage_label->label_url;
                    // return $shipment;
                    $ship = $shipment->buy($shipment->lowest_rate());
            
                    $makeRequest = [
                        'customer_id' => isset($chkcustomer->id) ? $chkcustomer->id : $customer->id,
                        'product_id' => $product->id,
                        'amount' => $value['amount'],
                        'payment_method' => $value['payment_method'],
                        'account_username' => $value['account_username'],
                        'account_bank' => $value['bank'],
                        'account_name' => $value['account_name'],
                        'account_number' => $value['account_number'],
                        'shipping_label' => $ship->postage_label->label_url,
                        'shipping_fee' => $ship->rates[0]->retail_rate,
                        'tracking_code' => $ship->tracking_code,
                        'carrier' => $ship->rates[0]->carrier,
                        'delivery_days' => $ship->rates[0]->delivery_days
                    ];
                    return $makeRequest;
                    $productStorage = $this->productStorageRepo->rawByField("product_id = ? and title = ?", [$product->id, $value['storage']]);


                    $makeRequest = [
                        'customer_id' => isset($chkcustomer->id) ? $chkcustomer->id : $customer->id,
                        'product_id' => $product->id,
                        'network_id' => $value['carrier_id'],
                        'order_id' => $order->id,
                        'product_storage_id' => $productStorage['id'], 
                        'amount' => $value['amount'],
                        'quantity' => $value['quantity'], 
                        'device_type' => $value['device_type']
                    ];
                    
                    $this->orderItemRepo->create($makeRequest);
                    // $this->deviceRepo->create($makeRequest);
                }
            }
            $email = $request['email'];
            $subject = "TronicsPay Email Confirmation";
            $data['header'] = "TronicsPay Email Confirmation";
            $data['fname'] = $request['fname'];
            $data['email'] = $request['email'];
            $data['password'] = $password;
            $data['company_email'] = $config->company_email;
            $data['model'] = $product->model;
            $content = view('mail.customer', $data)->render();
            Mailer::sendEmail($email, $subject, $content);
            $result = [
                'model' => $product->model,
                'fname' => $request['fname']
            ];
    
            $htmlResult = '<div class="container">
                                <div class="row">
                                    <div class="form-group col-md-12" align="center">
                                        <img src="'.url("./assets/images/logo.png").'" class="img-fluid">
                                    </div>
                                </div>
                                <br />
                                <br />
                                <div class="row">
                                    <div class="col-md-12">
                                    <div class="text-center">
                                        <h3>Thank you '.$result['fname'].' for selling '.$result['model'].'!</h3>
                                        <p>
                                        We are currently reviewing the device, we send a confirmation email.<br>
                                        Please check your email and login at <a href="../customer/auth/login">Member Login</a>.
                                        </p>
                                    </div>
                                    </div>
                                </div>
                            </div>';
    
            $response['status'] = 200;
            $response['message'] = $htmlResult;
        }
        return $response;
        // $chkcustomer = $this->customerRepo->findByField('email', $request['email']);
        // $config = $this->configRepo->find(1);

        /**
         *  start: Customer
         */
        
        // if(empty($chkcustomer)){
        //     $password = Str::random(10);
        //     $customerRequest = [
        //         'fname' => $request['fname'],
        //         'lname' => $request['lname'],
        //         'email' => $request['email'],
        //         'password' => bcrypt($password),
        //         'payment_method' => $request['payment_method'],
        //         'account_username' => $request['account_username'],
        //         'bank' => $request['bank'],
        //         'account_name' => $request['account_name'],
        //         'account_number' => $request['account_number']
        //     ];
        //     $customer = $this->customerRepo->create($customerRequest);
        //     $addressRequest = [
        //         'customer_id' => $customer->id,
        //         'address1' => $request['address1'],
        //         'address2' => $request['address2'],
        //         'city' => $request['city'],
        //         'state' => $request['state_id'],
        //         'zip' => $request['zip_code'],
        //         'phone' => $request['phone']
        //     ];
        //     $address = $this->addressRepo->create($addressRequest);
        // }

        // if ($request['cart'] != null) 
        // {
        //     foreach ($request['cart'] as $key => $value) 
        //     {
        //         $brand = $this->brandRepo->findByField('name', $value['brand']);
        //         $product = $this->productRepo->rawByField("brand_id = ? and network = ? and storage = ? and model = ?", [$brand->id, $value['network'], $value['storage'], $value['model']]);
                
        //         $parcel = $this->parcel($product);
        //         $shipment = $this->shipping($address, $parcel);
        //         // $ship = $shipment->buy($shipment->lowest_rate());
        
        //         // $makeRequest = [
        //         //     'customer_id' => isset($chkcustomer->id) ? $chkcustomer->id : $customer->id,
        //         //     'product_id' => $product->id,
        //         //     'amount' => $value['amount'],
        //         //     'payment_method' => $value['payment_method'],
        //         //     'account_username' => $value['account_username'],
        //         //     'account_bank' => $value['bank'],
        //         //     'account_name' => $value['account_name'],
        //         //     'account_number' => $value['account_number'],
        //         //     'shipping_label' => $ship->postage_label->label_url,
        //         //     'shipping_fee' => $ship->rates[0]->retail_rate,
        //         //     'tracking_code' => $ship->tracking_code,
        //         //     'carrier' => $ship->rates[0]->carrier,
        //         //     'delivery_days' => $ship->rates[0]->delivery_days
        //         // ];
        //         $makeRequest = [
        //             'customer_id' => isset($chkcustomer->id) ? $chkcustomer->id : $customer->id,
        //             'product_id' => $product->id,
        //             'amount' => $value['amount'],
        //             'payment_method' => $request['payment_method'],
        //             'account_username' => $request['account_username'],
        //             'account_bank' => $request['bank'],
        //             'account_name' => $request['account_name'],
        //             'account_number' => $request['account_number'],
        //             'shipping_label' => 'For Approval',
        //             'shipping_fee' => '10',
        //             'tracking_code' => '1029282812891',
        //             'carrier' => 'USPS',
        //             'delivery_days' => '5'
        //         ];
        
        //     }
        // }
        // $this->deviceRepo->create($makeRequest);
        // $email = $request['email'];
        // $subject = "TronicsPay Email Confirmation";
        // $data['header'] = "TronicsPay Email Confirmation";
        // $data['fname'] = $request['fname'];
        // $data['email'] = $request['email'];
        // $data['password'] = $password;
        // $data['company_email'] = $config->company_email;
        // $data['model'] = $product->model;
        // $content = view('mail.customer', $data)->render();
        // // Mailer::sendEmail($email, $subject, $content);
        // $result = [
        //     'model' => $product->model,
        //     'fname' => $request['fname']
        // ];

        // $htmlResult = '<div class="pt-70">
        //                     <div class="container pt-70">
        //                         <div class="row">
        //                             <div class="col-md-12">
        //                             <div class="text-center">
        //                                 <h3>Thank you '.$result['fname'].' for selling '.$result['model'].'!</h3>
        //                                 <p>
        //                                 We are currently reviewing the device, we send a confirmation email.<br>
        //                                 Please check your email and login at <a href="./customer/auth/login">Member Login</a>.
        //                                 </p>
        //                             </div>
        //                             </div>
        //                         </div>
        //                     </div>
        //                 </div>';

        // $response['status'] = 200;
        // $response['message'] = $htmlResult;

        return $response;

        /**
         * star: Old Codes
         */
        return redirect()->to('device')->with('result', json_encode($result));

        return $request->cart;

        // $chkcustomer = $this->customerRepo->findByField('email', $request['email']);
        // $brand = $this->brandRepo->findByField('name', $request['brand']);
        // $config = $this->configRepo->find(1);
        // $product = $this->productRepo->rawByField("brand_id = ? and network = ? and storage = ? and model = ?", [$brand->id, $request['network'], $request['storage'], $request['model']]);
        
        // if(empty($chkcustomer)){
        //     $password = Str::random(10);
        //     $customerRequest = [
        //         'fname' => $request['fname'],
        //         'lname' => $request['lname'],
        //         'email' => $request['email'],
        //         'password' => bcrypt($password),
        //         'payment_method' => $request['payment_method'],
        //         'account_username' => $request['account_username'],
        //         'bank' => $request['bank'],
        //         'account_name' => $request['account_name'],
        //         'account_number' => $request['account_number']
        //     ];
        //     $customer = $this->customerRepo->create($customerRequest);
        //     $addressRequest = [
        //         'customer_id' => $customer->id,
        //         'address1' => $request['address1'],
        //         'address2' => $request['address2'],
        //         'city' => $request['city'],
        //         'state' => $request['state_id'],
        //         'zip' => $request['zip_code'],
        //         'phone' => $request['phone']
        //     ];
        //     $address = $this->addressRepo->create($addressRequest);
        // }

        // $parcel = $this->parcel($product);
        // $shipment = $this->shipping($address, $parcel);
        // // $ship = $shipment->buy($shipment->lowest_rate());

        // // $makeRequest = [
        // //     'customer_id' => isset($chkcustomer->id) ? $chkcustomer->id : $customer->id,
        // //     'product_id' => $product->id,
        // //     'amount' => $request['amount'],
        // //     'payment_method' => $request['payment_method'],
        // //     'account_username' => $request['account_username'],
        // //     'account_bank' => $request['bank'],
        // //     'account_name' => $request['account_name'],
        // //     'account_number' => $request['account_number'],
        // //     'shipping_label' => $ship->postage_label->label_url,
        // //     'shipping_fee' => $ship->rates[0]->retail_rate,
        // //     'tracking_code' => $ship->tracking_code,
        // //     'carrier' => $ship->rates[0]->carrier,
        // //     'delivery_days' => $ship->rates[0]->delivery_days
        // // ];
        // $makeRequest = [
        //     'customer_id' => isset($chkcustomer->id) ? $chkcustomer->id : $customer->id,
        //     'product_id' => $product->id,
        //     'amount' => $request['amount'],
        //     'payment_method' => $request['payment_method'],
        //     'account_username' => $request['account_username'],
        //     'account_bank' => $request['bank'],
        //     'account_name' => $request['account_name'],
        //     'account_number' => $request['account_number'],
        //     'shipping_label' => 'For Approval',
        //     'shipping_fee' => '10',
        //     'tracking_code' => '1029282812891',
        //     'carrier' => 'USPS',
        //     'delivery_days' => '5'
        // ];

        // $this->deviceRepo->create($makeRequest);
        // $email = $request['email'];
        // $subject = "TronicsPay Email Confirmation";
        // $data['header'] = "TronicsPay Email Confirmation";
        // $data['fname'] = $request['fname'];
        // $data['email'] = $request['email'];
        // $data['password'] = $password;
        // $data['company_email'] = $config->company_email;
        // $data['model'] = $product->model;
        // $content = view('mail.customer', $data)->render();
        // Mailer::sendEmail($email, $subject, $content);
        // $result = [
        //     'model' => $product->model,
        //     'fname' => $request['fname']
        // ];
        // return redirect()->to('device')->with('result', json_encode($result));
    }

    private function shipping($address, $parcel)
    {
        EasyPost::setApiKey(config('account.easypost_apikey'));
        $config = $this->configRepo->find(1);
        $to_address = Address::create([
            'company' => $config->company_name,
            'street1' => $config->address1,
            'street2' => $config->address2,
            'city'    => $config->city,
            'state'   => $config->state,
            'zip'     => $config->zip_code,
            'phone'   => $config->phone,
        ]);

        $from_address = Address::create([
            'company' => '',
            'street1' => $address->address1,
            'street2' => $address->address2,
            'city'    => $address->city,
            'state'   => $address->state,
            'zip'     => $address->zip,
            'phone'   => $address->phone
        ]);

        $shipment = Shipment::create([
            'to_address'   => $to_address,
            'from_address' => $from_address,
            'parcel'       => $parcel
        ]);

        return $shipment;
    }

    private function parcel($product)
    {
        EasyPost::setApiKey(config('account.easypost_apikey'));
        return Parcel::create([
            // 'predefined_package' => $this->package($product->height, $product->width),
            'predefined_package' => $this->package('1', '1'),
            'height' => '1', // $product->height,
            'weight' => '1', // $product->weight,
            'width'  => '1', // $product->width,
            'length' => '1', // $product->length,
        ]);
    }

    private function package($height, $width)
    {
        if($height <= 8 && $width <= 5){
            return 'SmallFlatRateBox';
        } elseif(($height > 8 && $width > 5) && ($height <= 11 && $width <= 8)){
            return 'MediumFlatRateBox';
        } else {
            return 'LargeFlatRateBox';
        }
    }
}
