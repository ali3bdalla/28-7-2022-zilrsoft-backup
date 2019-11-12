<?php
	
	
	namespace App\Http\Controllers\ControllersHelper;
	
	
	use App\User;
	
	trait ChartControllerClientsHelper
	{
		
		public function chart_clients_view($chart)
		{
			
			$ids = $this->get_child_infinity_clients_children_ids($chart);
			$result_ids = array();
			array_walk_recursive($ids,function ($v) use (&$result_ids){ $result_ids[] = $v; });
			
			
			$all_clients = User::where("is_client",true)->whereIn('client_chart_id',$result_ids)->get();


////            return $all_histories;
//
			$all_data = [];
			foreach ($all_clients as $client){
				$client['debit'] = $client->client_history()->where('invoice_type','sale')->get();
				$client['credit'] = $client->client_history;
				
				//$history['payment_total'] = $this->get_clients_history_sum($history,'credit');
				
				$all_data[] = $client;
			}
			
			
			return $all_data;
//
//
//			return view('accounting.clients_histories',compact('items','chart'));
		}

//		public function get_single_item_history_depend_on_current_chart($item,$chart)
//		{
//			$ids = $this->get_child_infinity_clients_children_ids($chart);
//			$result_ids = array();
//			array_walk_recursive($ids,function($v) use (&$result_ids){ $result_ids[] = $v; });
//
//
//			return $item->get_accounting_clients($result_ids);
//
//
//
//		}
//
//		public function get_clients_history_sum($item, $type)
//		{
//			$total = 0;
//
//			$allowed_types = $type=='debit' ? ['purchase','r_sale'] : ['sale','r_purchase'];
//			foreach ($item['history'] as $history)
//			{
//
//				if(in_array($history['invoice_type'],$allowed_types))
//					$total = $total + ($history['cost'] * $history['qty']);
//
//			}
//
//
//			return $total;
//		}

//
//
//		public function get_child_infinity_clients_children_data($child)
//		{
//
//			$payment = $child->gateway->payments()->where('payment_type','payment')->sum("amount");
//			$receipt = $child->gateway->payments()->where('payment_type','receipt')->sum("amount");
//
//
//			foreach ($child->children as $child){
//				$calc = $this->get_child_infinity_clients_children_data($child);
//				$payment = $payment + $calc['payment'];
//				$receipt = $receipt + $calc['receipt'];
//			}
//
//			return ['payment' => $payment,'receipt' => $receipt];
//		}
//
		
		
		public function get_child_infinity_clients_children_ids($child)
		{
			
			$ids = [];
			$ids[] = $child->id;
			
			
			foreach ($child->children as $child){
				$ids[] = $this->get_child_infinity_clients_children_ids($child);
				
			}
			
			
			return $ids;
		}
	}