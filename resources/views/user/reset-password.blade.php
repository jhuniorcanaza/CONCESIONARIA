<x-guest-layout title="Reset Password" bodyClass="page-login">

  <form action="{{ route('password.store') }}" method="POST">
    @csrf

    <!-- Password Reset Token -->
    <input type="hidden" name="token" value="{{ $token }}">

    <x-ui.form-group name="email">
      <input type="email" name="email" placeholder="Your Email"  value="{{ old('email') }}" />
    </x-ui.form-group>

    <x-ui.form-group name="password">
      <input type="password" name="password" placeholder="Your Password" />
    </x-ui.form-group>

    <x-ui.form-group name="password_confirmation">
      <input type="password"  name="password_confirmation" placeholder="Repeat Password" />
    </x-ui.form-group>

    <button type="submit" class="btn btn-primary btn-login w-full">Reset</button>

  </form>

  <x-slot:footerLink>
    Don't have an account? -
    <a href="{{ route('register.create') }}"> Click here to create one</a>
  </x-slot:footerLink>

</x-guest-layout>  