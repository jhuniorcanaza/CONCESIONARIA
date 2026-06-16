<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;
use App\Models\Car;
use App\Policies\CarPolicy;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * Сопоставление моделей и их Policy.
     */
    protected $policies = [
        Car::class => CarPolicy::class,
    ];

    /**
     * Регистрация любых сервисов аутентификации / авторизации.
     */
    public function boot()
    {
        $this->registerPolicies();
    }
}
