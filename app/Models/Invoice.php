<?php
	
	namespace App\Models;

use App\Models\Traits\AccountingPeriodTrait;
use Illuminate\Database\Eloquent\SoftDeletes;
	use Illuminate\Support\Facades\Storage;
	
	/**
	 * @property mixed organization_id
	 * @property mixed creator_id
	 * @property mixed user_id
	 * @property mixed invoice_type
	 * @property mixed net
	 * @property mixed tax
	 * @property mixed id
	 * @property mixed branch_id
	 * @property mixed department_id
	 * @property mixed sale
	 * @property mixed purchase
	 * @property mixed total
	 * @property mixed invoice_number
	 * @property mixed items
	 * @property mixed managed_by_id
	 * @property mixed vendor_invoice_number
	 * @property mixed is_draft
	 * @property mixed created_at
	 * @method static create(array $array)
	 */
	class Invoice extends BaseModel
	{
		
		use SoftDeletes;
		use AccountingPeriodTrait;
		protected $guarded = [];
		protected $casts = [
			'printable_price' => 'boolean',
			'is_draft_converted' => 'boolean',
		];
		
		
		public function user()
		{
			return $this->belongsTo(User::class, 'user_id')->withoutGlobalScopes(["manager",'draft']);
			
		}
		
		
		public function manager()
		{
			return $this->belongsTo(Manager::class, 'managed_by_id');
			
		}
		
		
		public function expenses()
		{
			return $this->hasMany(InvoiceExpenses::class, 'invoice_id');
		}
		
		
		public function organization()
		{
			return $this->belongsTo(Organization::class, 'organization_id');
		}
		
		public function sale()
		{
			return $this->hasOne(Sale::class, 'invoice_id');
		}
		
		public function creator()
		{
			return $this->belongsTo(Manager::class, 'creator_id');
		}
		
		public function department()
		{
			return $this->belongsTo(Department::class, 'department_id');
		}
		
		public function branch()
		{
			return $this->belongsTo(Branch::class, 'branch_id');
		}
		
		public function items()
		{
			return $this->hasMany(InvoiceItems::class, 'invoice_id')->withoutGlobalScope('draft');
		}
		
		public function purchase()
		{
			return $this->hasOne(Purchase::class, 'invoice_id');
		}
		
		public function serial_history()
		{
			return $this->hasOne(SerialHistory::class, 'invoice_id');
		}
		
		public function child()
		{
			return $this->belongsTo(Invoice::class, 'parent_invoice_id');
		}
		
		public function transactions()
		{
			return $this->hasMany(Transaction::class, 'invoice_id');
		}
		
		public function payments()
		{
			return $this->hasMany(Payment::class, 'invoice_id');
		}
		
		
		public function getUserTypeAttribute()
		{
			if(in_array($this->invoice_type, ['sale', 'return_sale', 'quotation'])) {
				return __('pages/invoice.client');
			}
			
			return __('pages/invoice.vendor');
		}
		
		public function getManagerTypeAttribute()
		{
			if(in_array($this->invoice_type, ['sale', 'return_sale', 'quotation'])) {
				return __('pages/invoice.salesman');
			}
			
			return __('pages/invoice.receiver');
		}
		
		public function getBackgroundAssetAttribute()
		{
			
			if($this->is_draft) {
				if(app()->isLocale('ar')) {
					return asset('template/images/quotation-ar.png');
				} else {
					return asset('template/images/quotation.png');
				}
				
			}
			
			if(app()->isLocale('ar')) {
				return asset('template/images/paid-ar.png');
			} else {
				return asset('template/images/paid.png');
			}
			
		}
		
		public function getFinalUserNameAttribute()
		{
			if(in_array($this->invoice_type, ['sale', 'return_sale']) && $this->sale->alice_name != null) {
				return $this->sale->alice_name;
			}
			
			return $this->user->locale_name;
		}
		
		
		public function getHasDropboxSnapshotAttribute()
		{
			return Storage::disk('dropbox')->exists($this->dropbox_snapshot);
		}
		
		
		
		
		
		public function getDropboxSnapshotUrlAttribute()
		{
			if($this->has_dropbox_snapshot) {
				return Storage::disk('dropbox')->url($this->dropbox_snapshot);
			}
			
			
			return "";
		}
		
		
	}
