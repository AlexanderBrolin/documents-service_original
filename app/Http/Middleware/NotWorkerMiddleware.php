<?php

namespace App\Http\Middleware;

use App\Constants\Roles;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotWorkerMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        $urlFrom = url()->previous();

        if (Auth::check()) {
            if (Auth::user()->getRole()->alias !== Roles::WORKER_ALIAS) {
                return $next($request);
            }
        }

        return redirect($urlFrom);
    }
}
