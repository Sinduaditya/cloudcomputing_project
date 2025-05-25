<?php
// filepath: f:\UGM\cloudcomputing\cloudcomputing_project\app\Http\Requests\TokenRequest.php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class TokenRequest extends FormRequest
{
    public function authorize()
    {
        return auth()->check() && auth()->user()->is_admin;
    }

    public function rules()
    {
        return [
            'user_id' => 'required|exists:users,id',
            'amount' => 'required|integer|not_in:0',
            'type' => 'required|string|in:admin_adjustment,bonus,refund,penalty,reward,correction',
            'description' => 'nullable|string|max:255',
        ];
    }

    public function messages()
    {
        return [
            'amount.not_in' => 'Amount cannot be zero.',
            'user_id.exists' => 'Selected user does not exist.',
            'user_id.required' => 'Please select a user.',
            'amount.required' => 'Please enter an amount.',
            'type.required' => 'Please select a transaction type.',
        ];
    }
}
