<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;

use function auth, redirect;

class LogoutController extends Controller
{

    public function __invoke(): RedirectResponse
    {
        try {
            auth()->logout();
        } catch (\Exception) {
        }

        return redirect('/');
    }

}
