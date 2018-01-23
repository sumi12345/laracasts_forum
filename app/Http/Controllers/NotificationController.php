<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;

class NotificationController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        return auth()->user()->unreadNotifications;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  string $userName
     * @param  int $id
     */
    public function destroy($userName, $id)
    {
        auth()->user()->notifications()->findOrFail($id)->markAsRead();
    }
}
