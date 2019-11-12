<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('items', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('organization_id');
            $table->integer('creator_id');
            $table->integer('category_id');

            $table->string('name');
            $table->string('ar_name');
            $table->string('barcode',255);

            $table->boolean('is_kit')->default(0);
            $table->boolean('is_fixed_price')->default(0);
            $table->boolean('is_has_vts')->default(0);
            $table->boolean('is_has_vtp')->default(0);
            $table->boolean('is_need_serial')->default(0);
            $table->boolean('is_service')->default(0);


            $table->float('price',20,8)->default(0)->nullable();
            $table->float('price_with_tax',20,8)->default(0)->nullable();
            $table->float('last_p_price',20,8)->default(0);
            $table->float('cost',20,8)->default(0);


            $table->float('vts')->default(5);
            $table->float('vtp')->default(5);

            $table->integer('available_qty')->default(0);


            $table->enum('status',['active','pending'])->default('active');



            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('items');
    }
}
