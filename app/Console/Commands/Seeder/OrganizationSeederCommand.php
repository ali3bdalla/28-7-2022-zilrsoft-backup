<?php

namespace App\Console\Commands\Seeder;

use App\Models\Account;
use App\Models\Bank;
use App\Models\Branch;
use App\Models\Category;
use App\Models\CategoryFilters;
use App\Models\CategoryItems;
use App\Models\Country;
use App\Models\Department;
use App\Models\Expense;
use App\Models\Filter;
use App\Models\FilterValues;
use App\Models\Item;
use App\Models\ItemExpenses;
use App\Models\ItemFilters;
use App\Models\KitData;
use App\Models\KitItems;
use App\Models\Manager;
use App\Models\Organization;
use App\Models\Type;
use App\Models\User;
use App\Models\UserDetails;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class OrganizationSeederCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:organization_seeder_command';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {

        if (!Schema::connection('data_source')->hasColumn('invoices', 'new_db_id')) {
            Schema::connection('data_source')->table('invoices', function ($table) {
                $table->integer('new_db_id')->nullable();;
            });
        }


        foreach (DB::connection('data_source')->table('countries')->get() as $itemData) {
            $data = collect($itemData)->toArray();
            Country::create($data);
        }


        foreach (DB::connection('data_source')->table('organizations')->get() as $itemData) {
            $data = collect($itemData)->toArray();
            Organization::create($data);
        }
        foreach (DB::connection('data_source')->table('banks')->get() as $itemData) {
            $data = collect($itemData)->toArray();
            Bank::create($data);
        }

        foreach (DB::connection('data_source')->table('types')->get() as $itemData) {
            $data = collect($itemData)->toArray();
            Type::create($data);
        }


        foreach (DB::connection('data_source')->table('departments')->get() as $itemData) {
            $data = collect($itemData)->toArray();
            Department::create($data);
        }


        foreach (DB::connection('data_source')->table('branches')->get() as $itemData) {
            $data = collect($itemData)->toArray();
            Branch::create($data);
        }


        foreach (DB::connection('data_source')->table('users')->get() as $itemData) {
            $data = collect($itemData)->toArray();
            $data['balance'] = 0;
            $data['vendor_balance'] = 0;
            unset($data['client_chart_id']);
            unset($data['supplier_chart_id']);
            unset($data['manager_chart_id']);
            unset($data['vendor_chart_id']);
            User::create($data);
        }


        foreach (DB::connection('data_source')->table('managers')->get() as $itemData) {
            $data = collect($itemData)->toArray();
            Manager::create($data);
        }


        foreach (DB::connection('data_source')->table('user_details')->get() as $itemData) {
            $data = collect($itemData)->toArray();
            UserDetails::create($data);
        }


        foreach (DB::connection('data_source')->table('categories')->get() as $itemData) {
            $data = collect($itemData)->toArray();
            Category::create($data);
        }


        foreach (DB::connection('data_source')->table('filters')->get() as $itemData) {
            $data = collect($itemData)->toArray();
            Filter::create($data);
        }


        foreach (DB::connection('data_source')->table('filter_values')->get() as $itemData) {
            $data = collect($itemData)->toArray();
            FilterValues::create($data);
        }

        foreach (DB::connection('data_source')->table('category_filters')->get() as $itemData) {
            $data = collect($itemData)->toArray();
            CategoryFilters::create($data);
        }

//        foreach (DB::connection('data_source')->table('category_filter_values')->get() as $itemData) {
//            $data = collect($itemData)->toArray();
//            CategoryFilterValues::create($data);
//        }


//        foreach (DB::connection('data_source')->table('category_items')->get() as $itemData) {
//            $data = collect($itemData)->toArray();
//            CategoryItems::create($data);
//        }


        foreach (DB::connection('data_source')->table('expenses')->get() as $itemData) {
            $data = collect($itemData)->toArray();
            Expense::create($data);
        }


        foreach (DB::connection('data_source')->table('category_items')->get() as $itemData) {
            $data = collect($itemData)->toArray();
            CategoryItems::create($data);
        }


        foreach (DB::connection('data_source')->table('items')->get() as $itemData) {
            $data = collect($itemData)->toArray();
            $data['available_qty'] = 0;
            $data['cost'] = 0;
            Item::create($data);
        }


        foreach (DB::connection('data_source')->table('kit_data')->get() as $itemData) {
            $data = collect($itemData)->toArray();
            KitData::create($data);
        }


        foreach (DB::connection('data_source')->table('kit_items')->get() as $itemData) {
            $data = collect($itemData)->toArray();
            KitItems::create($data);
        }


        foreach (DB::connection('data_source')->table('item_expenses')->get() as $itemData) {
            $data = collect($itemData)->toArray();
            ItemExpenses::create($data);
        }

        foreach (DB::connection('data_source')->table('item_filters')->get() as $itemData) {
            $data = collect($itemData)->toArray();
            ItemFilters::create($data);
        }


        foreach (DB::connection('data_source')->table('accounts')->get() as $itemData) {
            $data = collect($itemData)->toArray();
            Account::create($data);
        }

        foreach (DB::connection('data_source')->table('roles')->get() as $itemData) {
            $data = collect($itemData)->toArray();
            Role::create($data);
        }

        foreach (DB::connection('data_source')->table('permissions')->get() as $itemData) {
            $data = collect($itemData)->toArray();
            Permission::create($data);
        }


        foreach (DB::connection('data_source')->table('role_has_permissions')->get() as $itemData) {
            $data = collect($itemData)->toArray();
            DB::connection('mysql')->table('role_has_permissions')->insert($data);
        }


        foreach (DB::connection('data_source')->table('role_has_permissions')->get() as $itemData) {
            $data = collect($itemData)->toArray();
            DB::connection('mysql')->table('role_has_permissions')->insert($data);
        }


        foreach (DB::connection('data_source')->table('model_has_roles')->get() as $itemData) {
            $data = collect($itemData)->toArray();
            DB::connection('mysql')->table('model_has_roles')->insert($data);
        }


        foreach (DB::connection('data_source')->table('warranty_subscriptions')->get() as $itemData) {
            $data = collect($itemData)->toArray();
            DB::connection('mysql')->table('warranty_subscriptions')->insert($data);
        }


        foreach (DB::connection('data_source')->table('manager_gateways')->get() as $itemData) {
            $data = collect($itemData)->toArray();
            DB::connection('mysql')->table('manager_gateways')->insert($data);
        }
    }
}
