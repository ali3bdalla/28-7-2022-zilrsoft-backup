<?php
	
	namespace App\Http\Requests\Accounting\Charts;
	
	use App\Models\Account;
	use App\Models\Accounting\TransactionAccounting;
    use Carbon\Carbon;
    use Illuminate\Foundation\Http\FormRequest;
	
	class PeriodCloseAccoundRequest extends FormRequest
	{
		
		use TransactionAccounting;
		
		/**
		 * Determine if the user is authorized to make this request.
		 *
		 * @return bool
		 */
		public function authorize()
		{
			return true;
		}
		
		/**
		 * Get the validation rules that apply to the request.
		 *
		 * @return array
		 */
		public function rules()
		{
			return [
				'gateways' => 'required|array',
				'gateways.*.id' => 'required|integer|exists:accounts,id',
//				'remaining_amount_account_id' => 'required|integer|exists:accounts,id',
				'gateways.*.amount' => 'required|price',
				'period_sales_amount' => 'required|price',
				'remaining_amount' => 'nullable|price',
			];
		}
		
		public function save()
		{
			$temp_reseller_account = Account::where([
				['is_system_account',true],
				['slug','temp_reseller_account'],
			])->first();
			
			$shifts_shortage_account = Account::where([
				['is_system_account',true],
				['slug','shifts_shortage'],
			])->first();
			
		
			$container = auth()->user()->organization->transactions_containers()->create(
				[
					'creator_id' => auth()->user()->id,
					'description' => 'account_close',
					'amount' => 0,
				]
			);
			$short_shortage_amount = $this->getShortageAmount();
			
			$debit_total = 0;
			$gateways_amount = 0;
			foreach ($this->input("gateways") as $gateway){
				if ($gateway['amount'] > 0){
					$data = [];
					$data['creator_id'] = auth()->user()->id;
					$data['organization_id'] = auth()->user()->organization_id;
					$data['debitable_id'] = $gateway['id'];
					$data['debitable_type'] = Account::class;
					$data['amount'] = $gateway['amount'];
					$data['description'] = "close_account";
					$container->transactions()->create($data);
					$gateways_amount = $gateways_amount + $gateway['amount'];
				}
				
			}
			$debit_total = $gateways_amount;
			
			
			if ($short_shortage_amount < 0){
				$debit_total = $debit_total + ($short_shortage_amount * -1);
				$data = [];
				$data['creator_id'] = auth()->user()->id;
				$data['organization_id'] = auth()->user()->organization_id;
				$data['debitable_id'] = $shifts_shortage_account->id;
				$data['debitable_type'] = Account::class;
				$data['amount'] = $short_shortage_amount * -1;
				$data['description'] = "close_account";
				$container->transactions()->create($data);
			}else{
				$data = [];
				$data['creator_id'] = auth()->user()->id;
				$data['organization_id'] = auth()->user()->organization_id;
				$data['creditable_id'] = $shifts_shortage_account->id;
				$data['creditable_type'] = Account::class;
				$data['amount'] = $short_shortage_amount;
				$data['description'] = "close_account";
				$container->transactions()->create($data);
				
			}
			
			
			// /////// *********
			$data = [];
			$data['creator_id'] = auth()->user()->id;
			$data['organization_id'] = auth()->user()->organization_id;
			$data['creditable_id'] = $temp_reseller_account->id;
			$data['creditable_type'] = Account::class;
			$data['amount'] = $this->input("period_sales_amount");
			$data['description'] = "close_account";
			$container->transactions()->create($data);
			//  //  ******
			
			
			$container->update([
				'amount' => $debit_total
			]);



            $lastDate = Carbon::now()->subDays(5);

            $loggedUser->private_transactoins()->create([
                'organization_id' => auth()->user()->organization_id,
                'transaction_type' => "close_account",
                'transaction_container_id' => $transaction_container_id,
                'close_account_start_date' => $lastDate,
                'close_account_end_date' => now(),
                'amount' => $amount,
                'shortage_amount' => $shortage,
            ]);


			$this->toCreateManagerCloseAccountTransaction($debit_total,$container->id,$short_shortage_amount);
			if ($this->filled('remaining_amount') && $this->has('remaining_amount') && $this->input("remaining_amount") > 0 && $this->filled('remaining_amount_account_id')
			&& $this->input('remaining_amount_account_id') >= 0){
                    $this->makeReminingCashAmountTransactions($temp_reseller_account);
			}
//			}
			
			
		}
		
		/**
		 * @return int|mixed
		 */
		public function getShortageAmount()
		{
			$gatewaysAmount = 0; 
			foreach ($this->input("gateways") as $gateway){
				$gatewaysAmount = $gatewaysAmount + $gateway['amount'];
			}
			return $gatewaysAmount - $this->input("period_sales_amount");
		}
		
	}
