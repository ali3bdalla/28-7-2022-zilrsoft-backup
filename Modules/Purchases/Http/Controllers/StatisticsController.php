<?php


namespace Modules\Purchases\Http\Controllers;


use App\Models\Invoice;

class StatisticsController
{
    public function getPendingCounts()
    {
        $counts = Invoice::where('invoice_type','pending_purchase')->count();
        return $counts;
    }
}