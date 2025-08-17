@extends('layouts.app')

@section('title', $question->Title)

@section('content')
@vite('resources/css/showQuestions.css')

<div class="question-container">
    @if(session('success'))
    <div class="status-message status-success">
        {{ session('success') }}
    </div>
    @endif

    @if(session('error'))
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
                        <span>Upvotes: <span id="question-upvotes">{{ $question->Upvotes }}</span></span>
                        <span>Answered: {{ $question->Answered ? 'Yes' : 'No' }}</span>
                        <span>Created: {{ $question->created_at->diffForHumans() }}</span>
                    </div>
                    @if ($question->Tags)
                        @php
                            $tags = explode(',', $question->Tags);
                        @endphp
                        <div class="question-tags">
                            @foreach ($tags as $tag)
                            <span>{{ trim($tag) }}</span>
                            @endforeach
                        </div>
                    @endif
                </div>
                <div class="question-content">
                    <pre>{{ $question->Content }}</pre>
                    <div class="button-container">
                        <button id="question-upvote-btn" data-question-id="{{ $question->id }}" class="btn" title="{{ isset($userVote) && $userVote && $userVote->vote_type == 1 ? 'Remove Upvote' : 'Upvote' }}">
                            @if(isset($userVote) && $userVote && $userVote->vote_type == 1)
                                <x-lucide-thumbs-up class="h-5 w-5 fill-current text-green-500" />
                            @else
                                <x-lucide-thumbs-up class="h-5 w-5" />
                            @endif
                        </button>

                        <button id="question-downvote-btn" data-question-id="{{ $question->id }}" class="btn" title="{{ isset($userVote) && $userVote && $userVote->vote_type == 0 ? 'Remove Downvote' : 'Downvote' }}">
                            @if(isset($userVote) && $userVote && $userVote->vote_type == 0)
                                <x-lucide-thumbs-down class="h-5 w-5 fill-current text-red-500" />
                            @else
                                <x-lucide-thumbs-down class="h-5 w-5" />
                            @endif
                        </button>

                        <form action="{{ route('report.question', $question->id) }}" method="POST" class="inline">
                            @csrf
                            <button type="submit" class="btn" title="Report">
                                <x-lucide-flag class="h-5 w-5" />
                            </button>
                        </form>

                        @if (Auth::user()->role === 'admin')
                        <form action="{{ route('questions.destroy', $question->id) }}" method="POST" class="inline">
                            @csrf @method('DELETE')
                            <button type="submit" class="btn" title="Delete" onclick="return confirm('Are you sure you want to delete this question?')">
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
                        <span>Upvotes: <span id="reply-upvotes-{{ $reply->id }}">{{ $reply->Upvotes }}</span></span>
                        <span>Created: {{ $reply->created_at->diffForHumans() }}</span>
                    </div>
                    <div class="reply-content">
                        <pre>{{ $reply->Content }}</pre>
                        <div class="button-container">
                            <button id="reply-upvote-btn-{{ $reply->id }}" data-reply-id="{{ $reply->id }}" class="btn" title="{{ isset($replyVotes[$reply->id]) && $replyVotes[$reply->id] == 1 ? 'Remove Upvote' : 'Upvote' }}">
                                @if(isset($replyVotes[$reply->id]) && $replyVotes[$reply->id] == 1)
                                    <x-lucide-thumbs-up class="h-5 w-5 fill-current text-green-500" />
                                @else
                                    <x-lucide-thumbs-up class="h-5 w-5" />
                                @endif
                            </button>

                            <button id="reply-downvote-btn-{{ $reply->id }}" data-reply-id="{{ $reply->id }}" class="btn" title="{{ isset($replyVotes[$reply->id]) && $replyVotes[$reply->id] == 0 ? 'Remove Downvote' : 'Downvote' }}">
                                @if(isset($replyVotes[$reply->id]) && $replyVotes[$reply->id] == 0)
                                    <x-lucide-thumbs-down class="h-5 w-5 fill-current text-red-500" />
                                @else
                                    <x-lucide-thumbs-down class="h-5 w-5" />
                                @endif
                            </button>

                            <form action="{{ route('report.reply', $reply->id) }}" method="POST" class="inline">
                                @csrf
                                <button type="submit" class="btn" title="Report">
                                    <x-lucide-flag class="h-5 w-5" />
                                </button>
                            </form>

                            @if (Auth::user()->role === 'admin')
                            <form action="{{ route('replies.destroy', $reply->id) }}" method="POST" class="inline">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn" title="Delete" onclick="return confirm('Are you sure you want to delete this reply?')">
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
                        <div class="text-xs text-gray-500 mt-1">
                            <span>{{ $ques->created_at->diffForHumans() }}</span>
                            <span class="ml-2">Votes: {{ $ques->Upvotes }}</span>
                        </div>
                    </li>
                    @endforeach
                </ul>
                {{ $recent_questions->links() }}
            </div>
        </div>
    </div>
