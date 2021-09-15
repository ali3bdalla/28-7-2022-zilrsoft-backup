<?php

namespace App\Jobs\Sales\Draft;

    use App\Models\Invoice;
use App\Scopes\DraftScope;
use Illuminate\Bus\Queueable;
    use Illuminate\Contracts\Queue\ShouldQueue;
    use Illuminate\Foundation\Bus\Dispatchable;
    use Illuminate\Queue\InteractsWithQueue;
    use Illuminate\Queue\SerializesModels;

    class SetDraftAsConvertedJob implements ShouldQueue
    {
        use Dispatchable;
        use InteractsWithQueue;
        use Queueable;
        use SerializesModels;

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
         */
        public function handle()
        {
            $draft = Invoice::withoutGlobalScopes([DraftScope::class])->where('id', $this->draftId)->first();
            if ($draft) {
                if ($draft->is_draft) {
                    $draft->update(
                        [
                            'is_draft_converted' => true,
                        ]
                    );
                }
            }
        }
    }
