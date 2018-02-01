<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\User;

class ConfirmationController extends Controller
{
    public function index()
    {
        $user = User::where('confirmation_token', request('token'))->first();

        if (empty($user)) {
            return redirect('/threads')->with('alert_flash', '地址失效');
        }

        $user->confirm();
        return redirect('/threads')->with('alert_flash', '验证成功!');
    }
}
