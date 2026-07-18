<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class MemberUpdateRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'name' => 'required|string|max:255',
            'name_bn' => 'nullable|string|max:255',
            'father_name' => 'required|string|max:255',
            'father_name_bn' => 'nullable|string|max:255',
            'mother_name' => 'required|string|max:255',
            'mother_name_bn' => 'nullable|string|max:255',
            'date_of_birth' => 'required|date|before:today',
            'gender' => 'required|in:male,female,other',
            'blood_group' => 'nullable|in:A+,A-,B+,B-,O+,O-,AB+,AB-',
            'mobile' => 'required|string|max:20',
            'email' => [
                'nullable',
                'email',
                Rule::unique('members')->ignore($this->route('member')->id),
            ],
            'national_id' => 'nullable|string|max:20',
            'passport_number' => 'nullable|string|max:20',
            'iqama_number' => 'nullable|string|max:20',
            'occupation' => 'nullable|string|max:255',
            'occupation_bn' => 'nullable|string|max:255',
            'designation' => 'nullable|string|max:255',
            'company_name' => 'nullable|string|max:255',
            'present_address' => 'required|string',
            'present_address_bn' => 'nullable|string',
            'permanent_address' => 'nullable|string',
            'permanent_address_bn' => 'nullable|string',
            'emergency_contact_name' => 'nullable|string|max:255',
            'emergency_contact_phone' => 'nullable|string|max:20',
            'emergency_contact_relation' => 'nullable|string|max:50',
            'join_date' => 'required|date',
            'member_type' => 'nullable|in:general,life,honorary,founder,associate',
            'status' => 'nullable|boolean',
            'position' => 'nullable|in:member,executive,secretary,vice_president,president,advisor',
            'nominee_name' => 'nullable|string|max:255',
            'nominee_relation' => 'nullable|string|max:50',
            'nominee_phone' => 'nullable|string|max:20',
            'referrer_member_id' => 'nullable|string|exists:members,member_id',
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'Member name is required.',
            'father_name.required' => 'Father\'s name is required.',
            'mother_name.required' => 'Mother\'s name is required.',
            'date_of_birth.required' => 'Date of birth is required.',
            'date_of_birth.before' => 'Date of birth must be before today.',
            'gender.required' => 'Gender is required.',
            'mobile.required' => 'Mobile number is required.',
            'email.email' => 'Please enter a valid email address.',
            'email.unique' => 'This email is already registered by another member.',
            'present_address.required' => 'Present address is required.',
            'join_date.required' => 'Join date is required.',
            'referrer_member_id.exists' => 'Referrer member ID does not exist.',
        ];
    }
}
