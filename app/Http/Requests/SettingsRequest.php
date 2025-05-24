<?php
// filepath: f:\UGM\cloudcomputing\cloudcomputing_project\app\Http\Requests\SettingsRequest.php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SettingsRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        // Only admins can change system settings
        return $this->user() && $this->user()->is_admin;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\Rule|array|string>
     */
    public function rules(): array
    {
        return [
            'site_name' => 'sometimes|string|max:255',
            'default_token_balance' => 'sometimes|integer|min:0',
            'token_price' => 'sometimes|numeric|min:0',
            'max_download_size' => 'sometimes|integer|min:0',
            'mb_per_token' => 'sometimes|numeric|min:0.01|max:100',
            'maintenance_mode' => 'sometimes|boolean',
            'youtube_enabled' => 'sometimes|boolean',
            'tiktok_enabled' => 'sometimes|boolean',
            'instagram_enabled' => 'sometimes|boolean',
            'max_storage_days' => 'sometimes|integer|min:1',
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
            'site_name.max' => 'The site name cannot exceed 255 characters.',
            'default_token_balance.integer' => 'The default token balance must be a whole number.',
            'default_token_balance.min' => 'The default token balance cannot be negative.',
            'token_price.numeric' => 'The token price must be a number.',
            'token_price.min' => 'The token price cannot be negative.',
            'max_download_size.integer' => 'The maximum download size must be a whole number.',
            'mb_per_token.numeric' => 'The MB per token value must be a number.',
            'mb_per_token.min' => 'The MB per token value must be at least 0.01.',
            'max_storage_days.integer' => 'The maximum storage days must be a whole number.',
            'max_storage_days.min' => 'The maximum storage days must be at least 1.',
        ];
    }

    /**
     * Prepare the data for validation.
     *
     * @return void
     */
    protected function prepareForValidation()
    {
        $this->merge([
            'maintenance_mode' => $this->has('maintenance_mode'),
            'youtube_enabled' => $this->has('youtube_enabled'),
            'tiktok_enabled' => $this->has('tiktok_enabled'),
            'instagram_enabled' => $this->has('instagram_enabled'),
        ]);
    }
}
