<?php

namespace Tests\Feature\Admin;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminOrderTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_is_redirected_from_orders_index(): void
    {
        $this->get(route('admin.orders.index'))
            ->assertRedirect(route('login.form'));
    }

    public function test_non_admin_gets_403_on_orders_index(): void
    {
        $user = User::factory()->create(['role' => 'user']);

        $this->actingAs($user)
            ->get(route('admin.orders.index'))
            ->assertForbidden();
    }

    public function test_admin_sees_orders_list_and_can_filter_by_status(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);

        // Създай поръчки с различни статуси
        $o1 = Order::create([
            'status' => 'pending',
            'total_price' => 10.00,
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
        $o3 = Order::create([
            'status' => 'completed',
            'total_price' => 30.00,
            'name' => 'Гост 3',
            'email' => 'g3@example.com',
            'phone' => '0888000003',
            'address' => 'Адрес 3',
        ]);

        // Без филтър
        $res = $this->actingAs($admin)->get(route('admin.orders.index'));
        $res->assertOk()
            ->assertViewIs('admin.orders.index')
            ->assertViewHas('orders', function ($orders) use ($o1, $o2, $o3) {
                return $orders->contains('id', $o1->id)
                    && $orders->contains('id', $o2->id)
                    && $orders->contains('id', $o3->id);
            });

        // С филтър по статус
        $res = $this->actingAs($admin)->get(route('admin.orders.index', ['status' => 'processing']));
        $res->assertOk()
            ->assertViewHas('orders', function ($orders) use ($o2) {
                return $orders->count() === 1 && $orders->first()->id === $o2->id;
            });
    }

    public function test_admin_can_view_order_details(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $product = Product::factory()->create(['name' => 'Тест продукт', 'price' => 9.99]);

        $order = Order::create([
            'status' => 'pending',
            'total_price' => 9.99,
            'name' => 'Гост',
            'email' => 'guest@example.com',
            'phone' => '0888123456',
            'address' => 'София',
        ]);

        OrderItem::create([
            'order_id' => $order->id,
            'product_id' => $product->id,
            'quantity' => 1,
            'price' => 9.99,
        ]);

        $this->actingAs($admin)
            ->get(route('admin.orders.show', $order))
            ->assertOk()
            ->assertViewIs('admin.orders.show')
            ->assertSee("Поръчка #{$order->id}")
            ->assertSee('Гост')
            ->assertSee('София')
            ->assertSee('Тест продукт');
    }

    public function test_admin_can_update_order_status(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $order = Order::create([
            'status' => 'pending',
            'total_price' => 15.00,
            'name' => 'Гост',
            'email' => 'guest@example.com',
            'phone' => '0888000000',
            'address' => 'Пловдив',
        ]);

        $this->actingAs($admin)
            ->patch(route('admin.orders.update.status', $order), ['status' => 'completed'])
            ->assertRedirect();

        $order->refresh();
        $this->assertEquals('completed', $order->status);
    }

    public function test_update_status_validation_fails_for_invalid_value(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $order = Order::create([
            'status' => 'pending',
            'total_price' => 10.00,
            'name' => 'Гост',
            'email' => 'guest@example.com',
            'phone' => '0888000000',
            'address' => 'София',
        ]);

        $this->actingAs($admin)
            ->patch(route('admin.orders.update.status', $order), ['status' => 'invalid'])
            ->assertSessionHasErrors('status');

        $this->assertEquals('pending', $order->fresh()->status);
    }

    public function test_admin_can_delete_order(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $order = Order::create([
            'status' => 'pending',
            'total_price' => 22.00,
            'name' => 'Гост',
            'email' => 'guest@example.com',
            'phone' => '0888111111',
            'address' => 'Варна',
        ]);

        $this->actingAs($admin)
            ->delete(route('admin.orders.destroy', $order))
            ->assertRedirect(route('admin.orders.index'))
            ->assertSessionHas('success', 'Поръчката е изтрита.');

        $this->assertDatabaseMissing('orders', ['id' => $order->id]);
    }
}
