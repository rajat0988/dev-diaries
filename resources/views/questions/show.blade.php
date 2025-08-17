@extends('layouts.app')

@section('content')
<head>
    @vite('resources/css/showQuestions.css')
</head>

<div class="question-container">
    @if (session('success'))
    <div class="status-message status-success">
        {{ session('success') }}
    </div>
    @endif

    @if (session('error'))
    <div class="status-message status-error">
        {{ session('error') }}
    </div>
    @endif

    <div class="flex flex-wrap -mx-4">
        <!-- Main Content (Question and Replies) -->
        <div class="w-full lg:w-3/4 px-4">
            <!-- Question Box -->
            <div class="question-box">
                <div class="question-header">
                    <h1 class="question-title">{{ $question->Title }}</h1>
                    <div class="question-meta">
                        <span>Asked by: <a href="{{ route('profile.show', ['id' => $question->user_id]) }}">{{ $question->UserName }}</a></span>
                        <span>Upvotes: {{ $question->Upvotes }}</span>
                        <span>Answered: {{ $question->Answered ? 'Yes' : 'No' }}</span>
                        <span>Created: {{ $question->created_at->diffForHumans() }}</span>
                    </div>
                    @if ($question->Tags)
                    @php
                    $tags = json_decode($question->Tags, true);
                    @endphp
                    <div class="question-tags">
                        @foreach ($tags as $tag)
                        <span>{{ $tag }}</span>
                        @endforeach
                    </div>
                    @endif
                </div>
                <div class="question-content">
                    <pre>{{ $question->Content }}</pre>
                    <div class="button-container">
                        <form action="{{ route('questions.upvote', $question->id) }}" method="POST" class="inline">
                            @csrf
                            <button type="submit" class="btn upvote-btn" title="Upvote">
                                <x-lucide-thumbs-up class="h-5 w-5" />
                            </button>
                        </form>

                        <form action="{{ route('questions.downvote', $question->id) }}" method="POST" class="inline">
                            @csrf
                            <button type="submit" class="btn downvote-btn" title="Downvote">
                                <x-lucide-thumbs-down class="h-5 w-5" />
                            </button>
                        </form>

                        <form action="{{ route('report.question', $question->id) }}" method="POST" class="inline">
                            @csrf
                            <button type="submit" class="btn report-btn" title="Report">
                                <x-lucide-flag class="h-5 w-5" />
                            </button>
                        </form>

                        @if (Auth::user()->role === 'admin')
                        <form action="{{ route('questions.destroy', $question->id) }}" method="POST" class="inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn delete-btn" title="Delete" onclick="return confirm('Are you sure you want to delete this question?')">
                                <x-lucide-trash class="h-5 w-5" />
                            </button>
                        </form>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Replies Section -->
            <div class="replies-section">
                <h3>Replies ({{ $question->replies->count() }})</h3>

                @forelse($question->replies as $reply)
                <div class="reply-box">
                    <div class="reply-meta">
                        <span>Replied by: <a href="{{ route('profile.show', ['id' => $reply->user_id]) }}">{{ $reply->UserName }}</a></span>
                        <span>Upvotes: {{ $reply->Upvotes }}</span>
                        <span>Created: {{ $reply->created_at->diffForHumans() }}</span>
                    </div>
                    <div class="reply-content">
                        <pre>{{ $reply->Content }}</pre>
                        <div class="button-container">
                            <form action="{{ route('replies.upvote', $reply->id) }}" method="POST" class="inline">
                                @csrf
                                <button type="submit" class="btn upvote-btn" title="Upvote">
                                    <x-lucide-thumbs-up class="h-5 w-5" />
                                </button>
                            </form>

                            <form action="{{ route('replies.downvote', $reply->id) }}" method="POST" class="inline">
                                @csrf
                                <button type="submit" class="btn downvote-btn" title="Downvote">
                                    <x-lucide-thumbs-down class="h-5 w-5" />
                                </button>
                            </form>

                            <form action="{{ route('report.reply', $reply->id) }}" method="POST" class="inline">
                                @csrf
                                <button type="submit" class="btn report-btn" title="Report">
                                    <x-lucide-flag class="h-5 w-5" />
                                </button>
                            </form>

                            @if (Auth::user()->role === 'admin')
                            <form action="{{ route('replies.destroy', $reply->id) }}" method="POST" class="inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn delete-btn" title="Delete" onclick="return confirm('Are you sure you want to delete this reply?')">
                                    <x-lucide-trash class="h-5 w-5" />
                                </button>
                            </form>
                            @endif
                        </div>
                    </div>
                </div>
                @empty
                <p class="no-replies">No replies yet. Be the first to answer!</p>
                @endforelse
            </div>

            <!-- Reply Form -->
            <div class="reply-form-box">
                <h4>Post Your Reply</h4>
                <form action="{{ route('replies.store', $question->id) }}" method="POST">
                    @csrf
                    <div class="form-group">
                        <label for="Content">Your Answer:</label>
                        <textarea id="Content" name="Content" class="input-text" rows="5" required></textarea>
                    </div>
                    <button type="submit" class="submit-btn">Submit Reply</button>
                </form>
            </div>
        </div>

        <!-- Sidebar -->
        <div class="w-full lg:w-1/4 px-4 mt-6 lg:mt-0">
            <div class="sidebar">
                <h4>Recent Questions</h4>
                <ul>
                    @foreach ($recent_questions as $ques)
                    <li>
                        <a href="{{ route('questions.show', $ques->id) }}">{{ $ques->Title }}</a>
                    </li>
                    @endforeach
                </ul>
                {{ $recent_questions->links() }}
            </div>
        </div>
    </div>
</div>
@endsection