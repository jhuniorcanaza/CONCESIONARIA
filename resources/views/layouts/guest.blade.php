@props(['title' => '', 'bodyClass' => '', 'socialLinks' => true])

<x-base-layout :$title :$bodyClass>

    <main>
        <div class="container-small page-login">
            <div class="flex" style="gap: 5rem">
                <div class="auth-page-form">

                    <div class="text-center">
                        <a href="/">
                            <img src="/img/logoipsum-265.svg" alt="" />
                        </a>
                    </div>

                    <h1 class="auth-page-title">{{ $title }}</h1>

                    {{ $slot }}

                    @if($socialLinks)
                        <div class="grid grid-cols-2 gap-1 social-auth-buttons">
                            <x-button url="/auth/google" name="Google" image="/img/google.png" />
                            <x-button url="/auth/facebook" name="Facebook" image="/img/facebook.png" />
                        </div>
                    @endif

                    @if(isset($footerLink))
                        <div class="login-text-dont-have-account">
                            {{ $footerLink }}
                        </div>
                    @endif

                </div>
                <div class="auth-page-image">
                    <img src="/img/car-png-39071.png" alt="" class="img-responsive" />
                </div>
            </div>
        </div>
    </main>

</x-base-layout>
