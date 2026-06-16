<x-guest-layout title="Forgot Password" bodyClass="page-login">
  
  <!-- Session Status -->
  <x-session-status class="mb-4" :status="session('status')" />

  <form action="{{ route('password.email') }}" method="POST">
    @csrf

    <x-ui.form-group name="email">
      <input type="email" name="email" placeholder="Your Email"  value="{{ old('email') }}" />
    </x-ui.form-group>

    <button type="submit" class="btn btn-primary btn-login w-full">Reset</button>

  </form>

</x-guest-layout>  