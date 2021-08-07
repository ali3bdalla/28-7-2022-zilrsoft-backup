<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class ChangeQuantitiesTablesColumsTypeFromIntgerToFloat extends Migration
{
    private function changeQuantityTypeToFloat($tableName, $quantityColumnName)
    {

        if (Schema::hasColumn($tableName, $quantityColumnName)) {
            $placeholderColumn = "{$tableName}_{$quantityColumnName}_placeholder";
            Schema::table($tableName, function (Blueprint $table) use ($placeholderColumn) {
                $table->double($placeholderColumn)->nullable()->default(0);
            });
            DB::table($tableName)->update([
                $placeholderColumn => DB::raw("{$quantityColumnName}")
            ]);
            DB::table($tableName)->where($placeholderColumn, null)->update([
                $placeholderColumn => 0
            ]);
            Schema::table($tableName, function (Blueprint $table) use ($quantityColumnName) {
                $table->dropColumn($quantityColumnName);
            });
            Schema::table($tableName, function (Blueprint $table) use ($quantityColumnName) {
                $table->double($quantityColumnName)->default(0);
            });
            DB::table($tableName)->update([
                $quantityColumnName => DB::raw("{$placeholderColumn}")
            ]);
            Schema::table($tableName, function (Blueprint $table) use ($placeholderColumn) {
                $table->dropColumn($placeholderColumn);
            });
        }
    }
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $this->changeQuantityTypeToFloat('items', 'available_qty');
        $this->changeQuantityTypeToFloat('invoice_items', 'available_qty');
        $this->changeQuantityTypeToFloat('invoice_items', 'qty');
        $this->changeQuantityTypeToFloat('invoice_items', 'returned_qty');
        $this->changeQuantityTypeToFloat('invoice_items', 'warranty_quantity');
        $this->changeQuantityTypeToFloat('order_item_qty_holders', 'qty');
        $this->changeQuantityTypeToFloat('kit_items', 'qty');
        $this->changeQuantityTypeToFloat('inventory_transactions', 'quantity');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
    }
}
