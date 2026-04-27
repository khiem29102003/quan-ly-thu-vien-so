<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class CheckAdminOrLibrarian
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
        // Check if user is authenticated and has admin or librarian role
        if (auth()->check() && in_array(auth()->user()->role, ['admin', 'librarian'])) {
            return $next($request);
        }

        // Redirect to dashboard if not authorized
        return redirect('/')->with('error', 'Bạn không có quyền truy cập trang này.');
    }
}
