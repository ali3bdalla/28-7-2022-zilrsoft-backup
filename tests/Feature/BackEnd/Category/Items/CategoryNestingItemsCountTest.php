<?php

namespace Tests\Feature\BackEnd\Category\Items;

use App\Models\Category;
use App\Models\Item;
use Tests\TestCase;

class CategoryNestingItemsCountTest extends TestCase
{

    /**
     * A basic feature test example.
     *
     * @return void
     * @test
     */
    public function itShouldReturnValidItemsCount()
    {
        $this->actingAsManager();
        $category = factory(Category::class)->create();


        $loopRand = $this->faker->numberBetween(3, 6);
        $items = [];
        $items[] = factory(Item::class, 5)->create([
            'category_id' => $category->id
        ]);
        for ($i = 0; $i < $loopRand; $i++) {
            $subCategory = factory(Category::class)->create([
                'parent_id' => $category->id
            ]);
            $items[] = factory(Item::class, 5)->create([
                'category_id' => $subCategory->id
            ]);
            $subCategory->updateHashMap();
            for ($j = 0; $j < $i; $j++) {
                $subcategories = factory(Category::class, $j + 1)->create([
                    'parent_id' => $subCategory->id
                ]);

                foreach ($subcategories as $subcategory1) {
                    $items[] = factory(Item::class, $j + 1)->create([
                        'category_id' => $subcategory1->id
                    ]);

                    $subcategory1->updateHashMap();
                }


            }
            $subCategory->updateHashMap();
        }
        $category->updateHashMap();

        $this->assertEquals(Item::whereIn('category_id',$category->fresh()->getChildrenIncludeMe())->count(),Item::count());
    }
}
