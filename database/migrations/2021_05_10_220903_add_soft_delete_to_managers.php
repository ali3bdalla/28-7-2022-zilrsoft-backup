<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class AddSoftDeleteToManagers extends Migration
{

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


        $removedUsers = [
            [
                'id' => 16,
                'name' => 'زياد الغفيلي',
                'email' => 'ziad@msbrshop.com',
                "password" => '00000',
                'deleted_at' => now(),
                'user_id' => 0,
                'organization_id' => 1,
                'branch_id' => 1,
                'department_id' => 1,
            ],
            [
                'id' => 15,
                'name' => 'احمد ضيف الله',
                'email' => 'a@msbrshop.com',
                "password" => '00000',
                'deleted_at' => now(),
                'user_id' => 0,
                'organization_id' => 1,
                'branch_id' => 1,
                'department_id' => 1,
            ],
            [
                'id' => 13,
                'name' => 'محمد البتال',
                'email' => 'm@msbrshop.com',
                "password" => '00000',
                'deleted_at' => now(),
                'user_id' => 0,
                'organization_id' => 1,
                'branch_id' => 1,
                'department_id' => 1,
            ], [
                'id' => 12,
                'name' => 'hgjgh',
                'email' => 'msbjjjar.acc.info@gmail.com',
                "password" => '00000',
                'deleted_at' => now(),
                'user_id' => 0,
                'organization_id' => 1,
                'branch_id' => 1,
                'department_id' => 1,
            ], [
                'id' => 6,
                'name' => 'عثمان',
                'email' => 'othman@msbrshop.com',
                "password" => '00000',
                'deleted_at' => now(),
                'user_id' => 0,
                'organization_id' => 1,
                'branch_id' => 1,
                'department_id' => 1,
            ], [
                'id' => 5,
                'name' => 'عبدالرحمن غزاي',
                'email' => 'g@msbrshop.com',
                "password" => '00000',
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
