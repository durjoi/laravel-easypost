<?php

namespace App\Http\Controllers\CustomerAuth;

use App\Models\Customer;
use App\Models\TableList;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Providers\RouteServiceProvider;
use Saperemarketing\SCart\Facades\Cart;
use App\Models\Customer\CustomerAddress;
use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\Auth\RegistersUsers;
use App\Http\Requests\Customer\CreateCustomerRequest;
use App\Repositories\Customer\StateRepositoryEloquent as State;
use App\Repositories\Customer\CustomerRepositoryEloquent as CustomerRepo;
use Illuminate\Support\Facades\Http;

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
        $recaptcha = $this->tablelist->recaptcha_test_local;
        $Response = file_get_contents("https://www.google.com/recaptcha/api/siteverify?secret=".$recaptcha['secret_key']."&response={$SecretKey}");
        $Return = json_decode($Response);
        return $Return;
    }


    protected function create(CreateCustomerRequest $request)
    {
        $response = Http::asForm()->post("https://www.google.com/recaptcha/api/siteverify",[
            "secret"    => env("RECAPTCHA_SECRET_KEY"),
            "response"  => $request->get('g-recaptcha-response'),
        ]);
        $response = json_decode($response->body());
        if($response->success)
        {
            if ($this->CheckExistingEmail($request['email']) == false) 
            {
                return redirect('customer/auth/register')->with('error', 'Email Address has been already used!');
            }
            $password = Str::random(10);
            $customer = Customer::create([
                'fname' => $request['first_name'],
                'lname' => $request['last_name'],
                'email' => $request['email'],
                'username' => '',
                'password' => Hash::make($request->get('password')),
                'authpw' => "",
                'verification_code' => app('App\Http\Controllers\GlobalFunctionController')->verificationCode(), 
                'status' => 'inactive'
            ]);
    
            CustomerAddress::create([
                'customer_id' => $customer->id,
                'address1' => $request['address1'],
                'address2' => $request['address2'],
                'city' => $request['city'],
                'state' => $request['state'],
                'zip' => $request['zip'],
                'phone' => $request['phone']
            ]);
    
            Auth::guard('customer')->login($customer);
            if(!Auth::guard('customer')->check()){
              return redirect()->to('customer/auth/login');  
            }
            if($request['cart']){
                return redirect()->to('products/checkout');
            }
            return redirect()->to('customer/dashboard');
        }
        else
        {
            return redirect('customer/auth/register')->with('error', "I'm sorry, but there was an issue with your registration");
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
