<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('organization_id');
           ;
            $table->integer('creator_id')->default(0);
            $table->string('name');
            $table->string('name_ar')->nullable();
            $table->string('phone_number');
            $table->string('user_slug')->nullable();
            $table->float('balance',20,8)->default(0);
            $table->boolean('is_supervisor')->default(false);
            $table->boolean('is_manager')->default(false);
            $table->boolean('is_vendor')->default(false);
            $table->boolean('is_client')->default(false);
            $table->boolean('is_supplier')->default(false);
	
	
	
	        $table->integer('client_chart_id')->default(0);
	        $table->integer('vendor_chart_id')->default(0);
	        $table->integer('manager_chart_id')->default(0);
	        $table->integer('supplier_chart_id')->default(0);
	        
	        
            $table->boolean('is_system_user')->default(false);
            $table->boolean('can_make_credit')->default(false);
            $table->enum('user_type',['company','individual']);
            $table->enum('user_title',['mis','mr','company'])->default ('mr');
            $table->timestamps();
            $table->softDeletes();


        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users');
    }
}
