<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ContributionUpdateRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'member_id' => 'required|exists:members,id',
            'year' => 'required|integer|min:2020|max:2030',
            'month' => 'required|integer|min:1|max:12',
            'amount' => 'required|numeric|min:0',
            'due_date' => 'nullable|date',
            'status' => 'required|in:pending,partial,paid,overdue,waived',
            'payment_method' => 'nullable|in:cash,bank_transfer,online,check,other',
            'transaction_id' => 'nullable|string|max:255',
            'notes' => 'nullable|string',
        ];
    }

    public function messages(): array
    {
        return [
            'member_id.required' => 'Please select a member.',
            'member_id.exists' => 'Selected member does not exist.',
            'year.required' => 'Year is required.',
            'month.required' => 'Month is required.',
            'amount.required' => 'Amount is required.',
            'amount.min' => 'Amount must be at least 0.',
            'status.required' => 'Status is required.',
        ];
    }
}
