<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RegisterTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_displays_the_register_page()
    {
        $response = $this->get(route('register.form'));

        $response->assertStatus(200);
        $response->assertViewIs('auth.register');
    }

    public function test_it_registers_a_user_with_valid_data()
    {
        $response = $this->post(route('register'), [
            'name' => 'Милен Атанасов',
            'email' => 'milen@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ]);

        $this->assertDatabaseHas('users', [
            'email' => 'milen@example.com',
        ]);

        $this->assertAuthenticated();

        $response->assertRedirect(route('products.index'));
    }

    public function test_it_requires_all_fields_for_registration()
    {
        $response = $this->post(route('register'), []);

        $response->assertSessionHasErrors(['name', 'email', 'password']);
    }

    public function test_it_requires_password_confirmation_to_match()
    {
        $response = $this->post(route('register'), [
            'name' => 'Милен',
            'email' => 'milen@example.com',
            'password' => 'password123',
            'password_confirmation' => 'wrongpass',
        ]);

        $response->assertSessionHasErrors('password');
    }

    public function test_it_requires_unique_email()
    {
        User::factory()->create([
            'email' => 'milen@example.com',
        ]);

        $response = $this->post(route('register'), [
            'name' => 'Друг Милен',
            'email' => 'milen@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ]);

        $response->assertSessionHasErrors('email');
    }
}
