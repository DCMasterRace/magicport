<?php

namespace App\Traits;

use App\Models\Project;
use App\Models\Task;

trait ProjectTrait
{
    public function get($model)
    {
        $results = $model->get();
        return $results;
    }

    public function getById($model, int $id)
    {
        $result = $model->find($id);
        return $result;
    }

    public function saveData($model, array $data)
    {
        try {

            $model->fill($data);

            $model->save();

            return response()->json(['message' => 'Project added', 'status' => 201]);

        } catch (Exception $e) {

            return response()->json(['message' => 'Error: '.$e, 'status' => 401]);

        }
    }

    public function edit($model, int $id, array $data)
    {
    }

    public function delete($model, int $id)
    {
    }
}
