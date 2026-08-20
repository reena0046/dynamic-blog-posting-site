@extends('layouts.admin')

@section('title', $user->name)

@section('content')
    <div class="user-profile-banner">
        <div class="user-profile-banner-top">
            <div class="user-profile-banner-identity">
                @include('partials.user-avatar', ['user' => $user, 'class' => 'user-avatar-lg'])
                <div>
                    <h1>{{ $user->name }}</h1>
                    <p>{{ $user->email }}</p>
                </div>
            </div>
            <a href="{{ route('admin.users.index') }}" class="cms-back-btn">
                <i class="fas fa-arrow-left"></i> Back
            </a>
        </div>

        <div class="user-profile-banner-stats">
            <div class="user-profile-banner-stat">
                <i class="fas fa-calendar-alt"></i>
                <span>Joined {{ optional($user->created_at)->format('M d, Y') }}</span>
            </div>
            <div class="user-profile-banner-stat">
                <i class="fas fa-heart"></i>
                <span>{{ number_format($user->likes_count) }} Likes</span>
            </div>
            <div class="user-profile-banner-stat">
                <i class="fas fa-comment"></i>
                <span>{{ number_format($user->comments_count) }} Comments</span>
            </div>
        </div>
    </div>

    <section class="user-activity-panel">
        <div class="user-activity-panel-head">
            <h5>
                Activity ({{ number_format($activities->total()) }}
                {{ $activities->total() === 1 ? 'Blog' : 'Blogs' }})
            </h5>
        </div>

        <div class="user-activity-panel-body">
            @forelse ($activities as $activity)
                <div class="user-blog-activity-card">
                    <div class="user-blog-activity-main">
                        <div class="user-liked-icon user-liked-icon-sm">
                            @if ($activity->blog && $activity->blog->thumbnailUrl())
                                <img src="{{ $activity->blog->thumbnailUrl() }}" alt=""
                                    onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                                <i class="fas fa-file-alt" style="display:none;"></i>
                            @else
                                <i class="fas fa-file-alt"></i>
                            @endif
                        </div>
                        <div class="user-liked-content">
                            <div class="user-liked-title">
                                {{ $activity->blog->title ?? 'Deleted blog' }}
                            </div>
                            <div class="user-blog-activity-flags">
                                @if ($activity->liked)
                                    <span class="user-blog-activity-flag is-liked">
                                        <i class="fas fa-heart"></i> Liked
                                    </span>
                                @endif
                                @if ($activity->liked && $activity->comments_count > 0)
                                    <span class="user-blog-activity-sep">·</span>
                                @endif
                                @if ($activity->comments_count > 0)
                                    <span class="user-blog-activity-flag is-commented">
                                        <i class="fas fa-comment"></i>
                                        {{ $activity->comments_count }}
                                        {{ $activity->comments_count === 1 ? 'Comment' : 'Comments' }}
                                    </span>
                                @endif
                            </div>
                        </div>
                    </div>

                    @if ($activity->comments_count > 0)
                        <div class="user-activity-comment-list">
                            @foreach ($activity->comments as $comment)
                                <div class="user-activity-comment-row">
                                    <span class="user-activity-comment-text">{{ $comment->body }}</span>
                                    <span class="user-activity-comment-date">
                                        {{ optional($comment->created_at)->format('M d, Y') }}
                                    </span>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>
            @empty
                <p class="user-activity-empty mb-0">No activity yet</p>
            @endforelse

            @if ($activities->hasPages())
                <div class="user-activity-pagination">
                    {{ $activities->onEachSide(1)->links('pagination::bootstrap-5') }}
                </div>
            @endif
        </div>
    </section>
@endsection
