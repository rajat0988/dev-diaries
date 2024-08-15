@extends('layouts.app')

@section('content')
<style>
    /* General link styling */
    a {
        text-decoration: none;
        font-size: 1rem;
        color: #007bff;
    }

    a:hover {
        text-decoration: underline;
    }

    /* Main Content Styling */
    .main-content {
        width: 100%; /* Use full width of the page */
        background-color: #ffffff;
        padding: 20px;
        border-radius: 5px;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    }

    /* Header Styling */
    .main-content h1 {
        font-size: 2rem;
        font-weight: 600;
        color: #343a40;
        margin-bottom: 20px;
    }

    /* Subheader Styling */
    .main-content h2 {
        font-size: 1.5rem;
        font-weight: 600;
        color: #343a40;
        margin-bottom: 10px;
    }

    /* Paragraph Styling */
    .main-content p {
        font-size: 1rem;
        color: #6c757d;
        margin-bottom: 10px;
    }

    /* List Styling */
    .list-disc {
        list-style-type: disc;
        padding-left: 1.5rem;
        margin-bottom: 1.5rem;
    }

    /* List Item Styling */
    .list-disc li {
        font-size: 1rem;
        color: #007bff;
        margin-bottom: 10px;
    }

    /* Pagination Styling */
    .pagination {
        padding: 1rem;
        background-color: #f8f9fa;
        border-radius: 5px;
    }

    /* Button Styling */
    .btn-primary {
        background-color: #20c997;
        border-color: #20c997;
        color: #fff;
        padding: 0.5rem 1rem;
        font-size: 1rem;
        border-radius: 5px;
    }

    .btn-primary:hover {
        background-color: #17a2b8;
        border-color: #17a2b8;
    }
</style>

<div class="main-content">
    <h1>{{ $user->name }}'s Profile</h1>

    <p>Email: <span class="font-medium text-gray-800">{{ $user->email }}</span></p>

    <h2>Questions Asked: {{ $questions->total() }}</h2>
    <ul class="list-disc">
        @foreach($questions as $question)
            <li>
                <a href="{{ route('questions.show', $question->id) }}">{{ $question->Title }}</a>
            </li>
        @endforeach
    </ul>

    <!-- Pagination controls for questions -->
    <div class="pagination">
        {{ $questions->links() }}
    </div>

    <h2>Replies Given: {{ $replies->total() }}</h2>
    <ul class="list-disc">
        @foreach($replies as $reply)
            <li>
                <p>Reply to question: 
                    <a href="{{ route('questions.show', $reply->question_id) }}">
                        {{ $reply->question->Title }}
                    </a>
                </p>
                <p>{{ $reply->Content }}</p>
            </li>
        @endforeach
    </ul>

    <!-- Pagination controls for replies -->
    <div class="pagination">
        {{ $replies->links() }}
    </div>
</div>
@endsection
