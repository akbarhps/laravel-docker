<?php

namespace App\Http\Controllers\Auth;

use App\Helper\Routes;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class LogOutController extends Controller
{
    public function __invoke(Request $request)
    {
        auth()->logout();
        session()->invalidate();
        session()->regenerateToken();

        return redirect()->intended(Routes::getIndexRoute());
    }
}
