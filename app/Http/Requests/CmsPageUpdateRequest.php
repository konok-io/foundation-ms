<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CmsPageUpdateRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'title' => 'required|string|max:255',
            'title_bn' => 'nullable|string|max:255',
            'slug' => [
                'nullable',
                'string',
                'max:255',
                Rule::unique('cms_pages')->ignore($this->route('cms')->id),
            ],
            'content' => 'nullable|string',
            'content_bn' => 'nullable|string',
            'excerpt' => 'nullable|string|max:500',
            'excerpt_bn' => 'nullable|string|max:500',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'icon' => 'nullable|string|max:50',
            'page_type' => 'nullable|in:' . implode(',', array_keys(\App\Models\CmsPage::PAGE_TYPES)),
            'position' => 'nullable|integer|min:0',
            'status' => 'nullable|boolean',
            'meta_title' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string|max:500',
            'meta_keywords' => 'nullable|string|max:255',
        ];
    }

    public function messages(): array
    {
        return [
            'title.required' => 'Page title is required.',
            'slug.unique' => 'This slug is already in use. Please choose a different one.',
            'image.image' => 'The file must be an image.',
            'image.max' => 'Image size must not exceed 2MB.',
        ];
    }
}
