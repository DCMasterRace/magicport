<?php

namespace App\Classes;
use App\Interfaces\ProjectTaskInterface;

use App\Models\Project as ProjectModel;


class Project implements ProjectTaskInterface
{
    public function get()
    {
        $results = ProjectModel::all();
        return $results;
    }

    public function getById(int $id)
    {
        $results = ProjectModel::find($id);
        return $results;
    }

    public function saveData(array $data)
    {
        try {
            $project = new ProjectModel;
            $project->fill($data);
            $project->save();

            return response()->json(['message' => 'Project added', 'status' => 201]);
        } catch (Exception $e) {
            return response()->json(['message' => 'Error: '.$e, 'status' => 401]);
        }
    }

    public function edit(int $id, array $data)
    {
        try {
            $project = ProjectModel::find($id);
            $project->fill($data);
            $project->update();

            return response()->json(['message' => 'Project updated', 'status' => 200]);
        } catch (Exception $e) {
            return response()->json(['message' => 'Error: '.$e, 'status' => 401]);
        }
    }

    public function delete(int $id)
    {
        try {
            $project = ProjectModel::find($id);
            $project->delete();

            return response()->json(['message' => 'Project deleted', 'status' => 200]);
        } catch (Exception $e) {
            return response()->json(['message' => 'Error: '.$e, 'status' => 401]);
        }
    }
}
