<?php
	
	namespace App\Events;
	
	
	class ChartCreatedEvent
	{
		
		public $chart;
		
		/**
		 * Create a new event instance.
		 *
		 * @return void
		 */
		public function __construct($chart)
		{
			$this->chart = $chart;
			//
		}
		
	}
