<?php

namespace R64\Checkout\Database\Factories;

use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Database\Eloquent\Factories\Factory;
use R64\Checkout\Models\CartItem;
use R64\Checkout\Models\OrderItem;
use R64\Checkout\Models\Product;

class OrderItemFactory extends Factory
{
    use WithFaker;

    protected $model = OrderItem::class;

    public function definition()
    {
        return [
            'product_id' => function () {
                return Product::factory()->create()->id;
            },
            'cart_item_id' => function () {
                return CartItem::factory()->create()->id;
            },
            'name' => $this->faker->word,
        ];
    }
}
