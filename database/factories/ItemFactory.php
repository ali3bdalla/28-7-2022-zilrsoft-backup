<?php

namespace Database\Factories;

use App\Dto\ItemDto;
use App\Models\Item;
use Illuminate\Database\Eloquent\Factories\Factory;

class ItemFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Item::class;

    public function setDto(ItemDto $itemDto): ItemFactory
    {
        return $this->state(function () use ($itemDto) {
            return [
                "organization_id" => $itemDto->getOrganizationId(),
                "creator_id" => $itemDto->getManagerId(),
                "category_id" => $itemDto->getCategoryId(),
                "name" => $itemDto->getName(),
                "ar_name" => $itemDto->getArName(),
                "barcode" => $itemDto->getBarcode(),
                "is_kit" => $itemDto->isItKit(),
                "is_fixed_price" => $itemDto->isFixedPrice(),
                "is_service" => $itemDto->isService(),
                "is_expense" => $itemDto->isExpense(),
                "is_available_online" => $itemDto->isAvailableOnline(),
                "price" => $itemDto->getPrice(),
                "price_with_tax" => $itemDto->getTaxedPrice(),
                "online_price" => $itemDto->getOnlinePrice(),
                "online_offer_price" => $itemDto->getOnlinePrice(),
                "shipping_discount" => $itemDto->getShippingDiscount(),
                "weight" => $itemDto->getShippingWeight(),
                "en_slug" => $itemDto->getEnSlug(),
                "ar_slug" => $itemDto->getArSlug(),
                "is_has_vts" => true,
                "is_has_vtp" => true,
                "is_need_serial" => $itemDto->isNeedSerial(),
            ];
        });
    }

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition(): array
    {
        return [
            "organization_id" => null,
            "creator_id" => null,
            "category_id" => null,
            "barcode" => $this->faker->creditCardNumber,
            "is_kit" => $this->faker->boolean,
            "is_fixed_price" => $this->faker->boolean,
            "is_service" => $this->faker->boolean,
            "is_expense" => $this->faker->boolean,
            "price" => $this->faker->randomFloat(),
            "price_with_tax" => $this->faker->randomFloat(),
            "online_price" => $this->faker->randomFloat(),
            "online_offer_price" => $this->faker->randomFloat(),
            "shipping_discount" => $this->faker->randomFloat(),
            "weight" => $this->faker->randomFloat(),
            "is_has_vts" => $this->faker->boolean,
            "is_has_vtp" => $this->faker->boolean,
            "is_need_serial" => $this->faker->boolean,
            'name' => $this->faker->name,
            'ar_name' => $this->faker->name,
            'description' => $this->faker->sentence,
            'ar_description' => $this->faker->sentence,
            'is_available_online' => $this->faker->boolean,
            'en_slug' => $this->faker->slug,
            'ar_slug' => $this->faker->slug,
        ];
    }
}
