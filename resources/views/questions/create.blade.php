@extends('layouts.app')

@section('content')
@php
    $tags = ['C', 'DBMS', 'JavaScript', 'CSS', 'C++'];
    $selectedTags = collect(old('Tags', []))->map(fn ($value) => (string) $value)->all();
@endphp

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 font-sans">
    <!-- Back Button -->
    <div class="mb-6">
        <a href="{{ route('questions.index') }}"
           class="inline-flex items-center gap-2 text-orange-700 hover:text-orange-800 dark:text-orange-400 dark:hover:text-orange-300 font-medium transition">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="h-5 w-5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18" />
            </svg>
            Back to Questions
        </a>
    </div>

    <!-- Main Form Card -->
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-lg overflow-hidden">
        <!-- Header -->
        <div class="bg-orange-100 dark:bg-gray-700 px-6 py-5 border-b border-orange-200 dark:border-gray-600">
            <h1 class="text-3xl font-bold text-orange-800 dark:text-orange-400" style="font-family: 'Spartan', sans-serif;">Create a New Post</h1>
            <p class="mt-1 text-sm text-gray-600 dark:text-gray-300">Share your knowledge and help the developer community grow.</p>
        </div>

        <!-- Form Content -->
        <div class="px-6 py-8">
            <!-- Status Messages -->
            @if(session('success'))
                <div class="mb-6 rounded-lg border border-green-200 bg-green-50 px-4 py-3 text-sm text-green-800 dark:border-green-800 dark:bg-green-900/30 dark:text-green-200">
                    <div class="flex items-center gap-2">
                        <svg class="h-5 w-5" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                        </svg>
                        {{ session('success') }}
                    </div>
                </div>
            @endif

            @if(session('error'))
                <div class="mb-6 rounded-lg border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-800 dark:border-red-800 dark:bg-red-900/30 dark:text-red-200">
                    <div class="flex items-center gap-2">
                        <svg class="h-5 w-5" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                        </svg>
                        {{ session('error') }}
                    </div>
                </div>
            @endif

            @if ($errors->any())
                <div class="mb-6 rounded-lg border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-800 dark:border-red-800 dark:bg-red-900/30 dark:text-red-200">
                    <p class="font-semibold mb-2 flex items-center gap-2">
                        <svg class="h-5 w-5" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                        </svg>
                        Please fix the following issues:
                    </p>
                    <ul class="ml-7 space-y-1 list-disc">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <!-- Form -->
            <form action="{{ route('questions.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                @csrf

                <!-- Title Field -->
                <div>
                    <label for="Title" class="block text-sm font-bold text-gray-700 dark:text-gray-200 mb-2">
                        <span class="flex items-center gap-2">
                            <span class="inline-flex items-center justify-center w-6 h-6 rounded-full bg-orange-100 dark:bg-orange-900/30 text-orange-700 dark:text-orange-400 text-xs font-semibold">1</span>
                            Question Title <span class="text-red-500">*</span>
                        </span>
                    </label>
                    <input
                        type="text"
                        id="Title"
                        name="Title"
                        value="{{ old('Title') }}"
                        required
                        placeholder="e.g., How to debug a memory leak in React?"
                        class="w-full px-4 py-3 rounded-md border border-gray-300 bg-white dark:bg-gray-700 dark:border-gray-600 text-gray-900 dark:text-gray-100 shadow-sm focus:outline-none focus:ring-2 focus:ring-orange-700 focus:border-orange-700 dark:focus:ring-orange-500 dark:focus:border-orange-500 transition"
                    >
                </div>

                <!-- Content Field -->
                <div>
                    <label for="Content" class="block text-sm font-bold text-gray-700 dark:text-gray-200 mb-2">
                        <span class="flex items-center gap-2">
                            <span class="inline-flex items-center justify-center w-6 h-6 rounded-full bg-orange-100 dark:bg-orange-900/30 text-orange-700 dark:text-orange-400 text-xs font-semibold">2</span>
                            Description <span class="text-red-500">*</span>
                        </span>
                    </label>
                    <textarea
                        id="Content"
                        name="Content"
                        required
                        rows="8"
                        placeholder="Describe your question in detail. Include any code snippets, error messages, or context that might help others understand your issue..."
                        class="w-full px-4 py-3 rounded-md border border-gray-300 bg-white dark:bg-gray-700 dark:border-gray-600 text-gray-900 dark:text-gray-100 shadow-sm focus:outline-none focus:ring-2 focus:ring-orange-700 focus:border-orange-700 dark:focus:ring-orange-500 dark:focus:border-orange-500 transition font-mono text-sm">{{ old('Content') }}</textarea>
                    <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">Tip: Be specific and provide context. The more details, the better answers you'll get!</p>
                </div>

                <!-- Image Upload -->
                <div>
                    <label for="image" class="block text-sm font-bold text-gray-700 dark:text-gray-200 mb-2">
                        <span class="flex items-center gap-2">
                            <span class="inline-flex items-center justify-center w-6 h-6 rounded-full bg-orange-100 dark:bg-orange-900/30 text-orange-700 dark:text-orange-400 text-xs font-semibold">3</span>
                            Attach Image <span class="text-xs font-normal text-gray-500 dark:text-gray-400">(optional)</span>
                        </span>
                    </label>
                    <div class="flex flex-col gap-3">
                        <input
                            type="file"
                            id="image"
                            name="image"
                            accept="image/*"
                            class="block w-full text-sm text-gray-600 dark:text-gray-300
                                file:mr-4 file:py-2 file:px-4
                                file:rounded-md file:border-0
                                file:text-sm file:font-semibold
                                file:bg-orange-700 file:text-white
                                hover:file:bg-orange-800
                                dark:file:bg-orange-600 dark:hover:file:bg-orange-700
                                file:cursor-pointer file:transition"
                        >
                        <p class="text-xs text-gray-500 dark:text-gray-400">Accepted formats: JPEG, PNG, JPG, GIF, WEBP • Max size: 5MB</p>
                    </div>
                </div>

                <!-- Tags Section -->
                <div>
                    <label class="block text-sm font-bold text-gray-700 dark:text-gray-200 mb-2">
                        <span class="flex items-center gap-2">
                            <span class="inline-flex items-center justify-center w-6 h-6 rounded-full bg-orange-100 dark:bg-orange-900/30 text-orange-700 dark:text-orange-400 text-xs font-semibold">4</span>
                            Select Tags
                        </span>
                    </label>
                    <p class="text-xs text-gray-500 dark:text-gray-400 mb-3">Help others find your question by selecting relevant tags</p>

                    <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-5 gap-3 mb-4">
                        @foreach($tags as $tag)
                            <label class="relative flex items-center cursor-pointer group">
                                <input
                                    type="checkbox"
                                    id="tag_{{ $tag }}"
                                    name="Tags[]"
                                    value="{{ $tag }}"
                                    @checked(in_array($tag, $selectedTags, true))
                                    class="peer sr-only"
                                >
                                <div class="w-full px-4 py-2.5 text-sm font-medium text-center rounded-md border-2 border-gray-300 bg-white dark:bg-gray-700 dark:border-gray-600 text-gray-700 dark:text-gray-300
                                    peer-checked:border-orange-700 peer-checked:bg-orange-50 peer-checked:text-orange-800
                                    dark:peer-checked:border-orange-500 dark:peer-checked:bg-orange-900/30 dark:peer-checked:text-orange-300
                                    hover:border-orange-400 dark:hover:border-orange-600 transition">
                                    {{ $tag }}
                                </div>
                            </label>
                        @endforeach
                    </div>

                    <!-- Custom Tags -->
                    <div class="mt-4 pt-4 border-t border-gray-200 dark:border-gray-600">
                        <label for="custom-tags" class="block text-sm font-medium text-gray-700 dark:text-gray-200 mb-2">
                            Add Custom Tags
                        </label>
                        <input
                            type="text"
                            id="custom-tags"
                            name="custom_tags"
                            value="{{ old('custom_tags') }}"
                            placeholder="e.g., Node.js, React Hooks, PostgreSQL (comma-separated)"
                            class="w-full px-4 py-3 rounded-md border border-gray-300 bg-white dark:bg-gray-700 dark:border-gray-600 text-gray-900 dark:text-gray-100 shadow-sm focus:outline-none focus:ring-2 focus:ring-orange-700 focus:border-orange-700 dark:focus:ring-orange-500 dark:focus:border-orange-500 transition"
                        >
                        <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">Separate multiple tags with commas</p>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="flex flex-col-reverse sm:flex-row gap-3 pt-6 border-t border-gray-200 dark:border-gray-600">
                    <a
                        href="{{ route('questions.index') }}"
                        class="w-full sm:w-auto inline-flex items-center justify-center px-6 py-3 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm text-sm font-semibold text-gray-700 dark:text-gray-200 bg-white dark:bg-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-orange-700 dark:focus:ring-orange-500 transition"
                    >
                        Cancel
                    </a>
                    <button
                        type="submit"
                        class="w-full sm:w-auto inline-flex items-center justify-center gap-2 px-6 py-3 border border-transparent rounded-md shadow-sm text-sm font-semibold text-white bg-orange-700 hover:bg-orange-800 dark:bg-orange-600 dark:hover:bg-orange-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-orange-700 dark:focus:ring-orange-500 transition"
                    >
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="h-5 w-5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                        </svg>
                        Create Post
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
