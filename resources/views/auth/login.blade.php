@php
    // Redirect to register page since we have a combined login/register page
    header('Location: ' . route('register'));
    exit();
@endphp
