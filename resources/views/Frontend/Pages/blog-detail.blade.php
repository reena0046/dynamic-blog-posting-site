@extends('layouts.frontend')

@php
    $blogTitle = $blog->title;
    $blogImage = $blog->imageUrl();
    $blogViews = (int) $blog->view_count;
    $blogLikes = (int) $blog->like_count;
    $blogCommentsCount = $comments->count();
    $blogAuthor = $author->name ?? 'Admin';
    $blogAuthorRole = 'BlogSpace Writer';
    $blogPublishedAt = $blog->created_at;
    $blogReadTime = $blog->readingTime();
    $commentAvatarColors = ['', 'avatar-blue', 'avatar-green'];
@endphp

@section('title', ($blog->seo_title ?: $blog->title) . ' | BlogSpace')
@section('meta_description', $blog->seo_description ?: \Illuminate\Support\Str::limit(strip_tags((string) $blog->description), 160))
@section('canonical', $blog->canonical_url ?: url()->current())

@push('head')
    @if ($blog->schema_markup)
        <script type="application/ld+json">{!! json_encode($blog->schema_markup, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) !!}</script>
    @endif
@endpush

@section('content')

    <!-- ========================= BREADCRUMB ========================== -->

    <section class="blog-breadcrumb">
        <div class="container">
            <nav aria-label="Breadcrumb">
                <a href="/">Home</a>
                <i class="bi bi-chevron-right"></i>
                <a href="{{ url('/') }}">Blogs</a>
                <i class="bi bi-chevron-right"></i>
                <span>{{ $blogTitle }}</span>
            </nav>
        </div>
    </section>

    <!-- ========================= BLOG DETAIL ========================== -->

    <section class="blog-detail-section">
        <div class="container">
            <div class="blog-detail-main">

                <!-- BANNER IMAGE -->
                <div class="blog-detail-banner">
                    <img src="{{ $blogImage }}" alt="{{ $blogTitle }}">
                </div>

                <!-- HEADER -->
                <div class="blog-detail-header">
                    <h1>{{ $blogTitle }}</h1>
                </div>

                <!-- VIEWS / LIKES -->
                <div class="blog-detail-stats">
                    <div class="detail-stat-list">
                        <span>
                            <i class="bi bi-eye"></i>
                            {{ $blogViews >= 1000 ? number_format($blogViews / 1000, 1) . 'K' : $blogViews }} Views
                        </span>
                        <span id="blog-like-count">
                            <i class="bi bi-heart"></i>
                            <span id="blog-like-count-value">{{ $blogLikes }}</span> Likes
                        </span>
                    </div>

                    @auth
                        <form id="blog-like-form" action="{{ route('blogs.like', ['slug' => $blog->slug]) }}" method="POST">
                            @csrf
                            <button type="submit" id="blog-like-btn"
                                class="detail-like-btn {{ $isLiked ? 'is-liked' : '' }}"
                                aria-label="{{ $isLiked ? 'Unlike' : 'Like' }}">
                                <i class="bi {{ $isLiked ? 'bi-heart-fill' : 'bi-heart' }}"></i>
                            </button>
                        </form>
                    @else
                        <a href="{{ route('login') }}" class="detail-login-like">
                            <i class="bi bi-box-arrow-in-right"></i>
                            Login to Like
                        </a>
                    @endauth
                </div>

                <!-- AUTHOR DETAILS -->
                <div class="author-card">
                    @if ($author)
                        @include('partials.user-avatar', [
                            'user' => $author,
                            'class' => 'author-avatar',
                        ])
                    @else
                        <div class="author-avatar">
                            {{ collect(explode(' ', $blogAuthor))->map(fn($part) => mb_substr($part, 0, 1))->join('') }}
                        </div>
                    @endif

                    <div class="author-info">
                        <span>Published by</span>
                        <h2>{{ $blogAuthor }}</h2>
                        <p>{{ $blogAuthorRole }}</p>
                    </div>

                    <div class="author-meta">
                        <span>
                            <i class="bi bi-calendar3"></i>
                            {{ \Carbon\Carbon::parse($blogPublishedAt)->format('F j, Y') }}
                        </span>
                        <span>
                            <i class="bi bi-clock"></i>
                            {{ $blogReadTime }}
                        </span>
                    </div>
                </div>

                @if (count($tocItems) > 0)
                    <div class="table-of-contents">

                        <div class="toc-title">
                            <i class="bi bi-list-ul"></i>
                            <h2>Table of Contents</h2>
                        </div>

                        <ul>
                            @foreach ($tocItems as $index => $item)
                                <li>
                                    <a href="#{{ $item['id'] }}">
                                        <span>{{ $index + 1 }}</span>
                                        {{ $item['title'] }}
                                    </a>
                                </li>
                            @endforeach
                        </ul>

                    </div>
                @else
                    <div class="blog-content-heading">
                        <h2>Blog Content</h2>
                    </div>
                @endif

                <article class="blog-content">
                    {!! $blogContent !!}
                </article>

                @if ($relatedBlogs->isNotEmpty())
                    <section class="related-blogs-section">

                        <div class="detail-search-section">
                            <div class="detail-search-header">
                                <h2>Search Related Articles</h2>
                                <p>Find an article from the recommendations below.</p>
                            </div>

                            <form id="related-blogs-search-form" role="search">
                                <div class="detail-search-row">
                                    <div class="hero-search related-hero-search">
                                        <div class="search-icon"><i class="bi bi-search"></i></div>
                                        <input type="search" id="related-blogs-search" class="form-control"
                                            placeholder="Search blogs..." aria-label="Search related blogs"
                                            autocomplete="off">
                                        <div class="search-enter" aria-hidden="true">Press Enter</div>
                                    </div>

                                    <div class="detail-sort-wrapper">
                                        <i class="bi bi-arrow-down-up"></i>
                                        <label class="visually-hidden" for="related-blogs-sort">Sort blogs</label>
                                        <select id="related-blogs-sort">
                                            <option value="newest" selected>Newest First</option>
                                            <option value="oldest">Oldest First</option>
                                            <option value="az">A-Z Order</option>
                                        </select>
                                    </div>
                                </div>
                            </form>
                        </div>

                        <div class="section-heading">
                            <span class="section-label"><span></span> KEEP READING</span>
                            <h2>Related Blogs</h2>
                            <p>You may also like these articles.</p>
                        </div>

                        <div class="row g-4" id="related-blogs-grid">
                            @foreach ($relatedBlogs as $relatedBlog)
                                <div class="col-lg-4 col-md-6 related-blog-item"
                                    data-title="{{ mb_strtolower($relatedBlog->title) }}"
                                    data-description="{{ mb_strtolower((string) $relatedBlog->description) }}"
                                    data-created="{{ optional($relatedBlog->created_at)->timestamp ?? 0 }}"
                                    data-id="{{ $relatedBlog->id }}">
                                    <a href="{{ route('blogs.show', ['slug' => $relatedBlog->slug]) }}"
                                        class="blog-card related-blog-card h-100">
                                        <div class="blog-image">
                                            <img src="{{ $relatedBlog->thumbnailUrl() ?: $relatedBlog->imageUrl() }}"
                                                alt="{{ $relatedBlog->title }}">
                                        </div>
                                        <div class="blog-card-content">
                                            <h3 class="blog-card-title">{{ $relatedBlog->title }}</h3>
                                            <p class="blog-card-description">{{ $relatedBlog->description }}</p>
                                            <div class="blog-stats" aria-label="Blog statistics">
                                                <span><i class="bi bi-eye"></i>
                                                    {{ $relatedBlog->view_count >= 1000 ? number_format($relatedBlog->view_count / 1000, 1) . 'K' : (int) $relatedBlog->view_count }}</span>
                                                <span><i class="bi bi-heart"></i>
                                                    {{ (int) $relatedBlog->like_count }}</span>
                                                <span><i class="bi bi-chat-dots"></i>
                                                    {{ (int) $relatedBlog->comment_count }}</span>
                                            </div>
                                        </div>
                                    </a>
                                </div>
                            @endforeach
                        </div>

                        <div class="explore-empty-state mt-3" id="related-blogs-empty" hidden>
                            <i class="bi bi-search"></i>
                            <p>No blogs found.</p>
                        </div>

                    </section>
                @endif

                <!-- COMMENTS -->
                <section class="comments-section" id="comments">

                    <div class="comments-heading">
                        <span class="section-label"><span></span> DISCUSSION</span>
                        <h2>Comments</h2>
                        <p>{{ $blogCommentsCount }} comments on this article</p>
                    </div>

                    @auth
                        <div class="comment-form-card">
                            <h3>Leave a Comment</h3>
                            <form action="{{ route('blogs.comments.store', ['slug' => $blog->slug]) }}" method="POST">
                                @csrf
                                <textarea name="comment" rows="5" placeholder="Write your comment...">{{ old('comment') }}</textarea>
                                @error('comment')
                                    <div class="text-danger small mt-2">{{ $message }}</div>
                                @enderror
                                <div class="comment-form-action">
                                    <button type="submit">
                                        Post Comment <i class="bi bi-send"></i>
                                    </button>
                                </div>
                            </form>
                        </div>
                    @else
                        <div class="login-comment-message">
                            <i class="bi bi-lock"></i>
                            <div>
                                <strong>Want to join the discussion?</strong>
                                <p>Please login to post a comment.</p>
                            </div>
                            <a href="{{ route('login') }}">Login</a>
                        </div>
                    @endauth

                    @foreach ($comments->unique('id') as $comment)
                        @continue(!$comment->user)
                        <div class="comment-item">

                            @include('partials.user-avatar', [
                                'user' => $comment->user,
                                'class' => 'comment-avatar',
                                'extraClass' => $commentAvatarColors[$loop->index % 3],
                            ])

                            <div class="comment-content">
                                <div class="comment-top">
                                    <h3>{{ $comment->user->name }}</h3>
                                    <span>{{ $comment->created_at->diffForHumans() }}</span>
                                </div>
                                <p>{{ $comment->body }}</p>
                            </div>

                        </div>
                    @endforeach

                </section>

            </div>
        </div>
    </section>

