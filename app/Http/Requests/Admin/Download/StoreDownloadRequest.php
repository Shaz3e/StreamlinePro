<?php

namespace App\Http\Requests\Admin\Download;

use App\Http\Requests\BaseFormRequest;
use Illuminate\Validation\Rule;

class StoreDownloadRequest extends BaseFormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'title' => [
                'required',
                'string',
                'max:255',
            ],
            'version' => [
                'required',
                'string',
                'max:50',
            ],
            'is_active' => [
                'required',
                'boolean',
            ],
            'description' => [
                'required',
            ],
            'file_path' => [
                'nullable', 'file', 'mimes:zip,exe,msi', 'max:102400',
            ],
            'download_link' => [
                'nullable', 'url', 'max:255',
            ],
            'user_id' => [
                'nullable',
                'array',
                Rule::exists('users', 'id'),
            ],
        ];
    }
}
