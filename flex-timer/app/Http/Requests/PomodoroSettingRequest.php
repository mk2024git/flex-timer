<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PomodoroSettingRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'duration' => ['required', 'integer'],
            'break_duration' => ['required', 'integer'],
            'alarm_path' => ['required', 'string'],
            'alarm_volume' => ['required', 'integer', 'between:0,100'],
        ];
    }
}
