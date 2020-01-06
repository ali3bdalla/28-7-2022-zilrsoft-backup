<?php
	
	namespace App\Http\Requests\Accounting\Transaction;
	
	use App\Account;
	use Illuminate\Foundation\Http\FormRequest;
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
				//
				'transactions' => 'required|array',
				"transactions.*.id" => "required|integer|exists:accounts,id",
				"transactions.*.credit_amount" => "required|numeric",
				"transactions.*.debit_amount" => "required|numeric",
				"transactions.*.is_credit" => "required|boolean",
				'description' => "required|string",
				'amount' => "required",
			];
		}
		
		public function save()
		{
			$this->validateAmounts();
			$container = auth()->user()->organization->transactions_containers()->create(
				[
					'creator_id' => auth()->user()->id,
					'description' => $this->description,
					'amount' => $this->amount,
				]
			);
			
			
			foreach ($this->transactions as $transaction){
				
				$data = [];
				$data['creator_id'] = auth()->user()->id;
				$data['organization_id'] = auth()->user()->organization_id;
				if ($transaction['is_credit']){
					$data['creditable_id'] = $transaction['id'];
					$data['creditable_type'] = Account::class;
					$data['amount'] = $transaction['credit_amount'];
				}else{
					
					$data['debitable_id'] = $transaction['id'];
					$data['debitable_type'] = Account::class;
					$data['amount'] = $transaction['debit_amount'];
				}
				
				$container->transactions()->create($data);
			}


//			dd($container);
		}
		
		public function validateAmounts()
		{
			$total_credit = 0;
			$total_debit = 0;
			foreach ($this->transactions as $transaction){
//				dd($transaction);
				if ($transaction['is_credit'])
					$total_credit = $total_credit + $transaction['credit_amount'];
				else
					$total_debit = $total_debit + $transaction['debit_amount'];
			}
			
			if ($total_debit !== $total_credit){
				
				$error = ValidationException::withMessages([
					'debit_and_credit' => ['debit does\'t equal credit amount'],
				]);
				throw $error;
				
			}
		}
		
	}
