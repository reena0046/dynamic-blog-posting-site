@extends('layouts.admin')

@section('title', 'Users')

@section('content')
    <div class="page-header">
        <div>
            <h1>User Management</h1>
            <p class="page-header-sub">View and manage registered BlogSpace users.</p>
        </div>
    </div>

    <div class="card w-100 blog-list-card">
        <div class="card-header blog-list-header">
            <h5 class="mb-0">Users</h5>
        </div>
        <div class="card-body blog-list-body">
            <div class="table-responsive">
                <table class="table border table-sm table-bordered mb-0 align-middle users-table">
                    <thead>
                        <tr>
                            <th width="60" class="text-center">#</th>
                            <th>Name</th>
                            <th>Email</th>
                            <th class="text-center">Comments</th>
                            <th class="text-center">Likes</th>
                            <th class="text-center">Joined</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($users as $index => $user)
                            <tr>
                                <td class="text-center">{{ $index + 1 }}</td>
                                <td>
                                    <a href="{{ route('admin.users.show', $user) }}" class="user-cell user-name-link">
                                        @include('partials.user-avatar', [
                                            'user' => $user,
                                            'class' => 'user-avatar-sm',
                                        ])
                                        <span class="user-name">{{ $user->name }}</span>
                                    </a>
                                </td>
                                <td>{{ $user->email }}</td>
                                <td class="text-center">{{ number_format($user->comments_count) }}</td>
                                <td class="text-center">{{ number_format($user->likes_count) }}</td>
                                <td class="text-center">{{ optional($user->created_at)->format('M d, Y') }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center text-muted py-4">No users found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
