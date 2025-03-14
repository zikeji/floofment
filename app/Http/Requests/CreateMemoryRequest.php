<?php

namespace App\Http\Requests;

use App\Rules\TurnstileValid;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\File;

class CreateMemoryRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'min:2', 'max:255'],
            'message' => ['sometimes', 'required_without:voiceMessage', 'nullable', 'string', 'min:2'],
            'voiceMessage' => [
                'sometimes', 'required_without:message', 'nullable',
                File::types(['audio/*'])
                    ->max(25 * 1024)
            ],
            'files.*' => [
                'sometimes',
                File::image(false)
                    ->max(25 * 1024)
            ],
            'turnstileToken' => [
                'sometimes',
                'nullable',
                new TurnstileValid,
            ]
        ];
    }

    public function messages(): array
    {
        return [
            'message.required_without' => 'At least a message or voice message is required.',
            'voiceMessage.required_without' => 'At least a voice message or message is required.',
            'files.*.image' => 'The file must be an image.',
            'files.*.max' => 'The file may not be greater than 25MB.',
        ];
    }
}
