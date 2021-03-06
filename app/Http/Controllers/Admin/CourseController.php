<?php

namespace App\Http\Controllers\Admin;

use App\Auth;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\DestroyCourse;
use App\Http\Requests\Admin\StoreCourse;
use App\Http\Requests\Admin\UpdateCourse;
use App\Models\Color;
use App\Models\Course;
use App\Models\CourseConfiguration;
use Illuminate\Support\Facades\Log;

class CourseController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:course-list');
        $this->middleware('permission:course-create', ['only' => ['create', 'store']]);
        $this->middleware('permission:course-edit', ['only' => ['edit', 'update']]);
        $this->middleware('permission:course-delete', ['only' => ['destroy']]);
    }

    public function index()
    {
        $courses = Course::all();
        return view('admin.course.index')->with(['courses' => $courses]);
    }

    public function show($id)
    {
        $course = Course::findOrFail($id);

        $color = $course->color;
        $config = $course->configuration();
        $coordinator = $course->coordinator;

        return view('admin.course.details')->with([
            'course' => $course,
            'config' => $config,
            'coordinator' => $coordinator,
            'color' => $color
        ]);
    }

    public function create()
    {
        $colors = Color::all()->sortBy('id');

        return view('admin.course.new')->with(['colors' => $colors]);
    }

    public function edit($id)
    {
        $course = Course::findOrFail($id);
        $colors = Color::all()->sortBy('id');

        return view('admin.course.edit')->with(['course' => $course, 'colors' => $colors]);
    }

    public function store(StoreCourse $request)
    {
        $course = new Course();
        $params = [];

        $validatedData = (object)$request->validated();

        $user = Auth::user();
        $log = "Novo curso";
        $log .= "\nUsuário: {$user->name}";

        $course->name = $validatedData->name;
        $course->color_id = $validatedData->color;
        $course->active = $validatedData->active;

        $saved = $course->save();
        $log .= "\nNovos dados: " . json_encode($course, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);

        if ($validatedData->hasConfig) {
            $config = new CourseConfiguration();

            $config->course_id = $course->id;
            $config->min_year = $validatedData->minYear;
            $config->min_semester = $validatedData->minSemester;
            $config->min_hours = $validatedData->minHours;
            $config->min_months = $validatedData->minMonths;
            $config->min_months_ctps = $validatedData->minMonthsCTPS;
            $config->min_grade = $validatedData->minGrade;

            $saved = $config->save();
        }

        if ($saved) {
            Log::info($log);
        } else {
            Log::error("Erro ao salvar curso");
        }

        $params['saved'] = $saved;
        $params['message'] = ($saved) ? 'Salvo com sucesso' : 'Erro ao salvar!';

        return redirect()->route('admin.course.index')->with($params);
    }

    public function update($id, UpdateCourse $request)
    {
        $course = Course::findOrFail($id);
        $params = [];

        $validatedData = (object)$request->validated();

        $user = Auth::user();
        $log = "Alteração de curso";
        $log .= "\nUsuário: {$user->name}";
        $log .= "\nDados antigos: " . json_encode($course, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);

        $course->name = $validatedData->name;
        $course->color_id = $validatedData->color;
        $course->active = $validatedData->active;

        $saved = $course->save();
        $log .= "\nNovos dados: " . json_encode($course, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);

        if ($saved) {
            Log::info($log);
        } else {
            Log::error("Erro ao salvar curso");
        }

        $params['saved'] = $saved;
        $params['message'] = ($saved) ? 'Salvo com sucesso' : 'Erro ao salvar!';

        return redirect()->route('admin.course.index')->with($params);
    }

    public function destroy($id, DestroyCourse $request)
    {
        $course = Course::findOrFail($id);
        $params = [];

        $validatedData = (object)$request->validated();

        $user = Auth::user();
        $log = "Exclusão de curso";
        $log .= "\nUsuário: {$user->name}";
        $log .= "\nDados antigos: " . json_encode($course, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);

        $saved = $course->delete();

        if ($saved) {
            Log::info($log);
        } else {
            Log::error("Erro ao excluir curso");
        }

        $params['saved'] = $saved;
        $params['message'] = ($saved) ? 'Excluído com sucesso' : 'Erro ao excluir!';
        return redirect()->route('admin.course.index')->with($params);
    }
}
