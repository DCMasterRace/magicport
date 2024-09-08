<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Traits\ProjectTrait;

use App\Classes\Project;


class ProjectController extends Controller
{
    use ProjectTrait;

    private $projects;

    public function __construct()
    {
        $this->projects = new Project;
    }

    public function index()
    {
        $results = $this->projects->get();
        return response()->json($results, 200);
    }

    public function show(Request $request, $id)
    {
        $result = $this->projects->getById($id);
        return response()->json($result, 200);
    }

    public function save(Request $request)
    {
        $request->validate([
            'name'          => 'required|min:5',
            'description'   => 'required|min:5',
        ]);

        $data = [
            'name'          => $request->name,
            'description'   => $request->description,
        ];
        $result = $this->projects->saveData($data);
        return response()->json($result);
    }

    public function edit(Request $request, $id)
    {
        $request->validate([
            'name'          => 'required|min:5',
            'description'   => 'required|min:5',
        ]);

        $data = [
            'name'          => $request->name,
            'description'   => $request->description,
        ];

        $result = $this->projects->edit($id, $data);
        return response()->json($result);
    }

    public function delete($id)
    {
        $result = $this->projects->delete($id);

        return response()->json($result);
    }
}
