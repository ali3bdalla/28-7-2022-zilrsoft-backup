<?php
	
	
	namespace App\DatabaseHelpers;
	
	
	use App\Account;
	
	trait OrganizationCreationHelper
	{
		private $vendors_chart_account_id = 1;
		private $clients_chart_account_id = 1;
		
		private $accounts_creator_id = 0;
		private $accounts_organization_id = 0;
		
		public function initData($supervisor)
		{

//
//			DB::insert("
//INSERT INTO `banks` (`id`, `name`, `ar_name`,`country_id`) VALUES
//(NULL , 'Al Rajhi Bank', 'مصرف الراجحي',1),
//(NULL, 'alinma bank', 'مصرف الإنماء',1),
//(NULL, 'Samba Financial Group (Samba)', 'مجموعة سامبا المالية (سامبا)',1),
//(NULL, 'Riyad Bank', 'بنك الرياض',1),
//(NULL, 'Bank AlJazira', 'بنك الجزيرة',1),
//(NULL, 'Bank AlBilad', 'بنك البلاد',1),
//(NULL, 'Arab National Bank', 'البنك العربي الوطني',1),
//(NULL, 'Saudi Investment Bank', 'البنك السعودي للاستثمار',1),
//(NULL, 'alawwal bank', 'البنك الأول',1),
//(NULL, 'Banque Saudi Fransi', 'البنك السعودي الفرنسي',1),
//(NULL, 'The Saudi British Bank', 'البنك السعودي البريطاني',1),
//(NULL, 'The National Commercial Bank', 'البنك الأهلي التجاري',1)
//");
			
			$this->accounts_creator_id = $supervisor->id;
			$this->accounts_organization_id = $this->id;
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
			
			
			foreach ($users as $user){
				$this->users()->create(
					$user
				);
			}
			
			
			return true;
		}
		
		public function create_init_accounting_accounts()
		{
			$this->create_assets_accounting_accounts();
			$this->create_liabitities_accounting_accounts();
			$this->create_equity_accounting_accounts();
			$this->create_income_accounting_accounts();
			$this->create_expenses_accounting_accounts();
//
		}
		
		public function create_assets_accounting_accounts()
		{
			
			
			$assets_account = Account::create([
				'is_system_account' => true,
				"ar_name" => "الاصول",
				'type' => 'debit',
				"name" => "assets",
				"slug" => "assets",
				"parent_id" => 0,
				"serial" => "100000000000000000000",
				'organization_id' => $this->accounts_organization_id,
				'creator_id' => $this->accounts_creator_id
			]);
			
			
			$current_assets_account = Account::create([
				'is_system_account' => true,
				"ar_name" => "الاصول المتداولة",
				'type' => 'debit',
				"name" => "current assets",
				"slug" => "current_assets",
				"parent_id" => $assets_account->id,
				"serial" => "110000000000000000000",
				'organization_id' => $this->accounts_organization_id,
				'creator_id' => $this->accounts_creator_id
			]);
			
			
			$this->create_gateway_accounts($current_assets_account);
			
			
			$clients = Account::create([
				'is_system_account' => true,
				"ar_name" => "العملاء",
				"name" => "customers ",
				'type' => 'debit',
				"slug" => "clients",
				"parent_id" => $current_assets_account->id,
				"serial" => "112000000000000000000",
				'organization_id' => $this->accounts_organization_id,
				'creator_id' => $this->accounts_creator_id
			]);
			
			$this->clients_chart_account_id = $clients->id; // add organization main client chart account
			
			
			Account::create([
				'is_system_account' => true,
				"ar_name" => "المخزون",
				'type' => 'debit',
				"name" => "stock ",
				"slug" => "stock",
				"parent_id" => $current_assets_account->id,
				"serial" => "112000000000000000000",
				'organization_id' => $this->accounts_organization_id,
				'creator_id' => $this->accounts_creator_id
			]);
			
			
			$fixed_assets_account = Account::create([
				'is_system_account' => true,
				"ar_name" => "الاصول الثابتة",
				'type' => 'debit',
				"name" => "fixed assets",
				"slug" => "fixed_assets",
				"parent_id" => $assets_account->id,
				"serial" => "110000000000000000000",
				'organization_id' => $this->accounts_organization_id,
				'creator_id' => $this->accounts_creator_id
			]);
			
			$current_assets_account = Account::create([
				'is_system_account' => true,
				'type' => 'debit',
				"ar_name" => " سيارات",
				"name" => "cars",
				"slug" => "fixed_assets",
				"parent_id" => $fixed_assets_account->id,
				"serial" => "110000000000000000000",
				'organization_id' => $this->accounts_organization_id,
				'creator_id' => $this->accounts_creator_id
			]);
			
			$cars_accounts = Account::create([
				'is_system_account' => false,
				'type' => 'debit',
				"ar_name" => " سيارات",
				"name" => "cars",
				"slug" => "fixed_assets",
				"parent_id" => $fixed_assets_account->id,
				"serial" => "110000000000000000000",
				'organization_id' => $this->accounts_organization_id,
				'creator_id' => $this->accounts_creator_id
			]);
			
			
			$current_assets_account = Account::create([
				'is_system_account' => false,
				'type' => 'debit',
				"ar_name" => " سيارة أ",
				"name" => "car A",
				"slug" => "fixed_assets",
				"parent_id" => $cars_accounts->id,
				"serial" => "110000000000000000000",
				'organization_id' => $this->accounts_organization_id,
				'creator_id' => $this->accounts_creator_id
			]);
			
			
		}
		
		public function create_gateway_accounts($current_assets_account)
		{
			//
			
			Account::create([
				'is_system_account' => true,
				"ar_name" => "الحساب الوسيط",
				"name" => "central tendency ",
				'type' => 'debit',
				"slug" => "temp_reseller_account",
				"parent_id" => $current_assets_account->id,
				"serial" => "112000000000000000000",
				'organization_id' => $this->accounts_organization_id,
				'creator_id' => $this->accounts_creator_id,
				'is_gateway' => true
			]);
			
			
			Account::create([
				'is_system_account' => true,
				"ar_name" => " عجز الفترات",
				"name" => "shifts shortage ",
				'type' => 'debit',
				"slug" => "shifts_shortage",
				"parent_id" => $current_assets_account->id,
				"serial" => "112000000000000000000",
				'organization_id' => $this->accounts_organization_id,
				'creator_id' => $this->accounts_creator_id,
				'is_gateway' => true
			]);
			
			
			Account::create([
				'is_system_account' => true,
				"ar_name" => "نقداً",
				"name" => "Cash ",
				'type' => 'debit',
				"slug" => "gateway",
				"parent_id" => $current_assets_account->id,
				"serial" => "112000000000000000000",
				'organization_id' => $this->accounts_organization_id,
				'creator_id' => $this->accounts_creator_id,
				'is_gateway' => true
			]);
			
			$banks_account = Account::create([
				'is_system_account' => true,
				"ar_name" => "بنوك",
				'type' => 'debit',
				"name" => "Banks",
				"slug" => "gateway",
				"parent_id" => $current_assets_account->id,
				"serial" => "112000000000000000000",
				'organization_id' => $this->accounts_organization_id,
				'creator_id' => $this->accounts_creator_id,
				'is_gateway' => false
			]);
			
			$alrajhi = Account::create([
				'is_system_account' => true,
				"ar_name" => "الراجحي",
				'type' => 'debit',
				"name" => "Alrghi",
				"slug" => "gateway",
				"parent_id" => $banks_account->id,
				"serial" => "112000000000000000000",
				'organization_id' => $this->accounts_organization_id,
				'creator_id' => $this->accounts_creator_id,
				'is_gateway' => false
			]);
			
			$alrass = Account::create([
				'is_system_account' => true,
				"ar_name" => "فرع الرس",
				'type' => 'debit',
				"name" => "Alrass",
				"slug" => "gateway",
				"parent_id" => $alrajhi->id,
				"serial" => "112000000000000000000",
				'organization_id' => $this->accounts_organization_id,
				'creator_id' => $this->accounts_creator_id,
				'is_gateway' => true
			]);
			
			
			$this->creat_single_bank_children($alrass);
			
			
		}
		
		public function creat_single_bank_children($bank)
		{
			$mada = Account::create([
				'is_system_account' => true,
				"ar_name" => "مدى",
				"name" => "Mada ",
				'type' => 'debit',
				"slug" => "gateway",
				"parent_id" => $bank->id,
				"serial" => "112000000000000000000",
				'organization_id' => $this->accounts_organization_id,
				'creator_id' => $this->accounts_creator_id,
				'is_gateway' => true
			]);
			
			
			Account::create([
				'is_system_account' => true,
				"ar_name" => "مدى احمد",
				"name" => "Mada Ahmed",
				"slug" => "gateway",
				'type' => 'debit',
				"parent_id" => $mada->id,
				"serial" => "112000000000000000000",
				'organization_id' => $this->accounts_organization_id,
				'creator_id' => $this->accounts_creator_id,
				'is_gateway' => true
			]);
			
			
			Account::create([
				'is_system_account' => true,
				"ar_name" => "مدى محمود",
				"name" => "Mada Mahmod",
				'type' => 'debit',
				"slug" => "gateway",
				"parent_id" => $mada->id,
				"serial" => "112000000000000000000",
				'organization_id' => $this->accounts_organization_id,
				'creator_id' => $this->accounts_creator_id,
				'is_gateway' => true
			]);
			
			
			Account::create([
				'is_system_account' => true,
				"ar_name" => "مدى عبدالرحمن",
				"name" => "Mada Abdalrahman",
				'type' => 'debit',
				"slug" => "gateway",
				"parent_id" => $mada->id,
				"serial" => "112000000000000000000",
				'organization_id' => $this->accounts_organization_id,
				'creator_id' => $this->accounts_creator_id,
				'is_gateway' => true
			]);
			
			
			Account::create([
				'is_system_account' => true,
				"ar_name" => "استي سي بي",
				"name" => "STC Pay",
				"slug" => "gateway",
				'type' => 'debit',
				"parent_id" => $bank->id,
				"serial" => "112000000000000000000",
				'organization_id' => $this->accounts_organization_id,
				'creator_id' => $this->accounts_creator_id,
				'is_gateway' => true
			]);
			
			
		}
		
		public function create_liabitities_accounting_accounts()
		{
			
			
			$liabilities_account = Account::create([
				'is_system_account' => true,
				"ar_name" => "الخصوم",
				'type' => 'debit',
				"name" => "Liabilities",
				"slug" => "liabilities",
				"parent_id" => 0,
				"serial" => "100000000000000000000",
				'organization_id' => $this->accounts_organization_id,
				'creator_id' => $this->accounts_creator_id
			]);
			
			Account::create([
				'is_system_account' => true,
				"ar_name" => "الخصوم المتداولة",
				"name" => "current Liabilities",
				'type' => 'debit',
				"slug" => "current_liabilities",
				"parent_id" => $liabilities_account->id,
				"serial" => "110000000000000000000",
				'organization_id' => $this->accounts_organization_id,
				'creator_id' => $this->accounts_creator_id
			]);
			
			
			$vendor_chart = Account::create([
				'is_system_account' => true,
				"ar_name" => "الموردين",
				'type' => 'credit',
				"name" => "accounts payable ",
				"slug" => "vendors",
				"parent_id" => $liabilities_account->id,
				"serial" => "112000000000000000000",
				'organization_id' => $this->accounts_organization_id,
				'creator_id' => $this->accounts_creator_id
			]);
			$this->vendors_chart_account_id = $vendor_chart->id; // add organization main vendors chart account
			
			Account::create([
				'is_system_account' => true,
				"ar_name" => "ضريبة القيمة المضافة",
				'type' => 'credit',
				"name" => "VAT ",
				"slug" => "vat",
				"parent_id" => $liabilities_account->id,
				"serial" => "112000000000000000000",
				'organization_id' => $this->accounts_organization_id,
				'creator_id' => $this->accounts_creator_id
			]);
		}
		
		public function create_equity_accounting_accounts()
		{
			
			
			$equity_account = Account::create([
				'is_system_account' => true,
				"ar_name" => "حقوق الملكية",
				"name" => "equity",
				"slug" => "equity",
				"parent_id" => 0,
				"serial" => "100000000000000000000",
				'organization_id' => $this->accounts_organization_id,
				'creator_id' => $this->accounts_creator_id
			]);
			
			
			$capital = Account::create([
				'is_system_account' => true,
				"ar_name" => "رأس المال",
				"name" => "Capital",
				"slug" => "capital",
				"parent_id" => $equity_account->id,
				"serial" => "100000000000000000000",
				'organization_id' => $this->accounts_organization_id,
				'creator_id' => $this->accounts_creator_id
			]);
			
			$capital = Account::create([
				'is_system_account' => true,
				"ar_name" => "جاري الشركاء",
				"name" => "withdrawals",
				"slug" => "withdrawals",
				"parent_id" => $equity_account->id,
				"serial" => "100000000000000000000",
				'organization_id' => $this->accounts_organization_id,
				'creator_id' => $this->accounts_creator_id
			]);
			
			
		}
		
		public function create_income_accounting_accounts()
		{
			
			
			$income_account = Account::create([
				'is_system_account' => true,
				"ar_name" => "الايرادات",
				"name" => "Income",
				"slug" => "income",
				"parent_id" => 0,
				"serial" => "100000000000000000000",
				'organization_id' => $this->accounts_organization_id,
				'creator_id' => $this->accounts_creator_id
			]);
			
			$net_sales_account = Account::create([
				'is_system_account' => true,
				"ar_name" => "صافي المبيعات",
				"name" => "net sales",
				'slug' => 'net_sales',
				"parent_id" => $income_account->id,
				"serial" => "110000000000000000000",
				'organization_id' => $this->accounts_organization_id,
				'creator_id' => $this->accounts_creator_id
			]);
			
			
			Account::create([
				'is_system_account' => true,
				"ar_name" => "مبيعات السلع",
				"name" => "sales",
				"slug" => "products_sales",
				"parent_id" => $net_sales_account->id,
				"serial" => "112000000000000000000",
				'organization_id' => $this->accounts_organization_id,
				'creator_id' => $this->accounts_creator_id
			]);
			
			
			Account::create([
				'is_system_account' => true,
				"ar_name" => "مرتجعات السلع",
				"name" => "sales return ",
				"slug" => "products_return_sales",
				"parent_id" => $net_sales_account->id,
				"serial" => "112000000000000000000",
				'organization_id' => $this->accounts_organization_id,
				'creator_id' => $this->accounts_creator_id
			]);
			
			Account::create([
				'is_system_account' => true,
				"ar_name" => " خصم  السلع",
				"name" => "sales discount ",
				"slug" => "products_sales_discount",
				"parent_id" => $net_sales_account->id,
				"serial" => "112000000000000000000",
				'organization_id' => $this->accounts_organization_id,
				'creator_id' => $this->accounts_creator_id
			]);
			
			Account::create([
				'is_system_account' => true,
				"ar_name" => "خدمي ",
				"name" => "services ",
				"slug" => "services_sales",
				"parent_id" => $net_sales_account->id,
				"serial" => "112000000000000000000",
				'organization_id' => $this->accounts_organization_id,
				'creator_id' => $this->accounts_creator_id
			]);
			
			
			Account::create([
				'is_system_account' => true,
				"ar_name" => "مرتجع خدمات ",
				"name" => "services return ",
				"slug" => "services_return_sales",
				"parent_id" => $net_sales_account->id,
				"serial" => "112000000000000000000",
				'organization_id' => $this->accounts_organization_id,
				'creator_id' => $this->accounts_creator_id
			]);
			
			Account::create([
				'is_system_account' => true,
				"ar_name" => " خصم  الخدمات",
				"name" => "services  discount ",
				"slug" => "services_sales_discount",
				"parent_id" => $net_sales_account->id,
				"serial" => "112000000000000000000",
				'organization_id' => $this->accounts_organization_id,
				'creator_id' => $this->accounts_creator_id
			]);
			
			Account::create([
				'is_system_account' => true,
				"ar_name" => "مبيعات الخدمات اخرى",
				"name" => "other services ",
				"slug" => "other_services_sales",
				"parent_id" => $net_sales_account->id,
				"serial" => "112000000000000000000",
				'organization_id' => $this->accounts_organization_id,
				'creator_id' => $this->accounts_creator_id
			]);
			
			
			Account::create([
				'is_system_account' => true,
				"ar_name" => "مرتجع الخدمات الاخرى ",
				"name" => "other services return ",
				"slug" => "other_services_return_sales",
				"parent_id" => $net_sales_account->id,
				"serial" => "112000000000000000000",
				'organization_id' => $this->accounts_organization_id,
				'creator_id' => $this->accounts_creator_id
			]);
			
			
			Account::create([
				'is_system_account' => true,
				"ar_name" => " خصم  الخدمات الاخرى",
				"name" => "other services  discount ",
				"slug" => "other_services_sales_discount",
				"parent_id" => $net_sales_account->id,
				"serial" => "112000000000000000000",
				'organization_id' => $this->accounts_organization_id,
				'creator_id' => $this->accounts_creator_id
			]);
			
			
		}
		
		public function create_expenses_accounting_accounts()
		{
			
			$expense_account = Account::create([
				'is_system_account' => true,
				"ar_name" => "المصروفات",
				'type' => 'debit',
				"name" => "expenses",
				"slug" => "expenses",
				"parent_id" => 0,
				"serial" => "100000000000000000000",
				'organization_id' => $this->accounts_organization_id,
				'creator_id' => $this->accounts_creator_id
			]);
			
			
			
			Account::create([
				'is_system_account' => true,
				"ar_name" => "تكلفة البضاعة المباعة",
				"name" => "cost of goods sold",
				"slug" => "cogs",
				"parent_id" => $expense_account->id,
				"serial" => "110000000000000000000",
				'organization_id' => $this->accounts_organization_id,
				'creator_id' => $this->accounts_creator_id
			]);
			
			
			Account::create([
				'is_system_account' => true,
				"ar_name" => "تسويات جرد المخزون",
				'type' => 'debit',
				"name" => "inventory adjustment",
				"slug" => "inventory_adjustment",
				"parent_id" => $expense_account->id,
				"serial" => "100000000000000000000",
				'organization_id' => $this->accounts_organization_id,
				'creator_id' => $this->accounts_creator_id
			]);
			
			
			
		}
	}
