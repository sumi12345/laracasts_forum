<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;

class AvatarController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function store()
    {
        //echo 'storing...';
        $this->validate(request(), [
            'avatar' => 'required|image'
        ]);
        //echo 'validate success!!!';
        $ori_path = request()->file('avatar')->getPathname();
        $file = request()->file('avatar')->move('storage/avatars', sha1_file($ori_path).'.jpg');
        //echo 'file moved!!!';
        auth()->user()->update([
            'avatar_path' => $file->getPathname(),
        ]);
    }
}
