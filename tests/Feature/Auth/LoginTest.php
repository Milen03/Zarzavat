<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class LoginTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_displays_the_login_page()
    {
        $response = $this->get(route('login.form'));

        $response->assertStatus(200);
        $response->assertViewIs('auth.login');
    }

    public function test_it_logs_in_user_and_redirects_to_products()
    {
        $user = User::factory()->create([
            'password' => Hash::make('password123'),
            'role' => 'user',
        ]);

        $response = $this->post(route('login'), [
            'email' => $user->email,
            'password' => 'password123',
        ]);

        $this->assertAuthenticatedAs($user);
        $response->assertRedirect(route('products.index'));
    }

    public function test_it_logs_in_user_and_redirects_to_dashboard()
    {
        $admin = User::factory()->create([
            'password' => Hash::make('password123'),
            'role' => 'admin',
        ]);

        $response = $this->post(route('login'), [
            'email' => $admin->email,
            'password' => 'password123',
        ]);

        $this->assertAuthenticatedAs($admin);
        $response->assertRedirect(route('admin.dashboard'));
    }

    public function test_it_requires_email_and_password()
    {
        $response = $this->post(route('login'), []);

        $response->assertSessionHasErrors(['email', 'password']);
        $this->assertGuest();
    }

    public function test_it_fails_with_invalid_credentials()
    {
        $user = User::factory()->create([
            'password' => Hash::make('password123'),
            'role' => 'user',
        ]);

        $response = $this->post(route('login'), [
            'email' => $user->email,
            'password' => 'wrongpass', // достатъчно дълга, за да мине валидация
        ]);

        $response->assertSessionHasErrors('email');
        $this->assertGuest();
    }
}
