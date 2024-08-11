<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Post</title>
</head>
<body>
    <h1>Create a New Post</h1>

    @if(session('success'))
        <p style="color: green;">{{ session('success') }}</p>
    @endif

    <form action="{{ route('questions.store') }}" method="POST">
        @csrf
        <label for="Title">Title:</label><br>
        <input type="text" id="Title" name="Title" required><br><br>

        <label for="Content">Content:</label><br>
        <textarea id="Content" name="Content" required></textarea><br><br>

        <div class="mt-4">
            <h4>Select Tags:</h4>
            @php
                // Example tags, this could be replaced with dynamic tags from the database
                $tags = ['Laravel', 'PHP', 'JavaScript', 'CSS', 'HTML'];
            @endphp

            @foreach($tags as $tag)
                <div>
                    <input type="checkbox" id="tag_{{ $tag }}" name="Tags[]" value="{{ $tag }}">
                    <label for="tag_{{ $tag }}">{{ $tag }}</label>
                </div>
            @endforeach
        </div>

        <button type="submit">Create Post</button>
    </form>
</body>
</html>