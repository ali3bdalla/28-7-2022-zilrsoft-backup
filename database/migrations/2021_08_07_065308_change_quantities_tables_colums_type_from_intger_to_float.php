<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class ChangeQuantitiesTablesColumsTypeFromIntgerToFloat extends Migration
{
    private function changeQuantityTypeToFloat($tableName, $quantityColumnName)
    {
        $placeholderColumn = "{$tableName}_{$quantityColumnName}_placeholder";
        Schema::table($tableName, function (Blueprint $table) use ($placeholderColumn) {
            $table->double($placeholderColumn)->default(0);
        });
        DB::table($tableName)->update([
            $placeholderColumn => DB::raw("${$quantityColumnName}")
        ]);
        Schema::table($tableName, function (Blueprint $table) use ($quantityColumnName) {
            $table->dropColumn($quantityColumnName);
        });
        Schema::table($tableName, function (Blueprint $table) use ($quantityColumnName) {
            $table->double($quantityColumnName)->default(0);
        });
        DB::table($tableName)->update([
            $quantityColumnName => DB::raw("${$placeholderColumn}")
        ]);
        Schema::table($tableName, function (Blueprint $table) use ($placeholderColumn) {
            $table->dropColumn($placeholderColumn);
        });
    }
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $this->changeQuantityTypeToFloat('items', 'available_qty');
        $this->changeQuantityTypeToFloat('invoice_items', 'quantity');
        $this->changeQuantityTypeToFloat('items', 'available_qty');
        $this->changeQuantityTypeToFloat('items', 'available_qty');
        $this->changeQuantityTypeToFloat('items', 'available_qty');
        $this->changeQuantityTypeToFloat('items', 'available_qty');
        $this->changeQuantityTypeToFloat('items', 'available_qty');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('intger_to_float', function (Blueprint $table) {
            //
        });
    }
}
