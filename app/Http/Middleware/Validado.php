<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Providers\RouteServiceProvider;

class Validado
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle($request, Closure $next)
    {
        if (auth()->check() && auth()->user()->codigomail_verified_at != NULL){
            return $next($request);
        }
        elseif(auth()->check()){
            return redirect()->intended(RouteServiceProvider::CODIGO);
        }
        else{
            //return $next($request);
            return redirect('login');
        }

    }

}
