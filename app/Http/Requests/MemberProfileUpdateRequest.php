<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class MemberProfileUpdateRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $memberId = $this->route('member') ? $this->route('member')->id : null;

        return [
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'name' => 'required|string|max:255',
            'name_bn' => 'nullable|string|max:255',
            'email' => [
                'nullable',
                'email',
                Rule::unique('members')->ignore($memberId),
            ],
            'mobile' => 'required|string|max:20',
            'present_address' => 'nullable|string',
            'present_address_bn' => 'nullable|string',
            'permanent_address' => 'nullable|string',
            'permanent_address_bn' => 'nullable|string',
            'emergency_contact_name' => 'nullable|string|max:255',
            'emergency_contact_phone' => 'nullable|string|max:20',
            'emergency_contact_relation' => 'nullable|string|max:50',
            'nominee_name' => 'nullable|string|max:255',
            'nominee_relation' => 'nullable|string|max:50',
            'nominee_phone' => 'nullable|string|max:20',
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'Name is required.',
            'mobile.required' => 'Mobile number is required.',
            'email.email' => 'Please enter a valid email address.',
        ];
    }
}
