@extends('layouts.frontend')

@section('title', 'Blogs | BlogSpace')

@section('content')
    <section class="blog-hero">
        <div class="hero-glow hero-glow-one"></div>
        <div class="hero-glow hero-glow-two"></div>
        <div class="container">
            <div class="row justify-content-center text-center">
                <div class="col-lg-9">
                    <div class="hero-badge"><i class="bi bi-stars"></i> Discover ideas and stories</div>
                    <h1>Explore <span>Amazing Blogs</span></h1>
                    <p>Discover fresh ideas, useful insights and stories worth reading.</p>
                    <form class="hero-search" action="{{ route('home') }}" method="GET" role="search">
                        <div class="search-icon"><i class="bi bi-search"></i></div>
                        <input type="search" name="search" class="form-control" value="{{ $search }}"
                            placeholder="Search blogs..." aria-label="Search blogs">
                        <input type="hidden" name="sort" value="{{ $sort }}">
                        <div class="search-enter" aria-hidden="true">Press Enter</div>
                    </form>
                </div>
            </div>
        </div>
    </section>

    <section class="blog-list-section">
        <div class="container">
            <div class="blog-list-header">
                <div>
                    <div class="section-label"><span></span> EXPLORE</div>
                    <h2>All Blogs</h2>
                    <p>{{ $search ? 'Search results for “' . $search . '”' : 'Explore our collection and discover something interesting.' }}
                    </p>
                </div>
                <form action="{{ route('home') }}" method="GET" class="sort-wrapper">
                    <input type="hidden" name="search" value="{{ $search }}"><i class="bi bi-arrow-down-up"></i>
                    <label class="visually-hidden" for="blog-sort">Sort blogs</label>
                    <select id="blog-sort" class="form-select blog-sort" name="sort" onchange="this.form.submit()">
                        <option value="newest" @selected($sort === 'newest')>Newest First</option>
                        <option value="oldest" @selected($sort === 'oldest')>Oldest First</option>
                        <option value="az" @selected($sort === 'az')>A-Z Order</option>
                    </select>
                </form>
            </div>
            @if ($blogs->isNotEmpty())
                <div class="row g-4">
                    @foreach ($blogs as $blog)
                        <div class="col-lg-4 col-md-6">
                            <article class="blog-card h-100">
                                <a href="{{ route('blog-detail', $blog->slug) }}" class="blog-image"><img
                                        src="{{ $blog->imageUrl() }}" alt="{{ $blog->title }}"></a>
                                <div class="blog-card-content">
                                    <h3 class="blog-card-title"><a
                                            href="{{ route('blog-detail', $blog->slug) }}">{{ $blog->title }}</a></h3>
                                    <p class="blog-card-description">{{ $blog->description }}</p>
                                    <div class="blog-stats" aria-label="Blog statistics"><span><i class="bi bi-eye"></i>
                                            {{ $blog->view_count >= 1000 ? number_format($blog->view_count / 1000, 1) . 'K' : (int) $blog->view_count }}</span><span><i
                                                class="bi bi-heart"></i> {{ (int) $blog->like_count }}</span><span><i
                                                class="bi bi-chat-dots"></i> {{ (int) $blog->comment_count }}</span></div>
                                </div>
                            </article>
                        </div>
                    @endforeach
                </div>
                <div class="mt-5">
                    {{ $blogs->links() }}
                </div>
            @else
                <div class="blog-empty-state"><i class="bi bi-search"></i>
                    <h3>No blogs found</h3>
                    <p>Try a different search term or <a href="{{ route('home') }}">view all blogs</a>.</p>
                </div>
            @endif
        </div>
    </section>
@endsection
