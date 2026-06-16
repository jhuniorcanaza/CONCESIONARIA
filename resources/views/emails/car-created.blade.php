<x-mail::message>
Hello,
# {{ $car->user->name }} just created a new car {{ $car->title }}


<x-mail::button :url="route('car.show', $car)">
Open this car
</x-mail::button>

Best regards,<br>
{{ config('app.name') }}
</x-mail::message>
