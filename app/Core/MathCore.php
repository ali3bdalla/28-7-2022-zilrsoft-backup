<?php
	
	
	namespace App\Core;
	
	
	class MathCore
	{
		/**
		 * @param int $qty
		 * @param int $price
		 * @param int $discount
		 * @param int $vat
		 *
		 * @return mixed
		 */
		public function accountingAmount($qty = 0,$price = 0,$discount = 0,$vat = 0)
		{
			$result['total'] = $this->getTotalAmount($price,$qty);
			$result['subtotal'] = $this->getSubTotalAmount($result['total'],$discount);
			$result['tax'] = $this->getTaxAmount($result['subtotal'],$vat);
			$result['net'] = $this->getNetAmount($result['subtotal'],$result['tax']);
			return $result ;
		}
		
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