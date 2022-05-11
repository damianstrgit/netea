<?php

namespace App\Services;
use Carbon\Carbon;

class CourseStatusCalculatorService
{
    private const ON_TRACK = 'on track';
    private const NOT_ON_TRACK = 'not on track';
    private const OVERDUE = 'overdue';

    private $courseDuration;
    private $learningProgress;
    private $startDate;
    private $endDate;
    private $currentDate;

    private $courseDurationDays;
    private $courseCurrentDay;

    public function __construct(
        int $courseDuration,
        int $learningProgress,
        Carbon $startDate,
        Carbon $endDate
    ) {
        $this->courseDuration = $courseDuration;
        $this->learningProgress = $learningProgress;

        $this->startDate = $startDate;
        $this->endDate = $endDate;

        $this->currentDate = Carbon::now()->endOfDay();

        $this->courseDurationDays = $this->calculateCourseDurationDays();
        $this->courseCurrentDay = $this->calculateCourseCurrentDay();
    }

    private function calculateCourseDurationDays()
    {
        return Carbon::parse($this->startDate)->diffInDays($this->endDate) + 1;
    }

    private function calculateCourseCurrentDay()
    {
        if ($this->endDate->lt($this->currentDate)) {
            return $this->courseDurationDays;
        }

        return Carbon::parse($this->startDate)->diffInDays($this->currentDate) +
            1;
    }

    private function calculateCourseExpectedProgress()
    {
        return round(
            (100 / $this->courseDurationDays) * $this->courseCurrentDay
        );
    }

    private function calculateCourseNeededDailyLearningTime()
    {
        return round($this->courseDuration / $this->courseDurationDays);
    }

    public function getProgressStatus()
    {
        if (
            $this->learningProgress >=
            (int) (100 / $this->courseDurationDays) * $this->courseCurrentDay
        ) {
            return self::ON_TRACK;
        } elseif ($this->endDate->gt($this->currentDate)) {
            return self::NOT_ON_TRACK;
        } else {
            return self::OVERDUE;
        }
    }

    public function getCourseExpectedProgress()
    {
        return $this->calculateCourseExpectedProgress();
    }

    public function getCourseNeededDailyLearningTime()
    {
        return $this->calculateCourseNeededDailyLearningTime();
    }
}
