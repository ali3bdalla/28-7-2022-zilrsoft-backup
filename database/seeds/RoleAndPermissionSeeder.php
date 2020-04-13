<?php
	
	use App\Manager;
	use Illuminate\Database\Seeder;
	use Spatie\Permission\Models\Permission;
	use Spatie\Permission\Models\Role;
	
	
	class RoleAndPermissionSeeder extends Seeder
	{
		/**
		 * Run the database seeds.
		 *
		 * @return void
		 */
		public function run()
		{
			
			$this->item_permissions();
			$this->category_permissions();
			$this->filter_permissions();
			$this->identities_permissions();
			$this->sales_permissions();
			$this->vouchers_permissions();
			$this->purchase_permissions();
			$this->reports_permissions();
			$this->accounting_permissions();
			$this->settings_permissions();
			$this->init_super_manager_permissions();
//
			
			//
		}
		
		private function item_permissions()
		{
			$role_item_manager = Role::create(['name' => 'item Manager','guard_name' => 'manager']);
			$permission_create_item = Permission::create(['name' => 'create item','guard_name' => 'manager']);
			$permission_edit_item = Permission::create(['name' => 'edit item','guard_name' => 'manager']);
			$permission_delete_item = Permission::create(['name' => 'delete item','guard_name' => 'manager']);
			$permission_view_item = Permission::create(['name' => 'view item','guard_name' => 'manager']);
			$permission_mange_kit_item = Permission::create(['name' => 'manage kit','guard_name' => 'manager']);
			$role_item_manager->givePermissionTo($permission_create_item);
			$role_item_manager->givePermissionTo($permission_edit_item);
			$role_item_manager->givePermissionTo($permission_delete_item);
			$role_item_manager->givePermissionTo($permission_view_item);
			$role_item_manager->givePermissionTo($permission_mange_kit_item);
			
		}
		
		private function category_permissions()
		{
			$role = Role::create(['name' => 'category manager','guard_name' => 'manager']);
			$permission_create = Permission::create(['name' => 'create category','guard_name' => 'manager']);
			$permission_edit = Permission::create(['name' => 'edit category','guard_name' => 'manager']);
			$permission_delete = Permission::create(['name' => 'delete category','guard_name' => 'manager']);
			$permission_view = Permission::create(['name' => 'view category','guard_name' => 'manager']);
			$role->givePermissionTo($permission_create);
			$role->givePermissionTo($permission_edit);
			$role->givePermissionTo($permission_delete);
			$role->givePermissionTo($permission_view);
			
		}
		
		private function filter_permissions()
		{
			$role = Role::create(['name' => 'filter manager','guard_name' => 'manager']);
			$permission_create = Permission::create(['name' => 'create filter','guard_name' => 'manager']);
			$permission_edit = Permission::create(['name' => 'edit filter','guard_name' => 'manager']);
			$permission_delete = Permission::create(['name' => 'delete filter','guard_name' => 'manager']);
			$permission_view = Permission::create(['name' => 'view filter','guard_name' => 'manager']);
			$role->givePermissionTo($permission_create);
			$role->givePermissionTo($permission_edit);
			$role->givePermissionTo($permission_delete);
			$role->givePermissionTo($permission_view);
			
		}
		
		private function identities_permissions()
		{
			$role = Role::create(['name' => 'identities manager','guard_name' => 'manager']);
			$permission_create = Permission::create(['name' => 'create identity','guard_name' => 'manager']);
			$permission_edit = Permission::create(['name' => 'edit identity','guard_name' => 'manager']);
			$permission_delete = Permission::create(['name' => 'delete identity','guard_name' => 'manager']);
			$permission_view = Permission::create(['name' => 'view identity','guard_name' => 'manager']);
			$permission_edit_manage_managers = Permission::create(['name' => 'manage managers','guard_name' =>
				'manager']);
			$role->givePermissionTo($permission_create);
			$role->givePermissionTo($permission_edit);
			$role->givePermissionTo($permission_delete);
			$role->givePermissionTo($permission_view);
			$role->givePermissionTo($permission_edit_manage_managers);
			
		}
		
		private function sales_permissions()
		{
			$role = Role::create(['name' => 'sales manager','guard_name' => 'manager']);
			$permission_create = Permission::create(['name' => 'create sale','guard_name' => 'manager']);
			$permission_edit = Permission::create(['name' => 'edit sale','guard_name' => 'manager']);
			$permission_delete = Permission::create(['name' => 'delete sale','guard_name' => 'manager']);
			$permission_view = Permission::create(['name' => 'view sale','guard_name' => 'manager']);
			$permission_mange_quotation_item = Permission::create(['name' => 'manage quotation','guard_name' => 'manager']);
			
			$role->givePermissionTo($permission_create);
			$role->givePermissionTo($permission_edit);
			$role->givePermissionTo($permission_delete);
			$role->givePermissionTo($permission_view);
			$role->givePermissionTo($permission_mange_quotation_item);
			
		}
		
		public function vouchers_permissions()
		{
			$role = Role::create(['name' => 'vouchers manager','guard_name' => 'manager']);
			$permission_create = Permission::create(['name' => 'create voucher','guard_name' => 'manager']);
			$permission_edit = Permission::create(['name' => 'edit voucher','guard_name' => 'manager']);
			$permission_delete = Permission::create(['name' => 'delete voucher','guard_name' => 'manager']);
			$permission_view = Permission::create(['name' => 'view voucher','guard_name' => 'manager']);
			$role->givePermissionTo($permission_create);
			$role->givePermissionTo($permission_edit);
			$role->givePermissionTo($permission_delete);
			$role->givePermissionTo($permission_view);
		}
		
		private function purchase_permissions()
		{
			$role = Role::create(['name' => 'purchases manager','guard_name' => 'manager']);
			$permission_create = Permission::create(['name' => 'create purchase','guard_name' => 'manager']);
			$permission_edit = Permission::create(['name' => 'edit purchase','guard_name' => 'manager']);
			$permission_delete = Permission::create(['name' => 'delete purchase','guard_name' => 'manager']);
			$permission_view = Permission::create(['name' => 'view purchase','guard_name' => 'manager']);
			$permission_manage_inventory = Permission::create(['name' => 'manage inventory','guard_name' => 'manager']);
			$permission_confirmed_invoice = Permission::create(['name' => 'confirm purchase','guard_name' => 'manager']);
			$role->givePermissionTo($permission_create);
			$role->givePermissionTo($permission_edit);
			$role->givePermissionTo($permission_delete);
			$role->givePermissionTo($permission_view);
			$role->givePermissionTo($permission_manage_inventory);
			$role->givePermissionTo($permission_confirmed_invoice);
			
		}
		
		public function reports_permissions()
		{
			$role = Role::create(['name' => 'report manager','guard_name' => 'manager']);
			$permission_create = Permission::create(['name' => 'create report','guard_name' => 'manager']);
			$permission_confirm = Permission::create(['name' => 'confirm report','guard_name' => 'manager']);
			$permission_view = Permission::create(['name' => 'view report','guard_name' => 'manager']);
			$role->givePermissionTo($permission_create);
			$role->givePermissionTo($permission_confirm);
			$role->givePermissionTo($permission_view);
			
		}
		
		private function accounting_permissions()
		{
			$role = Role::create(['name' => 'accounting manager','guard_name' => 'manager']);
			$permission_view_charts = Permission::create(['name' => 'view charts','guard_name' => 'manager']);
			$permission_create_chart = Permission::create(['name' => 'create chart','guard_name' => 'manager']);
			$permission_edit_chart = Permission::create(['name' => 'edit chart','guard_name' => 'manager']);
			$permission_delete_chart = Permission::create(['name' => 'delete chart','guard_name' => 'manager']);
			$permission_view_transactions = Permission::create(['name' => 'view transactions','guard_name' => 'manager']);
			$permission_create_transaction = Permission::create(['name' => 'create transaction','guard_name' => 'manager']);
			$permission_view_financial_statements = Permission::create(['name' => 'view financial statements','guard_name' =>
				'manager']);
			$permission_view_item_transactions = Permission::create(['name' => 'view item transactions','guard_name' =>
				'manager']);
			
			$role->givePermissionTo($permission_view_charts);
			$role->givePermissionTo($permission_create_chart);
			$role->givePermissionTo($permission_edit_chart);
			$role->givePermissionTo($permission_delete_chart);
			$role->givePermissionTo($permission_view_transactions);
			$role->givePermissionTo($permission_create_transaction);
			$role->givePermissionTo($permission_view_financial_statements);
			$role->givePermissionTo($permission_view_item_transactions);
			
		}
		
		private function settings_permissions()
		{
			
			$role = Role::create(['name' => 'system manager','guard_name' => 'manager']);
			$permission_create = Permission::create(['name' => 'manage settings','guard_name' => 'manager']);
			$permission_confirm = Permission::create(['name' => 'manage expenses','guard_name' => 'manager']);
			$permission_view = Permission::create(['name' => 'manage branches','guard_name' => 'manager']);
			$permission_view_system_events = Permission::create(['name' => 'view system events','guard_name' => 'manager']);
			$role->givePermissionTo($permission_create);
			$role->givePermissionTo($permission_confirm);
			$role->givePermissionTo($permission_view);
			$role->givePermissionTo($permission_view_system_events);
			
		}
		
		private function init_super_manager_permissions()
		{
			
			$role = Role::create(['name' => 'super admin','guard_name' => 'manager']);
			$manager = Manager::find(1);
			$manager->assignRole('super admin');
			
		}
	}
