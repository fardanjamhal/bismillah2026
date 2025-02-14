<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Models\Affiliate\ReferralLink;
use App\Models\Setting\Setting;

class HandleReferral
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if ($request->has('ref')) {
            $referrer = ReferralLink::where('referral_code', $request->get('ref'))->first();
            if ($referrer) {
                session(['referrer_id' => $referrer->user_id]);
            }
        }
    
        return $next($request);
    }
}
