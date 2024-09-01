<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Post</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;500;600&display=swap" rel="stylesheet">

    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Poppins', sans-serif;
            background-color: #f8f9fa;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
        }

        .background {
            position: absolute;
            width: 100%;
            height: 100%;
            top: 0;
            left: 0;
            z-index: -1;
        }

        .background .shape {
            height: 280px;
            width: 280px;
            position: absolute;
            border-radius: 50%;
        }

        .shape:first-child {
            background: linear-gradient(#df2550, #d75271);
            left: 10%;
            top: 50px;
        }

        .shape:last-child {
            background: linear-gradient(to right, #ea1647, #d75271);
            right: 10%;
            bottom: 50px;
        }

        .container {
            max-width: 800px;
            width: 90%;
            background-color: rgba(255, 255, 255, 0.9);
            border-radius: 15px;
            backdrop-filter: blur(15px);
            border: 1px solid rgba(255, 255, 255, 0.3);
            box-shadow: 0 0 40px rgba(8, 7, 16, 0.6);
            padding: 40px;
            text-align: center;
            z-index: 1;
        }

        .container h3 {
            font-size: 24px;
            font-weight: 600;
            color: #df2550;
            margin-bottom: 20px;
        }

        .success-message {
            color: green;
            margin-bottom: 15px;
            text-align: center;
        }

        label {
            display: block;
            margin-top: 15px;
            font-size: 14px;
            font-weight: 500;
            color: #000000;
            text-align: left;
        }

        input[type="text"],
        textarea {
            width: 100%;
            background-color: rgba(255, 255, 255, 0.8);
            border: 1px solid rgba(200, 200, 200, 0.6);
            border-radius: 5px;
            padding: 12px;
            margin-top: 8px;
            font-size: 14px;
            font-weight: 300;
            color: #333;
        }

        textarea {
            height: 100px;
            resize: vertical;
        }

        ::placeholder {
            color: #666;
            font-weight: 400;
        }

        .tags {
            margin: 20px 0;
            text-align: left;
        }

        .tags h4 {
            margin-bottom: 10px;
            font-size: 16px;
            color: #000000;
        }

        .tags div {
            margin-bottom: 10px;
            display: flex;
            align-items: center;
        }

        .tags input[type="checkbox"] {
            margin-right: 10px;
        }

        .custom-tag-input input {
            margin-top: 10px;
            margin-bottom: 20px;
        }

        .button-group {
            display: flex;
            justify-content: space-between;
            gap: 10px;
            margin-top: 20px;
            flex-wrap: wrap;
        }

        button {
            width: calc(50% - 10px);
            background-color: #ffffff;
            color: #333;
            padding: 12px 0;
            font-size: 16px;
            font-weight: 600;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s ease-in-out;
            border: 1px solid rgba(200, 200, 200, 0.6);
        }

        button:hover {
            background-color: #e0e0e0;
        }

        .cancel-button {
            background-color: #ba2020;
            color: #ffffff;
            border: none;
        }

        .cancel-button:hover {
            background-color: #a81c1c;
        }

        @media (max-width: 768px) {
            .container {
                padding: 30px;
            }

            .button-group {
                flex-direction: column;
            }

            button {
                width: 100%;
            }
        }

        @media (max-width: 480px) {
            .container {
                padding: 20px;
            }

            .container h3 {
                font-size: 20px;
            }

            input[type="text"],
            textarea {
                font-size: 12px;
            }

            button {
                font-size: 14px;
            }
        }
    </style>
</head>

<body>
    <div class="background">
        <div class="shape"></div>
        <div class="shape"></div>
    </div>

    <div class="container">
        <h3>Create a New Post</h3>

        @if(session('success'))
        <p class="success-message">{{ session('success') }}</p>
        @endif

        <form action="{{ route('questions.store') }}" method="POST">
            @csrf
            <label for="Title">Title:</label>
            <input type="text" id="Title" name="Title" placeholder="Enter the title" required>

            <label for="Content">Content:</label>
            <textarea id="Content" name="Content" placeholder="Write your content here" required></textarea>

            <div class="tags">
                <h4>Select Tags:</h4>
                @php
                $tags = ['C', 'DBMS', 'JavaScript', 'CSS', 'C++'];
                @endphp

                @foreach($tags as $tag)
                <div>
                    <input type="checkbox" id="tag_{{ $tag }}" name="Tags[]" value="{{ $tag }}">
                    <label for="tag_{{ $tag }}">{{ $tag }}</label>
                </div>
                @endforeach

                <div class="custom-tag-input">
                    <label for="custom-tags">Or add custom tags (comma-separated):</label>
                    <input type="text" id="custom-tags" name="custom_tags" placeholder="e.g. DSA, DBMS">
                </div>
            </div>

            <div class="button-group">
                <button type="button" class="cancel-button" onclick="window.location.href='{{ route('questions.index') }}'">Cancel</button>
                <button type="submit">Create Post</button>
            </div>
        </form>
    </div>
</body>

</html>