</div>

<!-- jQuery -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<!-- AJAX Voting Script -->
<script>
    // AJAX setup with CSRF token
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    // Question upvote/downvote
    function voteQuestion(questionId, voteType) {
        $.ajax({
            url: '/questions/' + questionId + '/' + (voteType === 'up' ? 'upvote' : 'downvote'),
            type: 'POST',
            success: function(response) {
                console.log('Success response:', response);
                // Update the UI with the response
                if (response.success) {
                    // Show success toast
                    if (typeof window.showToast !== 'undefined') {
                        window.showToast(response.success, 'success');
                    } else {
                        $('#question-vote-message').removeClass('status-error').addClass('status-success').text(response.success).show();
                        // Hide message after 3 seconds
                        setTimeout(function() {
                            $('#question-vote-message').hide();
                        }, 3000);
                    }
                    // Update vote count
                    $('#question-upvotes').text(response.upvotes);
                    // Update button states
                    updateQuestionVoteButtons(response.userVote, questionId);
                } else if (response.error) {
                    // Show error toast
                    if (typeof window.showToast !== 'undefined') {
                        window.showToast(response.error, 'error');
                    } else {
                        $('#question-vote-message').removeClass('status-success').addClass('status-error').text(response.error).show();
                        setTimeout(function() {
                            $('#question-vote-message').hide();
                        }, 3000);
                    }
                }
            },
            error: function(xhr, status, error) {
                console.log('AJAX Error:', xhr, status, error);
                console.log('Response text:', xhr.responseText);
                // Show error toast
                if (typeof window.showToast !== 'undefined') {
                    window.showToast('An error occurred. Please try again.', 'error');
                } else {
                    $('#question-vote-message').removeClass('status-success').addClass('status-error').text('An error occurred. Please try again.').show();
                    setTimeout(function() {
                        $('#question-vote-message').hide();
                    }, 3000);
                }
            }
        });
    }

    function updateQuestionVoteButtons(userVote, questionId) {
        // Reset button states
        $('#question-upvote-btn').html('<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="h-5 w-5"><path d="M7 10v12"/><path d="M15 5.88 14 10h5.83a2 2 0 0 1 1.92 2.56l-2.33 8A2 2 0 0 1 17.5 22H4a2 2 0 0 1-2-2v-8a2 2 0 0 1 2-2h2.76a2 2 0 0 0 1.79-1.11L12 2h0a3.13 3.13 0 0 1 3 3.88Z"/></svg>');
        $('#question-downvote-btn').html('<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="h-5 w-5"><path d="M17 14V2"/><path d="M9 18.12 10 14H4.17a2 2 0 0 1-1.92-2.56l2.33-8A2 2 0 0 1 6.5 2H20a2 2 0 0 1 2 2v8a2 2 0 0 1-2 2h-2.76a2 2 0 0 0-1.79 1.11L12 22h0a3.13 3.13 0 0 1-3-3.88Z"/></svg>');
        
        // Update button titles and fill icons based on user vote
        if (userVote === 1) {
            $('#question-upvote-btn').html('<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="currentColor" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="h-5 w-5 text-green-500"><path d="M7 10v12"/><path d="M15 5.88 14 10h5.83a2 2 0 0 1 1.92 2.56l-2.33 8A2 2 0 0 1 17.5 22H4a2 2 0 0 1-2-2v-8a2 2 0 0 1 2-2h2.76a2 2 0 0 0 1.79-1.11L12 2h0a3.13 3.13 0 0 1 3 3.88Z"/></svg>');
            $('#question-upvote-btn').attr('title', 'Remove Upvote');
            $('#question-downvote-btn').attr('title', 'Downvote');
        } else if (userVote === 0) {
            $('#question-downvote-btn').html('<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="currentColor" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="h-5 w-5 text-red-500"><path d="M17 14V2"/><path d="M9 18.12 10 14H4.17a2 2 0 0 1-1.92-2.56l2.33-8A2 2 0 0 1 6.5 2H20a2 2 0 0 1 2 2v8a2 2 0 0 1-2 2h-2.76a2 2 0 0 0-1.79 1.11L12 22h0a3.13 3.13 0 0 1-3-3.88Z"/></svg>');
            $('#question-downvote-btn').attr('title', 'Remove Downvote');
            $('#question-upvote-btn').attr('title', 'Upvote');
        } else {
            $('#question-upvote-btn').attr('title', 'Upvote');
            $('#question-downvote-btn').attr('title', 'Downvote');
        }
    }

    // Reply upvote/downvote
    function voteReply(replyId, voteType) {
        $.ajax({
            url: '/replies/' + replyId + '/' + (voteType === 'up' ? 'upvote' : 'downvote'),
            type: 'POST',
            success: function(response) {
                console.log('Success response:', response);
                // Update the UI with the response
                if (response.success) {
                    // Show success toast
                    if (typeof window.showToast !== 'undefined') {
                        window.showToast(response.success, 'success');
                    } else {
                        $('#reply-vote-message-' + replyId).removeClass('status-error').addClass('status-success').text(response.success).show();
                        // Hide message after 3 seconds
                        setTimeout(function() {
                            $('#reply-vote-message-' + replyId).hide();
                        }, 3000);
                    }
                    // Update vote count
                    $('#reply-upvotes-' + replyId).text(response.upvotes);
                    // Update button states
                    updateReplyVoteButtons(response.userVote, replyId);
                } else if (response.error) {
                    // Show error toast
                    if (typeof window.showToast !== 'undefined') {
                        window.showToast(response.error, 'error');
                    } else {
                        $('#reply-vote-message-' + replyId).removeClass('status-success').addClass('status-error').text(response.error).show();
                        setTimeout(function() {
                            $('#reply-vote-message-' + replyId).hide();
                        }, 3000);
                    }
                }
            },
            error: function(xhr, status, error) {
                console.log('AJAX Error:', xhr, status, error);
                console.log('Response text:', xhr.responseText);
                // Show error toast
                if (typeof window.showToast !== 'undefined') {
                    window.showToast('An error occurred. Please try again.', 'error');
                } else {
                    $('#reply-vote-message-' + replyId).removeClass('status-success').addClass('status-error').text('An error occurred. Please try again.').show();
                    setTimeout(function() {
                        $('#reply-vote-message-' + replyId).hide();
                    }, 3000);
                }
            }
        });
    }

    function updateReplyVoteButtons(userVote, replyId) {
        // Reset button states
        $('#reply-upvote-btn-' + replyId).html('<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="h-5 w-5"><path d="M7 10v12"/><path d="M15 5.88 14 10h5.83a2 2 0 0 1 1.92 2.56l-2.33 8A2 2 0 0 1 17.5 22H4a2 2 0 0 1-2-2v-8a2 2 0 0 1 2-2h2.76a2 2 0 0 0 1.79-1.11L12 2h0a3.13 3.13 0 0 1 3 3.88Z"/></svg>');
        $('#reply-downvote-btn-' + replyId).html('<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="h-5 w-5"><path d="M17 14V2"/><path d="M9 18.12 10 14H4.17a2 2 0 0 1-1.92-2.56l2.33-8A2 2 0 0 1 6.5 2H20a2 2 0 0 1 2 2v8a2 2 0 0 1-2 2h-2.76a2 2 0 0 0-1.79 1.11L12 22h0a3.13 3.13 0 0 1-3-3.88Z"/></svg>');
        
        // Update button titles and fill icons based on user vote
        if (userVote === 1) {
            $('#reply-upvote-btn-' + replyId).html('<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="currentColor" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="h-5 w-5 text-green-500"><path d="M7 10v12"/><path d="M15 5.88 14 10h5.83a2 2 0 0 1 1.92 2.56l-2.33 8A2 2 0 0 1 17.5 22H4a2 2 0 0 1-2-2v-8a2 2 0 0 1 2-2h2.76a2 2 0 0 0 1.79-1.11L12 2h0a3.13 3.13 0 0 1 3 3.88Z"/></svg>');
            $('#reply-upvote-btn-' + replyId).attr('title', 'Remove Upvote');
            $('#reply-downvote-btn-' + replyId).attr('title', 'Downvote');
        } else if (userVote === 0) {
            $('#reply-downvote-btn-' + replyId).html('<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="currentColor" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="h-5 w-5 text-red-500"><path d="M17 14V2"/><path d="M9 18.12 10 14H4.17a2 2 0 0 1-1.92-2.56l2.33-8A2 2 0 0 1 6.5 2H20a2 2 0 0 1 2 2v8a2 2 0 0 1-2 2h-2.76a2 2 0 0 0-1.79 1.11L12 22h0a3.13 3.13 0 0 1-3-3.88Z"/></svg>');
            $('#reply-downvote-btn-' + replyId).attr('title', 'Remove Downvote');
            $('#reply-upvote-btn-' + replyId).attr('title', 'Upvote');
        } else {
            $('#reply-upvote-btn-' + replyId).attr('title', 'Upvote');
            $('#reply-downvote-btn-' + replyId).attr('title', 'Downvote');
        }
    }

    // Set up event listeners
    $(document).ready(function() {
        // Question vote buttons
        $('#question-upvote-btn').click(function(e) {
            e.preventDefault();
            console.log('Question upvote button clicked');
            var questionId = $(this).data('question-id');
            console.log('Question ID:', questionId);
            voteQuestion(questionId, 'up');
        });

        $('#question-downvote-btn').click(function(e) {
            e.preventDefault();
            console.log('Question downvote button clicked');
            var questionId = $(this).data('question-id');
            console.log('Question ID:', questionId);
            voteQuestion(questionId, 'down');
        });

        // Reply vote buttons
        $('[id^=reply-upvote-btn-]').click(function(e) {
            e.preventDefault();
            console.log('Reply upvote button clicked');
            var replyId = $(this).data('reply-id');
            console.log('Reply ID:', replyId);
            voteReply(replyId, 'up');
        });

        $('[id^=reply-downvote-btn-]').click(function(e) {
            e.preventDefault();
            console.log('Reply downvote button clicked');
            var replyId = $(this).data('reply-id');
            console.log('Reply ID:', replyId);
            voteReply(replyId, 'down');
        });
    });
</script>
<script>
    // Test function to verify toast system is working
    function testToast() {
        if (typeof window.showToast !== 'undefined') {
            window.showToast('Toast system is working!', 'success');
        } else {
            console.log('Toast system not available');
        }
    }
    
    // Run test when page loads
    document.addEventListener('DOMContentLoaded', function() {
        console.log('Toast system check - available:', typeof window.showToast !== 'undefined');
    });
</script>
@endsection