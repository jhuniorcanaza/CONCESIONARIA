<x-guest-layout title="" bodyClass="page-signup" :socialLinks="false">

  <h2>Change Name and E-mail</h2>

  <form action="{{ route('user.update') }}" method="POST" enctype='multipart/form-data'>
    @csrf
    @method('PUT')

    <x-ui.form-group label="Current password" name="update_password">
      <input type="password" name="update_password" placeholder="Your Password"  value="" />
    </x-ui.form-group>

    <x-ui.form-group label="Email" name="email">
      <input type="email" name="email" placeholder="Your Email" value="{{ old('email', $user->email) }}" />
    </x-ui.form-group>

    <x-ui.form-group label="Name" name="name">
      <input type="text" name="name" placeholder="Your name"  value="{{ old('name', $user->name) }}" />
    </x-ui.form-group>

    <x-ui.form-group label="Avatar" name="avatar">
      @if($user->avatar)
        <img src="{{ asset('storage/' . $user->avatar) }}" alt="Avatar" style="max-width: 100px; max-height: 100px;">
      @endif  
      <input type="file" name="avatar" />
    </x-ui.form-group>            

    <button type="submit" class="btn btn-primary btn-login w-full">Update</button>

  </form>

  <h2>Change Password</h2>

  <form action="{{ route('user.update.password') }}" method="POST">
    @csrf
    @method('PUT')

    <x-ui.form-group label="Current Password" name="old_password">
      <input type="password" name="old_password" placeholder="Your Current Password"  value="" />
    </x-ui.form-group>
    <x-ui.form-group label="New Password" name="new_password">
      <input type="password" name="new_password" placeholder="Your New Password"  value="{{ old('password') }}" />
    </x-ui.form-group>

    <x-ui.form-group label="Confirm New Password" name="new_password_confirmation">
      <input type="password"  name="new_password_confirmation" placeholder="Repeat New Password" />
    </x-ui.form-group>

    <button type="submit" class="btn btn-primary btn-login w-full">Update Password</button>

  </form>

  <h2>Delete User</h2>

  <form action="{{ route('user.destroy') }}" method="POST"
    onsubmit="return confirm('Are you sure you want to delete your account?');"
  >
    @csrf
    @method('DELETE')

    <x-ui.form-group label="Current password" name="destroy_password" >
      <input type="password" name="destroy_password" placeholder="Your Password"  value="" />
    </x-ui.form-group>

    <button type="submit" class="btn btn-primary btn-login w-full">Delete User</button>

  </form>  

</x-guest-layout> 