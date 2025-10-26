<?php


namespace Tests\Feature;

use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProductTest extends TestCase
{
    use RefreshDatabase;

    public function test_products_index_loads_successfully(): void
    {
        $response = $this->get(route('products.index'));

        $response->assertStatus(200);
        $response->assertViewIs('products.index');
        $response->assertViewHas('products');
    }

    public function test_products_index_displays_products(): void
    {
        $products = Product::factory()->count(3)->create();

        $response = $this->get(route('products.index'));

        $response->assertStatus(200);
        foreach ($products as $product) {
            $response->assertSee(e($product->name));
        }
    }

    public function test_products_show_displays_single_product(): void
    {
        $product = Product::factory()->create();

        $response = $this->get(route('products.show', $product->id));

        $response->assertStatus(200);
        $response->assertViewIs('products.show');
        $response->assertSee(e($product->name));
    }

    public function test_products_show_returns_404_for_missing_product(): void
    {
        $response = $this->get(route('products.show', 999999));

        $response->assertNotFound();
    }
}