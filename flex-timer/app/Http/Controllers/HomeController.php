<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Task;
use App\Models\PomodoroSetting;

class HomeController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $tasks = Task::query();
        $pomodoro_setting = PomodoroSetting::query();
        if($user != null){
            $user_id = $user->id;
            $tasks->where('user_id', $user_id);
            $pomodoro_setting->where('user_id', $user_id);
        }else{
            $session_id = session()->getId();
            $tasks->where('session_id', $session_id);
            $pomodoro_setting->where('session_id', $session_id);
        }
        $tasks = $tasks->orderBy('id', 'desc')->get();
        $pomodoro_setting = $pomodoro_setting->first();
        return view('home', compact('user', 'tasks', 'pomodoro_setting'));
    }
}
