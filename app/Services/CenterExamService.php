<?php

namespace App\Services;

use App\Models\CenterExam;

class CenterExamService
{
    public function getAllCenterExams()
    {
        return CenterExam::all();
    }

    public function getCenterExamById($id)
    {
        return CenterExam::findOrFail($id);
    }

    public function createCenterExam(array $data)
    {
        return CenterExam::create($data);
    }

    public function updateCenterExam($id, array $data)
    {
        $centerExam = CenterExam::findOrFail($id);
        $centerExam->update($data);

        return $centerExam;
    }

    public function deleteCenterExam($id)
    {
        $centerExam = CenterExam::findOrFail($id);
        $centerExam->delete();
    }
}
