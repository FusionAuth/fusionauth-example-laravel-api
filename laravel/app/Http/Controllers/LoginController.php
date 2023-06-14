<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\RedirectResponse;

use function auth, hash_equals, redirect, session;

class LoginController extends Controller
{

    public function __invoke(): RedirectResponse
    {
        if (!$this->run()) {
            try {
                auth()->logout();
            } catch (\Exception) {
            }
        }

        return redirect('/');
    }

    protected function run(): bool
    {
        $session = session();
        $requestState = request()->get('state');
        $sessionState = $session->pull('state');
        if ((empty($requestState)) || (empty($sessionState))) {
            return false;
        }

        if (!hash_equals($sessionState, $requestState)) {
            $session->flash('error', 'State mismatch. Please log in again.');
            return false;
        }

        return auth()->user() instanceof User;
    }

}
