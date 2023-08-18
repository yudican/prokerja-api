<?php

namespace App\Http\Controllers;

use App\Models\Course;
use Illuminate\Http\Request;

class CourseController extends Controller
{
    public function listCourses()
    {
        return response()->json([
            'message' => 'List Course',
            'data' => Course::all()
        ]);
    }

    public function getCourseDetail($course_id)
    {
        return response()->json([
            'message' => 'List Course',
            'data' => Course::find($course_id)
        ]);
    }
}
