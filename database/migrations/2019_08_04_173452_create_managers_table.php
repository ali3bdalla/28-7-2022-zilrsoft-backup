<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateManagersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('managers', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->string('pin_code')->nullable();
            $table->string('name')->nullable();
            $table->string('name_ar')->nullable();
            $table->string('last_ip')->nullable();
            $table->string('profile_image')->nullable();
            $table->string('last_browser')->nullable();
            $table->integer('user_id');
            $table->integer('organization_id');
            $table->integer('branch_id');
            $table->integer('department_id');
            $table->enum('locale', ['en', 'ar'])->default('ar');
            $table->rememberToken();
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
        Schema::dropIfExists('managers');
    }
}
