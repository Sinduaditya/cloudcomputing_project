<?php
// filepath: f:\UGM\cloudcomputing\cloudcomputing_project\app\Http\Requests\TokenRequest.php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class TokenRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        // Ensure only admins can adjust tokens unless it's self-service
        if ($this->route('user') && $this->user()->id !== $this->route('user')->id) {
            return $this->user()->is_admin;
        }

        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\Rule|array|string>
     */
    public function rules(): array
    {
        return [
            'amount' => 'required|integer|not_in:0',
            'type' => 'required|in:admin_adjustment,refund',
            'description' => 'required|string|max:255',
        ];
    }

    /**
     * Get custom messages for validator errors.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'amount.required' => 'Please enter the token amount.',
            'amount.integer' => 'The token amount must be a whole number.',
            'amount.not_in' => 'The token amount cannot be zero.',
            'type.required' => 'Please select a transaction type.',
            'type.in' => 'Please select a valid transaction type.',
            'description.required' => 'Please provide a description for this transaction.',
            'description.max' => 'The description cannot exceed 255 characters.',
        ];
    }

    /**
     * Prepare the data for validation.
     *
     * @return void
     */
    protected function prepareForValidation()
    {
        // If negative amounts are provided with a minus sign, convert to positive
        // and prepend a minus sign for display purposes only
        if ($this->has('amount') && is_string($this->amount) && str_starts_with($this->amount, '-')) {
            $this->merge([
                'amount' => -1 * abs((int)$this->amount),
            ]);
        }
    }
}
