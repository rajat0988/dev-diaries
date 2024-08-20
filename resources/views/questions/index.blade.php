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

/* Container Styling */
.container {
    display: flex;
    flex-direction: column;
    padding: 20px;
}

/* Main Content and Sidebar Wrapping Container */
.content-wrapper {
    display: flex;
    flex-direction: column;
}

/* Left Content Area */
.main-content {
    width: 100%;
    margin-bottom: 20px;
}

/* Right Sidebar Styling */
.sidebar {
    width: 100%;
    background-color: #343a40;
    padding: 15px;
    color: #ffffff;
}

/* Sidebar Box Styling */
.sidebar .box {
    margin-bottom: 20px;
    background-color: #343a40;
    border-radius: 5px;
    padding: 15px;
}

/* Sidebar Title Styling */
.sidebar .box h3, .sidebar .box h2 {
    font-size: 1.2rem;
    margin-bottom: 10px;
}

/* Sidebar Link Styling */
.sidebar .box a {
    color: #ffffff;
    display: block;
    margin-bottom: 5px;
    font-size: 1rem;
    text-decoration: none;
}

.sidebar .box a:hover {
    text-decoration: underline;
}

/* Forum Category Header Styling */
.forum-category {
    background-color: #20c997;
    color: #ffffff;
    padding: 10px;
    font-size: 1.2rem;
    border-radius: 5px;
    margin-bottom: 10px;
}

/* Forum List Styling */
.forum-list {
    background-color: #ffffff;
    border-radius: 5px;
    padding: 20px;
    margin-bottom: 20px;
}

.forum-list h4 {
    font-size: 1rem;
    color: #6c757d;
    margin-bottom: 10px;
}

.forum-list a {
    color: #007bff;
    text-decoration: none;
    font-size: 1rem;
    display: block;
    margin-bottom: 10px;
}

.forum-list a:hover {
    text-decoration: underline;
}

.forum-list .forum-meta {
    display: flex;
    justify-content: space-between;
    margin-top: 10px;
    font-size: 0.9rem;
    color: #6c757d;
}

/* Question List Styling */
.list-disc {
    list-style-type: disc;
    padding-left: 1.5rem;
    margin-bottom: 1.5rem;
}

/* Pagination Styling */
.pagination {
    display: flex;
    justify-content: center;
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

/* Media Queries for Responsiveness */
@media (min-width: 768px) {
    /* On medium screens and up, display the content side by side */
    .content-wrapper {
        flex-direction: row;
        justify-content: space-between;
    }

    .main-content {
        width: 70%;
        margin-bottom: 0;
    }

    .sidebar {
        width: 25%;
        margin-top: 0;
    }
}
</style>

<div class="container">
    <div class="content-wrapper">
        <div class="main-content">
            <a href="{{ route('questions.create') }}" class="btn btn-primary">Ask a Question</a>
            <br>
            <br>
            @if(isset($selectedTags) && !empty($selectedTags))
                <h1>Filtered Questions</h1>
                <ul class="list-disc pl-5">
                    @forelse($filtered_questions as $question)
                        <li class="mb-4">
                            <a href="{{ route('questions.show', $question->id) }}" class="text-blue-500 hover:underline">{{ $question->Title }}</a>
                            <br>
                            <span>{{ $question->UserName }}</span>
                            <br>
                            <span>{{ $question->Upvotes >= 0 ? $question->Upvotes . ': Upvotes' : -1 * $question->Upvotes . ': Downvotes' }}</span>
                            <br>
                            <span>Answered: {{ $question->Answered ? 'Yes' : 'No' }}</span>
                        </li>
                    @empty
                        <li>No questions match the selected tags.</li>
                    @endforelse
                </ul>
                {{ $filtered_questions->appends(['tags' => $selectedTags])->links() }}
            @endif

            <h1>Recent Questions</h1>
            <ul class="list-disc pl-5">
                @foreach($all_questions as $question)
                    <li class="mb-4">
                        <a href="{{ route('questions.show', $question->id) }}" class="text-blue-500 hover:underline">{{ $question->Title }}</a>
                        <br>
                        <span>{{ $question->UserName }}</span>
                        <br>
                        <span>{{ $question->Upvotes >= 0 ? $question->Upvotes . ': Upvotes' : -1 * $question->Upvotes . ': Downvotes' }}</span>
                        <br>
                        <span>Answered: {{ $question->Answered ? 'Yes' : 'No' }}</span>
                    </li>
                @endforeach
            </ul>

            {{ $all_questions->links() }}
        </div>
        <div class="sidebar">
            <div class="box">
                <h2>Apply filters:</h2>
                <form action="{{ route('questions.filter') }}" method="GET" class="mb-4">
                    @if(isset($tagsToShow))
                        <div>
                            <h2>All Tags</h2>
                            @foreach($tagsToShow as $tag)
                                <label class="block mb-2">
                                    <input type="checkbox" name="tags[]" value="{{ $tag }}"
                                    @if(is_array(request('tags')) && in_array($tag, request('tags'))) checked @endif>
                                    {{ $tag }}
                                </label>
                            @endforeach
                        </div>
                    @else
                        <div>
                            <h3>Filter by Tags</h3>
                            @foreach($mostUsedTags as $tag)
                                <label class="block mb-2">
                                    <input type="checkbox" name="tags[]" value="{{ $tag }}"
                                    @if(is_array(request('tags')) && in_array($tag, request('tags'))) checked @endif>
                                    {{ $tag }}
                                </label>
                            @endforeach
                        </div>
                    @endif
                    <button type="submit" class="btn btn-primary">Filter</button>
                </form>
                <a href="{{ route('questions.loadMoreTags') }}" class="btn btn-primary">Load More Tags</a>
            </div>
        </div>
    </div>
</div>
@endsection
