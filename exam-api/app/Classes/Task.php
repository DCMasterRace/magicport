<?php

namespace App\Classes;
use App\Models\Task as TaskModel;


class Task extends Project
{
    public function getByProjectId($project_id)
    {
        // $results = $this->getById($project_id);
        // return $results;
        $results = TaskModel::where('project_id', $project_id)->get();
        return $results;
    }

    public function getByProjectTaskId($project_id, $task_id)
    {
        $result = TaskModel::where([['project_id', $project_id],['id', $task_id]])->projects()->get();
        return $result;
    }

    public function saveData(array $data)
    {
        try {
            $task = new TaskModel();
            $task->fill($data);
            $task->save();

            return response()->json(['message' => 'Task added', 'status' => 201]);
        } catch (Exception $e) {
            return response()->json(['message' => 'Error: '.$e, 'status' => 401]);
        }
    }

    public function edit(int $task_id, array $data)
    {
        try {
            $task = TaskModel::find($task_id);
            $task->fill($data);
            $task->update();

            return response()->json(['message' => 'Task updated', 'status' => 200]);
        } catch (Exception $e) {
            return response()->json(['message' => 'Error: '.$e, 'status' => 401]);
        }
    }

    public function delete(int $task_id)
    {
        try {
            $project = TaskModel::find($task_id);
            $project->delete();

            return response()->json(['message' => 'Project deleted', 'status' => 200]);
        } catch (Exception $e) {
            return response()->json(['message' => 'Error: '.$e, 'status' => 401]);
        }
    }
}
