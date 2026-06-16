<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class AuthTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_cannot_access_protected_route()
    {
        $response = $this->get('/profile');
        $response->assertRedirect('/login');
    }

    public function test_authenticated_user_can_access_protected_route()
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get('/profile');
        $response->assertStatus(200);
    }

    public function test_user_can_access_register_route()
    {
        $response = $this->get('/register');
        $response->assertStatus(200);
    }

    public function test_user_can_register_via_form()
    {

        $response = $this->post('/register', [
            'email'=> 'user@example.com',
            'name' => 'User',
            'password' => 'password',
            'password_confirmation' => 'password',
        ]);
        
        $this->assertAuthenticated();

        $response->assertRedirect('/');
        
        $this->assertDatabaseHas('users', [
            'email' => 'user@example.com',
            'name' => 'User',
        ]);        
    }    

    public function test_user_can_access_login_route()
    {
        $response = $this->get('/login');
        $response->assertStatus(200);
    }

    public function test_user_can_login_via_form()
    {
        $user = User::factory()->create([
            'email' => 'user@example.com',
            'password' => bcrypt('password'),
        ]);
    
        $response = $this->post('/login', [
            'email' => 'user@example.com',
            'password' => 'password',
        ]);
    
        $this->assertAuthenticated();
        $response->assertRedirect('/');
    }    
    public function test_user_can_logout()
    {
        $user = User::factory()->create([
            'email' => 'user@example.com',
            'password' => bcrypt('password'),
        ]);
    
        $this->actingAs($user);

        $response = $this->post('/logout');
    
        $this->assertGuest();
        
        $response->assertRedirect('/');
    }    

    public function test_user_can_update_profile()
    {
        $user = User::factory()->create([
            'email' => 'user@example.com',
            'name' => 'User',
            'password' => bcrypt('password'),
        ]);
    
        $this->actingAs($user);

        $response = $this->put('/user-update', [
            'email'=> 'new_user@example.com',
            'name' => 'New User',
            'update_password' => 'password',
        ]);
        
        $this->assertAuthenticated();

        $response->assertRedirect('/');
        
        $this->assertDatabaseHas('users', [
            'id' => $user->id,
            'email' => 'new_user@example.com',
            'name' => 'New User',
        ]);        
    }    
    public function test_user_can_update_password()
    {
        $user = User::factory()->create([
            'email' => 'user@example.com',
            'name' => 'User',
            'password' => bcrypt('password'),
        ]);
    
        $this->actingAs($user);

        $response = $this->put('/user-update-password', [
            'old_password'=> 'password',
            'new_password' => 'new_password',
            'new_password_confirmation' => 'new_password',
        ]);
        
        $this->assertAuthenticated();

        $response->assertRedirect('/');

        $user->refresh();
        
        $this->assertTrue(Hash::check('new_password', $user->password));
    }    
    public function test_user_can_delete_profile()
    {
        $user = User::factory()->create([
            'email' => 'user@example.com',
            'name' => 'User',
            'password' => bcrypt('password'),
        ]);
    
        $this->actingAs($user);

        $response = $this->delete('/user-delete', [
            'destroy_password' => 'password',
        ]);
        
        $response->assertRedirect('/');
        
        $this->assertDatabaseMissing('users', [
            'id' => $user->id
        ]);        
        
        $this->assertGuest();
    }    

}
