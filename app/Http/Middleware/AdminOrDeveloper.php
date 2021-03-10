<?php

namespace App\Http\Middleware;

use App\Constants\Roles;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminOrDeveloper
{
    public function handle(Request $request, Closure $next)
    {
        $urlFrom = url()->previous();

        if (Auth::check()) {
            $authUser = Auth::user();
            if (
                $authUser->getRole()->alias === Roles::DEVELOPER_ALIAS
                || $authUser->getRole()->alias === Roles::ADMINISTRATOR_ALIAS
            ) {
                return $next($request);
            }
        }

        return redirect($urlFrom);
    }
}
