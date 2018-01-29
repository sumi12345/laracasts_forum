<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;

class AvatarController extends Controller
{
    public function __construct()
    {
        $this->base_path = env('APP_ENV') === 'testing' ? 'storage/avatars' : 'avatars';
        $this->middleware('auth');
    }

    public function store()
    {
        $this->validate(request(), [
            'avatar' => 'required|image'
        ]);

        $ori_path = request()->file('avatar')->getPathname();
        $new_file_name = sha1_file($ori_path).'.jpg';
        request()->file('avatar')->move($this->base_path, $new_file_name);

        auth()->user()->update([
            'avatar_path' => $new_file_name,
        ]);
    }
}
