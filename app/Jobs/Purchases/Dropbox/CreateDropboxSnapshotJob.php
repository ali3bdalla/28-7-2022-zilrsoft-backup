<?php

namespace App\Jobs\Purchases\Dropbox;

use App\Models\Invoice;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Storage;

class CreateDropboxSnapshotJob implements ShouldQueue
{
	use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

	/**
	 * @var Invoice
	 */
	private $invoice;
	private $dropBoxPendingPath;

	/**
	 * Create a new job instance.
	 *
	 * @param Invoice $invoice
	 * @param $dropBoxPendingPath
	 */
	public function __construct(Invoice $invoice, $dropBoxPendingPath)
	{
		//
		$this->invoice = $invoice;
		$this->dropBoxPendingPath = $dropBoxPendingPath;
	}

	/**
	 * Execute the job.
	 *
	 * @return void
	 */
	public function handle()
	{
		if (Storage::disk('dropbox')->exists($this->dropBoxPendingPath)) {
			$array = explode('.', $this->dropBoxPendingPath);
			$ext = end($array);
			$imagePath = config('filesystems.disks.dropbox.folders.completed_purchases') . '/' . $this->invoice->invoice_number . '.' . $ext;
			if (!Storage::disk('dropbox')->exists($imagePath)) {
				Storage::disk('dropbox')->move($this->dropBoxPendingPath, $imagePath);
			}
			$this->invoice->update(
				[
					'dropbox_snapshot' => $imagePath
				]
			);
		}
		//
	}
}
