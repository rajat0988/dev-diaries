@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
    <div class="bg-white shadow rounded-lg p-6">
        <h1 class="text-3xl font-semibold text-gray-800 mb-4">{{ $user->name }}'s Profile</h1>

        <p class="text-gray-600 mb-4">Email: <span class="font-medium text-gray-800">{{ $user->email }}</span></p>

        <h2 class="text-2xl font-semibold text-gray-800 mb-4">Questions Asked: {{ $questions->count() }}</h2>
        <ul class="list-disc list-inside mb-6">
            @foreach($questions as $question)
                <li class="text-indigo-600 hover:underline">
                    <a href="{{ route('questions.show', $question->id) }}">{{ $question->Title }}</a>
                </li>
                <br>
            @endforeach
        </ul>

        <h2 class="text-2xl font-semibold text-gray-800 mb-4">Replies Given: {{ $replies->count() }}</h2>
        <ul class="list-disc list-inside">
            @foreach($replies as $reply)
                <li class="mb-4">
                    <p class="text-gray-600">
                        Reply to question: 
                        <a href="{{ route('questions.show', $reply->question_id) }}" class="text-indigo-600 hover:underline">
                            {{ $reply->question->Title }}
                        </a>
                    </p>
                    <p class="text-gray-800 mt-2">{{ $reply->Content }}</p>
                </li>
            @endforeach
        </ul>
    </div>
</div>
@endsection
