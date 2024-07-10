<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\TaskCreateRequest;
use App\Http\Requests\TaskDestroyRequest;
use App\Models\Task;
use Illuminate\Support\Facades\Auth;

class TaskController extends Controller
{
    public function create(TaskCreateRequest $request)
    {
        $validated = $request->validated();
        $session_id = null;
        $user_id = null;
        if($request->user() != null){
            $user_id = $request->user()->id;
        }else{
            $session_id = session()->getId();
        }
        $task = new Task();
        $task->user_id = $user_id;
        $task->session_id = $session_id;
        $task->task_title = $validated['task_title'];
        $task->task_status = $validated['task_status'];
        $task->save();
        return response()->json($task);
    }

    public function index(Request $request)
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
        return response()->json($tasks);
    }

    public function destroy(TaskDestroyRequest $request)
    {
        $validated = $request->validated();
        $user = Auth::user();
        $tasks = Task::query();
        if($user != null){
            $user_id = $user->id;
            $tasks->where('user_id', $user_id);
        }else{
            $session_id = session()->getId();
            $tasks->where('session_id', $session_id);
        }
        $tasks = $tasks->where('id', $validated['task_id'])->first();
        $tasks->delete();
        return response()->json($tasks);
    }
}
