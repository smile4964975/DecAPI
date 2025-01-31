<?php

namespace App\Http\Middleware;

use Closure;

use App\IpBlacklist;
use Log;

use Exception;

class IPBasedBlacklist
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        try {
            $ip = $request->ip();
            $blacklist = IpBlacklist
                    ::where('ip_address', $ip)
                    ->first();

            if (empty($blacklist)) {
                return $next($request);
            }

            Log::Info(sprintf('Blocked %s from accessing %s due to reason: %s', $ip, $request->fullUrl(), $blacklist->reason));
            abort(503);
        }
        catch (Exception $ex)
        {
            // If the database connection errors for some reason, just let the request continue.
            // Most requests are _not_ blacklisted and it feels unfair to "punish" them because
            // of an error that shouldn't affect them at all.
            Log::error($ex);
            return next($request);
        }
    }
}
