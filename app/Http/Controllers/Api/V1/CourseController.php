<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Carbon\Carbon;

use App\Http\Requests\CourseStatus;
use App\Services\CourseStatusCalculatorService;

class CourseController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param  \App\Http\Requests\StatusCourse  $request
     * @return \Illuminate\Http\Response
     */
    public function status(CourseStatus $request)
    {
        $validatedData = $request->validated();

        $startDate = Carbon::createFromFormat(
            DATE_RFC3339,
            $validatedData['start_date']
        )->startOfDay();

        $endDate = Carbon::createFromFormat(
            DATE_RFC3339,
            $validatedData['end_date']
        )->endOfDay();

        $courseCurrentDay = new CourseStatusCalculatorService(
            $validatedData['course_duration'],
            $validatedData['learning_progress'],
            $startDate,
            $endDate
        );

        return response()->json([
            'progress_status' => $courseCurrentDay->getProgressStatus(),
            'expected_progress' => $courseCurrentDay->getCourseExpectedProgress(),
            'needed_daily_learning_time' => $courseCurrentDay->getCourseNeededDailyLearningTime(),
        ]);
    }
}
