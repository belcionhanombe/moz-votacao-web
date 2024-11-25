<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class auteticarAdminis
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        $admin = session("administradores");
        

        if(!$admin){
            return redirect()->back();
        }
        if($admin->nivel == 4){

            return redirect()->back();
        }
        return $next($request);
    }
}
