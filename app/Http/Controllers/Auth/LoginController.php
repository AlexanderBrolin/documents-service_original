<?php

namespace App\Http\Controllers\Auth;

use App\Exceptions\User\UserBlockedException;
use App\Http\Controllers\Controller;
use App\Models\User;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;

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

    use AuthenticatesUsers {
        AuthenticatesUsers::login as parentLogin;
    }

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = RouteServiceProvider::DOCUMENTS;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    public function login(Request $request)
    {
        $user = User::where('email', '=', $request->email)->get()->first();
        if ($user) {
            if ($user->is_blocked) {
                throw new UserBlockedException();
            }
        }

        return $this->parentLogin($request);
    }

    protected function sendLoginResponse(Request $request)
    {
        $this->setRememberMe($request);

        $request->session()->regenerate();


        $this->clearLoginAttempts($request);

        return $this->authenticated($this->guard()->user())
            ?: redirect()->intended($this->redirectPath());
    }

    protected function authenticated($user)
    {
        return response()->json([
            'success' => true,
            'user' => [
                'email' => $user->email
            ]
        ]);
    }

    public function logout(Request $request)
    {
        $this->guard()->user();

        $this->guard()->logout();

        $request->session()->regenerateToken();

        return response()->json(['success' => true, 'token' => csrf_token()]);
    }

    protected function setRememberMe(Request $request)
    {
        if ($request->filled('remember')) {
            $rememberTokenExpireMinutes = config('session.remember_me_lifetime');

            $rememberTokenName = $this->guard()->getRecallerName();

            $cookieJar = $this->guard()->getCookieJar();

            if ($rememberTokenCookie = $cookieJar->queued($rememberTokenName)) {
                $cookieValue = $rememberTokenCookie->getValue();

                $cookieJar->queue($rememberTokenName, $cookieValue, $rememberTokenExpireMinutes);
            }
        }
    }
}
