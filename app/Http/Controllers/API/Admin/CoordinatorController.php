<?php

namespace App\Http\Controllers\API\Admin;

use App\APIUtils;
use App\Http\Controllers\Controller;
use App\Models\Coordinator;
use Illuminate\Http\Request;

class CoordinatorController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:coordinator-list');
        $this->middleware('permission:coordinator-create', ['only' => ['create', 'store']]);
        $this->middleware('permission:coordinator-edit', ['only' => ['edit', 'update']]);
    }

    public function get(Request $request)
    {
        $coordinators = Coordinator::all()->sortBy('id');

        if (!is_array($coordinators)) {
            $coordinators = array_values($coordinators->toArray());
        }

        if (!empty($request->q)) {
            $coordinators = APIUtils::search($coordinators, $request->q, 'name');
        }

        return response()->json(
            $coordinators,
            200,
            [
                'Content-Type' => 'application/json; charset=UTF-8',
                'charset' => 'utf-8'
            ],
            JSON_UNESCAPED_UNICODE);
    }

    public function getById($id)
    {
        $coordinator = Coordinator::findOrFail($id);

        return response()->json(
            $coordinator,
            200,
            [
                'Content-Type' => 'application/json; charset=UTF-8',
                'charset' => 'utf-8'
            ],
            JSON_UNESCAPED_UNICODE);
    }

    public function getByCourse($id, Request $request)
    {
        $coordinators = Coordinator::with('user')->whereNull('temp_of')->where('course_id', '=', $id)->get();

        if (!is_array($coordinators)) {
            $coordinators = array_values($coordinators->toArray());
        }

        if (!empty($request->q)) {
            $coordinators = APIUtils::search($coordinators, $request->q, 'name');
        }

        return response()->json(
            array_values($coordinators),
            200,
            [
                'Content-Type' => 'application/json; charset=UTF-8',
                'charset' => 'utf-8'
            ],
            JSON_UNESCAPED_UNICODE);
    }
}
