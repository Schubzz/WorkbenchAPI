<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreTaskRequest;
use App\Http\Resources\TasksResource;
use App\Models\Task;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TasksController extends Controller
{
    public function index()
    {
        return TasksResource::collection(
            Task::where('user_id', Auth::user()->id)->get()
        );
    }

    public function store(StoreTaskRequest $request)
    {
        $request->validated($request->all());

        $task = Task::create([
            'user_id' => Auth::user()->id,
            'project_id' => $request->project_id,
            'title' => $request->title,
            'description' => $request->description,
            'priority' => $request->priority
        ]);

        return new TasksResource($task);
    }

    public function show(Task $task)
    {
        return $this->isNotAuthorized($task) ? $this->isNotAuthorized($task) : new TasksResource($task);
    }

    public function update(Request $request, Task $task)
    {

        if (Auth::user()->id !== $task->user_id) {
            return $this->error('', 'Not authorized for this request', 403);
        }

        $task->update($request->all());

        return new TasksResource($task);
    }

    public function destroy(Task $task)
    {
        return $this->isNotAuthorized($task) ? $this->isNotAuthorized($task) : $task->delete();
    }

    private function isNotAuthorized($task)
    {
        if (Auth::user()->id !== $task->user_id) {
            return $this->error('', 'Not authorized for this request', 403);
        }
    }
}
