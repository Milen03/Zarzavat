<?php

namespace Tests\Feature;

use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CartTest extends TestCase
{
    use RefreshDatabase;

    public function test_cart_index_shows_empty_state(): void
    {
        $response = $this->get(route('cart.index'));

        $response->assertOk();
        $response->assertViewIs('cart.index');
        $response->assertSee('Вашата количка е празна');
    }

    public function test_add_adds_product_to_cart_and_flashes_success(): void
    {
        $product = Product::factory()->create(['price' => 9.99]);

        $response = $this->post(route('cart.add', $product->id));

        $response->assertRedirect(route('cart.index'));
        $response->assertSessionHas('success', 'Продуктът е добавен в количката!');

        $cart = session('cart', []);
        $this->assertArrayHasKey($product->id, $cart);
        $this->assertEquals(1, $cart[$product->id]['quantity']);
        $this->assertEquals($product->price, $cart[$product->id]['price']);
        $this->assertEquals($product->name, $cart[$product->id]['name']);
    }

    public function test_add_same_product_increments_quantity(): void
    {
        $product = Product::factory()->create(['price' => 5.50]);

        $this->post(route('cart.add', $product->id));
        $this->post(route('cart.add', $product->id));

        $cart = session('cart', []);
        $this->assertEquals(2, $cart[$product->id]['quantity']);
    }

    public function test_update_changes_quantity(): void
    {
        $product = Product::factory()->create(['price' => 3.25]);

        $this->withSession([
            'cart' => [
                $product->id => [
                    'name' => $product->name,
                    'quantity' => 1,
                    'price' => $product->price,
                    'image' => null,
                ],
            ],
        ])->post(route('cart.update', $product->id), ['quantity' => 2.5])
            ->assertRedirect(route('cart.index'));

        $cart = session('cart', []);
        $this->assertEquals(2.5, $cart[$product->id]['quantity']);
    }

    public function test_remove_removes_item_and_flashes_success(): void
    {
        $product = Product::factory()->create(['price' => 7.00]);

        $this->withSession([
            'cart' => [
                $product->id => [
                    'name' => $product->name,
                    'quantity' => 1,
                    'price' => $product->price,
                    'image' => null,
                ],
            ],
        ])->post(route('cart.remove', $product->id))
            ->assertRedirect(route('cart.index'))
            ->assertSessionHas('success', 'Продуктът е премахнат!');

        $cart = session('cart', []);
        $this->assertArrayNotHasKey($product->id, $cart);
    }
}
