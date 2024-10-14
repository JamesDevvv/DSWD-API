<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateReportRequests extends FormRequest
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
            //
            'user_id' => 'required|string|max:255',
            'role_id' => 'nullable|string|max:255',
            'incident_date' => 'required|string|max:255',
            'disaster_type' => 'required|string|max:255',
            'start' => 'required|string|max:255',
            'end' => 'required|string|max:255',
            'longitude' => 'required|string|max:255',
            'latitude' => 'required|string|max:255',
            'district_code' => 'required|string|max:255',
            'municipality_code' => 'required|string|max:255',
            'barangay_code' => 'required|string|max:255',
            'no_families' => 'required|string|max:255',
            'no_individual' => 'required|string|max:255',
            'dead' => 'required|string|max:255',
            'injured' => 'required|string|max:255',
            'missing' => 'required|string|max:255',
            'residential' => 'required|string|max:255',
            'commercial' => 'required|string|max:255',
            'mix' => 'nullable|string|max:255',
            'total_damage' => 'required|string|max:255',
            'partial_damage' => 'required|string|max:255',
            'situational_overview' => 'required|string|max:1000',
            'augmentation' => 'nullable|string|max:1000',
            'remarks' => 'nullable|string|max:1000',
            'disaggregated_data' => '',
            'disaggregated_data.*.age' => 'nullable|string|max:255',
            'disaggregated_data.*.male' => 'nullable|string|max:255',
            'disaggregated_data.*.female' => 'nullable|string|max:255',


            'evacuation' => 'required',
            'evacuation.*.name'=> 'required|string|max:255',
            'evacuation.*.inside.families'=> 'nullable|string|max:255',
            'evacuation.*.inside.individuals'=> 'nullable|string|max:255',
            'evacuation.*.outside.families'=> 'nullable|string|max:255',
            'evacuation.*.outside.individuals'=> 'nullable|string|max:255',
            'file.*' => 'file|mimes:jpeg,png,jpg,gif,svg,mp4,pdf|max:25000',
            'augmentation_file.*' => 'file|mimes:jpeg,png,jpg,gif,svg,mp4,pdf|max:25000'
        ];
    }
}
