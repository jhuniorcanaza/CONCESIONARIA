<x-mail::message>
# Welcome to {{ config('app.name') }}

Hello {{ $user->name }},

Thank you for registering with us! We're excited to have you on board.

<x-mail::button :url="route('home')">
Visit Our Site
</x-mail::button>

Best regards,<br>
{{ config('app.name') }}
</x-mail::message>
