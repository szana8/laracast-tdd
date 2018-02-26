<?php

namespace App\Http\Controllers\Api;

use App\User;
use App\Http\Controllers\Controller;

class RegisterConfirmationController extends Controller
{
    public function index()
    {
        $user = User::where('confirmation_token', request('token'))
            ->firstOrFail()
            ->confirm();

        return redirect('/issues')->with('flash', 'Your account is now confirmed. You may post the forum.');
    }
}
