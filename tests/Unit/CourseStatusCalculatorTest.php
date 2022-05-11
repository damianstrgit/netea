<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Services\CourseStatusCalculatorService;

class CourseStatusCalculatorTest extends TestCase
{
    /**
     * Test status 'on track' and correct calculation
     *
     * @return void
     */
    public function testOnTrack()
    {
        $data = [
            'course_duration' => 1800,
            'learning_progress' => 60,
            'start_date' => $this->getCarbonDate(-9),
            'end_date' => $this->getCarbonDate(+10),
        ];

        $courseCurrentDay = new CourseStatusCalculatorService(
            $data['course_duration'],
            $data['learning_progress'],
            $data['start_date'],
            $data['end_date']
        );

        $progress_status = $courseCurrentDay->getProgressStatus();
        $this->assertEquals($progress_status, 'on track');

        $expected_progress = $courseCurrentDay->getCourseExpectedProgress();
        $this->assertEquals($expected_progress, (100 / 20) * 10);

        $needed_daily_learning_time = $courseCurrentDay->getCourseNeededDailyLearningTime();
        $this->assertEquals($needed_daily_learning_time, 1800 / 20);
    }

    /**
     * Test status 'not on track'
     *
     * @return void
     */
    public function testNotOnTrack()
    {
        $data = [
            'course_duration' => 1800,
            'learning_progress' => 25,
            'start_date' => $this->getCarbonDate(-9),
            'end_date' => $this->getCarbonDate(+10),
        ];

        $courseCurrentDay = new CourseStatusCalculatorService(
            $data['course_duration'],
            $data['learning_progress'],
            $data['start_date'],
            $data['end_date']
        );

        $progress_status = $courseCurrentDay->getProgressStatus();
        $this->assertEquals($progress_status, 'not on track');

        $expected_progress = $courseCurrentDay->getCourseExpectedProgress();
        $this->assertEquals($expected_progress, (100 / 20) * 10);

        $needed_daily_learning_time = $courseCurrentDay->getCourseNeededDailyLearningTime();
        $this->assertEquals($needed_daily_learning_time, 1800 / 20);
    }

    /**
     * Test status 'overdue' after end date
     *
     * @return void
     */
    public function testOverdue()
    {
        $data = [
            'course_duration' => 1800,
            'learning_progress' => 25,
            'start_date' => $this->getCarbonDate(-19),
            'end_date' => $this->getCarbonDate(-1),
        ];

        $courseCurrentDay = new CourseStatusCalculatorService(
            $data['course_duration'],
            $data['learning_progress'],
            $data['start_date'],
            $data['end_date']
        );

        $progress_status = $courseCurrentDay->getProgressStatus();
        $this->assertEquals($progress_status, 'overdue');
    }

    /**
     * Test status 'on track' after end date
     *
     * @return void
     */
    public function testOnTrackAfterEndDate()
    {
        $data = [
            'course_duration' => 1800,
            'learning_progress' => 100,
            'start_date' => $this->getCarbonDate(-19),
            'end_date' => $this->getCarbonDate(-1),
        ];

        $courseCurrentDay = new CourseStatusCalculatorService(
            $data['course_duration'],
            $data['learning_progress'],
            $data['start_date'],
            $data['end_date']
        );

        $progress_status = $courseCurrentDay->getProgressStatus();
        $this->assertEquals($progress_status, 'on track');
    }
}
