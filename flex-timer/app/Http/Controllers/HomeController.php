<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Task;

class HomeController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $tasks = Task::query();
        if($user != null){
            $user_id = $user->id;
            $tasks->where('user_id', $user_id);
        }else{
            $session_id = session()->getId();
            $tasks->where('session_id', $session_id);
        }
        $tasks = $tasks->orderBy('id', 'desc')->get();
        return view('home', compact('user', 'tasks'));
    }
}
