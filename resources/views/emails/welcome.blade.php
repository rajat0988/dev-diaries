<!DOCTYPE html>
<html>
<head>
    <title>Welcome to JimsIndia</title>
</head>
<body>
    <h1>Welcome, {{ $user->name }}!</h1>
    <p>Your account has been approved by the administrator.</p>
    <p>You can now login to the platform using your credentials.</p>
    <p>
        <a href="{{ route('login') }}">Login Here</a>
    </p>
    <p>Thank you!</p>
</body>
</html>
