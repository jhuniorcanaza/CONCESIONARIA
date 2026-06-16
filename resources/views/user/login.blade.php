<x-guest-layout title="Login" bodyClass="page-login">

  <!-- Session Status -->
  <x-session-status class="mb-4" :status="session('status')" />

  <form action="{{ route('login.auth') }}" method="POST">
    @csrf

    <x-ui.form-group name="email">
      <input type="email" name="email" placeholder="Your Email"  value="{{ old('email') }}" />
    </x-ui.form-group>

    <x-ui.form-group name="password">
      <input type="password" name="password" placeholder="Your Password" />
    </x-ui.form-group>

    <div class="text-right mb-medium">
      <a href="{{ route('password.forgot') }}" class="auth-page-password-reset">Reset Password</a>
    </div>

    <button type="submit" class="btn btn-primary btn-login w-full">Login</button>

  </form>

  <x-slot:footerLink>
    Don't have an account? -
    <a href="{{ route('register.create') }}"> Click here to create one</a>
  </x-slot:footerLink>

</x-guest-layout>  