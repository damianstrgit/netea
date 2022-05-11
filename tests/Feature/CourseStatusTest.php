<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CourseStatusTest extends TestCase
{
    /**
     * Test correct data input and correct response
     *
     * @return void
     */
    public function testCorectData()
    {
        $response = $this->json('GET', 'api/v1/courses/status', [
            'course_duration' => 1800,
            'learning_progress' => 60,
            'start_date' => $this->getFromatedDateString(-9),
            'end_date' => $this->getFromatedDateString(+10),
        ]);

        $response->assertStatus(200);

        $response->assertJsonStructure([
            'progress_status',
            'expected_progress',
            'needed_daily_learning_time',
        ]);

        $response->assertJson([
            'progress_status' => 'on track',
            'expected_progress' => 50,
            'needed_daily_learning_time' => 90,
        ]);
    }

    /**
     * Test empty data request
     *
     * @return void
     */
    public function testEmptyDataRequest()
    {
        $response = $this->json('GET', 'api/v1/courses/status', []);

        $response->assertStatus(422);

        // $response->assertJsonStructure(['errors']);

        $response->assertJsonStructure([
            'errors' => [
                'course_duration' => ['*' => ['code', 'message']],
                'learning_progress' => ['*' => ['code', 'message']],
                'start_date' => ['*' => ['code', 'message']],
                'end_date' => ['*' => ['code', 'message']],
            ],
        ]);
    }

    /**
     * Test Incorrect Data
     *
     * @return void
     */
    public function testIncorrectData()
    {
        $response = $this->json('GET', 'api/v1/courses/status', [
            'course_duration' => 'aaa',
            'learning_progress' => 'aaa',
            'start_date' => 'aaa',
            'end_date' => 'aaa',
        ]);

        $response
            ->assertStatus(422)
            ->assertJsonStructure(['errors'])
            ->assertJsonFragment([
                'course_duration' => [
                    [
                        'code' => 'course_duration_integer_error',
                        'message' => 'The course duration must be an integer.',
                    ],
                ],
            ])
            ->assertJsonFragment([
                'learning_progress' => [
                    [
                        'code' => 'learning_progress_integer_error',
                        'message' =>
                            'The learning progress must be an integer.',
                    ],
                ],
            ])
            ->assertJsonFragment([
                'code' => 'start_date_date_error',
                'message' => 'The start date is not a valid date.',
            ])
            ->assertJsonFragment([
                'code' => 'end_date_date_error',
                'message' => 'The end date is not a valid date.',
            ]);
    }

    /**
     * Test Error Start Date after End Date
     *
     * @return void
     */
    public function testErrorStartDateAfterEndDate()
    {
        $response = $this->json('GET', 'api/v1/courses/status', [
            'course_duration' => 1800,
            'learning_progress' => 60,
            'start_date' => $this->getFromatedDateString(+19),
            'end_date' => $this->getFromatedDateString(+10),
        ]);

        $response
            ->assertStatus(422)
            ->assertJsonStructure(['errors'])
            ->assertJsonFragment([
                'code' => 'end_date_after_error',
                'message' => 'The end date must be a date after start date.',
            ]);
    }

    /**
     * Test Error Start Date after Now
     *
     * @return void
     */
    public function testErrorStartDateAfterNow()
    {
        $response = $this->json('GET', 'api/v1/courses/status', [
            'course_duration' => 1800,
            'learning_progress' => 60,
            'start_date' => $this->getFromatedDateString(+2),
            'end_date' => $this->getFromatedDateString(+10),
        ]);

        $response
            ->assertStatus(422)
            ->assertJsonStructure(['errors'])
            ->assertJsonFragment([
                'code' => 'start_date_beforeorequal_error',
                'message' =>
                    'The start date must be a date before or equal to now.',
            ]);
    }
}
