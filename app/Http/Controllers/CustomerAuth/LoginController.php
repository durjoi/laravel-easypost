<?php

namespace App\Http\Controllers\CustomerAuth;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Providers\RouteServiceProvider;
use Illuminate\Validation\ValidationException;
use Illuminate\Foundation\Auth\RedirectsUsers;
use Illuminate\Foundation\Auth\ThrottlesLogins;
use Illuminate\Foundation\Auth\AuthenticatesUsers;

class LoginController extends Controller
{
    use RedirectsUsers, ThrottlesLogins;

    protected $redirectTo = '/customer/dashboard';

    public function __construct()
    {
        $this->middleware('guest:customer')->except('logout');
    }

    public function showLoginForm()
    {
        return view('customer.login');
    }

    public function login(Request $request)
    {
        $this->validateLogin($request);

        // If the class is using the ThrottlesLogins trait, we can automatically throttle
        // the login attempts for this application. We'll key this by the username and
        // the IP address of the client making these requests into this application.
        if (method_exists($this, 'hasTooManyLoginAttempts') &&
            $this->hasTooManyLoginAttempts($request)) {
            $this->fireLockoutEvent($request);

            return $this->sendLockoutResponse($request);
        }

        if ($this->attemptLogin($request)) {
            return $this->sendLoginResponse($request);
        }

        // If the login attempt was unsuccessful we will increment the number of attempts
        // to login and redirect the user back to the login form. Of course, when this
        // user surpasses their maximum number of attempts they will get locked out.
        $this->incrementLoginAttempts($request);

        return $this->sendFailedLoginResponse($request);
    }

    protected function validateLogin(Request $request)
    {
        $request->validate([
            $this->username() => 'required|string',
            'password' => 'required|string',
        ]);
    }

    protected function attemptLogin(Request $request)
    {
        return $this->guard()->attempt(
            $this->credentials($request), $request->filled('remember')
        );
    }

    protected function credentials(Request $request)
    {
        return $request->only($this->username(), 'password');
    }

    protected function sendLoginResponse(Request $request)
    {
        $request->session()->regenerate();
        $this->clearLoginAttempts($request);
        if ($response = $this->authenticated($request, $this->guard()->user())) {
            return $response;
        }
        if(Auth::guard('customer')->check()){
            if($request['cart']){
                return redirect()->back();
            }
            return redirect()->to($this->redirectTo);
        }
    }

    protected function authenticated(Request $request, $user)
    {
        
    }

    protected function sendFailedLoginResponse(Request $request)
    {
        throw ValidationException::withMessages([
            $this->username() => [trans('auth.failed')],
        ]);
    }

    public function username()
    {
        return 'email';
    }

    protected function guard()
    {
        return Auth::guard('customer');
    }

    public function logout(Request $request)
    {
        $this->guard()->logout();

        $request->session()->invalidate();

        return redirect('/customer/auth/login');
    }
    
}
