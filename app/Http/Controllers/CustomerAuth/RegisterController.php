<?php

namespace App\Http\Controllers\CustomerAuth;

use App\Models\Customer;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Providers\RouteServiceProvider;
use Saperemarketing\SCart\Facades\Cart;
use App\Models\Customer\CustomerAddress;
use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\Auth\RegistersUsers;
use App\Models\TableList;
use App\Repositories\Customer\StateRepositoryEloquent as State;
use Illuminate\Support\Str;
use App\Repositories\Customer\CustomerRepositoryEloquent as CustomerRepo;

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    protected $redirectTo = RouteServiceProvider::HOME;
    protected $tablelist;
    protected $stateRepo;
    protected $customerRepo;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(TableList $tablelist, State $stateRepo, CustomerRepo $customerRepo)
    {
        $this->tablelist = $tablelist;
        $this->stateRepo = $stateRepo;
        $this->customerRepo = $customerRepo;
        $this->middleware('guest:customer');
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'fname' => ['required', 'string', 'max:255'],
            'lname' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'username' => ['required'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'street' => ['required'],
            'city' => ['required'],
            'state' => ['required'],
            'zip' => ['required'],
            'phone' => ['required']
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return \App\Models\User
     */

    private function getCaptcha($SecretKey)
    {
        $recaptcha = $this->tablelist->recaptcha_test_server;
        $Response = file_get_contents("https://www.google.com/recaptcha/api/siteverify?secret=".$recaptcha['secret_key']."&response={$SecretKey}");
        $Return = json_decode($Response);
        return $Return;
    }


    protected function create(Request $data)
    {
        
        $validateRecaptcha = $this->getCaptcha($data["g-recaptcha-response"]);
        if($validateRecaptcha->success == true && $validateRecaptcha->score > 0.5)
        {
            if ($this->CheckExistingEmail($data['email']) == false) 
            {
                return redirect('customer/auth/register')->with('error', 'Email Address has been already used!');
            }

            $password = Str::random(10);
            $customer = Customer::create([
                'fname' => $data['fname'],
                'lname' => $data['lname'],
                'email' => $data['email'],
                'username' => '',
                'password' => Hash::make($password),
                'authpw' => $password,
                'verification_code' => app('App\Http\Controllers\GlobalFunctionController')->verificationCode(), 
                'status' => 'In-Active'
            ]);
    
            CustomerAddress::create([
                'customer_id' => $customer->id,
                'address1' => $data['address1'],
                'address2' => $data['address2'],
                'city' => $data['city'],
                'state' => $data['state'],
                'zip' => $data['zip'],
                'phone' => $data['phone']
            ]);
    
            Auth::guard('customer')->login($customer);
            if(!Auth::guard('customer')->check()){
              return redirect()->to('customer/auth/login');  
            }
            if($data['cart']){
                return redirect()->to('products/checkout');
            }
            return redirect()->to('customer/dashboard');
        }
        else
        {
            return redirect('customer/auth/register')->with('error', 'Spammer Detected!');
        }
    }

    public function showRegistrationForm()
    {
        $data['recaptcha'] = $this->tablelist->recaptcha_test_server;
        $data['stateList'] = $this->stateRepo->selectlist('name', 'abbr');
        $data['cartcount'] = Cart::count();
        return view('customer.register', $data);
    }

    private function CheckExistingEmail ($email) 
    {
        $checker = $this->customerRepo->rawByWithField(['bill'], 'email = ?', [$email]);
        if ($checker) {
            return false;
        } else {
            return true;
        }
    }
}
