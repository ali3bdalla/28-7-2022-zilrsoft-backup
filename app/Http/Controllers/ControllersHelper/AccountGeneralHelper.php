<?php
	
	
	namespace App\Http\Controllers\ControllersHelper;
	
	
	trait ChartControllerGatewayHelper
	{
		
		public function chart_gateway_view($chart)
		{
			
			$payments = [];
			$fake_payments = $chart->gateway->payments;
			$main_children_report = $this->loop_in_main_children($chart);
			
			foreach ($main_children_report as $child){
				$payments[] = $child;
			}
			
			
			foreach ($fake_payments as $child){
				$payments[] = $child;
			}
			
			
			return view('accounts.gateway_histories',compact('payments','chart'));
		}
		
		public function loop_in_main_children($chart)
		{
			$data = [];
			
			foreach ($chart->children as $child){
				
				
				$child['is_main_child'] = true;
				$single_main_child = $child;
				
				
				$calc = $this->get_child_infinity_children_data($child);
				
				
				$single_main_child['receipt_total'] = $calc['receipt'];
				$single_main_child['payment_total'] = $calc['payment'];
				$data[] = $single_main_child;
				
			}
			
			return $data;
		}
		
		public function get_child_infinity_children_data($child)
		{
			
			$payment = $child->gateway->payments()->where('payment_type','payment')->sum("amount");
			$receipt = $child->gateway->payments()->where('payment_type','receipt')->sum("amount");
			
			
			foreach ($child->children as $child){
				$calc = $this->get_child_infinity_children_data($child);
				$payment = $payment + $calc['payment'];
				$receipt = $receipt + $calc['receipt'];
			}
			
			return ['payment' => $payment,'receipt' => $receipt];
		}
		
	}