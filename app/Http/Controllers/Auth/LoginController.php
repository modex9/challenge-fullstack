<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use App\User;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = RouteServiceProvider::HOME;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    public function logout(Request $request)
    {
        $this->guard()->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        if ($response = $this->loggedOut($request)) {
            return $response;
        }
        //Send new csrf, as page will not be refreshed automatically.
        return response()->json(array('success' => true, 'csrf' => csrf_token()), 200);
    }

    protected function sendLoginResponse(Request $request)
    {
        $request->session()->regenerate();

        $this->clearLoginAttempts($request);

        if ($response = $this->authenticated($request, $this->guard()->user())) {
            return $response;
        }

        return response()->json(array(
            'success' => true,
            'user' => $this->guard()->user(),
            'csrf' => csrf_token(),
            ), 200);
    }

    protected function validator(array $data)
    {
        return Validator::make($data, [
            $this->username() => 'required|string',
            'password' => 'required|string',
        ]);
    }


    protected function validateLogin(Request $request)
    {
        $validator = $this->validator($request->all());
        if($validator->fails()) {
            return response()->json(array(
                'success' => false,
                'errors' => $validator->getMessageBag()->toArray()

            )); 
        }
    }

    protected function sendFailedLoginResponse(Request $request)
    {
        return response()->json(array(
            'success' => false,
            'errors' => [$this->username() => [trans('auth.failed')]]

        )); 
    }

    public function redirectToProvider($provider)
    {
        try {
            return Socialite::driver($provider)->redirect();
        } catch(\Throwable $e) {
            return response()->json(array(
                'success' => false,
                'errors' => $e->getMessage()
            ));
        }
        
    }

    public function handleProviderCallback($provider)
    {
        $provider_user = Socialite::driver($provider)->user();
        if($provider_user->email) {
            $user = User::firstOrCreate([
                'email' => $provider_user->email
            ], [
                'name' => $provider_user->name ?? $provider_user->nickname,
                'password' => Hash::make(Str::random(16)),
            ]);
            Auth::login($user, true);
        }
        return redirect('/');
    }
}
