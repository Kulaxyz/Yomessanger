<?php

namespace App\Http\Controllers\Api;

use App\Chat;
use App\Http\Controllers\Controller;
use App\User;
use Illuminate\Http\Request;

class ChatController extends Controller
{
    public function __construct()
    {
//        $this->middleware('auth');
    }

    public function index(Request $request)
    {
//        $user = auth()->user();
        $user = User::find(4);
        $chats = $user->chats()->with('participants')->get();
//        foreach ($chats as $ch) {
//            foreach ($ch->participants as &$participant)
//                $participant->name = $participant->name;
//        }
        return $chats;
    }

}
