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
			
			
			$all_clients = User::where([
				["is_client",true],
				['user_slug',null]
			])->whereIn('client_chart_id',$result_ids)->get();
			
			
			
			$clients = [];

			
			
//
			foreach ($all_clients as $client){
				$client['histories'] = $client->to_client_invoices_history();
				
				
				$clients[] = $client;
			}
			
			
//			return $clients[0]->histories['total_credit'];
			
			return view('accounts.clients_histories',compact('clients','chart'));
			
		}
		
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