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
use App\Models\CodigoMail;
use App\Models\CodigoCel;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use App\Mail\CodeConfirm;
use Illuminate\Support\Facades\URL;
use Illuminate\Database\Query\Builder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

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
    //HOLA

    /**
     * Handle an incoming authentication request.
     */
    
    public function store(LoginRequest $request)
    {
        // $request->authenticate();
        // $request->session()->regenerate();
        $mail = $request->email;
        $pass = $request->password;
        $usuario = User::whereEmail($mail)->first();
        if(Hash::check($pass, $usuario->password)){
            return self::Redireccion($usuario);
        }
        else{
            $request->authenticate();
        }
        //return redirect()->intended(RouteServiceProvider::HOME);
    }

    public function Redireccion($usuario)
    {
        $codigo = self::GeneraCodigo($usuario->id);
        if(! $codigo){

        }
        else
        {
            $URL = URL::temporarySignedRoute('vcodigo', now()->addMinutes(5), ['codigo' => $codigo]);
            $continue = self::enviacodigo($usuario->email, $URL);
            if($continue == true){
                return redirect()->intended(RouteServiceProvider::CODIGO);
            }
        }
    }

    // Public function VistaCodigo()
    // {
    //     return view('pantallas.muestracodigo');
    // }

    Public function GeneraCodigo($id)
    {
        $codigo = rand(100000, 999999);
        $codigomail = CodigoMail::create([
            'codigomail' => Hash::make($codigo),
            'codigomail_created_at' => Carbon::now(),
            'codigomail_verified_at' => NULL,
            'user_id' => $id,
        ]);
        
        if($codigomail)
        {
            return $codigo;
        }
        else{
            return false;
        }
    }

    Public function GeneraCodigoMovil($id)
    {
        $codigo = rand(100000, 999999);
        $codigocel = CodigoCel::create([
            'codigocel' => Hash::make($codigo),
            'codigocel_created_at' => Carbon::now(),
            'codigocel_verified_at' => NULL,
            'user_id' => $id,
        ]);
        if($codigocel)
        {
            return $codigo;
        }
        else{
            return "Algo salió mal durante la generación del codigo, por favor intentelo mas tarde";
        }
    }

    Public function ValidaCodigo(Request $request){
        $request->validate([
            'codigo' => 'required|numeric',
        ]);
        $cod = $request->codigo;
        $codigos = DB::table('codigo_mails')->select('codigomail', 'codigomail_created_at', 'user_id')->get();
        
        foreach($codigos as $code){
            if(Hash::check($cod, $code->codigomail)){
                $date = Carbon::now();
                if($date->subminutes(5) <= $code->codigomail_created_at){
                    if(DB::table('codigo_mails')->where('user_id', $code->user_id)->update(['codigomail_verified_at' => Carbon::now()])){
                        return self::GeneraCodigoMovil($code->user_id);
                    }
                    else{
                        return "El usuario no existe";
                    }
                }
                else{
                    return "El codigo ya expiró";
                }
            }
        }

        // $code = $request->codigo;
        // if($user = User::where('email', auth()->user()->email)->first()){
        //     if (Hash::check($code, $user->codigomail)) {
        //         if($affected = DB::table('users')->where('email', auth()->user()->email)->update(['codigomail_verified_at' => date('Y-m-d H:i:s')]))
        //         {
        //          return self::GeneraCodigoMovil();
        //         }
        //     }
        //     else{
        //         return redirect()->intended(RouteServiceProvider::CODIGO);
        //     }
        // }
    }

    Public function ValidaCodigoCel(Request $request){
        $request->validate([
            'codigo' => 'required|numeric',
        ]);
        $cod = $request->codigo;
        $codigos = DB::table('codigo_cels')->select('codigocel', 'codigocel_created_at', 'user_id')->get();
        
        foreach($codigos as $code){
            if(Hash::check($cod, $code->codigocel)){
                $date = Carbon::now();
                if($date->subminutes(5) <= $code->codigocel_created_at){
                    if(DB::table('codigo_cels')->where('user_id', $code->user_id)->update(['codigocel_verified_at' => Carbon::now()])){
                        $usuario = User::whereId($code->user_id)->first();
                        Auth::login($usuario);
                        return redirect()->intended(RouteServiceProvider::HOME);
                    }
                    else{
                        return "El usuario no existe";
                    }
                }
                else{
                    return "El codigo ya expiró";
                }
            }
        }
        // $request->validate([
        //     'codigo' => 'required|numeric'
        // ]);
        // $code = $request->codigo;
        // if($user = User::where('email', auth()->user()->email)->first()){
        //     if (Hash::check($code, $user->codigocel)) {
        //         if($affected = DB::table('users')->where('email', auth()->user()->email)->update(['codigocel_verified_at' => date('Y-m-d H:i:s')]))
        //         {
        //          return redirect()->intended(RouteServiceProvider::HOME);
        //         }
        //     }
        //     else{
        //         return redirect()->intended(RouteServiceProvider::CODIGO);
        //     }

        // }
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {

        //if($affected = DB::table('users')->where('email', auth()->user()->email)->update(['codigomail_verified_at' => NULL])){
            Auth::guard('web')->logout();
            $request->session()->invalidate();

            $request->session()->regenerateToken();
            return redirect('/');
        //}

        
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
