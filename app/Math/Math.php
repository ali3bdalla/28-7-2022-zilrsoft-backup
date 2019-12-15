<?php
	
	
	namespace App\Math;
	
	
	trait Math
	{
		
		/**
		 * @param $price
		 * @param $qty
		 *
		 * @return float|int
		 */
		public function getTotalAmount($price,$qty)
		{
			return $price * $qty;
		}
		
		/**
		 * @param $total
		 * @param $discount
		 *
		 * @return mixed
		 */
		public function getSubTotalAmount($total,$discount)
		{
			return $total - $discount;
		}
		
		/**
		 * @param $subtotal
		 * @param $vat
		 *
		 * @return float|int
		 */
		public function getTaxAmount($subtotal,$vat)
		{
			return $subtotal * $vat / 100;
		}
		
		/**
		 * @param $subtotal
		 * @param $tax
		 *
		 * @return mixed
		 */
		public function getNetAmount($subtotal,$tax)
		{
			return $subtotal + $tax;
		}
	}
	
	