<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\NSac\Student;
use Illuminate\Http\Request;

class GraduationController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:graduation-list');
    }

    public function index()
    {
        $students = Student::getActives()->filter(function (Student $s) {
            return $s->canGraduate();
        });

        return view('admin.graduation.index')->with(['students' => $students]);
    }

    public function graduate($ra, Request $request)
    {
        dd("Graduação do aluno de RA = {$ra}", "Parte resposnsável do sistema NSac");
    }
}
