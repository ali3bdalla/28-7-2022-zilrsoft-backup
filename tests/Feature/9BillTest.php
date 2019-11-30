<?php
//
//	namespace Tests\Feature;
//
//	use App\Account;
//	use App\Expense;
//	use App\Item;
//	use App\PurchaseInvoice;
//	use App\SaleInvoice;
//	use App\User;
//	use Illuminate\Foundation\Testing\WithFaker;
//	use Tests\TestCase;
//
//	class BillTest extends TestCase
//	{
//
//		use WithFaker;
//
//		/**
//		 * A basic feature test example.
//		 *
//		 * @test
//		 * @return void
//		 */
//		public function toCreatePurchase()
//		{
//
//
//			$total = 0;
//			$net = 0;
//			$discount_value = 0;
//			$discount_percent = 0;
//			$remaining = 0;
//			$subtotal = 0;
//			$tax = 0;
//
//
//			$methods = Account::where('slug','gateway')->inRandomOrder()->take(2)->get();
//
//
//			$expense = Expense::first();
//			$expense->amount = 10;
//			$expense->is_open = true;
//			$expense->is_apended_to_net = true;
//
//			$invoice_items = [];
//
//			$items = Item::where([
//				['is_need_serial',false],
//				['is_expense',false]
//			])->get();
//
//			foreach ($items as $item){
//				$item['qty'] = $this->faker->numberBetween(1,20);
//				$item['purchase_price'] = 60;
//				$price = $item['purchase_price'];
//				$item['total'] = $item['qty'] * $price;
//				$item['discount'] = 0;
//				$item['subtotal'] = $item['total'] - $item['discount'];
//				$item['tax'] = $item['subtotal'] * 5 / 100;
//				$item['net'] = $item['tax'] + $item['subtotal'];
//				$item['widget'] = 1;
//				$invoice_items[] = $item;
//				$total = $total + $item['total'];
//				$subtotal = $subtotal + $item['subtotal'];
//				$net = $net + $item['net'];
//				$tax = $tax + $item['tax'];
//				$discount_value = $discount_value + $item['discount'];
//			}
//
//
//			if ($expense->is_apended_to_net){
//				$net = $net + $expense->amount;
//			}
//
//
//			$gateways = [];
//			foreach ($methods as $method){
//				$method['amount'] = floatval($net) / 2 - $remaining / 2;
//				$gateways[] = $method;
//			}
////
//
//
//			$data = [
//				'receiver_id' => auth()->user()->id,
//				'creator_id' => auth()->user()->id,
//				'vendor_id' => User::where('is_vendor',true)->first()->id,
//				'branch_id' => auth()->user()->branch_id,
//				'department_id' => auth()->user()->department_id,
//				'vendor_inc_number' => $this->faker->numerify(),
//				'items' => $invoice_items,
//				'status' => 'paid',
//				'total' => $total,
//				'subtotal' => $subtotal,
//				'tax' => $tax,
//				'net' => $net,
//				'discount_value' => $discount_value,
//				'discount_percent' => $discount_percent,
//				'remaining' => $remaining,
//				'methods' => $gateways,
//				'expenses' => [collect($expense)->toArray()]
//
//			];
//
//
//			$response = $this->json('post',
//				route('management.purchases.store'),$data);
//
////			$response->dump();
//
//			$response->assertStatus(200);
//		}
//
//		/**
//		 * A basic feature test example.
//		 *
//		 * @test
//		 * @return void
//		 */
//		public function toCreateSaleInvoice()
//		{
//
////
//
//
//			$total = 0;
//			$net = 0;
//			$discount_value = 0;
//			$discount_percent = 0;
//			$remaining = 0;
//			$subtotal = 0;
//			$tax = 0;
//
//
//			$invoice_items = [];
//			$items = Item::where('is_need_serial',false)->take(2)->get();
//			foreach ($items as $item){
//				$item['qty'] = 2;
//				$item['price'] = 100;
//
//
//				$item['discount'] = 0;
//				if ($item['is_expense']){
//					$item['qty'] = 1;
//					$item['price'] = 50;
//					$item['purchase_price'] = 25;
//
//				}
//
//
//				$price = $item['price'];
//
//				$item['total'] = $item['qty'] * $price;
//
//				$item['subtotal'] = $item['total'] - $item['discount'];
//				$item['tax'] = $item['subtotal'] * 5 / 100;
//				$item['net'] = $item['tax'] + $item['subtotal'];
//				$item['widget'] = 1;
//
//				$invoice_items[] = $item;
//
//				$total = $total + $item['total'];
//				$subtotal = $subtotal + $item['subtotal'];
//				$net = $net + $item['net'];
//				$tax = $tax + $item['tax'];
//				$discount_value = $discount_value + $item['discount'];
//			}
//
//
//			$gateways = [];
//
//
//			$data = [
//				'salesman_id' => auth()->user()->id,
//				'creator_id' => auth()->user()->id,
//				'client_id' => User::where('is_client',true)->first()->id,
//				'branch_id' => auth()->user()->branch_id,
//				'department_id' => auth()->user()->department_id,
//				'vendor_inc_number' => $this->faker->numerify(),
//				'items' => $invoice_items,
//				'status' => 'paid',
//				'total' => $total,
//				'subtotal' => $subtotal,
//				'tax' => $tax,
//				'net' => $net,
//				'discount_value' => $discount_value,
//				'discount_percent' => $discount_percent,
//				'remaining' => $remaining,
//				'methods' => $gateways,
//
//			];
//
//
//			$response = $this->json('post',
//				route('management.sales.store'),$data);
////
////			$response->dump();
//
//			$response->assertStatus(200);
//		}
//
//		/**
//		 * @test
//		 */
//		public function toCreateReturnPurchase()
//		{
//
//
//			$method = Account::where('slug','gateway')->first();
//
//
//			$invoice = PurchaseInvoice::inRandomOrder()->first();
//			$data_items = $invoice->invoice->items()->take(4)->get();
//
//			$net = 0;
//			$items = [];
//			foreach ($data_items as $item){
//
//
////				$item_net = $item['net'] / $item['qty'];
//				$item['returned_qty'] = $item['qty'];
////				$new_net= $item['returned_qty'] * $item_net;
////				$item['net'] = $new_net;
//				$net += $item['net'];
//				$items[] = $item;
//			}
//
//
//			$method['amount'] = $net;
//			$data = [
//				'receiver_id' => auth()->user()->id,
//				'creator_id' => auth()->user()->id,
//				'vendor_id' => User::where('is_vendor',true)->first()->id,
//				'items' => $items,
//				'methods' => [collect($method)->toArray()]
//
//			];
//
//
//			$response = $this->json('put',
//				route('management.purchases.update',$invoice->id),$data);
//
////			$response->dump();
//
//			$response->assertStatus(200);
//		}
//
//		/**
//		 * @test
//		 */
//		public function toCreateReturnSale()
//		{
////			dd(1);
//
//			$method = Account::where('slug','gateway')->first();
//
//			$invoice = SaleInvoice::inRandomOrder()->first();
//			$data_items = $invoice->invoice->items;
//
//
////			dd($data_items);
//			$net = 0;
//			$total = 0;
//			$discount_value = 0;
//			$discount_percent = 0;
//			$remaining = 0;
//			$subtotal = 0;
//			$tax = 0;
//
//			$items = [];
//			foreach ($data_items as $item){
//
//				if (!$item->item->is_expense){
//					$price = $item['price'];
//
//					$item['returned_qty'] = $item['qty'];
//					$item['is_expense'] = false;
//
//
//					$item['total'] = $item['returned_qty'] * $price;
//
//					$item['discount'] = $item['discount'] / $item['qty'] * $item['returned_qty'];
//					$item['subtotal'] = $item['total'] - $item['discount'];
//					$item['tax'] = $item['subtotal'] * 5 / 100;
//					$item['net'] = $item['tax'] + $item['subtotal'];
//					$item['widget'] = 1;
//
//
//					$total = $total + $item['total'];
//					$subtotal = $subtotal + $item['subtotal'];
//					$net = $net + $item['net'];
//					$tax = $tax + $item['tax'];
//					$discount_value = $discount_value + $item['discount'];
//
//
//					$items[] = $item;
//				}
//
//
//			}
//
//
//			$method['amount'] = $net;
//
//			$data = [
//
//				'items' => $items,
//				'total' => $total,
//				'subtotal' => $subtotal,
//				'tax' => $tax,
//				'net' => $net,
//				'discount_value' => $discount_value,
//				'discount_percent' => $discount_percent,
//				'remaining' => $remaining,
//				'methods' => [collect($method)->toArray()]
//
//			];
//
//
//			$response = $this->json('put',
//				route('management.sales.update',$invoice->id),$data);
//
////			$response->dump();
//
//			$response->assertStatus(201);
//		}
//
//		protected function setUp():void
//		{
//			parent::setUp(); // TODO: Change the autogenerated stub
//			auth()->loginUsingId(1);
//		}
//
//	}
