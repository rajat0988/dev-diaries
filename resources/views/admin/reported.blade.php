@extends('layouts.app')

@section('content')
@vite('resources/css/showQuestions.css')

<div class="questions-index-container">
    <div class="page-header">
        <div class="header-content">
            <h1 class="page-title">Reported Content</h1>
            <p class="page-subtitle">Moderate flagged questions and replies</p>
        </div>
        <div class="header-actions">
            <a href="{{ route('admin.dashboard') }}" class="btn-primary" style="background-color: #4b5563;">
                 <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                </svg>
                Dashboard
            </a>
        </div>
    </div>

    <div class="content-layout">
        <div class="questions-main" style="grid-column: 1 / -1; display: flex; flex-direction: column; gap: 2.5rem;">
            
            <!-- Reported Questions Section -->
            <section>
                <div class="section-header flex justify-between items-center border-b-2 border-red-100 dark:border-gray-700 pb-4 mb-8">
                    <div class="flex items-center gap-3">
                        <div class="p-2 bg-red-100 dark:bg-red-900 rounded-lg text-red-600 dark:text-red-300">
                             <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                        <h2 class="section-title m-0">Reported Questions</h2>
                        <span class="bg-red-100 text-red-700 dark:bg-red-900 dark:text-red-200 px-3 py-1 rounded-full text-xs font-black">
                            {{ $reportedQuestions->total() }}
                        </span>
                    </div>
                </div>

                @if($reportedQuestions->count() > 0)
                    <div class="grid grid-cols-1 gap-6">
                        @foreach($reportedQuestions as $question)
                            <div class="p-6 bg-white dark:bg-gray-800 rounded-2xl border border-gray-100 dark:border-gray-700 shadow-md transition-all hover:border-red-200 dark:hover:border-red-900 group">
                                <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
                                    <div class="flex-1">
                                        <div class="flex items-center gap-2 mb-2">
                                            <span class="text-xs font-bold uppercase tracking-wider text-gray-400">Question #{{ $question->id }}</span>
                                            <span class="text-xs font-black text-red-500"> Flagged</span>
                                        </div>
                                        <a href="{{ route('questions.show', $question->id) }}" class="group/link">
                                            <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-2 group-hover/link:text-orange-500 transition-colors">{{ $question->Title }}</h3>
                                        </a>
                                        <div class="flex flex-wrap items-center gap-4 text-sm text-gray-500 dark:text-gray-400">
                                            <span class="flex items-center gap-1 font-medium">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                                </svg>
                                                {{ $question->UserName }}
                                            </span>
                                            <span class="flex items-center gap-1">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                </svg>
                                                {{ $question->created_at->diffForHumans() }}
                                            </span>
                                        </div>
                                    </div>
                                    <div class="flex gap-3 w-full md:w-auto mt-4 md:mt-0">
                                        <a href="{{ route('questions.show', $question->id) }}" class="flex-1 md:flex-none text-center px-6 py-2.5 bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-200 rounded-xl font-bold hover:bg-gray-200 dark:hover:bg-gray-600 transition-all">Review</a>
                                        <form action="{{ route('questions.destroy', $question->id) }}" method="POST" onsubmit="return confirm('Delete this question permanently?');" class="flex-1 md:flex-none">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="w-full px-6 py-2.5 bg-red-600 text-white rounded-xl font-bold hover:bg-red-700 transition-all shadow-sm">Delete</button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                    <div class="mt-6">
                        {{ $reportedQuestions->links() }}
                    </div>
                @else
                    <div class="text-center py-16 bg-gray-50 dark:bg-gray-800/50 rounded-2xl border border-gray-100 dark:border-gray-700">
                        <p class="text-gray-500 dark:text-gray-400 font-medium italic">All quiet here. No questions reported.</p>
                    </div>
                @endif
            </section>

            <!-- Reported Replies Section -->
            <section>
                <div class="section-header flex justify-between items-center border-b-2 border-red-100 dark:border-gray-700 pb-4 mb-8">
                    <div class="flex items-center gap-3">
                        <div class="p-2 bg-red-100 dark:bg-red-900 rounded-lg text-red-600 dark:text-red-300">
                             <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8h2a2 2 0 012 2v6a2 2 0 01-2 2h-2v4l-4-4H9a1.994 1.994 0 01-1.414-.586m0 0L11 14h4a2 2 0 002-2V6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2v4l.586-.586z" />
                            </svg>
                        </div>
                        <h2 class="section-title m-0">Reported Replies</h2>
                        <span class="bg-red-100 text-red-700 dark:bg-red-900 dark:text-red-200 px-3 py-1 rounded-full text-xs font-black">
                            {{ $reportedReplies->total() }}
                        </span>
                    </div>
                </div>

                @if($reportedReplies->count() > 0)
                    <div class="grid grid-cols-1 gap-6">
                        @foreach($reportedReplies as $reply)
                            <div class="p-6 bg-white dark:bg-gray-800 rounded-2xl border border-gray-100 dark:border-gray-700 shadow-md transition-all hover:border-red-200 dark:hover:border-red-900 group">
                                <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
                                    <div class="flex-1">
                                         <div class="flex items-center gap-2 mb-2">
                                            <span class="text-xs font-bold uppercase tracking-wider text-gray-400">Reply #{{ $reply->id }}</span>
                                            <span class="text-xs font-black text-red-500"> Flagged</span>
                                        </div>
                                        <p class="text-gray-900 dark:text-gray-100 font-medium mb-3 leading-relaxed">
                                            "{{ Str::limit($reply->Content, 150) }}"
                                        </p>
                                        <div class="flex flex-wrap items-center gap-4 text-sm text-gray-500 dark:text-gray-400">
                                            <span class="flex items-center gap-1 font-medium">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                                </svg>
                                                {{ $reply->UserName }}
                                            </span>
                                            <span class="flex items-center gap-1">
                                                <span class="font-bold text-gray-300 dark:text-gray-600"></span>
                                                On <a href="{{ route('questions.show', $reply->question_id) }}" class="underline hover:text-orange-500 transition-colors">Question: {{ Str::limit($reply->question->Title, 30) }}</a>
                                            </span>
                                        </div>
                                    </div>
                                    <div class="flex gap-3 w-full md:w-auto mt-4 md:mt-0">
                                         <a href="{{ route('questions.show', $reply->question_id) }}" class="flex-1 md:flex-none text-center px-6 py-2.5 bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-200 rounded-xl font-bold hover:bg-gray-200 dark:hover:bg-gray-600 transition-all">Review</a>
                                        <form action="{{ route('replies.destroy', $reply->id) }}" method="POST" onsubmit="return confirm('Delete this reply permanently?');" class="flex-1 md:flex-none">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="w-full px-6 py-2.5 bg-red-600 text-white rounded-xl font-bold hover:bg-red-700 transition-all shadow-sm">Delete</button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                    <div class="mt-6">
                        {{ $reportedReplies->links() }}
                    </div>
                @else
                    <div class="text-center py-16 bg-gray-50 dark:bg-gray-800/50 rounded-2xl border border-gray-100 dark:border-gray-700">
                        <p class="text-gray-500 dark:text-gray-400 font-medium italic">No reported replies found.</p>
                    </div>
                @endif
            </section>

        </div>
    </div>
</div>
@endsection
