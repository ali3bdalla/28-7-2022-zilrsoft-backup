<?php
	
	namespace App\Http\Requests\Accounting\Transaction;
	
	use App\Account;
    use App\Events\User\ShouldUpdateUserBalanceEvent;
    use App\TransactionsContainer;
    use App\User;
	use Exception;
	use Illuminate\Foundation\Http\FormRequest;
	use Illuminate\Support\Facades\DB;
	use Illuminate\Validation\ValidationException;
	
	class CreateTransactionRequest extends FormRequest
	{
		

		
		/**
		 * Determine if the user is authorized to make this request.
		 *
		 * @return bool
		 */
		public function authorize()
		{
			return $this->user()->can('create transaction');
		}
		
		/**
		 * Get the validation rules that apply to the request.
		 *
		 * @return array
		 */
		public function rules()
		{
			return [
				'transactions' => 'required|array',
				"transactions.*.id" => "required|integer|exists:accounts,id",
				"transactions.*.credit_amount" => "required|price",
				"transactions.*.debit_amount" => "required|price",
				"transactions.*.is_credit" => "required|boolean",
				"transactions.*.vendor_id" => ["integer","exists:users,id"],
				"transactions.*.client_id" => ["integer","exists:users,id"],
				"transactions.*.item_id" =>["integer","exists:items,id"],
				'description' => "required|string",
				'amount' => "required|numeric",
			];
		}

        private function _validateMissingRules($validator = 'item_id',$index)
        {
            if(!collect($this->input('transactions')[$index])->has($validator))
                 throw  new ValidationException($validator,'is required');
		}


        private function validateTransactionEntities()
        {
            $total_credit = 0;
            $total_debit = 0;
            foreach ($this->input("transactions") as $transaction){
                if ($transaction['is_credit'])
                    $total_credit = $total_credit + floatval($transaction['credit_amount']);
                else
                    $total_debit = $total_debit + floatval($transaction['debit_amount']);
            }

            return $total_credit == $total_debit || $total_credit != $this->input('amount');
        }



		public function save()
		{

			if($this->validateTransactionEntities())
            {
                DB::beginTransaction();
                try{

                    $container = auth()->user()->organization->transactions_containers()->create(
                        [
                            'creator_id' => auth()->user()->id,'description' => $this->input("description"),'amount' => $this->input("amount"),
                        ]
                    );
                    foreach ($this->input("transactions") as $index => $account_json ){
                        $account = Account::find($account_json['id']);
                        if ($account->_isStock()){
                            $this->_validateMissingRules('item_id',$index);
                            $this->toCreateStockTransaction($account_json,$account,$container);
                        }elseif ($account->_isClients()){
                            $this->_validateMissingRules('client_id',$index);
                            $this->toCreateClientTransaction($account_json,$account,$container);
                        }elseif ($account->_isVendors()){
                            $this->_validateMissingRules('vendor_id',$index);
                            $this->toCreateVendorTransaction($account_json,$account,$container);
                        }else{
                            $this->toCreateAccountTransaction($account_json,$account,$container);
                        }
                    }
                    DB::commit();
                    return  response($container->fresh()->load('transactions'),200);
                }
                catch (ValidationException $exception){
                    DB::rollBack();
                    return response($exception->errors(),422);
                }
                catch (Exception $exception){
                    DB::rollBack();
                    return response($exception->getMessage(),500);
                }
            }else
            {
                return response(["message" => "Credit Amount Should Equal Debit Amount"],400);
            }
		}



        private function toCreateAccountTransaction($requestData,Account $account,TransactionsContainer $container)
        {
            $data = [];
            $data['creator_id'] =$this->user()->id;
            $data['organization_id'] = $this->user()->organization_id;
            if ($requestData['is_credit']){
                $data['creditable_id'] = $account->id;
                $data['creditable_type'] = Account::class;
                $data['amount'] = $requestData['credit_amount'];
            }else{
                $data['debitable_id'] = $account->id;
                $data['debitable_type'] = Account::class;
                $data['amount'] = $requestData['debit_amount'];
            }
            $container->transactions()->create($data);

		}
        private function toCreateStockTransaction($requestData,Account $account,TransactionsContainer $container)
		{
		
		}
        private function toCreateClientTransaction($requestData,Account $account,TransactionsContainer $container)
		{
			$client = User::find($requestData['client_id']);
			$data = [];
			$data['creator_id'] = auth()->user()->id;
			$data['organization_id'] = auth()->user()->organization_id;
			if ($requestData['is_credit']){
				$data['creditable_id'] = $account->id;
				$data['creditable_type'] = Account::class;
				$data['amount'] = $requestData['credit_amount'];
                $operator = 'sub';
			}else{
				
				$data['debitable_id'] = $account->id;
				$data['debitable_type'] = Account::class;
				$data['amount'] = $requestData['debit_amount'];
                $operator = 'add';
			}
			$data['user_id'] = $requestData['client_id'];
			$data['description'] = "client_balance";
			$container->transactions()->create($data);
			event(new ShouldUpdateUserBalanceEvent($client,$data['amount'],'client_balance',$operator));
		}
        private function toCreateVendorTransaction($requestData,Account $account,TransactionsContainer $container)
		{
			
			$vendor = User::find($requestData['vendor_id']);
			$data = [];
			$data['creator_id'] = auth()->user()->id;
			$data['organization_id'] = auth()->user()->organization_id;
			if ($requestData['is_credit']){
				$data['creditable_id'] = $account->id;
				$data['creditable_type'] = Account::class;
				$data['amount'] = $requestData['credit_amount'];
                $operator = 'add';
			}else{
				
				$data['debitable_id'] = $account->id;
				$data['debitable_type'] = Account::class;
				$data['amount'] = $requestData['debit_amount'];
                $operator = 'sub';
			}
			
			$data['user_id'] = $requestData['vendor_id'];
			$data['description'] = "vendor_balance";
			$container->transactions()->create($data);
            event(new ShouldUpdateUserBalanceEvent($vendor,$data['amount'],'vendor_balance',$operator));
		}
		
	}
