<?php
	
	namespace App\Models;
	
	use Illuminate\Database\Eloquent\SoftDeletes;
	
	/**
	 * @property mixed is_fixed_price
	 * @property mixed price
	 * @property mixed is_expense
	 * @property mixed cost
	 * @property mixed id
	 * @property mixed vts
	 * @property mixed available_qty
	 * @property mixed data
	 * @property mixed items
	 * @property mixed vtp
	 * @property mixed expense_vendor_id
	 * @property mixed invoice_id
	 * @property mixed total_credit_amount
	 * @property mixed total_debit_amount
	 * @property mixed returned_qty
	 * @property mixed is_need_serial
	 * @property mixed total_cost_amount
	 * @property mixed total_stock_amount
	 * @property mixed is_service
	 * @property mixed ar_name
	 * @property mixed creator_id
	 * @property mixed online_offer_price
	 * @method static findOrFail($id)
	 * @method static InRandomOrder()
	 * @method static find($input)
	 */
	class Item extends BaseModel
	{
		
		use SoftDeletes;
		
		protected $appends = [
			'locale_name'
		
		];
		protected $casts = [
			'id' => 'integer',
			'is_has_vts' => 'boolean',
			'is_has_vtp' => 'boolean',
			'is_fixed_price' => 'boolean',
			'is_kit' => 'boolean',
			'is_service' => 'boolean',
			'is_need_serial' => 'boolean',
			'available_qty' => 'integer',
			'price' => 'float',
			'price_with_tax' => 'float',
			'is_expense' => 'boolean',
		];
		protected $guarded = [];
		
		public function scopeKits($query)
		{
			return $query->where('is_kit', true);
		}
		
		public function organization()
		{
			return $this->belongsTo(Organization::class, 'organization_id');
		}
		
		public function serials()
		{
			return $this->hasMany(ItemSerials::class, 'item_id');
		}
		
		public function pipeline()
		{
			return $this->hasMany(InvoiceItems::class, 'item_id')->orderBy('created_at', 'asc');
		}
		
		public function category()
		{
			return $this->belongsTo(Category::class, 'category_id');
		}
		
		public function creator()
		{
			return $this->belongsTo(Manager::class, 'creator_id');
		}
		
		public function attachments()
		{
			return $this->morphMany(Attachment::class, 'attachable');
		}
		
		public function scopeLastFiveSearch($query, $search)
		{
			return $query
				->where('barcode', 'ILIKE', '%' . $search . '%')
				->orWhere('name', 'ILIKE', '%' . $search . '%')
				->orWhere('ar_name', 'ILIKE', '%' . $search . '%')
				->with('items', 'data')
				->take(5);
		}
		
		public function scopeItemBySerialSearch($query, $search)
		{
			$productSerials = ItemSerials::where('serial', $search)->whereIn('current_status', ['available', 'return_sale'])->get();
			$serials = [];
			foreach($productSerials as $serial) {
				if(!empty($serial)) {
					$item = $serial->item;
					$item->has_init_serial = true;
					$item->init_serial = $serial->fresh();
					$serials[] = $item;
				}
			}
			
			return $serials;
		}
		
		public function getLocaleNameAttribute()
		{
			return $this->ar_name;
		}
		
	
		
		public function filters()
		{
			return $this->hasMany(ItemFilters::class, 'item_id');
		}
		
		public function scopeChildrenHaveAvailableQty($query)
		{
			return $query->with(
				['items' => function($query2) {
					return $query2->with(
						[
							'item' => function($query3) {
								return $query3->where('available_qty', '>', 0);
							}
						]
					);
				}]
			);
			
		}
		
		public function addKitChildren($items)
		{
			foreach($items as $kit_item) {
				$obj = collect($kit_item)->only(['organization_id', 'qty', 'discount', 'tax', 'price', 'net', 'total', 'subtotal']);
				$obj['item_id'] = $kit_item['id'];
				$obj['creator_id'] = $this->creator_id;
				$items[] = $this->items()->create(collect($obj)->toArray());
			}
		}
		
		public function items()
		{
			return $this->hasMany(KitItems::class, 'kit_id')->with('item');
		}
		
		public function fillKitData($data)
		{
			
			$kit_data = [
				'total' => $data['total'],
				'subtotal' => $data['subtotal'],
				'tax' => $data['tax'],
				'discount' => $data['discount'],
				'net' => $data['net']
			];
			
			$this->data()->create($kit_data);
			
		}
		
		public function data()
		{
			return $this->hasOne(KitData::class, 'kit_id');
		}
		
		public function fetchKitData($qty, $kit_data)
		{
			
			
			$data['total'] = $kit_data['total'];
			$data['subtotal'] = $kit_data['subtotal'];
			$data['tax'] = $kit_data['tax'];
			$data['net'] = $kit_data['net'];
			$data['price'] = $kit_data['price'];
			
			
			return $data;
			
		}
	}
