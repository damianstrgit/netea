<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

use App\Rules\DateRFC3339;

class CourseStatus extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'course_duration' => 'required|integer|min:0',
            'learning_progress' => 'required|integer|min:0|max:100',
            'start_date' => [
                'required',
                'date',
                'before_or_equal:now',
                new DateRFC3339(),
            ],
            'end_date' => [
                'required',
                'date',
                'after:start_date',
                new DateRFC3339(),
            ],
        ];
    }
}
