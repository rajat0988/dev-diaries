@extends('layouts.app')

@section('header')
    <h2 class="font-semibold text-xl text-gray-800 leading-tight">
        {{ $question->Title }}
    </h2>
@endsection

@section('content')
    <a href="{{ url('/') }}" class="btn btn-primary">Home</a>
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 text-gray-900">
                <h3>{{ $question->Title }}</h3>
                <p>{{ $question->Content }}</p>
                <p>Asked by: {{ $question->UserName }}</p>
                <p>Email: {{ $question->EmailId }}</p>
                <p>Upvotes: {{ $question->Upvotes }}</p>
                <p>Answered: {{ $question->Answered ? 'Yes' : 'No' }}</p>

                @if($question->Tags)
                    @php
                        $tags = json_decode($question->Tags, true);
                    @endphp
                    <p>Tags: {{ implode(', ', $tags) }}</p>
                @endif
                <form action="{{ route('questions.upvote', $question->id) }}" method="POST" class="inline">
                    @csrf
                    <button type="submit" class="bg-green-500 text-black px-4 py-2">Upvote</button>
                </form>

                <form action="{{ route('questions.downvote', $question->id) }}" method="POST" class="inline">
                    @csrf
                    <button type="submit" class="bg-red-500 text-black px-4 py-2">Downvote</button>
                </form>
            </div>
        </div>

        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mt-6">
            <div class="p-6 text-gray-900">
                <h4>Replies:</h4>
                @forelse($question->replies as $reply)
                    <div class="border-t border-gray-200 mt-4">
                        <p>{{ $reply->Content }}</p>
                        <p>Replied by: {{ $reply->UserName }}</p>
                        <p>Email: {{ $reply->EmailId }}</p>
                        <p>Upvotes: {{ $reply->Upvotes }}</p>
                        <p>Created at: {{ $reply->created_at->diffForHumans() }}</p>
                        
                        <form action="{{ route('replies.upvote', $reply->id) }}" method="POST" class="inline">
                            @csrf
                            <button type="submit" class="bg-green-500 text-gray px-4 py-2">Upvote</button>
                        </form>

                        <form action="{{ route('replies.downvote', $reply->id) }}" method="POST" class="inline">
                            @csrf
                            <button type="submit" class="bg-red-500 text-black px-4 py-2">Downvote</button>
                        </form>
                    </div>
                @empty
                    <p>No replies yet.</p>
                @endforelse
                </div>
            </div>

            <br>
            {{-- replies form --}}
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mt-6">
                 <div class="mt-6">
                    <h4>Reply Here:</h4>
                    <form action="{{ route('replies.store', $question->id) }}" method="POST">
                        @csrf
                        <div>
                            <label for="UserName">Your Name:</label>
                            <input type="text" id="UserName" name="UserName" class="border p-2" required>
                        </div>
                        <div class="mt-2">
                            <label for="EmailId">Your Email:</label>
                            <input type="email" id="EmailId" name="EmailId" class="border p-2" required>
                        </div>
                        <div class="mt-2">
                            <label for="Content">Reply:</label>
                            <textarea id="Content" name="Content" class="border p-2" rows="4" required></textarea>
                        </div>
                        <button type="submit" class="mt-4 px-4 py-2">Submit Reply</button>
                    </form>
                </div>
            </div>
        </div>
@endsection