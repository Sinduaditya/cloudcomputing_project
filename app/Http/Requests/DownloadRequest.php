<?php
// filepath: f:\UGM\cloudcomputing\cloudcomputing_project\app\Http\Requests\DownloadRequest.php
namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class DownloadRequest extends FormRequest
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
     * @return array<string, \Illuminate\Contracts\Validation\Rule|array|string>
     */
    public function rules(): array
    {
        return [
            'url' => 'required|url',
            'format' => 'required|in:mp3,mp4',
            'quality' => 'nullable|in:360p,480p,720p,1080p',
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
            'url.required' => 'Please enter a URL to download.',
            'url.url' => 'Please enter a valid URL.',
            'format.required' => 'Please select a format.',
            'format.in' => 'Please select a valid format (MP3 or MP4).',
            'quality.in' => 'Please select a valid quality (360p, 480p, 720p, 1080p).',
        ];
    }
}
