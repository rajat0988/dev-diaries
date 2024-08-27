@extends('layouts.app')

@section('content')

    <head>
        @vite('resources/css/profile.css')
    </head>

    <div class="page-content page-container" id="page-content">
        <div class="padding">
            <div class="row container d-flex justify-content-center">
                <div class="col-xl-6 col-md-12">
                    <div class="card user-card-full">
                        <div class="row m-l-0 m-r-0">
                            <div class="col-sm-4 bg-c-lite-green user-profile">
                                <div class="card-block text-center text-white">
                                    <div class="m-b-25">
                                        <img src="https://img.icons8.com/bubbles/100/000000/user.png" class="img-radius"
                                            alt="User-Profile-Image">
                                    </div>
                                    <h6 class="f-w-600">{{ $user->name }}'s Profile</h6>
                                    <p>Student</p>
                                    <i class=" mdi mdi-square-edit-outline feather icon-edit m-t-10 f-16"></i>
                                </div>
                            </div>
                            <div class="col-sm-8">
                                <div class="card-block">
                                    <h6 class="m-b-20 p-b-5 b-b-default f-w-600">Information</h6>
                                    <div class="row">
                                        <div class="col-sm-6">
                                            <p class="m-b-10 f-w-600">Email</p>
                                            <h6 class="text-muted f-w-400">{{ $user->email }}</h6>
                                        </div>
                                    </div>
                                    <h6 class="m-b-20 m-t-40 p-b-5 b-b-default f-w-600">Activity</h6>
                                    <div class="row">
                                        <div class="col-sm-6">
                                            <p class="m-b-10 f-w-600">Questions Asked: {{ $questions->total() }}</p>
                                            <p class="m-b-10 f-w-600">Questions asked : </p>
                                            @foreach ($questions as $question)
                                                <h6 class="text-muted f-w-400">
                                                    <a
                                                        href="{{ route('questions.show', $question->id) }}">{{ $question->Title }}</a>
                                                </h6>
                                            @endforeach
                                            <div class="pagination">
                                                {{ $questions->links() }}
                                            </div>
                                        </div>
                                        <div class="col-sm-6">
                                            <p class="m-b-10 f-w-600">Replies Given: {{ $replies->total() }}</p>
                                            @foreach ($replies as $reply)
                                                <h6 class="text-muted f-w-400">Reply to question:
                                                    <a href="{{ route('questions.show', $reply->question_id) }}">
                                                        {{ $reply->question->Title }}
                                                    </a>
                                                    <p>{{ $reply->Content }}</p>
                                                </h6>
                                            @endforeach
                                        </div>
                                        <div class="pagination">
                                            {{ $replies->links() }}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection