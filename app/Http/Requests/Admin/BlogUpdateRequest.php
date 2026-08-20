<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class BlogUpdateRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    protected function prepareForValidation(): void
    {
        if (trim((string) $this->input('schema_markup')) === '') {
            $this->merge(['schema_markup' => null]);
        }

        if (trim((string) $this->input('slug')) === '') {
            $this->merge(['slug' => null]);
        }
    }

    public function rules(): array
    {
        return [
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'slug' => [
                'nullable',
                'string',
                'max:255',
                Rule::unique('blogs', 'slug')->ignore($this->route('blog')),
            ],
            'content' => 'required',
            'thumbnail_image' => 'sometimes|nullable|image',
            'banner_image' => 'sometimes|nullable|image',
            'seo_title' => 'nullable|string|max:255',
            'seo_description' => 'nullable|string',
            'canonical_url' => 'nullable|url|max:255',
            'schema_markup' => 'nullable|json',
            'tags' => 'nullable|string',
        ];
    }

    public function messages(): array
    {
        return [
            'title.required' => 'Title is required',
            'description.required' => 'Description is required',
            'slug.unique' => 'This URL slug already exists',
            'content.required' => 'Blog content is required',
            'thumbnail_image.image' => 'Thumbnail must be an image',
            'banner_image.image' => 'Banner image must be an image',
            'canonical_url.url' => 'Please enter a valid URL',
            'schema_markup.json' => 'Schema markup must be valid JSON',
        ];
    }
}
