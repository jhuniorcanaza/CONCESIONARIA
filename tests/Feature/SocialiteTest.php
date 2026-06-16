<?php
namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Socialite\Facades\Socialite;
use Laravel\Socialite\Contracts\User as SocialiteUser;
use Mockery;
use Tests\TestCase;

class SocialiteTest extends TestCase
{
    use RefreshDatabase;

    public function test_redirect_to_provider()
    {
        $response = $this->get(route('socialite.redirect', ['provider' => 'google']));

        $response->assertStatus(302);
    }

    public function test_callback_creates_user_and_logs_in()
    {
        $socialiteUser = Mockery::mock(SocialiteUser::class, function ($mock) {
            $mock->shouldReceive('getId')->andReturn('123456');
            $mock->shouldReceive('getEmail')->andReturn('user@example.com');
            $mock->shouldReceive('getName')->andReturn('Test User');
            $mock->shouldReceive('getAvatar')->andReturn('http://example.com/avatar.jpg');
        });

        Socialite::shouldReceive('driver->user')->andReturn($socialiteUser);

        $response = $this->get(route('socialite.callback', ['provider' => 'google']));

        $this->assertDatabaseHas('users', [
            'email' => 'user@example.com',
            'name' => 'Test User',
        ]);

        $this->assertAuthenticated();

        $response->assertRedirect('/');
    }

    public function test_callback_logs_in_existing_user()
    {
        $user = User::factory()->create([
            'email' => 'user@example.com',
        ]);

        $socialiteUser = Mockery::mock(SocialiteUser::class, function ($mock) {
            $mock->shouldReceive('getId')->andReturn('123456');
            $mock->shouldReceive('getEmail')->andReturn('user@example.com');
            $mock->shouldReceive('getName')->andReturn('Test User');
            $mock->shouldReceive('getAvatar')->andReturn('http://example.com/avatar.jpg');
        });

        Socialite::shouldReceive('driver->user')->andReturn($socialiteUser);

        $response = $this->get(route('socialite.callback', ['provider' => 'google']));

        $this->assertDatabaseCount('users', 1);

        $this->assertAuthenticatedAs($user);

        $response->assertRedirect('/');
    }
}