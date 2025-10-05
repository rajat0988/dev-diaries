@extends('layouts.app')

@section('content')
@vite('resources/css/showQuestions.css')

<div class="questions-index-container">
    <!-- Page Header -->
    <div class="page-header">
        <div class="header-content">
            <h1 class="page-title">Reported Content</h1>
            <p class="page-subtitle">Review and manage reported questions and replies</p>
        </div>
        <div class="header-actions">
            <a href="{{ route('questions.index') }}" class="btn-primary">
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <line x1="19" y1="12" x2="5" y2="12"></line>
                    <polyline points="12 19 5 12 12 5"></polyline>
                </svg>
                Back to Questions
            </a>
        </div>
    </div>

    <div class="content-layout">
        <!-- Reported Questions Section -->
        <div class="questions-main" style="grid-column: 1 / -1;">
            <div class="recent-section">
                <div class="section-header">
                    <h2 class="section-title">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <circle cx="12" cy="12" r="10"></circle>
                            <path d="M9.09 9a3 3 0 0 1 5.83 1c0 2-3 3-3 3"></path>
                            <line x1="12" y1="17" x2="12.01" y2="17"></line>
                        </svg>
                        Reported Questions ({{ $reportedQuestions->count() }})
                    </h2>
                </div>
                <div class="questions-list">
                    @forelse($reportedQuestions as $question)
                        <div class="question-card-modern">
                            <div class="question-main">
                                <a href="{{ route('questions.show', $question->id) }}" class="question-link">
                                    <h3 class="question-title-modern">{{ $question->Title }}</h3>
                                </a>
                                <div class="question-meta-modern">
                                    <span class="meta-item-modern">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                            <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
                                            <circle cx="12" cy="7" r="4"></circle>
                                        </svg>
                                        {{ $question->UserName }}
                                    </span>
                                    <span class="meta-item-modern">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                            <path d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"></path>
                                            <line x1="12" y1="9" x2="12" y2="13"></line>
                                            <line x1="12" y1="17" x2="12.01" y2="17"></line>
                                        </svg>
                                        Reported {{ $question->updated_at ? $question->updated_at->diffForHumans() : 'recently' }}
                                    </span>
                                </div>
                            </div>
                            <div class="question-stats">
                                <div class="stat-box">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        <path d="M14 9V5a3 3 0 0 0-3-3l-4 9v11h11.28a2 2 0 0 0 2-1.7l1.38-9a2 2 0 0 0-2-2.3zM7 22H4a2 2 0 0 1-2-2v-7a2 2 0 0 1 2-2h3"></path>
                                    </svg>
                                    <span class="stat-number">{{ $question->Upvotes }}</span>
                                </div>
                                <span class="status-badge" style="background-color: #fee2e2; color: #991b1b;">Reported</span>
                            </div>
                        </div>
                    @empty
                        <div class="no-results">
                            <svg xmlns="http://www.w3.org/2000/svg" width="64" height="64" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path>
                                <polyline points="22 4 12 14.01 9 11.01"></polyline>
                            </svg>
                            <p>No reported questions at this time.</p>
                        </div>
                    @endforelse
                </div>
            </div>

            <!-- Reported Replies Section -->
            <div class="recent-section">
                <div class="section-header">
                    <h2 class="section-title">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"></path>
                        </svg>
                        Reported Replies ({{ $reportedReplies->count() }})
                    </h2>
                </div>
                <div class="questions-list">
                    @forelse($reportedReplies as $reply)
                        <div class="question-card-modern">
                            <div class="question-main">
                                <a href="{{ route('questions.show', $reply->question_id) }}" class="question-link">
                                    <h3 class="question-title-modern">Reply: {{ Str::limit($reply->Content, 80) }}</h3>
                                </a>
                                <div class="question-meta-modern">
                                    <span class="meta-item-modern">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                            <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
                                            <circle cx="12" cy="7" r="4"></circle>
                                        </svg>
                                        {{ $reply->UserName }}
                                    </span>
                                    <span class="meta-item-modern">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                            <path d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"></path>
                                            <line x1="12" y1="9" x2="12" y2="13"></line>
                                            <line x1="12" y1="17" x2="12.01" y2="17"></line>
                                        </svg>
                                        Reported {{ $reply->updated_at ? $reply->updated_at->diffForHumans() : 'recently' }}
                                    </span>
                                </div>
                                <p class="reply-preview">{{ Str::limit($reply->Content, 200) }}</p>
                            </div>
                            <div class="question-stats">
                                <div class="stat-box">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        <path d="M14 9V5a3 3 0 0 0-3-3l-4 9v11h11.28a2 2 0 0 0 2-1.7l1.38-9a2 2 0 0 0-2-2.3zM7 22H4a2 2 0 0 1-2-2v-7a2 2 0 0 1 2-2h3"></path>
                                    </svg>
                                    <span class="stat-number">{{ $reply->Upvotes }}</span>
                                </div>
                                <span class="status-badge" style="background-color: #fee2e2; color: #991b1b;">Reported</span>
                            </div>
                        </div>
                    @empty
                        <div class="no-results">
                            <svg xmlns="http://www.w3.org/2000/svg" width="64" height="64" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path>
                                <polyline points="22 4 12 14.01 9 11.01"></polyline>
                            </svg>
                            <p>No reported replies at this time.</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</div>
            <!-- Pagination -->
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-6">
                <div class="flex justify-between items-center">
                    <div>
                        {{-- Show counts --}}
                        <p class="text-sm text-gray-600 dark:text-gray-300">Showing {{ $reportedQuestions->firstItem() ?? 0 }} - {{ $reportedQuestions->lastItem() ?? 0 }} of {{ $reportedQuestions->total() }} reported questions</p>
                    </div>
                    <div>
                        {{ $reportedQuestions->links() }}
                    </div>
                </div>
            </div>

            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-4">
                <div class="flex justify-between items-center">
                    <div>
                        <p class="text-sm text-gray-600 dark:text-gray-300">Showing {{ $reportedReplies->firstItem() ?? 0 }} - {{ $reportedReplies->lastItem() ?? 0 }} of {{ $reportedReplies->total() }} reported replies</p>
                    </div>
                    <div>
                        {{ $reportedReplies->links() }}
                    </div>
                </div>
            </div>

@endsection
