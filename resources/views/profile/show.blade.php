@extends('layouts.app')

@section('content')
    <head>
        @vite('resources/css/profile.css')
    </head>

    <div class="profile-container">
        <!-- Profile Header Card -->
        <div class="profile-header-card">
            <div class="profile-header-gradient">
                <div class="profile-avatar-section">
                    <div class="profile-avatar">
                        <img src="https://img.icons8.com/bubbles/100/000000/user.png" alt="User Profile Image">
                    </div>
                    <h1 class="profile-name">{{ $user->name }}</h1>
                    <p class="profile-role">Student Developer</p>
                    <div class="profile-stats">
                        <div class="stat-item">
                            <span class="stat-number">{{ $questions->total() }}</span>
                            <span class="stat-label">Questions</span>
                        </div>
                        <div class="stat-divider"></div>
                        <div class="stat-item">
                            <span class="stat-number">{{ $replies->total() }}</span>
                            <span class="stat-label">Replies</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Profile Content Grid -->
        <div class="profile-content-grid">
            <!-- Information Card -->
            <div class="profile-info-card">
                <h2 class="section-title">
                    <svg class="section-icon" xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
                        <circle cx="12" cy="7" r="4"></circle>
                    </svg>
                    Information
                </h2>
                <div class="info-item">
                    <span class="info-label">Email</span>
                    <span class="info-value">{{ $user->email }}</span>
                </div>
                <div class="info-item">
                    <span class="info-label">Member Since</span>
                    <span class="info-value">{{ $user->created_at->format('M d, Y') }}</span>
                </div>
            </div>

            <!-- Questions Card -->
            <div class="profile-activity-card">
                <h2 class="section-title">
                    <svg class="section-icon" xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <circle cx="12" cy="12" r="10"></circle>
                        <path d="M9.09 9a3 3 0 0 1 5.83 1c0 2-3 3-3 3"></path>
                        <line x1="12" y1="17" x2="12.01" y2="17"></line>
                    </svg>
                    Questions Asked ({{ $questions->total() }})
                </h2>
                <div class="activity-list">
                    @forelse ($questions as $question)
                        <div class="activity-item">
                            <a href="{{ route('questions.show', $question->id) }}" class="activity-link">
                                <div class="activity-content">
                                    <h3 class="activity-title">{{ $question->Title }}</h3>
                                    <div class="activity-meta">
                                        <span class="meta-item">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                <circle cx="12" cy="12" r="10"></circle>
                                                <polyline points="12 6 12 12 16 14"></polyline>
                                            </svg>
                                            {{ $question->created_at->diffForHumans() }}
                                        </span>
                                        <span class="meta-item">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                <path d="M14 9V5a3 3 0 0 0-3-3l-4 9v11h11.28a2 2 0 0 0 2-1.7l1.38-9a2 2 0 0 0-2-2.3zM7 22H4a2 2 0 0 1-2-2v-7a2 2 0 0 1 2-2h3"></path>
                                            </svg>
                                            {{ $question->Upvotes }} votes
                                        </span>
                                        @if($question->Answered)
                                            <span class="meta-badge answered">Answered</span>
                                        @else
                                            <span class="meta-badge unanswered">Open</span>
                                        @endif
                                    </div>
                                </div>
                            </a>
                        </div>
                    @empty
                        <div class="no-activity">
                            <p>No questions asked yet.</p>
                        </div>
                    @endforelse
                </div>
                <div class="pagination-wrapper">
                    {{ $questions->links() }}
                </div>
            </div>

            <!-- Replies Card -->
            <div class="profile-activity-card">
                <h2 class="section-title">
                    <svg class="section-icon" xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"></path>
                    </svg>
                    Replies Given ({{ $replies->total() }})
                </h2>
                <div class="activity-list">
                    @forelse ($replies as $reply)
                        <div class="activity-item">
                            <a href="{{ route('questions.show', $reply->question_id) }}" class="activity-link">
                                <div class="activity-content">
                                    <h3 class="activity-title">Reply to: {{ $reply->question->Title }}</h3>
                                    <p class="reply-preview">{{ Str::limit($reply->Content, 150) }}</p>
                                    <div class="activity-meta">
                                        <span class="meta-item">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                <circle cx="12" cy="12" r="10"></circle>
                                                <polyline points="12 6 12 12 16 14"></polyline>
                                            </svg>
                                            {{ $reply->created_at->diffForHumans() }}
                                        </span>
                                        <span class="meta-item">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                <path d="M14 9V5a3 3 0 0 0-3-3l-4 9v11h11.28a2 2 0 0 0 2-1.7l1.38-9a2 2 0 0 0-2-2.3zM7 22H4a2 2 0 0 1-2-2v-7a2 2 0 0 1 2-2h3"></path>
                                            </svg>
                                            {{ $reply->Upvotes }} votes
                                        </span>
                                    </div>
                                </div>
                            </a>
                        </div>
                    @empty
                        <div class="no-activity">
                            <p>No replies given yet.</p>
                        </div>
                    @endforelse
                </div>
                <div class="pagination-wrapper">
                    {{ $replies->links() }}
                </div>
            </div>
        </div>
    </div>
@endsection
