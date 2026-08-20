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
                    <form class="hero-search" id="blog-search-form" role="search">
                        <div class="search-icon"><i class="bi bi-search"></i></div>
                        <input type="search" id="blog-search" class="form-control"
                            placeholder="Search blogs..." aria-label="Search blogs" autocomplete="off">
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
                    <p id="blog-list-subtitle">Explore our collection and discover something interesting.</p>
                </div>
                <div class="sort-wrapper">
                    <i class="bi bi-arrow-down-up"></i>
                    <label class="visually-hidden" for="blog-sort">Sort blogs</label>
                    <select id="blog-sort" class="form-select blog-sort">
                        <option value="newest" selected>Newest First</option>
                        <option value="oldest">Oldest First</option>
                        <option value="az">A-Z Order</option>
                    </select>
                </div>
            </div>
            <div class="row g-4" id="blog-grid" @if ($blogs->isEmpty()) hidden @endif>
                @foreach ($blogs as $blog)
                    <div class="col-lg-4 col-md-6 blog-grid-item"
                        data-title="{{ mb_strtolower($blog->title) }}"
                        data-description="{{ mb_strtolower((string) $blog->description) }}"
                        data-created="{{ optional($blog->created_at)->timestamp ?? 0 }}"
                        data-id="{{ $blog->id }}">
                        <article class="blog-card h-100">
                            <a href="{{ route('blogs.show', ['slug' => $blog->slug]) }}" class="blog-image"><img
                                    src="{{ $blog->thumbnailUrl() }}" alt="{{ $blog->title }}"></a>
                            <div class="blog-card-content">
                                <h3 class="blog-card-title"><a
                                        href="{{ route('blogs.show', ['slug' => $blog->slug]) }}">{{ $blog->title }}</a></h3>
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
            <div class="blog-empty-state" id="blog-empty-state" @if ($blogs->isNotEmpty()) hidden @endif><i class="bi bi-search"></i>
                <h3>No blogs found</h3>
                <p>Try a different search term or <a href="{{ route('home') }}" id="blog-clear-search">view all blogs</a>.</p>
            </div>
        </div>
    </section>
@endsection

@push('scripts')
    <script>
        (function () {
            const form = document.getElementById('blog-search-form');
            const searchInput = document.getElementById('blog-search');
            const sortSelect = document.getElementById('blog-sort');
            const grid = document.getElementById('blog-grid');
            const emptyState = document.getElementById('blog-empty-state');
            const subtitle = document.getElementById('blog-list-subtitle');
            const clearLink = document.getElementById('blog-clear-search');
            const defaultSubtitle = 'Explore our collection and discover something interesting.';

            if (!form || !searchInput || !sortSelect || !grid) {
                return;
            }

            function applyListing() {
                const query = searchInput.value.trim().toLowerCase();
                const sort = sortSelect.value;
                const cards = Array.from(grid.querySelectorAll('.blog-grid-item'));

                cards.sort(function (a, b) {
                    if (sort === 'oldest') {
                        return (Number(a.dataset.created) - Number(b.dataset.created))
                            || (Number(a.dataset.id) - Number(b.dataset.id));
                    }

                    if (sort === 'az') {
                        return (a.dataset.title || '').localeCompare(b.dataset.title || '');
                    }

                    return (Number(b.dataset.created) - Number(a.dataset.created))
                        || (Number(b.dataset.id) - Number(a.dataset.id));
                }).forEach(function (card) {
                    grid.appendChild(card);
                });

                let visibleCount = 0;

                cards.forEach(function (card) {
                    const matches = query === ''
                        || (card.dataset.title || '').includes(query)
                        || (card.dataset.description || '').includes(query);

                    card.hidden = !matches;

                    if (matches) {
                        visibleCount += 1;
                    }
                });

                const hasVisible = visibleCount > 0;
                grid.hidden = !hasVisible;
                emptyState.hidden = hasVisible;
                subtitle.textContent = query
                    ? 'Search results for “' + searchInput.value.trim() + '”'
                    : defaultSubtitle;
            }

            form.addEventListener('submit', function (event) {
                event.preventDefault();
                applyListing();
            });

            sortSelect.addEventListener('change', applyListing);

            clearLink?.addEventListener('click', function (event) {
                event.preventDefault();
                searchInput.value = '';
                applyListing();
            });
        })();
    </script>
@endpush
