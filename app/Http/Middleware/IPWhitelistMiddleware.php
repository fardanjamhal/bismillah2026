<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Models\Setting\InternetProtocolWhitelist;
use App\Models\Setting\Setting;
use Auth;

class IPWhitelistMiddleware
{

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function handle(Request $request, Closure $next): Response
    {
        $setting = Setting::first();

        if($setting && $setting->public_access == 0) {
            $ipWhitelist = InternetProtocolWhitelist::pluck('ip')->toArray();

            if (!in_array($request->ip(), $ipWhitelist)) {
                Auth::logout();
                return redirect()->route('private-access');
            }
        }
        
        return $next($request);
    }
}
