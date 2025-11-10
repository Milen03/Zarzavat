<?php

namespace Tests\Feature;

use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class OrderTest extends TestCase
{
    use RefreshDatabase;

    public function test_checkout_redirects_when_cart_is_empty()
    {

        $response = $this->get(route('checkout'));

        $response->assertRedirect(route('cart.index'));
        $response->assertSessionHas('error', 'Количката е празна');
    }

    public function test_checkout_shows_view_when_cart_has_items()
    {

        $product = Product::factory()->create(['price' => 10.50, 'stock' => 10]);

        $response = $this
            ->withSession([
                'cart' => [
                    $product->id => [
                        'quantity' => 2,
                        'price' => $product->price,
                        'name' => $product->name,
                    ],

                ],
            ])
            ->get(route('checkout'));

        $response->assertOk();
        $response->assertViewIs('checkout.index');
        $response->assertViewHas('cart');
    }

    public function test_place_order_as_guest_creates_order_items_and_clears_cart(): void
    {
        $product = Product::factory()->create(['price' => 12.00, 'stock' => 7]);

        $payload = [
            'name' => 'Гост Потребител',
            'email' => 'guest@example.com',
            'phone' => '0888123456',
            'address' => 'София, ул. Тест 1',
        ];

        $response = $this
            ->withSession([
                'cart' => [
                    $product->id => [
                        'quantity' => 2,
                        'price' => $product->price,
                        'name' => $product->name,
                    ],
                ],
            ])
            ->post(route('checkout.place'), $payload);

        $response->assertRedirect(route('cart.index'));
        $response->assertSessionHas('success', 'Поръчката е успешно направена!');

        // Поръчката е създадена
        $this->assertDatabaseHas('orders', [
            'email' => 'guest@example.com',
            'name' => 'Гост Потребител',
            'address' => 'София, ул. Тест 1',
        ]);

        // Артикулът към поръчката е създаден
        $order = Order::first();
        $this->assertNotNull($order);
        $this->assertDatabaseHas('order_items', [
            'order_id' => $order->id,
            'product_id' => $product->id,
            'quantity' => 2,
            'price' => 12.00,
        ]);

        // Наличността е намалена
        $product->refresh();
        $this->assertEquals(5, $product->stock);

        // Маркиране на guest_orders в сесията
        $response->assertSessionHas('guest_orders');

        // Количката е изчистена
        $this->assertEquals([], session('cart', []));
    }

    public function test_place_order_as_authenticated_user_sets_user_id_and_no_guest_orders(): void
    {
        $user = User::factory()->create();
        $product = Product::factory()->create(['price' => 5.00, 'stock' => 3]);

        $payload = [
            'name' => 'Иван Иванов',
            'email' => 'ivan@example.com',
            'phone' => '0888000000',
            'address' => 'Пловдив, бул. Тест 2',
        ];

        $response = $this
            ->actingAs($user)
            ->withSession([
                'cart' => [
                    $product->id => [
                        'quantity' => 1,
                        'price' => $product->price,
                        'name' => $product->name,
                    ],
                ],
            ])
            ->post(route('checkout.place'), $payload);

        $response->assertRedirect(route('cart.index'));
        $response->assertSessionHas('success');

        $order = Order::first();
        $this->assertNotNull($order);
        $this->assertEquals($user->id, $order->user_id);

        // Няма guest_orders флаг за логнат потребител
        $this->assertFalse(session()->has('guest_orders'));

        // Проверка за артикула и наличността
        $this->assertDatabaseHas('order_items', [
            'order_id' => $order->id,
            'product_id' => $product->id,
            'quantity' => 1,
        ]);

        $product->refresh();
        $this->assertEquals(2, $product->stock);
    }

    public function test_place_order_redirects_to_cart_when_cart_empty(): void
    {
        $response = $this->post(route('checkout.place'), [
            'name' => 'Иван',
            'email' => 'ivan@example.com',
            'phone' => '0888123456',
            'address' => 'София',
        ]);

        $response->assertRedirect(route('cart.index'));
        $this->assertDatabaseCount('orders', 0);
        $this->assertDatabaseCount('order_items', 0);
    }
}
