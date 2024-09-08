<?php

namespace App\Interfaces;


interface ProjectTaskInterface
{
    public function get();
    public function getById(int $id);
    public function saveData(array $data);
    public function edit(int $id, array $data);
    public function delete(int $id);
}

