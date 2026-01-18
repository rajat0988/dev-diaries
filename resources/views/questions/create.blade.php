@extends('layouts.app')

@section('content')
@php
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
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-lg">
        <!-- Header -->
        <div class="bg-orange-100 dark:bg-gray-700 px-6 py-5 border-b border-orange-200 dark:border-gray-600 rounded-t-lg">
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
                <div x-data="tagSelector" class="space-y-4">
                    <label class="block text-sm font-bold text-gray-700 dark:text-gray-200 mb-2">
                        <span class="flex items-center gap-2">
                            <span class="inline-flex items-center justify-center w-6 h-6 rounded-full bg-orange-100 dark:bg-orange-900/30 text-orange-700 dark:text-orange-400 text-xs font-semibold">4</span>
                            Select Tags
                        </span>
                    </label>
                    <p class="text-xs text-gray-500 dark:text-gray-400 mb-3">Add up to 5 relevant tags. Start typing to see suggestions.</p>

                    <!-- Selected Tags Display -->
                    <div class="flex flex-wrap gap-2 mb-3" x-show="tags.length > 0" style="display: none;" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100">
                        <template x-for="(tag, index) in tags" :key="index">
                            <div class="group inline-flex items-center gap-2 rounded-full border border-orange-200 dark:border-orange-500/20 bg-orange-50/50 dark:bg-orange-500/10 px-4 py-2 shadow-sm hover:border-orange-300 dark:hover:border-orange-500/40 transition-all duration-200">
                                <span x-text="tag" class="text-base font-bold text-orange-700 dark:text-orange-400 capitalize select-none max-w-[220px] truncate"></span>
                                <button
                                    type="button"
                                    @click.stop="removeTag(index)"
                                    class="h-6 w-6 inline-flex items-center justify-center rounded-full text-orange-400 hover:text-red-500 hover:bg-white dark:hover:bg-gray-800 transition-all duration-200 cursor-pointer"
                                    title="Remove tag"
                                >
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M6 18L18 6M6 6l12 12" />
                                    </svg>
                                </button>
                                <input type="hidden" name="Tags[]" :value="tag">
                            </div>
                        </template>
                    </div>

                    <!-- Input and Suggestions -->
                    <div class="relative">
                        <input
                            type="text"
                            x-model="search"
                            @input="filterTags()"
                            @keydown.enter.prevent="selectHighlighted()"
                            @keydown.backspace="handleBackspace()"
                            @keydown.arrow-down.prevent="highlightNext()"
                            @keydown.arrow-up.prevent="highlightPrev()"
                            placeholder="Search or create tags..."
                            :disabled="tags.length >= 5"
                            class="w-full px-4 py-3 rounded-md border border-gray-300 bg-white dark:bg-gray-700 dark:border-gray-600 text-gray-900 dark:text-gray-100 shadow-sm focus:outline-none focus:ring-2 focus:ring-orange-700 focus:border-orange-700 dark:focus:ring-orange-500 dark:focus:border-orange-500 transition disabled:bg-gray-100 dark:disabled:bg-gray-800 disabled:cursor-not-allowed"
                        >

                        <!-- Suggestions Dropdown -->
                        <div x-show="suggestions.length > 0 && showSuggestions"
                             @click.away="showSuggestions = false"
                             class="absolute z-50 w-full mt-1 bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-600 rounded-md shadow-2xl max-h-80 overflow-y-auto"
                             style="display: none;">
                            <ul class="py-1 text-base sm:text-sm">
                                <template x-for="(suggestion, index) in suggestions" :key="index">
                                    <li
                                        @click="addTag(suggestion)"
                                        @mouseenter="highlightedIndex = index"
                                        class="px-4 py-3 cursor-pointer flex items-center justify-between border-b border-gray-100 dark:border-gray-700 last:border-0"
                                        :class="index === highlightedIndex ? 'bg-orange-100 dark:bg-gray-700' : 'bg-white dark:bg-gray-800'"
                                    >
                                        <div class="flex flex-col">
                                            <span
                                                class="font-semibold capitalize"
                                                :class="index === highlightedIndex ? 'text-gray-900 dark:text-gray-100' : 'text-gray-700 dark:text-gray-300'"
                                            >
                                                <!-- Show 'Create: ' prefix if it's the custom tag option (last item and matches search) -->
                                                <span x-text="suggestion === search && !allAvailableTags.includes(suggestion.toLowerCase()) ? 'Create: ' + suggestion : suggestion"></span>
                                            </span>
                                        </div>

                                        <!-- Add Icon -->
                                        <svg x-show="index === highlightedIndex" class="w-5 h-5 text-orange-600 dark:text-orange-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="display: none;">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                                        </svg>
                                    </li>
                                </template>
                            </ul>
                        </div>
                    </div>

                    <p x-show="tags.length >= 5" class="text-xs text-red-500 mt-1" style="display: none;">Maximum of 5 tags allowed.</p>
                </div>

                <script>
                    document.addEventListener('alpine:init', () => {
                        Alpine.data('tagSelector', () => ({
                            tags: @json($selectedTags),
                            allAvailableTags: @json($allTags),
                            search: '',
                            suggestions: [],
                            showSuggestions: false,
                            highlightedIndex: -1,

                            filterTags() {
                                if (this.search.length < 1) {
                                    this.suggestions = [];
                                    this.showSuggestions = false;
                                    return;
                                }

                                const query = this.search.toLowerCase();
                                const exactMatchExists = this.allAvailableTags.some(tag => tag.toLowerCase() === query);

                                // Filter existing tags
                                this.suggestions = this.allAvailableTags.filter(tag =>
                                    tag.toLowerCase().includes(query) &&
                                    !this.tags.map(t => t.toLowerCase()).includes(tag.toLowerCase())
                                ).slice(0, 50); // Increased limit significantly

                                // Add "Create new tag" option if exact match doesn't exist and not already selected
                                if (!exactMatchExists && !this.tags.map(t => t.toLowerCase()).includes(query)) {
                                    this.suggestions.push(this.search); // Push original case
                                }

                                this.showSuggestions = true;
                                this.highlightedIndex = -1;
                            },

                            addTag(tag) {
                                tag = tag.trim();
                                // Check if tag is already selected (case-insensitive)
                                if (tag && this.tags.length < 5 && !this.tags.map(t => t.toLowerCase()).includes(tag.toLowerCase())) {
                                    this.tags.push(tag);
                                    this.search = '';
                                    this.suggestions = [];
                                    this.showSuggestions = false;
                                }
                            },

                            removeTag(index) {
                                this.tags.splice(index, 1);
                            },

                            handleBackspace() {
                                if (this.search === '' && this.tags.length > 0) {
                                    this.tags.pop();
                                }
                            },

                            highlightNext() {
                                if (this.showSuggestions && this.suggestions.length > 0) {
                                    this.highlightedIndex = (this.highlightedIndex + 1) % this.suggestions.length;
                                }
                            },

                            highlightPrev() {
                                if (this.showSuggestions && this.suggestions.length > 0) {
                                    this.highlightedIndex = (this.highlightedIndex - 1 + this.suggestions.length) % this.suggestions.length;
                                }
                            },

                            selectHighlighted() {
                                if (this.highlightedIndex >= 0 && this.suggestions.length > 0) {
                                    this.addTag(this.suggestions[this.highlightedIndex]);
                                } else {
                                     this.addTag(this.search);
                                }
                            }
                        }));
                    });
                </script>

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
