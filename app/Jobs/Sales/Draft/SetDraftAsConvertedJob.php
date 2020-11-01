<?php
	
	namespace App\Jobs\Sales\Draft;
	
	use App\Models\Invoice;
	use Illuminate\Bus\Queueable;
	use Illuminate\Contracts\Queue\ShouldQueue;
	use Illuminate\Foundation\Bus\Dispatchable;
	use Illuminate\Queue\InteractsWithQueue;
	use Illuminate\Queue\SerializesModels;
	use Illuminate\Support\Facades\DB;
	
	class SetDraftAsConvertedJob implements ShouldQueue
	{
		use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
		
		/**
		 * @var int
		 */
		private $draftId;
		/**
		 * @var int
		 */
		private $invoiceId;
		
		/**
		 * Create a new job instance.
		 *
		 * @param int $draftId
		 * @param int $invoiceId
		 */
		public function __construct($draftId = 0, $invoiceId = 0)
		{
			
			
			$this->draftId = $draftId;
			$this->invoiceId = $invoiceId;
		}
		
		/**
		 * Execute the job.
		 *
		 * @return void
		 */
		public function handle()
		{
			$draft = Invoice::withoutGlobalScope('draft')->where('id', $this->draftId)->first();
			if($draft) {
				if($draft->is_draft) {
					$draft->update(
						[
							'is_draft_converted' => true
						]
					);
					
					DB::table('draft_invoices_activities')->insert(
						[
							'draft_id' => $draft->id,
							'invoice_id' => $this->invoiceId,
							'created_at' => now(),
							'updated_at' => now(),
						]
					);
				}
				
			}
			
		}
	}
