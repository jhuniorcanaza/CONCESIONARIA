<!DOCTYPE html>
<html>
<head>
    <title>Welcome to {{ config('app.name') }}</title>
</head>
<body>
    <h1>Welcome to {{ config('app.name') }}</h1>
    <p>Hello {{ $user->name }},</p>
    <p>Thank you for registering with us! We're excited to have you on board.</p>
    <p><a href="{{ route('home') }}">Visit Our Site</a></p>
    <p>Best regards,<br>{{ config('app.name') }}</p>
</body>
</html>