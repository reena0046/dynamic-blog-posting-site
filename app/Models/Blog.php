<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class Blog extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'slug',
        'content',

        'thumbnail_image',
        'banner_image',

        'seo_title',
        'seo_description',
        'canonical_url',
        'schema_markup',

        'tags',
        'view_count',
        'like_count',
        'comment_count',
        'status',
    ];

    protected $casts = [
        'schema_markup' => 'array',
    ];

    /**
     * Display-time cache for heading ids and TOC. Not stored in the database.
     */
    protected $processedContentCache;

    /**
     * Generate a unique slug from a custom value or the title.
     */
    public function applySlug($request)
    {
        $base = Str::slug($request->slug ?: $request->title) ?: 'blog';
        $slug = $base;
        $suffix = 2;

        while (static::query()
            ->where('slug', $slug)
            ->when($this->exists, function ($query) {
                $query->where('id', '!=', $this->id);
            })
            ->exists()) {
            $slug = $base . '-' . $suffix;
            $suffix++;
        }

        $this->slug = $slug;

        return $this;
    }

    /**
     * Decode JSON-LD schema markup from the form.
     */
    public function applySchemaMarkup($request)
    {
        $value = $request->schema_markup;

        if (is_array($value)) {
            $this->schema_markup = $value ?: null;
        } else {
            $this->schema_markup = $value
                ? json_decode($value, true)
                : null;
        }

        return $this;
    }

    /**
     * Store thumbnail and banner images.
     */
    public function storeMedia($request)
    {
        if ($request->hasFile('thumbnail_image')) {
            $this->thumbnail_image = Storage::disk('public')->put(
                'blog/thumbnails',
                $request->file('thumbnail_image')
            );
        }

        if ($request->hasFile('banner_image')) {
            $this->banner_image = Storage::disk('public')->put(
                'blog/banners',
                $request->file('banner_image')
            );
        }

        return $this;
    }

    /**
     * Replace thumbnail and banner images when new files are uploaded.
     */
    public function updateMedia($request)
    {
        if ($request->hasFile('thumbnail_image')) {
            $this->deleteFile($this->thumbnail_image);
            $this->thumbnail_image = Storage::disk('public')->put(
                'blog/thumbnails',
                $request->file('thumbnail_image')
            );
        }

        if ($request->hasFile('banner_image')) {
            $this->deleteFile($this->banner_image);
            $this->banner_image = Storage::disk('public')->put(
                'blog/banners',
                $request->file('banner_image')
            );
        }

        return $this;
    }

    /**
     * Delete stored thumbnail and banner files.
     */
    public function deleteMedia()
    {
        $this->deleteFile($this->thumbnail_image);
        $this->deleteFile($this->banner_image);

        return $this;
    }

    protected function deleteFile(?string $path): void
    {
        if ($path) {
            Storage::disk('public')->delete($path);
        }
    }

    /**
     * Update blog status.
     */
    public function updateStatus($status)
    {
        $this->status = $status;
        $this->save();

        return $this;
    }

    /**
     * Public image URL for listing and detail pages.
     */
    public function imageUrl()
    {
        $path = $this->banner_image ?: $this->thumbnail_image;

        return $path ? asset(Storage::url($path)) : '';
    }

    /**
     * Tags as a trimmed list.
     */
    public function tagList()
    {
        return collect(explode(',', (string) $this->tags))
            ->map(function ($tag) {
                return trim($tag);
            })
            ->filter()
            ->values()
            ->all();
    }

    /**
     * Only published blogs.
     */
    public function scopeActive($query)
    {
        return $query->where('status', 'ACTIVE');
    }

    /**
     * Heading ids and table of contents generated from current HTML content.
     * Original database content is not changed.
     */
    public function processedContent()
    {
        if (isset($this->processedContentCache)) {
            return $this->processedContentCache;
        }

        $html = clean((string) $this->content, [
            'AutoFormat.AutoParagraph' => false,
            'AutoFormat.RemoveEmpty' => false,
        ]);

        if (trim($html) === '') {
            return $this->processedContentCache = [
                'html' => '',
                'toc' => [],
            ];
        }

        $dom = new \DOMDocument();
        libxml_use_internal_errors(true);
        $dom->loadHTML('<?xml encoding="UTF-8">' . $html, LIBXML_HTML_NODEFDTD);
        libxml_clear_errors();

        $headings = $dom->getElementsByTagName('h2');
        $usedIds = [];
        $toc = [];

        foreach ($headings as $heading) {
            $title = trim($heading->textContent);

            if ($title === '') {
                continue;
            }

            $baseId = Str::slug($title) ?: 'section';
            $id = $baseId;
            $suffix = 2;

            while (in_array($id, $usedIds, true)) {
                $id = $baseId . '-' . $suffix;
                $suffix++;
            }

            $usedIds[] = $id;
            $heading->setAttribute('id', $id);
            $toc[] = [
                'id' => $id,
                'title' => $title,
            ];
        }

        $body = $dom->getElementsByTagName('body')->item(0);
        $innerHtml = '';

        if ($body) {
            foreach ($body->childNodes as $child) {
                $innerHtml .= $dom->saveHTML($child);
            }
        }

        return $this->processedContentCache = [
            'html' => $innerHtml,
            'toc' => $toc,
        ];
    }

    /**
     * Table of Contents items from h2 headings.
     * Empty when there are fewer than 2 headings.
     */
    public function tableOfContents()
    {
        $toc = $this->processedContent()['toc'];

        return count($toc) >= 2 ? $toc : [];
    }

    /**
     * Blog HTML with unique ids on each h2 heading.
     */
    public function contentWithHeadingIds()
    {
        return $this->processedContent()['html'];
    }

    /**
     * Other blogs that share one or more tags, ranked by match count.
     * Status (ACTIVE / INACTIVE) is not used for related matching.
     */
    public function relatedBlogs($limit = 4)
    {
        $tags = $this->tagList();

        if (empty($tags)) {
            return collect();
        }

        $normalizedTags = array_map('mb_strtolower', $tags);

        $candidates = static::query()
            ->active()
            ->where('id', '!=', $this->id)
            ->where(function ($query) use ($tags) {
                foreach ($tags as $tag) {
                    $query->orWhere('tags', 'like', '%' . $tag . '%');
                }
            })
            ->get();

        return $candidates
            ->filter(function ($blog) use ($normalizedTags) {
                $blogTags = array_map('mb_strtolower', $blog->tagList());

                return count(array_intersect($normalizedTags, $blogTags)) > 0;
            })
            ->sortByDesc(function ($blog) use ($normalizedTags) {
                $blogTags = array_map('mb_strtolower', $blog->tagList());

                return count(array_intersect($normalizedTags, $blogTags));
            })
            ->take($limit)
            ->values();
    }
}
