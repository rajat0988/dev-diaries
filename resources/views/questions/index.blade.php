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

    .button-44 {
        background: #e62143;
        border-radius: 11px;
        box-sizing: border-box;
        color: #fff;
        cursor: pointer;
        display: flex;
        font-family: 'Quicksand', sans-serif;
        font-size: 1.15em;
        font-weight: 700;
        justify-content: center;
        line-height: 33.4929px;
        padding: .8em 1em;
        text-align: center;
        text-shadow: rgba(0, 0, 0, .3) 1px 1px 1px;
        transition: all .2s ease-in-out;
        user-select: none;
        width: 100%;
        border: 0;
    }

    .button-44:hover {
        background: #d11a39;
    }

    .button-44:active,
    .button-44:focus {
        box-shadow: rgba(0, 0, 0, .3) 0 3px 3px inset;
        outline: 0;
    }
</style>

<div class="relative text-gray-600 mt-8 lg:mr-16">
    <a href="{{ route('questions.create') }}" class="button-44">Hi, there. Want to ask a question?</a>
    <br>

    @if (Auth::user()->role === 'admin')
        <a href="{{ route('admin.reported') }}" class="button-44">View Reported</a>
        <br><br>
    @endif
</div>

<article id="the-article">
    <div class="mx-auto max-w-6xl">
        <div class="p-2 rounded">
            <div class="flex flex-col md:flex-row">
                <div class="md:w-1/3 p-4 text-sm">
                    <div class="sticky inset-x-0 top-0 left-0 py-12">
                        <div class="mb-2">Apply Filters:</div>
                        <div class="text-xs text-gray-600">
                            {{-- filters --}}
                            <div class="box">
                                <h2>Apply filters:</h2>
                                <form action="{{ route('questions.filter') }}" method="GET" class="mb-4">
                                    @if (isset($tagsToShow))
                                        <div>
                                            <h2>All Tags</h2>
                                            @foreach ($tagsToShow as $tag)
                                                <label class="block mb-2">
                                                    <input type="checkbox" name="tags[]"
                                                        value="{{ $tag }}"
                                                        @if (is_array(request('tags')) && in_array($tag, request('tags'))) checked @endif>
                                                    {{ $tag }}
                                                </label>
                                            @endforeach
                                        </div>
                                    @else
                                        <div>
                                            <h3>Filter by Tags</h3>
                                            @foreach ($mostUsedTags as $tag)
                                                <label class="block mb-2">
                                                    <input type="checkbox" name="tags[]"
                                                        value="{{ $tag }}"
                                                        @if (is_array(request('tags')) && in_array($tag, request('tags'))) checked @endif>
                                                    {{ $tag }}
                                                </label>
                                            @endforeach
                                        </div>
                                    @endif
                                    <button type="submit" class="btn btn-primary">Filter</button>
                                </form>
                                <a href="{{ route('questions.loadMoreTags') }}" class="btn btn-primary">Load More
                                    Tags</a>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="md:w-2/3 py-12">
                    <div class="p-4">
                        @if(isset($selectedTags) && !empty($selectedTags))
                            <h3 class="text-2xl text-blue-500 mb-4">Filtered Questions</h3>
                            @forelse($filtered_questions as $question)
                                <div class="item px-6 py-6" x-data="{ isOpen: false }">
                                    <div class="flex items-center justify-between cursor-pointer"
                                         @click="isOpen = !isOpen; window.location.href = '{{ route('questions.show', $question->id) }}'">
                                        <h4 :class="{ 'text-green-400 font-medium': isOpen }">
                                            {{ $question->Title }}
                                        </h4>
                                        <svg @click.stop="isOpen = !isOpen"
                                            :class="{ 'transform rotate-180': isOpen }"
                                            class="w-5 h-5 text-gray-500" fill="none" stroke-linecap="round"
                                            stroke-linejoin="round" stroke-width="2" viewBox="0 0 24 24"
                                            stroke="currentColor">
                                            <path d="M19 9l-7 7-7-7"></path>
                                        </svg>
                                    </div>
                                    <div x-show="isOpen" @click.away="isOpen = false" class="mt-3"
                                        :class="{ 'text-gray-600': isOpen }">
                                        <pre>
{{ $question->Upvotes >= 0 ? 'Upvotes: ' . $question->Upvotes : 'Downvotes: ' . -1 * $question->Upvotes }}
Answered: {{ $question->Answered ? 'Yes' : 'No' }}
                                        </pre>
                                    </div>
                                </div>
                            @empty
                                <p>No questions match the selected tags.</p>
                            @endforelse
                            {{ $filtered_questions->appends(['tags' => $selectedTags])->links() }}
                        @endif

                        <h3 class="text-2xl text-blue-500 mt-8 mb-4">Recent Questions</h3>
                        @foreach ($all_questions as $question)
                            <div class="item px-6 py-6" x-data="{ isOpen: false }">
                                <div class="flex items-center justify-between cursor-pointer"
                                     @click="isOpen = !isOpen; window.location.href = '{{ route('questions.show', $question->id) }}'">
                                    <h4 :class="{ 'text-green-400 font-medium': isOpen }">
                                        {{ $question->Title }}
                                    </h4>
                                    <svg @click.stop="isOpen = !isOpen"
                                        :class="{ 'transform rotate-180': isOpen }"
                                        class="w-5 h-5 text-gray-500" fill="none" stroke-linecap="round"
                                        stroke-linejoin="round" stroke-width="2" viewBox="0 0 24 24"
                                        stroke="currentColor">
                                        <path d="M19 9l-7 7-7-7"></path>
                                    </svg>
                                </div>
                                <div x-show="isOpen" @click.away="isOpen = false" class="mt-3"
                                    :class="{ 'text-gray-600': isOpen }">
                                    <pre>
{{ $question->Upvotes >= 0 ? 'Upvotes: ' . $question->Upvotes : 'Downvotes: ' . -1 * $question->Upvotes }}
Answered: {{ $question->Answered ? 'Yes' : 'No' }}
                                    </pre>
                                </div>
                            </div>
                        @endforeach
                        {{ $all_questions->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</article>

<div x-data="scrollHandler(document.getElementById('the-article'))" x-cloak aria-hidden="true"
    @scroll.window="calculateHeight(window.scrollY)"
    class="fixed h-screen w-1 hover:bg-gray-200 top-0 left-0 bg-gray-300">
    <div :style="`max-height:${height}%`" class="h-full bg-green-400"></div>
</div>

<div id="alpine-devtools" x-data="devtools()" x-show="alpines.length" x-init="start()">
</div>

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

@endsection
