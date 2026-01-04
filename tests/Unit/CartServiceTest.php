<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Services\CartService;
use App\Models\User;
use App\Models\Product;
use Illuminate\Support\Facades\Session;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CartServiceTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_add_item_to_cart()
    {
        Session::start();
        $service = new CartService();
        $service->add(1, 2);

        $this->assertEquals(2, $service->count());
        $cart = Session::get('cart');
        $this->assertIsArray($cart);
        $this->assertCount(1, $cart);
        $firstItem = reset($cart);
        $this->assertEquals(1, $firstItem['product_id']);
        $this->assertEquals(2, $firstItem['quantity']);
    }

    public function test_can_update_quantity()
    {
        Session::start();
        $service = new CartService();
        $service->add(1, 1);
        
        $cart = Session::get('cart');
        $rowId = array_key_first($cart);
        
        $service->update($rowId, 5);

        $this->assertEquals(5, $service->count());
        $cart = Session::get('cart');
        $this->assertEquals(5, $cart[$rowId]['quantity']);
    }

    public function test_can_remove_item()
    {
        Session::start();
        $service = new CartService();
        $service->add(1, 1);
        
        $cart = Session::get('cart');
        $rowId = array_key_first($cart);
        
        $service->remove($rowId);

        $this->assertEquals(0, $service->count());
        $this->assertEmpty(Session::get('cart'));
    }

    public function test_can_clear_cart()
    {
        Session::start();
        $service = new CartService();
        $service->add(1, 1);
        $service->add(2, 3);
        $service->clear();

        $this->assertEquals(0, $service->count());
        $this->assertNull(Session::get('cart'));
    }

    public function test_can_add_item_with_variants()
    {
        Session::start();
        $service = new CartService();
        $variants = ['color' => 'red', 'size' => 'M'];
        $service->add(1, 2, $variants);

        $this->assertEquals(2, $service->count());
        
        $cart = Session::get('cart');
        $firstItem = reset($cart);
        
        $this->assertEquals(1, $firstItem['product_id']);
        $this->assertEquals(2, $firstItem['quantity']);
        $this->assertEquals($variants, $firstItem['variants']);
    }

    public function test_different_variants_create_different_rows()
    {
        Session::start();
        $service = new CartService();
        
        $service->add(1, 1, ['color' => 'red']);
        $service->add(1, 1, ['color' => 'blue']);

        $this->assertEquals(2, $service->count());
        $this->assertCount(2, Session::get('cart'));
    }

    public function test_authenticated_user_syncs_cart_to_db()
    {
        Session::start();
        $user = User::factory()->create();
        $this->actingAs($user);
        
        // Ensure product exists for foreign key constraint
        $product = Product::factory()->create(['id' => 1]);

        $service = new CartService();
        $service->add(1, 2, ['color' => 'red']);

        $this->assertDatabaseHas('cart_items', [
            'product_id' => 1,
            'quantity' => 2,
        ]);
        
        // Verify JSON variants are stored (syntax depends on DB driver, but basic check is enough)
        $cartItem = $user->cart->items->first();
        $this->assertEquals(['color' => 'red'], $cartItem->variants);
    }
}
