<?php

namespace App\Models;

use App\Enums\InvoiceItemStatusEnum;
use App\Enums\InvoiceTypeEnum;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class WarrantyTracingHistory extends Model
{
    protected $guarded = [];
    use HasFactory;
    use SoftDeletes;
    protected $casts = [
      'status' => InvoiceItemStatusEnum::class . ":nullable"
    ];

    public function creator()
    {
        return $this->belongsTo(Manager::class, 'creator_id');
    }

    public function warrantyTracing(): BelongsTo
    {
        return $this->belongsTo(Invoice::class, 'warranty_tracing_id')->where('invoice_type', InvoiceTypeEnum::warranty_tracing());
    }
}
