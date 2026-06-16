<?php

namespace App\Http\Controllers;

use App\Http\Requests\Auth\LoginRequest;
use App\Models\User;
use Illuminate\Auth\Events\Login;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Events\Logout;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class UserController extends Controller
{
    public function create() {
        return view("user.register");
    }

    public function store(Request $request) {   
        
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'password' => 'required|confirmed|min:5'
        ]);

        $user = User::create($validated);

        event(new Registered($user));

        auth()->login($user);
        
        return redirect()->route('home');
    }    
    public function login() {
        return view("user.login");
    }    

    public function auth(LoginRequest $request) {    

        $request->authenticate();

        event(new Login('web', auth()->user(), true));

        $request->session()->regenerate();

        return redirect()->intended(route('home'));    
    }

    public function logout(Request $request) {  
        
        $user = auth()->user(); // Get user before logout
        auth()->logout();

        event(new Logout('web', $user));

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect()->route('home');
    }

    public function passwordForgot() {
        return view("user.forgot-password");
    }

    public function passwordEmail(Request $request) {   

        $request->validate([
            'email' => ['required', 'email'],
        ]);

        // We will send the password reset link to this user. Once we have attempted
        // to send the link, we will examine the response then see the message we
        // need to show to the user. Finally, we'll send out a proper response.
        $status = Password::sendResetLink(
            $request->only('email')
        );

        return $status == Password::RESET_LINK_SENT
                    ? back()->with('status', __($status))
                    : back()->withInput($request->only('email'))
                        ->withErrors(['email' => __($status)]);

    }

    public function passwordReset(string $token) {

        return view("user.reset-password", compact('token'));
    }

    public function passwordStore(Request $request) {

        $request->validate([
            'token' => ['required'],
            'email' => ['required', 'email'],
            'password' => ['required', 'confirmed', 'min:5'],
        ]);

        // Here we will attempt to reset the user's password. If it is successful we
        // will update the password on an actual user model and persist it to the
        // database. Otherwise we will parse the error and return the response.
        $status = Password::reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function (User $user) use ($request) {
                $user->forceFill([
                    'password' => Hash::make($request->password),
                    'remember_token' => Str::random(60),
                ])->save();

                event(new PasswordReset($user));
            }
        );

        // If the password was successfully reset, we will redirect the user back to
        // the application's home authenticated view. If there is an error we can
        // redirect them back to where they came from with their error message.
        return $status == Password::PASSWORD_RESET
                    ? redirect()->route('login')->with('status', __($status))
                    : back()->withInput($request->only('email'))
                        ->withErrors(['email' => __($status)]);

    }   
    public function edit() {
        $user = auth()->user();
        return view("user.edit", compact('user'));
    }

    public function update(Request $request) {   
        
        $user = auth()->user();
        
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:users,email,' . $user->id],
            'avatar' => 'nullable|image|max:1024',
            'update_password' => 'required'
        ]);

        if(Hash::check($validated['update_password'], $user->password)) {
            if($request->hasFile('avatar')) {
                if ($user->avatar) {
                    Storage::disk('public')->delete($user->avatar);
                }
                $validated['avatar'] = $request->file('avatar')->store('avatars', 'public');
            } 

            $user->update($validated);

            return back()->with('success', 'User updated');
        } else {
            return back()->withErrors(['password' => 'Password is incorrect'])->withInput();
        }
    } 

    public function updatePassword(Request $request) {   
        
        $user = auth()->user();
        
        $validated = $request->validate([
            'old_password' => 'required',
            'new_password' => 'required|confirmed|min:5'
        ]);

        if(Hash::check($validated['old_password'], $user->password)) {
            $user->update(['password' => Hash::make($validated['new_password'])]);
            event(new PasswordReset($user));
            return back()->with('success', 'Password was updated');
        } else {
            return back()->withErrors(['old_password' => 'Password is incorrect'])->withInput();
        }
    } 

    public function destroy(Request $request) { 

        $user = auth()->user();
        
        $validated = $request->validate([
            'destroy_password' => 'required'
        ]);

        if(Hash::check($validated['destroy_password'], $user->password)) {

            auth()->logout();
            
            // we can't do $user->cars()->delete() because it will not trigger model events
            $user->cars->each->delete();
            
            $user->delete();

            return back()->with('success','User was deleted');
        } else {
            return back()->withErrors(['password' => 'Password is incorrect'],)->withInput();
        }
    }
}
