<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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
    protected $redirectTo = '/';

    /**
     * Create a new controller instance.
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    public function authenticate(Request $request)
    {
        $credentials = $request->only('email', 'password');

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();

            return redirect()->intended('dashboard');
        }

        return back();
    }

    public function authenticated(Request $request, $user)
    {
        if ($user->isStudent()) {
            Auth::logout();
            abort(401, "je bent als student niet gemachtigd om hier in te loggen.");
        }
    }

    protected function sendFailedLoginResponse(Request $request)
    {
        return redirect()->route('login')->with('error', 'De gebruikersnaam en het wachtwoord die je hebt ingevoerd komen niet overeen met ons archief. Controleer de gegevens en probeer het opnieuw.');
    }
}
