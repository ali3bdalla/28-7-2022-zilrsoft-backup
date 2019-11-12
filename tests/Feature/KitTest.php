<?php
//
//namespace Tests\Feature;
//
//use App\Item;
//use Tests\TestCase;
//use Illuminate\Foundation\Testing\WithFaker;
//use Illuminate\Foundation\Testing\RefreshDatabase;
//
//class KitTest extends TestCase
//{
//
//    use WithFaker;
//    protected function setUp(): void
//    {
//        parent::setUp(); // TODO: Change the autogenerated stub
//        auth()->loginUsingId(1);
//    }
//
//    /**
//     * A basic feature test example.
//     *
//     * @return void
//     */
//    public function testCreation()
//    {
//        $data_source_items = Item::where([
//            ['is_need_serial',false],
//            ['is_kit',false],
//        ])->take(5)->inRandomOrder()->get();
//        $items = [];
//        $total = 0;
//        $tax = 0;
//        $subtotal = 0;
//        $net = 0;
//        foreach ($data_source_items as $item){
//            $item['qty'] = 10;
//            $item['total'] = $item['price'] *  $item['qty'];
//            $item['tax'] = 1;
//            $item['subtotal'] = $this->faker->randomFloat(2,50);
//            $item['net'] = $this->faker->randomFloat(2,50);
//            $item['discount'] = $this->faker->randomFloat(2,50);
//
//            $items[] = $item;
//            $total+=$item['total'];
//            $subtotal+=$item['subtotal'];
//            $net+=$item['net'];
//            $tax+=$item['tax'];
//
//        }
//        $data = [
//            'name'=>$this->faker->name,
//            'ar_name'=>$this->faker->name,
//            'barcode'=>$this->faker->numberBetween(),
//            'items'=>$items,
//            'total'=>$this->faker->numberBetween(1,100),
//            'subtotal'=>$this->faker->numberBetween(1,100),
//            'discount_value'=>0,
//            'discount_percent'=>0,
//            'total'=>$total,
//            'tax'=>$tax,
//            'remaining'=>$this->faker->numberBetween(1,100),
//            'net'=>$net,
//        ];
//
//        $response = $this->withHeaders(
//                [
//                    'HTTP_Authorization' => csrf_token(),
//                    'X-Requested-With'=>'XMLHttpRequest'
//                ]
//            )->json('post','/management/kits',$data);
//
////        $response->dump();
//        $response->assertOk();
//    }
//}
