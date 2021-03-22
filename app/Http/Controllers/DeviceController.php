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
use App\Repositories\Admin\BrandRepositoryEloquent as Brand;
use App\Repositories\Admin\ConfigRepositoryEloquent as Config;
use App\Repositories\Customer\StateRepositoryEloquent as State;
use App\Repositories\Admin\ProductRepositoryEloquent as Product;
use App\Repositories\Customer\DeviceRepositoryEloquent as Device;
use App\Repositories\Customer\CustomerRepositoryEloquent as Customer;
use App\Repositories\Customer\CustomerAddressRepositoryEloquent as CustomerAddress;

class DeviceController extends Controller
{
    protected $brandRepo;
    protected $productRepo;
    protected $stateRepo;
    protected $customerRepo;
    protected $addressRepo;
    protected $deviceRepo;
    protected $configRepo;

    function __construct(Brand $brandRepo, Product $productRepo, State $stateRepo, Customer $customerRepo, CustomerAddress $addressRepo, Device $deviceRepo, Config $configRepo)
    {
        $this->brandRepo = $brandRepo;
        $this->productRepo = $productRepo;
        $this->stateRepo = $stateRepo;
        $this->customerRepo = $customerRepo;
        $this->addressRepo = $addressRepo;
        $this->deviceRepo = $deviceRepo;
        $this->configRepo = $configRepo;
    }

    public function index()
    {
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
        $result = $this->brandRepo->findByField('name', $brand);
        $data['chkproduct'] = $this->productRepo->rawCount("brand_id = ?", [$result->id]);
        $data['networks'] =  $this->productRepo->queryTable()->whereRaw("brand_id = ?", [$result->id])->groupBy('network')->get();
        return view('front.device.checkout', $data);
    }

    public function network(Request $request)
    {
        $brand = $this->brandRepo->findByField('name', $request['brand']);
        $products = $this->productRepo->rawWithGroup(['photo'], "brand_id = ? and network = ?", [$brand->id, $request['network']], 'model'); 
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

    public function model(Request $request)
    {
        $id = $request['id'];
        $network = $request['network'];
        $device_type = $request['device_type'];
        $result = $this->productRepo->findWith($id, ['photo']);
        $storagelist = '';
        $products = $this->productRepo->rawAll("brand_id = ? and network = ?", [$result->brand_id, $network]);
        foreach ($products as $key => $product) {
            $active = ($key == 0) ? ' active' : '';
            $checked = ($key == 0) ? ' checked' : '';
            $storagelist .= '<label class="btn btn-outline-warning radio-btn'.$active.'" style="margin-right: 4px;">';
            $storagelist .= '<input type="radio" class="device-storage" name="storage" value="'.$product->storage.'" onchange="storage(\''.$product->storage.'\')" autocomplete="off"'.$checked.'> '.$product->storage;
            $storagelist .= '</label>';
        }
        $storage = isset($request['storage']) ? $request['storage'] : $products[0]->storage;
        $data['product'] = $product = $this->productRepo->rawByWithField(['photo'], "brand_id = ? and storage = ? and network = ?", [$result->brand_id, $storage, $network]);
        $condition = '';
        if($device_type == 1){
            $amount = number_format($product->excellent_offer,2);
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
            $amount = number_format($product->good_offer,2);
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
            $amount = number_format($product->fair_offer,2);
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
            $amount = number_format($product->poor_offer,2);
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
        return response()->json($data);
    }

    public function store(SellRequest $request)
    {
        $chkcustomer = $this->customerRepo->findByField('email', $request['email']);
        $brand = $this->brandRepo->findByField('name', $request['brand']);
        $config = $this->configRepo->find(1);
        $product = $this->productRepo->rawByField("brand_id = ? and network = ? and storage = ? and model = ?", [$brand->id, $request['network'], $request['storage'], $request['model']]);
        if(empty($chkcustomer)){
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
        }

        $parcel = $this->parcel($product);
        $shipment = $this->shipping($address, $parcel);
        $ship = $shipment->buy($shipment->lowest_rate());

        $makeRequest = [
            'customer_id' => isset($chkcustomer->id) ? $chkcustomer->id : $customer->id,
            'product_id' => $product->id,
            'amount' => $request['amount'],
            'payment_method' => $request['payment_method'],
            'account_username' => $request['account_username'],
            'account_bank' => $request['bank'],
            'account_name' => $request['account_name'],
            'account_number' => $request['account_number'],
            'shipping_label' => $ship->postage_label->label_url,
            'shipping_fee' => $ship->rates[0]->retail_rate,
            'tracking_code' => $ship->tracking_code,
            'carrier' => $ship->rates[0]->carrier,
            'delivery_days' => $ship->rates[0]->delivery_days
        ];

        $this->deviceRepo->create($makeRequest);
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
        return redirect()->to('device')->with('result', json_encode($result));
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
            'phone'   => $config->phone
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
            'predefined_package' => $this->package($product->height, $product->width),
            'height' => $product->height,
            'weight' => $product->weight,
            'width'  => $product->width,
            'length' => $product->length,
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
