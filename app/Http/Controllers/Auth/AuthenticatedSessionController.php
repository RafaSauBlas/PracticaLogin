<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Providers\RouteServiceProvider;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use App\Mail\CodeConfirm;
use Illuminate\Support\Facades\URL;
use Illuminate\Database\Query\Builder;
use Illuminate\Support\Facades\DB;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(): View
    {
        return view('auth.login');
        //return view('pantallas.espera');
    }

    /**
     * Handle an incoming authentication request.
     */
    public $peticion = "";
    public function store(LoginRequest $request)
    {
        $request->authenticate();
        $request->session()->regenerate();
        return self::Redireccion($request);
        //return redirect()->intended(RouteServiceProvider::HOME);
    }

    public function Redireccion(LoginRequest $request)
    {
        $codigo = self::GeneraCodigo($request);
        if(! $codigo){

        }
        else
        {
            $URL = URL::temporarySignedRoute('vcodigo', now()->addMinutes(5), ['codigo' => $codigo]);
            $continue = self::enviacodigo($request->email, $URL);
            if($continue == true){
                return redirect()->intended(RouteServiceProvider::CODIGO);
            }
        }
    }

    // Public function VistaCodigo()
    // {
    //     return view('pantallas.muestracodigo');
    // }

    Public function GeneraCodigo(LoginRequest $request)
    {
        $codigo = rand(100000, 999999);
        if($affected = DB::table('users')->where('email', $request->email)->update(['codigomail' => Hash::make($codigo)]))
        {
            return $codigo;
        }
        else{
            return false;
        }
    }

    Public function ValidaCodigo(Request $request){
        $request->validate([
            'codigo' => 'required|numeric'
        ]);
        $code = $request->input('codigo');
        if($user = User::where('email', auth()->user()->email)->first()){
            if (Hash::check($code, $user->codigomail)) {
                if($affected = DB::table('users')->where('email', auth()->user()->email)->update(['codigomail_verified_at' => date('Y-m-d H:i:s')]))
                {
                 return redirect()->intended(RouteServiceProvider::HOME);
                }
            }
            else{
                return redirect()->intended(RouteServiceProvider::CODIGO);
            }
        //     if($user->codigomail === $code){

        //     }
        //     else{
        //         $request->session()->invalidate();

        // $request->session()->regenerateToken();
        //         return abort(401);
        //     }

        }
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {

        if($affected = DB::table('users')->where('email', auth()->user()->email)->update(['codigomail_verified_at' => NULL])){
            Auth::guard('web')->logout();
            $request->session()->invalidate();

            $request->session()->regenerateToken();
            return redirect('/');
        }

        
    }

    public function enviacodigo($mail, $codigo){
        // $code = $request->codigo;
        // $usuario = User::where('codigomail', Hash::check($code))->first();
        // return $usuario;
        if(Mail::to($mail)->send(new CodeConfirm($codigo))){
            return true;
        }
        else{
            return false;
        }

    }
}
