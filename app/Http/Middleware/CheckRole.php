<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
          if (!$request->user()) {
            return redirect()->route('auth.login.form');
        }
        // Kiểm tra nếu user có một trong các role được phép
        if (!in_array($request->user()->role, $roles)) {
            // Redirect về trang phù hợp với role của user
            if (in_array($request->user()->role, ['ADMIN', 'STAFF'])) {
                return redirect()->route('admin.dashboard')->with('error', 'Bạn không có quyền truy cập trang này');
            }
            return redirect()->route('booking.index')->with('error', 'Bạn không có quyền truy cập trang này');
        }
        return $next($request);
    }
}
