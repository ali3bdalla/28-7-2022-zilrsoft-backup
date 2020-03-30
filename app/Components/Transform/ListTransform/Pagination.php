<?php
	
	
	namespace App\Components\Transform\ListTransform;
	
	
	use Illuminate\Http\Request;
	use Illuminate\Pagination\LengthAwarePaginator;
	use Illuminate\Pagination\Paginator;
	use Illuminate\Support\Collection;
	
	class Pagination
	{
		private $page = null;
		private $options = [];
		private $items = [];
		private $perPage = 20;
		private $result = [];
		
		public function __construct($items,Request $request,$options = [])
		{
			$this->items = $items;
			$this->page = $request->has('page') &&  $request->filled('page') ?  $request->input('page') : null ;
			$this->perPage = $request->has('perPage') &&  $request->filled('perPage') ?  $request->input('perPage') :
				20;
			$this->options = $options;
		}
		
		public function paginate()
		{
			
			$this->page = $this->page ?: (Paginator::resolveCurrentPage() ?: 1);
			$this->items = $this->items instanceof Collection ? $this->items : Collection::make($this->items);
			
			$this->result = new LengthAwarePaginator($this->items->forPage($this->page,$this->perPage),
				$this->items->count(),
				$this->perPage,
				$this->page,[
				$this->options]);
			
			return $this->result;
		}
		
		/**
		 * @param array $items
		 */
		public function setItems(array $items):void
		{
			$this->items = $items;
		}
		
		/**
		 * @param int $perPage
		 */
		public function setPerPage(int $perPage):void
		{
			$this->perPage = $perPage;
		}
		
		/**
		 * @param array $options
		 */
		public function setOptions(array $options):void
		{
			$this->options = $options;
		}
		
		/**
		 * @param null $page
		 */
		public function setPage($page):void
		{
			$this->page = $page;
		}
		
		/**
		 * @return Collection
		 */
		public function getResult(): Collection
		{
			return $this->result;
		}
		
	}