@endsection

@push('scripts')
    <script>
        $(function() {
            var $form = $('#related-blogs-search-form');
            var $search = $('#related-blogs-search');
            var $sort = $('#related-blogs-sort');
            var $grid = $('#related-blogs-grid');
            var $empty = $('#related-blogs-empty');

            function applyRelatedBlogs() {
                if (!$grid.length) {
                    return;
                }

                var query = $.trim($search.val() || '').toLowerCase();
                var sort = $sort.val();
                var $items = $grid.find('.related-blog-item').get();

                $items.sort(function(a, b) {
                    var $a = $(a);
                    var $b = $(b);

                    if (sort === 'oldest') {
                        return (Number($a.data('created')) - Number($b.data('created')))
                            || (Number($a.data('id')) - Number($b.data('id')));
                    }

                    if (sort === 'az') {
                        return String($a.data('title') || '').localeCompare(String($b.data('title') || ''));
                    }

                    return (Number($b.data('created')) - Number($a.data('created')))
                        || (Number($b.data('id')) - Number($a.data('id')));
                });

                $.each($items, function(_, item) {
                    $grid.append(item);
                });

                var visibleCount = 0;

                $grid.find('.related-blog-item').each(function() {
                    var $item = $(this);
                    var title = String($item.data('title') || '');
                    var description = String($item.data('description') || '');
                    var match = query === '' || title.indexOf(query) !== -1 || description.indexOf(query) !== -1;

                    $item.toggle(match);

                    if (match) {
                        visibleCount++;
                    }
                });

                $grid.toggle(visibleCount > 0);
                $empty.prop('hidden', visibleCount > 0);
            }

            if ($form.length) {
                $form.on('submit', function(e) {
                    e.preventDefault();
                    applyRelatedBlogs();
                });

                $sort.on('change', applyRelatedBlogs);

                $search.on('search', function() {
                    applyRelatedBlogs();
                });
            }

            @auth
            $('#blog-like-form').on('submit', function(e) {
                e.preventDefault();

                var $btn = $('#blog-like-btn');

                if ($btn.prop('disabled')) {
                    return;
                }

                $btn.prop('disabled', true);

                $.ajax({
                    url: $(this).attr('action'),
                    type: 'POST',
                    data: {
                        _token: $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(data) {
                        if (data.status === 'success') {
                            if (data.liked) {
                                $btn.addClass('is-liked');
                                $btn.find('i').attr('class', 'bi bi-heart-fill');
                                $btn.attr('aria-label', 'Unlike');
                            } else {
                                $btn.removeClass('is-liked');
                                $btn.find('i').attr('class', 'bi bi-heart');
                                $btn.attr('aria-label', 'Like');
                            }

                            $('#blog-like-count-value').text(data.like_count);
                        }
                    },
                    error: function() {
                        toastr.error('Something went wrong!');
                    },
                    complete: function() {
                        $btn.prop('disabled', false);
                    }
                });
            });
            @endauth
        });
    </script>
@endpush
