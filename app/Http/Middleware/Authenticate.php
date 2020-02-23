<?php

namespace App\Http\Middleware;

use Illuminate\Auth\Middleware\Authenticate as Middleware;

class Authenticate extends Middleware
{
    /**
     * Get the path the user should be redirected to when they are not authenticated.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return string|null
     */
    protected function redirectTo($request)
    {
        if (! $request->expectsJson()) {
            if(strrpos((\Request::route()->getName()), "backend::") !== false){
                //後台
                return route('backend::login',['redirectUrl'=> "/".$request->path()]);
            }else{
                //前台
                return route('login');
            }
        }
    }
}
