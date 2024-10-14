<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ReportRequest extends FormRequest
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

            'user_id' => 'required|string|max:255',
            'qrt' => 'nullable|string|max:225',
            'lgu_id'=>'nullable|string|max:225',
            'incident_date' => 'required|string|max:255',
            'start' => 'required|string|max:255',
            'disaster_type' => 'required|string|max:255',
            'longitude' => 'required|string|max:255',
            'latitude' => 'required|string|max:255',
            'district_code' => 'required|string|max:255',
            'municipality_code' => 'nullable|string|max:255',
            'barangay_code' => 'required|string|max:255',
            'situational_overview' => 'nullable|string|max:1000',
            'file.*' => 'file'

        ];
    }
}
