<?php


namespace Tests\Feature\Admin;

use App\Models\Category;
use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class AdminProductTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_is_redirected_from_admin_products_index(): void
    {
        $this->get(route('admin.products.index'))
            ->assertRedirect(route('login.form'));
    }

    public function test_non_admin_gets_403_on_admin_products_index(): void
    {
        $user = User::factory()->create(['role' => 'user']);

        $this->actingAs($user)
            ->get(route('admin.products.index'))
            ->assertForbidden();
    }

    public function test_admin_sees_products_index_listing(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        Product::factory()->count(2)->create();

        $this->actingAs($admin)
            ->get(route('admin.products.index'))
            ->assertOk()
            ->assertViewIs('admin.products.index')
            ->assertViewHas('products');
    }

    public function test_admin_can_open_create_product_form(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);

        $this->actingAs($admin)
            ->get(route('admin.products.create'))
            ->assertOk()
            ->assertViewIs('admin.products.create')
            ->assertSee('Нов продукт');
    }

    public function test_admin_can_store_product_with_image(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $category = Category::factory()->create();
        Storage::fake('public');

        $payload = [
            'name' => 'Тест продукт',
            'description' => 'Описание',
            'price' => 19.99,
            'stock' => 5,
            'category_id' => $category->id,
            'image' => UploadedFile::fake()->image('photo.jpg'),
        ];

        $this->actingAs($admin)
            ->post(route('admin.products.store'), $payload)
            ->assertRedirect(route('admin.products.index'))
            ->assertSessionHas('success', 'Продуктът е добавен.');

        $product = Product::latest('id')->first();
        $this->assertNotNull($product);
        $this->assertEquals('Тест продукт', $product->name);
        $this->assertEquals(19.99, (float)$product->price);
        $this->assertEquals(5, (int)$product->stock);
        $this->assertEquals($category->id, (int)$product->category_id);

        $this->assertNotNull($product->image);
        $this->assertTrue(Storage::disk('public')->exists($product->image));
    }

    public function test_store_validation_fails_when_required_fields_missing(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);

        $this->actingAs($admin)
            ->post(route('admin.products.store'), [])
            ->assertSessionHasErrors(['name', 'price', 'stock', 'category_id']);
    }

    public function test_admin_can_update_product_and_replace_image(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $category = Category::factory()->create();
        $product = Product::factory()->create([
            'category_id' => $category->id,
            'image' => 'products/old.jpg',
        ]);

        Storage::fake('public');

        $payload = [
            'name' => 'Новo име',
            'description' => 'Ново описание',
            'price' => 29.50,
            'stock' => 7,
            'category_id' => $category->id,
            'image' => UploadedFile::fake()->image('new.jpg'),
        ];

        $this->actingAs($admin)
            ->put(route('admin.products.update', $product), $payload)
            ->assertRedirect(route('admin.products.index'))
            ->assertSessionHas('success', 'Продуктът е обновен.');

        $product->refresh();
        $this->assertEquals('Новo име', $product->name);
        $this->assertEquals(29.50, (float)$product->price);
        $this->assertEquals(7, (int)$product->stock);
        $this->assertTrue(Storage::disk('public')->exists($product->image));
    }

    public function test_admin_can_delete_product(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $product = Product::factory()->create();

        $this->actingAs($admin)
            ->delete(route('admin.products.destroy', $product))
            ->assertRedirect(route('admin.products.index'))
            ->assertSessionHas('success', 'Продуктът е изтрит.');

        $this->assertDatabaseMissing('products', ['id' => $product->id]);
    }
}