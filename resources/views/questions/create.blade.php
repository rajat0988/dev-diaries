<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Post</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f8f9fa;
            color: #212529;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        .container {
            max-width: 600px;
            width: 95%;
            background-color: #ffffff;
            border-radius: 8px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
            padding: 20px;
        }

        h1 {
            color: #343a40;
            margin-bottom: 20px;
            font-size: 24px;
            text-align: center;
        }

        form {
            display: flex;
            flex-direction: column;
        }

        label {
            font-weight: bold;
            margin-bottom: 5px;
        }

        input[type="text"],
        textarea {
            width: 95%;
            padding: 10px;
            border: 1px solid #ced4da;
            border-radius: 4px;
            margin-bottom: 15px;
        }

        textarea {
            resize: vertical;
            height: 150px;
        }

        .tags {
            margin: 20px 0;
        }

        .tags h4 {
            margin-bottom: 10px;
            font-size: 18px;
            color: #495057;
        }

        .tags div {
            margin-bottom: 10px;
        }

        .tags input[type="checkbox"] {
            margin-right: 8px;
        }

        .custom-tag-input {
            margin-top: 10px;
            margin-bottom: 20px;
        }

        button {
            padding: 10px 15px;
            font-size: 16px;
            color: #ffffff;
            background-color: #007bff;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            transition: background-color 0.2s ease-in-out;
            align-self: center;
            margin-right: 10px;
        }

        button:hover {
            background-color: #0056b3;
        }

        .cancel-button {
            background-color: #ba2020;
        }

        .cancel-button:hover {
            background-color: #b82f2f;
        }

        .success-message {
            color: green;
            margin-bottom: 15px;
            text-align: center;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Create a New Post</h1>

        @if(session('success'))
            <p class="success-message">{{ session('success') }}</p>
        @endif

        <form action="{{ route('questions.store') }}" method="POST">
            @csrf
            <label for="Title">Title:</label>
            <input type="text" id="Title" name="Title">

            <label for="Content">Content:</label>
            <textarea id="Content" name="Content"></textarea>

            <div class="tags">
                <h4>Select Tags:</h4>
                @php
                    $tags = ['Laravel', 'PHP', 'JavaScript', 'CSS', 'HTML'];
                @endphp

                @foreach($tags as $tag)
                    <div>
                        <input type="checkbox" id="tag_{{ $tag }}" name="Tags[]" value="{{ $tag }}">
                        <label for="tag_{{ $tag }}">{{ $tag }}</label>
                    </div>
                @endforeach

                <div class="custom-tag-input">
                    <label for="custom-tags">Or add custom tags (comma-separated):</label>
                    <input type="text" id="custom-tags" name="custom_tags" placeholder="e.g. DSA,DBMS">
                </div>
            </div>

            <div style="text-align: center;">
                <button type="submit">Create Post</button>
                <button type="button" class="cancel-button" onclick="window.location.href='{{ route('questions.index') }}'">Cancel</button>
            </div>
        </form>
    </div>
</body>
</html>
