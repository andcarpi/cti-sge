<?php

namespace App\Http\Controllers;

use App\Color;
use App\Course;
use App\CourseConfiguration;
use Carbon\Carbon;
use Illuminate\Http\Request;

class CourseController extends Controller
{
    public function index()
    {
        $courses = Course::all();
        return view('admin.course.index')->with(['courses' => $courses]);
    }

    public function details($id)
    {
        if (!is_numeric($id)) {
            return redirect()->route('curso.index');
        }

        $course = Course::findOrFail($id);
        $color = Color::all()->find($course->id_color);
        $config = CourseConfiguration::all()->where('id_course', '=', $id)->sortBy('id');
        $config = sizeof($config) > 0 ? $config->last() : null;

        return view('admin.course.details')->with(['course' => $course, 'config' => $config, 'color' => $color]);
    }

    public function new()
    {
        $colors = Color::all();
        return view('admin.course.new')->with(['colors' => $colors]);
    }

    public function edit($id)
    {
        if (!is_numeric($id)) {
            return redirect()->route('curso.index');
        }

        $course = Course::findOrFail($id);
        $colors = Color::all();

        return view('admin.course.edit')->with(['course' => $course, 'colors' => $colors]);
    }

    public function save(Request $request)
    {
        $course = new Course();
        $params = [];

        if (!$request->exists('cancel')) {
            $validatedData = (object)$request->validate(
                [
                    'name' => 'required|max:30',
                    'color' => 'required',
                    'active' => 'required'
                ]);

            if ($request->exists('id')) { // Edit
                $id = $request->input('id');
                $course = Course::all()->find($id);

                $course->updated_at = Carbon::now();
            } else { // New
                $course->created_at = Carbon::now();

                $config = new CourseConfiguration();
                $configValidatedData = (object)$request->validate(
                    [
                        'minYear' => 'required',
                        'minSemester' => 'required',
                        'minHour' => 'required',
                        'minMonth' => 'required',
                        'minMonthCTPS' => 'required',
                        'minMark' => 'required'
                    ]
                );

                $config->min_year = $configValidatedData->minYear;
                $config->min_semester = $configValidatedData->minSemester;
                $config->min_hours = $configValidatedData->minHour;
                $config->min_months = $configValidatedData->minMonth;
                $config->min_months_ctps = $configValidatedData->minMonthCTPS;
                $config->min_grade = $configValidatedData->minMark;
            }

            $course->name = $validatedData->name;
            $course->id_color = $validatedData->color;
            $course->active = $validatedData->active == 'true';

            $saved = $course->save();

            if (isset($config)) {
                $config->id_course = $course->id;

                $saved = $config->save();
            }

            $params['saved'] = $saved;
            $params['message'] = ($saved) ? 'Salvo com sucesso' : 'Erro ao salvar!';
        }

        return redirect()->route('curso.index')->with($params);
    }

    public function delete(Request $request)
    {
        $params = [];
        $saved = false;

        if ($request->exists('id')) {
            $id = $request->input('id');
            $course = Course::findOrFail($id);
            $saved = $course->delete();
        }

        $params['saved'] = $saved;
        $params['message'] = ($saved) ? 'Excluído com sucesso' : 'Erro ao excluir!';
        return redirect()->route('curso.index')->with($params);
    }
}
