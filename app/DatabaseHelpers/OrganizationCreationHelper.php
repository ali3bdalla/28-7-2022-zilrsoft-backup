<?php
	
	
	namespace App\DatabaseHelpers;
	
	
	use App\Chart;
	use App\Gateway;
	
	trait OrganizationCreationHelper
	{
		private $vendors_chart_account_id;
		private $clients_chart_account_id;
		
		private $init_gateway_ids = [
			1,2,3,4,5,6,7
		];
		
		public function initData($supervisor)
		{
			
			
			$this->create_init_accounting_accounts();


			$branch = $this->branches()->create([
				'name' => 'main branch',
				'phone_number' => $supervisor->phone_number,
				'ar_name' => 'الفرع الرئيسي',
				'creator_id' => $supervisor->id
			]);

			$departmemt = $this->departments()->create([
				'branch_id' => $branch->id,
				'title' => 'الادارة',
				'ar_title' => 'management',
				'creator_id' => $supervisor->id
			]);


			$supervisor->update([
				'branch_id' => $departmemt->branch_id,
				'department_id' => $departmemt->id
			]);

			$users =
				[
					[
						'creator_id' => $supervisor->id,
						'client_chart_id' => $this->clients_chart_account_id,
						'phone_number' => '0000000000',
						'balance' => 0,
						'is_client' => true,
						'is_system_user' => true,
						'name' => 'عميل نقدي',
						'user_slug' => 'client-ready',
						'user_type' => 'individual',
					],
					[
						'creator_id' => $supervisor->id,
						'vendor_chart_id' => $this->vendors_chart_account_id,
						'phone_number' => '0000000000',
						'balance' => 0,
						'is_vendor' => true,
						'name' => 'اول مدة',
						'user_slug' => 'beginning-inventory',
						'is_system_user' => true,
						'user_type' => 'individual'
					]
				];

//			dd($users);

			foreach ($users as $user){
				$this->users()->create(
					$user
				);
			}
			$this->gateways()->attach($this->init_gateway_ids,[
				'creator_id' => $supervisor->id
			]);
		
		
		}
		
		public function create_init_accounting_accounts()
		{
			$this->create_assets_accounting_accounts();
			$this->create_liabitities_accounting_accounts();
			$this->create_income_accounting_accounts();
			$this->create_expenses_accounting_accounts();
			
		}
		
		public function create_assets_accounting_accounts()
		{
			
			
			$organization_id = $this->id;
			$creator_id = auth()->user()->id;
			
			$assets_account = Chart::create([
				"ar_name" => "الاصول",
				"name" => "assets",
				"slug" => "assets",
				"parent_id" => 0,
				"serial" => "100000000000000000000",
				'organization_id' => $organization_id,
				'creator_id' => $creator_id
			]);
			
			$current_assets_account = Chart::create([
				"ar_name" => "الاصول المتداولة",
				"name" => "current assets",
				"slug" => "current_assets",
				"parent_id" => $assets_account->id,
				"serial" => "110000000000000000000",
				'organization_id' => $organization_id,
				'creator_id' => $creator_id
			]);

			


			$gateways = Gateway::find($this->init_gateway_ids);

			foreach ($gateways as $index => $gateway){
				
				$gateway_chart = Chart::create([
					"ar_name" => $gateway->ar_name,
					"name" =>  $gateway->name,
					"slug" => "gateway",
					"parent_id" => $current_assets_account->id,
					"serial" => "112000000000000000000",
					'organization_id' => $organization_id,
					'creator_id' => $creator_id
				]);
				
				
				
				
				$gateway->update([
					'chart_id' => $gateway_chart->id
				]);
			}
//
			
			
			$clients = Chart::create([
				"ar_name" => "العملاء",
				"name" => "customers ",
				"slug" => "clients",
				"parent_id" => $current_assets_account->id,
				"serial" => "112000000000000000000",
				'organization_id' => $organization_id,
				'creator_id' => $creator_id
			]);
			
			$this->clients_chart_account_id = $clients->id; // add organization main client chart account
			
			
			Chart::create([
				"ar_name" => "المخزون",
				"name" => "stock ",
				"slug" => "stock",
				"parent_id" => $current_assets_account->id,
				"serial" => "112000000000000000000",
				'organization_id' => $organization_id,
				'creator_id' => $creator_id
			]);
		}
		
		public function create_liabitities_accounting_accounts()
		{
			
			
			$organization_id = $this->id;
			$creator_id = auth()->user()->id;
			
			$liabilities_account = Chart::create([
				"ar_name" => "الخصوم",
				"name" => "Liabilities",
				"slug" => "liabilities",
				"parent_id" => 0,
				"serial" => "100000000000000000000",
				'organization_id' => $organization_id,
				'creator_id' => $creator_id
			]);
			
			Chart::create([
				"ar_name" => "الخصوم المتداولة",
				"name" => "current Liabilities",
				"slug" => "current_liabilities",
				"parent_id" => $liabilities_account->id,
				"serial" => "110000000000000000000",
				'organization_id' => $organization_id,
				'creator_id' => $creator_id
			]);
			
			
			$vendor_chart = Chart::create([
				"ar_name" => "الموردين",
				"name" => "accounts payable ",
				"slug" => "vendors",
				"parent_id" => $liabilities_account->id,
				"serial" => "112000000000000000000",
				'organization_id' => $organization_id,
				'creator_id' => $creator_id
			]);
			$this->vendors_chart_account_id = $vendor_chart->id; // add organization main vendors chart account
			
			Chart::create([
				"ar_name" => "ضريبة القيمة المضافة",
				"name" => "VAT ",
				"slug" => "vat",
				"parent_id" => $liabilities_account->id,
				"serial" => "112000000000000000000",
				'organization_id' => $organization_id,
				'creator_id' => $creator_id
			]);
		}
		
		public function create_income_accounting_accounts()
		{
			
			
			$organization_id = $this->id;
			$creator_id = auth()->user()->id;
			
			$income_account = Chart::create([
				"ar_name" => "الايرادات",
				"name" => "Income",
				"slug" => "income",
				"parent_id" => 0,
				"serial" => "100000000000000000000",
				'organization_id' => $organization_id,
				'creator_id' => $creator_id
			]);
			
			$net_sales_account = Chart::create([
				"ar_name" => "صافي المبيعات",
				"name" => "net sales",
				'slug' => 'net_sales',
				"parent_id" => $income_account->id,
				"serial" => "110000000000000000000",
				'organization_id' => $organization_id,
				'creator_id' => $creator_id
			]);
			
			
			Chart::create([
				"ar_name" => "مبيعات السلع",
				"name" => "sales",
				"slug" => "products_sales",
				"parent_id" => $net_sales_account->id,
				"serial" => "112000000000000000000",
				'organization_id' => $organization_id,
				'creator_id' => $creator_id
			]);
			
			
			Chart::create([
				"ar_name" => "مرتجعات السلع",
				"name" => "sales return ",
				"slug" => "products_return_sales",
				"parent_id" => $net_sales_account->id,
				"serial" => "112000000000000000000",
				'organization_id' => $organization_id,
				'creator_id' => $creator_id
			]);
			
			
			Chart::create([
				"ar_name" => "خدمي ",
				"name" => "services ",
				"slug" => "services_sales",
				"parent_id" => $net_sales_account->id,
				"serial" => "112000000000000000000",
				'organization_id' => $organization_id,
				'creator_id' => $creator_id
			]);
			
			
			Chart::create([
				"ar_name" => "مرتجع خدمات ",
				"name" => "services return ",
				"slug" => "services_return_sales",
				"parent_id" => $net_sales_account->id,
				"serial" => "112000000000000000000",
				'organization_id' => $organization_id,
				'creator_id' => $creator_id
			]);
			
			Chart::create([
				"ar_name" => " خصم على المبيعات",
				"name" => "sales discount ",
				"slug" => "product_sales_discount",
				"parent_id" => $net_sales_account->id,
				"serial" => "112000000000000000000",
				'organization_id' => $organization_id,
				'creator_id' => $creator_id
			]);
			
		}
		
		public function create_expenses_accounting_accounts()
		{
			$organization_id = $this->id;
			$creator_id = auth()->user()->id;
			
			$expense_account = Chart::create([
				"ar_name" => "المصروفات",
				"name" => "expenses",
				"slug" => "expenses",
				"parent_id" => 0,
				"serial" => "100000000000000000000",
				'organization_id' => $organization_id,
				'creator_id' => $creator_id
			]);
			
			Chart::create([
				"ar_name" => "تكلفة البضاعة المباعة",
				"name" => "cost of goods sold",
				"slug" => "cost_of_goods_sale",
				"parent_id" => $expense_account->id,
				"serial" => "110000000000000000000",
				'organization_id' => $organization_id,
				'creator_id' => $creator_id
			]);
			
			
		}
	}
