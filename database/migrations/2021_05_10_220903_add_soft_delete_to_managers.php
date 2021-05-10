<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class AddSoftDeleteToManagers extends Migration
{
    use WithFaker;

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('managers', function (Blueprint $table) {
            //
            $table->softDeletes();
        });
//        $table->bigIncrements('id');
//        $table->string('email')->unique();
//        $table->timestamp('email_verified_at')->nullable();
//        $table->string('password');
//        $table->string('pin_code')->nullable();
//        $table->string('name')->nullable();
//        $table->string('name_ar')->nullable();
//        $table->string('last_ip')->nullable();
//        $table->string('profile_image')->nullable();
//        $table->string('last_browser')->nullable();
//        $table->integer('user_id');
//        $table->integer('organization_id');
//        $table->integer('branch_id');
//        $table->integer('department_id');
//        $table->enum('locale',['en','ar'])->default('ar');
        $removedUsers = [
            [
                'id' => 16,
                'name' => 'زياد الغفيلي',
                'email' => $this->faker->safeEmail,
                'deleted_at' => now(),
                'user_id' => 0,
                'organization_id' => 1,
                'branch_id' => 1,
                'department_id' => 1,
            ],
            [
                'id' => 15,
                'name' => 'احمد ضيف الله',
                'email' => $this->faker->safeEmail,
                'deleted_at' => now(),
                'user_id' => 0,
                'organization_id' => 1,
                'branch_id' => 1,
                'department_id' => 1,
            ],
            [
                'id' => 13,
                'name' => 'محمد البتال',
                'email' => $this->faker->safeEmail,
                'deleted_at' => now(),
                'user_id' => 0,
                'organization_id' => 1,
                'branch_id' => 1,
                'department_id' => 1,
            ], [
                'id' => 12,
                'name' => 'hgjgh',
                'email' => $this->faker->safeEmail,
                'deleted_at' => now(),
                'user_id' => 0,
                'organization_id' => 1,
                'branch_id' => 1,
                'department_id' => 1,
            ], [
                'id' => 6,
                'name' => 'عثمان',
                'email' => $this->faker->safeEmail,
                'deleted_at' => now(),
                'user_id' => 0,
                'organization_id' => 1,
                'branch_id' => 1,
                'department_id' => 1,
            ], [
                'id' => 5,
                'name' => 'عبدالرحمن غزاي',
                'email' => $this->faker->safeEmail,
                'deleted_at' => now(),
                'user_id' => 0,
                'organization_id' => 1,
                'branch_id' => 1,
                'department_id' => 1,
            ]
        ];
        foreach ($removedUsers as $user) {
            DB::table('managers')->insertGetId($user);
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('managers', function (Blueprint $table) {
            //
        });
    }
}
