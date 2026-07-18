<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PublicDonationRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'donor_name' => 'required|string|max:255',
            'donor_name_bn' => 'nullable|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'required|string|max:50',
            'amount' => 'required|numeric|min:1',
            'purpose' => 'required|in:general,medical,education,emergency,infrastructure,other',
            'purpose_other' => 'required_if:purpose,other|nullable|string|max:255',
            'gateway' => 'nullable|in:stripe,paypal',
            'is_anonymous' => 'nullable|boolean',
            'message' => 'nullable|string|max:1000',
        ];
    }

    public function messages(): array
    {
        return [
            'donor_name.required' => 'Please enter your name.',
            'email.required' => 'Please enter your email address.',
            'email.email' => 'Please enter a valid email address.',
            'phone.required' => 'Please enter your phone number.',
            'amount.required' => 'Please enter the donation amount.',
            'amount.min' => 'Minimum donation amount is 1 SAR.',
            'purpose.required' => 'Please select a donation purpose.',
            'purpose_other.required_if' => 'Please specify the purpose when selecting "Other".',
        ];
    }
}
