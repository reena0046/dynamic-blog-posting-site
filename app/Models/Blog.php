<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
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

    public function comments(): HasMany
    {
        return $this->hasMany(Comment::class)->oldest();
    }

    public function likes(): HasMany
    {
        return $this->hasMany(BlogLike::class);
    }

    public function isLikedBy(?User $user): bool
    {
        if (! $user) {
            return false;
        }

        return $this->likes()->where('user_id', $user->id)->exists();
    }

    /**
     * Estimated reading time from content. Minimum 1 minute.
     */
    public function readingTime(): string
    {
        $words = str_word_count(strip_tags((string) $this->content));

        return max(1, (int) ceil($words / 200)) . ' min read';
    }

    /**
     * Publisher shown on the public detail page.
     */
    public function publisher(): ?User
    {
        return User::query()->where('is_admin', true)->orderBy('id')->first();
    }

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
            ->exists()
        ) {
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
            if ($this->thumbnail_image && Storage::disk('public')->exists($this->thumbnail_image)) {
                Storage::disk('public')->delete($this->thumbnail_image);
            }

            $this->thumbnail_image = Storage::disk('public')->put(
                'blog/thumbnails',
                $request->file('thumbnail_image')
            );
        }

        if ($request->hasFile('banner_image')) {
            if ($this->banner_image && Storage::disk('public')->exists($this->banner_image)) {
                Storage::disk('public')->delete($this->banner_image);
            }

            $this->banner_image = Storage::disk('public')->put(
                'blog/banners',
                $request->file('banner_image')
            );
        }

        return $this;
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
     * Thumbnail URL for blog listing cards (falls back to banner).
     */
    public function thumbnailUrl()
    {
        $path = $this->thumbnail_image;

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
     * Heading ids and table of contents generated from current HTML content.
     * Original database content is not changed.
     */
    public function processedContent()
    {
        if (isset($this->processedContentCache)) {
            return $this->processedContentCache;
        }

        $html = $this->normalizedContentHtml();

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

        // Snapshot nodes first — live NodeList shifts while attributes are updated.
        $headingNodes = [];
        foreach ($headings as $heading) {
            $headingNodes[] = $heading;
        }

        foreach ($headingNodes as $heading) {
            $title = trim(preg_replace('/\s+/u', ' ', $heading->textContent) ?? '');

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
     * Normalize stored blog HTML so escaped tags become real markup for display/TOC.
     */
    protected function normalizedContentHtml(): string
    {
        $html = trim((string) $this->content);

        if ($html === '') {
            return '';
        }

        // Content saved with entity-encoded tags (e.g. &lt;h2&gt;Title&lt;/h2&gt;).
        if (preg_match('/&lt;\s*\/?\s*h[1-6]\b/i', $html) || preg_match('/&lt;\s*\/?\s*p\b/i', $html)) {
            $html = html_entity_decode($html, ENT_QUOTES | ENT_HTML5, 'UTF-8');
        }

        $html = clean($html, [
            'AutoFormat.AutoParagraph' => false,
            'AutoFormat.RemoveEmpty' => false,
        ]);

        // Remove empty paragraphs left after decoder/sanitizer moves block tags out.
        $html = preg_replace('/<p>(?:\s|&nbsp;)*<\/p>/i', '', $html) ?? $html;

        return trim($html);
    }

    /**
     * Table of Contents items from h2 headings.
     * Empty when the content has no h2 headings.
     */
    public function tableOfContents()
    {
        return $this->processedContent()['toc'];
    }

    /**
     * Blog HTML with unique ids on each h2 heading.
     */
    public function contentWithHeadingIds()
    {
        return $this->processedContent()['html'];
    }

    /**
     * Other published blogs that share one or more tags.
     * The current blog is never included.
     */
    public function relatedBlogs($limit = 4)
    {
        $tags = $this->tagList();

        if (empty($tags)) {
            return collect();
        }

        $normalizedTags = array_map('mb_strtolower', $tags);

        $candidates = static::query()
            ->where('status', 'ACTIVE')
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
