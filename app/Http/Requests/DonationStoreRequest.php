<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class DonationStoreRequest extends FormRequest
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
            'email' => 'nullable|email|max:255',
            'phone' => 'nullable|string|max:50',
            'member_id' => 'nullable|exists:members,id',
            'amount' => 'required|numeric|min:1',
            'currency' => 'nullable|string|size:3',
            'purpose' => 'required|in:general,medical,education,emergency,infrastructure,other',
            'purpose_other' => 'required_if:purpose,other|nullable|string|max:255',
            'payment_method' => 'required|in:cash,bank_transfer,online,check,other',
            'status' => 'nullable|in:pending,completed,refunded,cancelled',
            'is_anonymous' => 'nullable|boolean',
            'message' => 'nullable|string',
            'notes' => 'nullable|string',
        ];
    }

    public function messages(): array
    {
        return [
            'donor_name.required' => 'Donor name is required.',
            'amount.required' => 'Donation amount is required.',
            'amount.min' => 'Donation amount must be at least 1.',
            'purpose.required' => 'Donation purpose is required.',
            'payment_method.required' => 'Payment method is required.',
            'purpose_other.required_if' => 'Please specify the purpose when selecting "Other".',
        ];
    }
}
