@extends('layouts.app')

@section('content')

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/tailwindcss/2.0.3/tailwind.min.css"
        integrity="sha512-wl80ucxCRpLkfaCnbM88y4AxnutbGk327762eM9E/rRTvY/ZGAHWMZrYUq66VQBYMIYDFpDdJAOGSLyIPHZ2IQ=="
        crossorigin="anonymous" />
    <link rel="preconnect" href="https://fonts.gstatic.com">
    <script src="https://cdn.jsdelivr.net/gh/alpinejs/alpine@v2.x.x/dist/alpine.js" defer></script>
    <link href="https://fonts.googleapis.com/css2?family=Quicksand:wght@500&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Spartan:wght@600&display=swap" rel="stylesheet">
</head>

<style>
    * {
        font-family: 'Quicksand', sans-serif;
    }

    body {
        background-color: #f9fafb;
        color: #333;
    }

    .button-primary {
        background-color: #e62143;
        border-radius: 10px;
        padding: 0.8rem 1.5rem;
        font-size: 1rem;
        color: white;
        font-weight: bold;
        transition: background-color 0.2s ease-in-out;
    }

    .button-primary:hover {
        background-color: #d11a39;
    }

    .container {
        max-width: 1200px;
        margin: 0 auto;
        padding: 1rem;
    }

    .heading {
        font-size: 2rem;
        font-weight: bold;
        text-align: center;
        margin-bottom: 2rem;
    }

    .card {
        background-color: white;
        border-radius: 0.75rem;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        padding: 1.5rem;
        margin-bottom: 1.5rem;
    }

    .filters {
        background-color: #f1f1f1;
        padding: 1rem;
        border-radius: 0.75rem;
        margin-bottom: 2rem;
    }
</style>

<body>

    <div class="container">
        <!-- Page Title -->
        <h1 class="heading">Dev Diaries</h1>

        <!-- Button: Ask a Question -->
        <div class="text-center mb-6">
            <a href="{{ route('questions.create') }}" class="button-primary">
                Ask a Question
            </a>
        </div>

        <!-- Admin View: Reported Questions -->
        @if (Auth::user()->role === 'admin')
            <div class="text-center mb-6">
                <a href="{{ route('admin.reported') }}" class="button-primary">View Reported Questions</a>
            </div>
        @endif

        <!-- Filter Section -->
        <div class="filters">
            <h2 class="text-lg font-semibold mb-4">Apply Filters</h2>
            <form action="{{ route('questions.filter') }}" method="GET">
                @if (isset($tagsToShow))
                    <div>
                        <h3 class="text-base font-semibold mb-2">All Tags</h3>
                        @foreach ($tagsToShow as $tag)
                            <label class="block mb-2">
                                <input type="checkbox" name="tags[]" value="{{ $tag }}"
                                    @if (is_array(request('tags')) && in_array($tag, request('tags'))) checked @endif>
                                {{ $tag }}
                            </label>
                        @endforeach
                    </div>
                @else
                    <div>
                        <h3 class="text-base font-semibold mb-2">Most Used Tags</h3>
                        @foreach ($mostUsedTags as $tag)
                            <label class="block mb-2">
                                <input type="checkbox" name="tags[]" value="{{ $tag }}"
                                    @if (is_array(request('tags')) && in_array($tag, request('tags'))) checked @endif>
                                {{ $tag }}
                            </label>
                        @endforeach
                    </div>
                @endif
                <button type="submit" class="button-primary mt-4">Filter</button>
            </form>
            <a href="{{ route('questions.loadMoreTags') }}" class="button-primary mt-4 inline-block">Load More Tags</a>
        </div>

        <!-- Filtered Questions -->
        @if (isset($selectedTags) && !empty($selectedTags))
            <h2 class="text-xl text-blue-500 mb-4">Filtered Questions</h2>
            @forelse($filtered_questions as $question)
                <div class="card">
                    <div class="flex justify-between items-center cursor-pointer"
                        @click="window.location.href = '{{ route('questions.show', $question->id) }}'">
                        <h3 class="font-semibold">{{ $question->Title }}</h3>
                        <div class="text-gray-500">{{ $question->Upvotes >= 0 ? 'Upvotes: ' . $question->Upvotes : 'Downvotes: ' . -$question->Upvotes }}</div>
                    </div>
                    <p class="mt-2 text-gray-600">Answered: {{ $question->Answered ? 'Yes' : 'No' }}</p>
                </div>
            @empty
                <p>No questions match the selected tags.</p>
            @endforelse
            {{ $filtered_questions->appends(['tags' => $selectedTags])->links() }}
        @endif

        <!-- Recent Questions -->
        <h2 class="text-xl text-orange-700 mt-8 mb-4">Recent Questions</h2>
        @foreach ($all_questions as $question)
            <div class="card">
                <div class="flex justify-between items-center cursor-pointer">
                    <a href="{{ route('questions.show', $question->id) }}" class="font-semibold"> <h3>{{ $question->Title }}</h3></a>
                    <div class="text-gray-500">{{ $question->Upvotes >= 0 ? 'Upvotes: ' . $question->Upvotes : 'Downvotes: ' . -$question->Upvotes }}</div>
                </div>
                <p class="mt-2 text-gray-600">Answered: {{ $question->Answered ? 'Yes' : 'No' }}</p>
            </div>
        @endforeach
        {{ $all_questions->links() }}
    </div>

    <!-- Progress Scrollbar -->
    <div x-data="scrollHandler(document.getElementById('the-article'))" x-cloak aria-hidden="true" 
        @scroll.window="calculateHeight(window.scrollY)"
        class="fixed h-screen w-1 top-0 left-0 bg-gray-300">
        <div :style="`max-height:${height}%`" class="h-full bg-green-400"></div>
    </div>

    <!-- Alpine.js Scroll Handler -->
    <script>
        function scrollHandler(element = null) {
            return {
                height: 0,
                element: element,
                calculateHeight(position) {
                    const distanceFromTop = this.element.offsetTop;
                    const contentHeight = this.element.clientHeight;
                    const visibleContent = contentHeight - window.innerHeight;
                    const start = Math.max(0, position - distanceFromTop);
                    const percent = (start / visibleContent) * 100;
                    requestAnimationFrame(() => {
                        this.height = percent;
                    });
                },
                init() {
                    this.element = this.element || document.body;
                    this.calculateHeight(window.scrollY);
                }
            };
        }
    </script>
</body>

@endsection
