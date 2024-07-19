<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\PomodoroSettingRequest;
use App\Models\PomodoroSetting;
use Illuminate\Support\Facades\Auth;

class PomodoroSettingController extends Controller
{

    public function save(PomodoroSettingRequest $request)
    {
        $validated = $request->validated();
        $session_id = null;
        $user_id = null;
        $pomodoro_setting = PomodoroSetting::query();
        if($request->user() != null){
            $user_id = $request->user()->id;
            $pomodoro_setting->where('user_id', $user_id);
        }else{
            $session_id = session()->getId();
            $pomodoro_setting->where('session_id', $session_id);
        }
        $pomodoro_setting = $pomodoro_setting->first();
        if($pomodoro_setting == null) {
            $pomodoro_setting = new PomodoroSetting();
        }
        $pomodoro_setting->user_id = $user_id;
        if ($user_id == null) {
            $pomodoro_setting->session_id = $session_id;
        }
        $pomodoro_setting->duration = $validated['duration'];
        $pomodoro_setting->break_duration = $validated['break_duration'];
        $pomodoro_setting->alarm_path = $validated['alarm_path'];
        $pomodoro_setting->alarm_volume = $validated['alarm_volume'];
        $pomodoro_setting->save();
        return response()->json($pomodoro_setting);
    }
}
