@extends('layouts.frontend')

@section('title', 'Blogs | BlogSpace')

@section('content')
    @php
        $search = '';
        $sort = 'newest';
        $blogs = [
            [
                'id' => 1,
                'title' => 'Getting Started with Laravel: A Complete Beginner Guide',
                'description' =>
                    'Learn the fundamentals of Laravel and discover how this powerful PHP framework helps developers build modern, scalable and reliable web applications.',
                'image' =>
                    'https://images.unsplash.com/photo-1499750310107-5fef28a66643?auto=format&fit=crop&w=1400&q=80',
                'views' => 1200,
                'likes' => 230,
                'comments' => 45,
            ],
            [
                'id' => 2,
                'title' => 'The Future of Digital Innovation',
                'description' =>
                    'Explore the latest trends, ideas and technologies that are shaping the digital world and creating new opportunities for businesses.',
                'image' =>
                    'https://images.unsplash.com/photo-1516321318423-f06f85e504b3?auto=format&fit=crop&w=900&q=80',
                'views' => 980,
                'likes' => 186,
                'comments' => 28,
            ],
            [
                'id' => 3,
                'title' => 'Exploring the Wonders of Space',
                'description' =>
                    'A journey through the mysteries of space and discoveries that inspire scientists and curious minds around the world.',
                'image' =>
                    'https://images.unsplash.com/photo-1446776811953-f506d9d4e4d3?auto=format&fit=crop&w=900&q=80',
                'views' => 2400,
                'likes' => 412,
                'comments' => 67,
            ],
            [
                'id' => 4,
                'title' => 'Essential Skills Every Developer Should Learn',
                'description' =>
                    'Explore important technical and problem-solving skills that can help developers grow and build better software applications.',
                'image' =>
                    'https://images.unsplash.com/photo-1516321165247-4aa89a48be28?auto=format&fit=crop&w=900&q=80',
                'views' => 1800,
                'likes' => 320,
                'comments' => 54,
            ],
            [
                'id' => 5,
                'title' => 'Travel Experiences That Inspire New Perspectives',
                'description' =>
                    'Discover how exploring new places, meeting people and experiencing different cultures can change the way we see the world.',
                'image' =>
                    'https://images.unsplash.com/photo-1500534314209-a25ddb9d4e3f?auto=format&fit=crop&w=900&q=80',
                'views' => 1500,
                'likes' => 278,
                'comments' => 42,
            ],
            [
                'id' => 6,
                'title' => 'How Artificial Intelligence Is Shaping Our Future',
                'description' =>
                    'Learn how artificial intelligence is influencing industries, businesses, technology and everyday life in the modern digital world.',
                'image' =>
                    'https://images.unsplash.com/photo-1451187580459-43490279c0fa?auto=format&fit=crop&w=900&q=80',
                'views' => 3100,
                'likes' => 589,
                'comments' => 91,
            ],
        ];
    @endphp
    <section class="blog-hero">
        <div class="hero-glow hero-glow-one"></div>
        <div class="hero-glow hero-glow-two"></div>
        <div class="container">
            <div class="row justify-content-center text-center">
                <div class="col-lg-9">
                    <div class="hero-badge"><i class="bi bi-stars"></i> Discover ideas and stories</div>
                    <h1>Explore <span>Amazing Blogs</span></h1>
                    <p>Discover fresh ideas, useful insights and stories worth reading.</p>
                    <form class="hero-search" action="{{ url('/') }}" method="GET" role="search">
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
                <form action="{{ url('/') }}" method="GET" class="sort-wrapper">
                    <input type="hidden" name="search" value="{{ $search }}"><i class="bi bi-arrow-down-up"></i>
                    <label class="visually-hidden" for="blog-sort">Sort blogs</label>
                    <select id="blog-sort" class="form-select blog-sort" name="sort" onchange="this.form.submit()">
                        <option value="newest" @selected($sort === 'newest')>Newest First</option>
                        <option value="oldest" @selected($sort === 'oldest')>Oldest First</option>
                        <option value="az" @selected($sort === 'az')>A-Z Order</option>
                    </select>
                </form>
            </div>
            @if ($blogs)
                <div class="row g-4">
                    @foreach ($blogs as $blog)
                        <div class="col-lg-4 col-md-6">
                            <article class="blog-card h-100">
                                <a href="{{ route('blog-detail', $blog['id']) }}" class="blog-image"><img
                                        src="{{ $blog['image'] }}" alt="{{ $blog['title'] }}"></a>
                                <div class="blog-card-content">
                                    <h3 class="blog-card-title"><a
                                            href="{{ route('blog-detail', $blog['id']) }}">{{ $blog['title'] }}</a></h3>
                                    <p class="blog-card-description">{{ $blog['description'] }}</p>
                                    <div class="blog-stats" aria-label="Blog statistics"><span><i class="bi bi-eye"></i>
                                            {{ $blog['views'] >= 1000 ? number_format($blog['views'] / 1000, 1) . 'K' : $blog['views'] }}</span><span><i
                                                class="bi bi-heart"></i> {{ $blog['likes'] }}</span><span><i
                                                class="bi bi-chat-dots"></i> {{ $blog['comments'] }}</span></div>
                                </div>
                            </article>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="blog-empty-state"><i class="bi bi-search"></i>
                    <h3>No blogs found</h3>
                    <p>Try a different search term or <a href="{{ url('/') }}">view all blogs</a>.</p>
                </div>
            @endif
        </div>
    </section>
@endsection
