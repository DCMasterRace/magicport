<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Classes\Task;

class TaskController extends Controller
{
    private $task;

    public function __construct()
    {
        $this->task = new Task;
    }

    public function index($project_id)
    {
        $results = $this->task->getByProjectId($project_id);
        return response()->json($results, 200);
    }

    public function show($project_id, $task_id)
    {
        $result = $this->task->getByProjectTaskId($project_id, $task_id);
        return response()->json($result, 200);
    }

    public function save(Request $request, $project_id)
    {

        $validate = $request->validate([
            'name'          => 'required|min:5',
            'description'   => 'required|min:5',
            'status'        => 'required|in:todo,in-progress,done',
        ]);

        $data = [
            'project_id'    => $project_id,
            'name'          => $request->name,
            'description'   => $request->description,
            'status'        => $request->status,
        ];

        $result = $this->task->saveData($data);
        return response()->json($result, 200);
    }

    public function edit(Request $request, $project_id, $task_id)
    {
        $validate = $request->validate([
            'name'          => 'required|min:5',
            'description'   => 'required|min:5',
            'status'   => 'required|in:todo,in-progress,done',
        ]);

        $data = [
            'project_id'    => $project_id,
            'name'          => $request->name,
            'description'   => $request->description,
            'status'        => $request->status,
        ];

        $result = $this->task->edit($task_id, $data);
        return response()->json($result, 200);
    }

    public function delete(Request $request, $project_id, $task_id)
    {
        $result = $this->task->delete($task_id);
        return response()->json($result);
    }
}
