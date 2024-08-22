@extends('layouts.app')

@section('content')
    <h1>Reported Questions</h1>
    <ol>
        @foreach($reportedQuestions as $question)
        <li>Quesiton: <a style="color: #105aa3;" href="{{ route('questions.show', $question->id) }}">{{ $question->Title }}</a></li>
            {{-- <li>{{ $question->Title }} (Reported at: {{ $question->updated_at }})</li> --}}
        @endforeach
    </ol>

    <h1>Reported Replies</h1>
    <ul>
        @foreach($reportedReplies as $reply)
        <li>Reply on Quesiton: <a style="color: #105aa3;" href="{{ route('questions.show', $question->id) }}">{{ $reply->Content }}</a></li>
        @endforeach
    </ul>
@endsection
