<?php

namespace Tests\Feature\Profile;

use App\Models\Order;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProfileTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_index_shows_empty_message_when_no_orders(): void
    {
        $this->get(route('profile.index'))
            ->assertOk()
            ->assertViewIs('profile.index')
            ->assertSee('Нямате направени поръчки.');
    }

    public function test_guest_index_lists_guest_orders_from_session(): void
    {
        $o1 = Order::create([
            'status' => 'pending',
            'total_price' => 12.50,
            'name' => 'Гост 1',
            'email' => 'g1@example.com',
            'phone' => '0888000001',
            'address' => 'Адрес 1',
        ]);
        $o2 = Order::create([
            'status' => 'processing',
            'total_price' => 20.00,
            'name' => 'Гост 2',
            'email' => 'g2@example.com',
            'phone' => '0888000002',
            'address' => 'Адрес 2',
        ]);

        $this->withSession(['guest_orders' => [$o1->id => true, $o2->id => true]])
            ->get(route('profile.index'))
            ->assertOk()
            ->assertSee((string) $o1->id)
            ->assertSee((string) $o2->id);
    }

    public function test_authenticated_index_shows_only_own_orders(): void
    {
        $owner = User::factory()->create();
        $other = User::factory()->create();

        $ownOrder = Order::create([
            'status' => 'pending',
            'total_price' => 15.00,
            'name' => $owner->name,
            'email' => $owner->email,
            'phone' => '0888000000',
            'address' => 'София',
            'user_id' => $owner->id,
        ]);
        $foreignOrder = Order::create([
            'status' => 'pending',
            'total_price' => 9.99,
            'name' => $other->name,
            'email' => $other->email,
            'phone' => '0888999999',
            'address' => 'Пловдив',
            'user_id' => $other->id,
        ]);

        $response = $this->actingAs($owner)->get(route('profile.index'));

        $response->assertOk();
        $response->assertViewHas('orders', function ($orders) use ($ownOrder, $foreignOrder) {
            return $orders->contains('id', $ownOrder->id)
                && ! $orders->contains('id', $foreignOrder->id);
        });
    }

    public function test_edit_redirects_with_error_when_order_not_accessible_for_guest(): void
    {
        $order = Order::create([
            'status' => 'pending',
            'total_price' => 10.00,
            'name' => 'Гост',
            'email' => 'guest@example.com',
            'phone' => '0888000000',
            'address' => 'София',
        ]);

        $this->get(route('profile.edit', $order->id))
            ->assertRedirect(route('profile.index'))
            ->assertSessionHas('error', 'Поръчката не е намерена или нямате достъп до нея');
    }

    public function test_edit_displays_order_for_guest_when_in_session(): void
    {
        $order = Order::create([
            'status' => 'pending',
            'total_price' => 10.00,
            'name' => 'Гост',
            'email' => 'guest@example.com',
            'phone' => '0888000000',
            'address' => 'София',
        ]);

        $this->withSession(['guest_orders' => [$order->id => true]])
            ->get(route('profile.edit', $order->id))
            ->assertOk()
            ->assertViewIs('profile.edit')
            ->assertSee("Поръчка #{$order->id}");
    }

    public function test_edit_displays_order_for_owner_user(): void
    {
        $user = User::factory()->create();
        $order = Order::create([
            'status' => 'pending',
            'total_price' => 22.00,
            'name' => $user->name,
            'email' => $user->email,
            'phone' => '0888000000',
            'address' => 'София',
            'user_id' => $user->id,
        ]);

        $this->actingAs($user)
            ->get(route('profile.edit', $order->id))
            ->assertOk()
            ->assertViewIs('profile.edit')
            ->assertSee("Поръчка #{$order->id}");
    }

    public function test_authenticated_user_cannot_edit_foreign_order(): void
    {
        $owner = User::factory()->create();
        $other = User::factory()->create();

        $order = Order::create([
            'status' => 'pending',
            'total_price' => 12.00,
            'name' => $owner->name,
            'email' => $owner->email,
            'phone' => '0888000000',
            'address' => 'София',
            'user_id' => $owner->id,
        ]);

        $this->actingAs($other)
            ->get(route('profile.edit', $order->id))
            ->assertRedirect(route('profile.index'))
            ->assertSessionHas('error', 'Поръчката не е намерена или нямате достъп до нея');
    }
}
