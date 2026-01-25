<!DOCTYPE html>
<html>
<head>
    <title>Account Created - JimsIndia</title>
</head>
<body>
    <h1>Welcome, {{ $user->name }}!</h1>
    <p>An account has been created for you on the JimsIndia platform.</p>
    <p>Here are your login credentials:</p>
    <p>
        <strong>Email:</strong> {{ $user->email }}<br>
        <strong>Password:</strong> {{ $password }}
    </p>
    <p>Please login and change your password immediately.</p>
    <p>
        <a href="{{ route('login') }}">Login Here</a>
    </p>
    <p>Thank you!</p>
</body>
</html>
