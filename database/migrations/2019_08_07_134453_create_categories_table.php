<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCategoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('categories', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('organization_id');
            $table->integer('chart_id')->default(0);
            $table->integer('creator_id');
            $table->string('name')->nullable();
            $table->string('ar_name')->nullable();
            $table->string('description')->nullable();
            $table->string('ar_description')->nullable();
            $table->string('sorting')->nullable();
            $table->integer('parent_id')->default(0);
            $table->enum('status',["blocked","active"])->default("active");
            $table->softDeletes();
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
        Schema::dropIfExists('categories');
    }
}
