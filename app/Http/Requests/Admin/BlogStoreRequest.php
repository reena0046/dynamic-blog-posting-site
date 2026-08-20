<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class BlogStoreRequest extends FormRequest
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
            'slug' => 'nullable|string|max:255|unique:blogs,slug',
            'content' => 'required',
            'thumbnail_image' => 'required|image|mimes:jpg,jpeg,png,webp|max:2048|dimensions:width=700,height=430',
            'banner_image' => 'required|image|mimes:jpg,jpeg,png,webp|max:4096|dimensions:width=1140,height=420',
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
            'thumbnail_image.required' => 'Thumbnail image is required',
            'thumbnail_image.image' => 'Thumbnail must be an image',
            'thumbnail_image.mimes' => 'Thumbnail must be a JPG, PNG, or WebP image',
            'thumbnail_image.max' => 'Thumbnail must not be larger than 2 MB',
            'thumbnail_image.dimensions' => 'Thumbnail image must be exactly 700 × 430 px',
            'banner_image.required' => 'Banner image is required',
            'banner_image.image' => 'Banner image must be an image',
            'banner_image.mimes' => 'Banner must be a JPG, PNG, or WebP image',
            'banner_image.max' => 'Banner must not be larger than 4 MB',
            'banner_image.dimensions' => 'Banner image must be exactly 1140 × 420 px',
            'canonical_url.url' => 'Please enter a valid URL',
            'schema_markup.json' => 'Schema markup must be valid JSON',
        ];
    }
}
