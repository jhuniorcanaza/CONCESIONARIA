<x-guest-layout title="Signup" bodyClass="page-signup">

  <form action="{{ route('register.store') }}" method="POST">
    @csrf

    <x-ui.form-group name="email">
      <input type="email" name="email" placeholder="Your Email" value="{{ old('email') }}" />
    </x-ui.form-group>

    <x-ui.form-group name="name">
      <input type="text" name="name" placeholder="Your name"  value="{{ old('name') }}" />
    </x-ui.form-group>

    <x-ui.form-group name="password">
      <input type="password" name="password" placeholder="Your Password"  value="{{ old('password') }}" />
    </x-ui.form-group>

    <x-ui.form-group name="password_confirmation">
      <input type="password"  name="password_confirmation" placeholder="Repeat Password" />
    </x-ui.form-group>

    <button type="submit" class="btn btn-primary btn-login w-full">Register</button>
  </form>

  <x-slot:footerLink>
    Already have an account? -
    <a href="{{ route('login') }}"> Click here to login </a>
  </x-slot:footerLink>

</x-guest-layout> 