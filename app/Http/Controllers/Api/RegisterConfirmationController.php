<?php

namespace App\Http\Controllers\Api;

use App\User;
use App\Http\Controllers\Controller;

class RegisterConfirmationController extends Controller
{
    public function index()
    {
        $user = User::where('confirmation_token', request('token'))->first();

        if (! $user) {
            return redirect(route('issues'))->with('flash', 'Unknown token.');
        }

        $user->confirm();

        return redirect(route('issues'))
            ->with('flash', 'Your account is now confirmed. You may post the forum.');
    }
}
