<?php

namespace Tests\Feature\Profile;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProfileOrderItemTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_cannot_access_create_when_not_own_guest_order(): void
    {
        $order = Order::create([
            'status' => 'pending',
            'total_price' => 0,
            'name' => 'Гост',
            'email' => 'guest@example.com',
            'phone' => '0888000000',
            'address' => 'София',
            'user_id' => null,
        ]);

        $this->get(route('profile.add-item.form', $order->id))
            ->assertRedirect(route('profile.index'))
            ->assertSessionHas('error', 'Поръчката не е намерена или нямате достъп до нея');
    }

    public function test_guest_can_add_item_to_own_guest_order(): void
    {
        $product = Product::factory()->create(['price' => 10, 'stock' => 5]);
        $order = Order::create([
            'status' => 'pending',
            'total_price' => 0,
            'name' => 'Гост',
            'email' => 'guest@example.com',
            'phone' => '0888000000',
            'address' => 'София',
            'user_id' => null,
        ]);

        $this->withSession(['guest_orders' => [$order->id => true]])
            ->post(route('profile.add-item', $order->id), [
                'product_id' => $product->id,
                'quantity' => 2,
            ])
            ->assertRedirect(route('profile.edit', $order->id))
            ->assertSessionHas('success', 'Продуктът е добавен успешно към поръчката');

        $this->assertDatabaseHas('order_items', [
            'order_id' => $order->id,
            'product_id' => $product->id,
            'quantity' => 2,
            'price' => 10,
        ]);

        $product->refresh();
        $this->assertEquals(3, $product->stock);

        $order->refresh();
        $this->assertEquals(20, (float) $order->total_price);
    }

    public function test_user_cannot_modify_foreign_order(): void
    {
        $owner = User::factory()->create();
        $other = User::factory()->create();
        $order = Order::create([
            'status' => 'pending',
            'total_price' => 0,
            'name' => $owner->name,
            'email' => $owner->email,
            'phone' => '0888000000',
            'address' => 'София',
            'user_id' => $owner->id,
        ]);
        $product = Product::factory()->create(['price' => 5, 'stock' => 10]);

        $this->actingAs($other)
            ->post(route('profile.add-item', $order->id), [
                'product_id' => $product->id,
                'quantity' => 1,
            ])
            ->assertRedirect(route('profile.index'))
            ->assertSessionHas('error', 'Поръчката не е намерена или нямате достъп до нея');
    }

    public function test_user_can_update_item_quantity_and_recalculate_total(): void
    {
        $user = User::factory()->create();
        $product = Product::factory()->create(['price' => 7, 'stock' => 10]);
        $order = Order::create([
            'status' => 'pending',
            'total_price' => 7,
            'name' => $user->name,
            'email' => $user->email,
            'phone' => '0888000000',
            'address' => 'София',
            'user_id' => $user->id,
        ]);
        $item = OrderItem::create([
            'order_id' => $order->id,
            'product_id' => $product->id,
            'quantity' => 1,
            'price' => 7,
        ]);

        $this->actingAs($user)
            ->patch(route('profile.update', $item->id), ['quantity' => 3])
            ->assertRedirect(route('profile.edit', $order->id))
            ->assertSessionHas('success', 'Количеството е обновено успешно');

        $item->refresh();
        $this->assertEquals(3, (int) $item->quantity);

        $order->refresh();
        $this->assertEquals(21.0, (float) $order->total_price);
    }

    public function test_user_cannot_update_when_order_not_editable(): void
    {
        $user = User::factory()->create();
        $product = Product::factory()->create(['price' => 5]);
        $order = Order::create([
            'status' => 'completed',
            'total_price' => 5,
            'name' => $user->name,
            'email' => $user->email,
            'phone' => '0888000000',
            'address' => 'София',
            'user_id' => $user->id,
        ]);
        $item = OrderItem::create([
            'order_id' => $order->id,
            'product_id' => $product->id,
            'quantity' => 1,
            'price' => 5,
        ]);

        $this->actingAs($user)
            ->patch(route('profile.update', $item->id), ['quantity' => 2])
            ->assertRedirect(route('profile.edit', $order->id))
            ->assertSessionHas('error', 'Може да редактирате само поръчки със статус "В очакване"');

        $this->assertEquals(1, (int) $item->fresh()->quantity);
    }

    public function test_user_can_delete_item_and_order_deleted_when_empty(): void
    {
        $user = User::factory()->create();
        $product = Product::factory()->create(['price' => 4, 'stock' => 1]);
        $order = Order::create([
            'status' => 'pending',
            'total_price' => 4,
            'name' => $user->name,
            'email' => $user->email,
            'phone' => '0888000000',
            'address' => 'София',
            'user_id' => $user->id,
        ]);
        $item = OrderItem::create([
            'order_id' => $order->id,
            'product_id' => $product->id,
            'quantity' => 1,
            'price' => 4,
        ]);

        $this->actingAs($user)
            ->delete(route('profile.destroy', $item->id))
            ->assertRedirect(route('profile.index'))
            ->assertSessionHas('success', 'Поръчката е изтрита, защото не съдържаше продукти');

        $this->assertDatabaseMissing('orders', ['id' => $order->id]);
        $product->refresh();
        $this->assertEquals(2, $product->stock);
    }

    public function test_create_redirects_when_no_available_products(): void
    {
        $user = User::factory()->create();
        $order = Order::create([
            'status' => 'pending',
            'total_price' => 0,
            'name' => $user->name,
            'email' => $user->email,
            'phone' => '0888000000',
            'address' => 'София',
            'user_id' => $user->id,
        ]);
        // Един продукт без наличност и един вече в поръчката
        $p1 = Product::factory()->create(['stock' => 0]);
        $p2 = Product::factory()->create(['stock' => 5]);
        OrderItem::create([
            'order_id' => $order->id,
            'product_id' => $p2->id,
            'quantity' => 1,
            'price' => $p2->price,
        ]);

        $this->actingAs($user)
            ->get(route('profile.add-item.form', $order->id))
            ->assertRedirect(route('profile.edit', $order->id))
            ->assertSessionHas('error', 'Няма налични продукти, които да добавите към поръчката');
    }

    public function test_store_fails_when_product_already_in_order(): void
    {
        $user = User::factory()->create();
        $product = Product::factory()->create(['price' => 6, 'stock' => 10]);
        $order = Order::create([
            'status' => 'pending',
            'total_price' => 6,
            'name' => $user->name,
            'email' => $user->email,
            'phone' => '0888000000',
            'address' => 'София',
            'user_id' => $user->id,
        ]);
        OrderItem::create([
            'order_id' => $order->id,
            'product_id' => $product->id,
            'quantity' => 1,
            'price' => 6,
        ]);

        $this->actingAs($user)
            ->post(route('profile.add-item', $order->id), [
                'product_id' => $product->id,
                'quantity' => 1,
            ])
            ->assertSessionHas('error', 'Този продукт вече е добавен в поръчката');

        $this->assertEquals(1, OrderItem::where('order_id', $order->id)->count());
    }
}
