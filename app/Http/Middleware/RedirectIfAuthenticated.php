<?php

namespace Noox\Http\Middleware;

use Closure;
use JWTAuth;
use Illuminate\Support\Facades\Auth;

class RedirectIfAuthenticated
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string|null  $guard
     * @return mixed
     */
    public function handle($request, Closure $next, $guard = null)
    {
        switch ($guard) {
        case 'admin':
          if (Auth::guard($guard)->check()) {
            $this->validateJwt($request);
            return redirect()->route('admin.dashboard');
          }
          break;
        default:
          if (Auth::guard($guard)->check()) {
              return redirect('/home');
          }
          break;
      }
      
      return $next($request);
    }

    protected function validateJwt($request)
    {
      if (! $request->session()->get('JWTToken')) {
        $token = JWTAuth::fromUser(Auth::guard('admin')->user(), ['type' => 'admin']);

        $request->session()->put('JWTToken', $token);
      }
    }
}
