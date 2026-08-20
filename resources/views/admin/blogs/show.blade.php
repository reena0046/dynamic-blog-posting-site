@php
    $empty = function ($value) {
        return filled($value) ? $value : 'Not available';
    };
    $schemaJson = $blog->schema_markup
        ? json_encode($blog->schema_markup, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE)
        : null;
    $tags = $blog->tagList();
    $thumbnailUrl = $blog->thumbnail_image ? asset(Storage::url($blog->thumbnail_image)) : null;
    $bannerUrl = $blog->banner_image ? asset(Storage::url($blog->banner_image)) : null;
@endphp

<div class="admin-blog-show">
    <div class="row g-3 mb-3">
        <div class="col-md-6">
            <div class="blog-show-label">Thumbnail Image</div>
            @if ($thumbnailUrl)
                <img src="{{ $thumbnailUrl }}" alt="{{ $blog->title }}" class="blog-show-image">
            @else
                <p class="blog-show-empty mb-0">Not available</p>
            @endif
        </div>
        <div class="col-md-6">
            <div class="blog-show-label">Banner Image</div>
            @if ($bannerUrl)
                <img src="{{ $bannerUrl }}" alt="{{ $blog->title }}" class="blog-show-image">
            @else
                <p class="blog-show-empty mb-0">Not available</p>
            @endif
        </div>
    </div>

    <div class="blog-show-row">
        <div class="blog-show-label">Title</div>
        <div>{{ $empty($blog->title) }}</div>
    </div>

    <div class="blog-show-row">
        <div class="blog-show-label">Description</div>
        <div>{{ $empty($blog->description) }}</div>
    </div>

    <div class="blog-show-row">
        <div class="blog-show-label">URL Slug</div>
        <div>{{ $empty($blog->slug) }}</div>
    </div>

    <div class="blog-show-row">
        <div class="blog-show-label">Status</div>
        <div>{{ $empty($blog->status) }}</div>
    </div>

    <div class="blog-show-row">
        <div class="blog-show-label">Tags</div>
        <div>
            @if (count($tags))
                @foreach ($tags as $tag)
                    <span class="blog-show-tag">{{ $tag }}</span>
                @endforeach
            @else
                <span class="blog-show-empty">Not available</span>
            @endif
        </div>
    </div>

    <div class="blog-show-row">
        <div class="blog-show-label">SEO Title</div>
        <div>{{ $empty($blog->seo_title) }}</div>
    </div>

    <div class="blog-show-row">
        <div class="blog-show-label">SEO Description</div>
        <div>{{ $empty($blog->seo_description) }}</div>
    </div>

    <div class="blog-show-row">
        <div class="blog-show-label">Canonical Tag</div>
        <div>{{ $empty($blog->canonical_url) }}</div>
    </div>

    <div class="blog-show-row">
        <div class="blog-show-label">Schema Markup</div>
        @if ($schemaJson)
            <pre class="blog-show-schema">{{ $schemaJson }}</pre>
        @else
            <div class="blog-show-empty">Not available</div>
        @endif
    </div>

    <div class="blog-show-row mb-0">
        <div class="blog-show-label">Blog Content</div>
        <div class="blog-show-content">
            @if (filled($blog->content))
                {!! $blog->content !!}
            @else
                <span class="blog-show-empty">Not available</span>
            @endif
        </div>
    </div>
</div>
