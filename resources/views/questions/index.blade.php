@extends('layouts.app')

<style>
a {
    text-decoration: none;
    font-size: 1.5rem;
}

a:hover {
    text-decoration: underline;
}
</style>

@section('content')
<a href="{{ route('questions.create') }}" class="btn btn-primary">Ask a Question</a>
<br>
<h2>Apply filters: </h2>
<form action="{{ route('questions.filter') }}" method="GET">
    <div>
        <h3>Filter by Tags</h3>
        @php
            $availableTags = ['Laravel', 'PHP', 'JavaScript', 'CSS', 'HTML'];
        @endphp

        @foreach($availableTags as $tag)
            <label>
                <input type="checkbox" name="tags[]" value="{{ $tag }}"
                @if(is_array(request('tags')) && in_array($tag, request('tags'))) checked @endif>
                {{ $tag }}
            </label><br>
        @endforeach
    </div>
    <button type="submit" class="btn btn-primary">Filter</button>
</form>

@if(isset($selectedTags) && !empty($selectedTags))
    <h1>Filtered Questions</h1>
    <ul>
        @forelse($filtered_questions as $question)
            <li>
                <a href="{{ route('questions.show', $question->id) }}">{{ $question->Title }}</a>
                <br>
                <span>{{ $question->UserName }}</span>
                <br>
                <span>{{ $question->Upvotes >= 0 ? $question->Upvotes . ': Upvotes' : -1 * $question->Upvotes . ': Downvotes'  }}</span>
                <br>
                <span> Answered: {{ $question->Answered ? 'Yes' : 'No' }}</span>
                <br>
            </li>
        @empty
            <li>No questions match the selected tags.</li>
        @endforelse
    </ul>
@endif

<h1>Recent Questions</h1>
<ul>
    @foreach($all_questions as $question)
        <li>
            <a href="{{ route('questions.show', $question->id) }}">{{ $question->Title }}</a>
            <br>
            <span>{{ $question->UserName }}</span>
            <br>
            <span>{{ $question->Upvotes >= 0 ? $question->Upvotes . ': Upvotes' : -1 * $question->Upvotes . ': Downvotes'  }}</span>
            <br>
            <span> Answered: {{ $question->Answered ? 'Yes' : 'No' }}</span>
            <br>
        </li>
    @endforeach
</ul>
@endsection