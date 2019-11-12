<?php
	
	
	namespace App\Http\Controllers\ControllersHelper;
	
	
	use App\Item;
    use Illuminate\Database\Eloquent\Builder;

    trait ChartControllerStockHelper
	{
		
		public function chart_stock_view($chart)
		{

            $ids = $this->get_child_infinity_stock_children_ids($chart);
            $result_ids = array();
            array_walk_recursive($ids,function($v) use (&$result_ids){ $result_ids[] = $v; });
            $all_histories = Item::with([
                "history" => function($query) use ($chart,$result_ids){
                     return $query->with('invoice.chart')->whereHas('invoice', function (Builder $query) use ($result_ids){
                         $query->whereIn('chart_id',$result_ids);
                     });
                }
            ],'item')->get();


            $items = [];
            foreach ($all_histories as $history)
            {
                $history['receipt_total'] = $this->get_stock_history_sum($history,'debit');
                $history['payment_total'] = $this->get_stock_history_sum($history,'credit');

                $items[] = $history;
            }


			
			return view('accounting.stock_histories',compact('items','chart'));
		}


        public function get_stock_history_sum($item, $type)
        {
            $total = 0;

            $allowed_types = $type=='debit' ? ['purchase','r_sale'] : ['sale','r_purchase'];
            foreach ($item['history'] as $history)
            {

               if(in_array($history['invoice_type'],$allowed_types))
                   $total = $total + $history['net'];

            }


            return $total;
		}
		
		public function loop_in_main__stock_children($chart)
		{
			$data = [];
			
			foreach ($chart->children as $child){
				
				
				$child['is_main_child'] = true;
				$single_main_child = $child;
				
				
				$calc = $this->get_child_infinity_stock_children_data($child);
				
				
				$single_main_child['receipt_total'] = $calc['receipt'];
				$single_main_child['payment_total'] = $calc['payment'];
				$data[] = $single_main_child;
				
			}
			
			return $data;
		}
		
		public function get_child_infinity_stock_children_data($child)
		{
			
			$payment = $child->gateway->payments()->where('payment_type','payment')->sum("amount");
			$receipt = $child->gateway->payments()->where('payment_type','receipt')->sum("amount");
			
			
			foreach ($child->children as $child){
				$calc = $this->get_child_infinity_stock_children_data($child);
				$payment = $payment + $calc['payment'];
				$receipt = $receipt + $calc['receipt'];
			}
			
			return ['payment' => $payment,'receipt' => $receipt];
		}




        public function get_child_infinity_stock_children_ids($child)
        {

            $ids = [];
            $ids[] = $child->id;



            foreach ($child->children as $child){
                $ids[] = $this->get_child_infinity_stock_children_ids($child);

            }





            return $ids;
        }

    }
