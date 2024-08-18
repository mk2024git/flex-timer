<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\TaskCreateRequest;
use App\Http\Requests\TaskDestroyRequest;
use App\Http\Requests\TaskSortOrderRequest;
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
        $task->task_sort_order = 9999;
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
        $tasks = $tasks->orderBy('task_sort_order','asc')->orderBy('id', 'desc')->get();
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

    public function updateTaskSortOrder(TaskSortOrderRequest $request)
    {
        $validated = $request->validated();
        $task_ids = array_flip($validated['task_sort_orders']);
        $user = Auth::user();
        $tasks = Task::query();
        if($user != null){
            $user_id = $user->id;
            $tasks->where('user_id', $user_id);
        }else{
            $session_id = session()->getId();
            $tasks->where('session_id', $session_id);
        }
        $tasks = $tasks->whereIn('id', $validated['task_sort_orders'])->get();
        foreach($tasks as $task){
            if(isset($task_ids[$task->id])){
                $task->task_sort_order = $task_ids[$task->id] + 1;
                $task->save();
            }
        }
        return response()->json($task_ids);

    }
}
