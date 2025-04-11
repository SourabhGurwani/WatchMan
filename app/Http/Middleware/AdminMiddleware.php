<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class AdminMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // 1. Check if user is authenticated
        if (!auth()->check()) {
            Log::warning('Unauthorized admin access attempt', [
                'ip' => $request->ip(),
                'url' => $request->fullUrl(),
                'user' => 'guest'
            ]);
            return redirect()->route('login')->with('error', 'Please login to access admin area');
        }

        // 2. Check if user is admin
        if (!auth()->user()->is_admin) {
            Log::warning('Unauthorized admin access attempt', [
                'ip' => $request->ip(),
                'url' => $request->fullUrl(),
                'user' => auth()->user()->email
            ]);
            return redirect('/')->with('error', 'You do not have admin privileges');
        }

        // 3. Additional security checks
        if ($this->shouldBlockAccess($request)) {
            Log::alert('Suspicious admin access blocked', [
                'ip' => $request->ip(),
                'user' => auth()->user()->email,
                'url' => $request->fullUrl()
            ]);
            abort(403, 'Access denied');
        }

        // 4. Set security headers for admin routes
        $response = $next($request);

        $response->headers->set('X-Content-Type-Options', 'nosniff');
        $response->headers->set('X-Frame-Options', 'DENY');
        $response->headers->set('X-XSS-Protection', '1; mode=block');

        return $response;
    }

    /**
     * Additional security checks
     */
    protected function shouldBlockAccess(Request $request): bool
    {
        // Example: Block access from suspicious IPs
        $suspiciousIPs = [
            // Add IPs you want to block
        ];

        return in_array($request->ip(), $suspiciousIPs);
    }
}