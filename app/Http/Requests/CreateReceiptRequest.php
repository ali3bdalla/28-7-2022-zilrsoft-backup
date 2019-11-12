<?php
	
	namespace App\Http\Requests;
	
	use AliAbdalla\Tafqeet\Core\Tafqeet;
	use App\Invoice;
	use App\User;
	use Illuminate\Foundation\Http\FormRequest;
	use Illuminate\Support\Facades\DB;
	use Illuminate\Validation\Rule;
	
	
	class CreateReceiptRequest extends FormRequest
	{
		
		private $invoice_ids = [];
		
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
				'user_id' => 'required|integer|exists:users,id',
				'gateway_id' => 'required|integer|exists:gateways,id',
				'amount' => 'required|numeric|min:1',
				'voucher_type' => ['required',Rule::in(['balance','invoice'])],
				'invoices' => 'requiredIf:voucher_type,invoice|array',
				'invoices.*.invoice.id' => 'required|integer|exists:invoices,id'
			];
		}
		
		public function save()
		{
			$payment = null;
			DB::beginTransaction();
			try{
				$user = $this->user();
				$organization = $user->organization;
				$data = $this->detectTargetTypeAndGetData();
				$payment = $organization->payments()->create($data);
				if ($this->voucher_type == 'invoice'){
					Invoice::updateInvoiceStatusAsPaid($this->invoice_ids,$payment);
				}else{
					$user = User::find($this->user_id);
					$user->updateUserBalance('add',$this->amount);
				}
				DB::commit();
			}catch (\Exception $e){
				DB::rollBack();
				throw new \Exception($e->getMessage());
			}
			
			
			return $payment;
		}
		
		private function detectTargetTypeAndGetData()
		{
			
			
			$data = $this->only('user_id','gateway_id');
			$data['creator_id'] = $this->user()->id;
			
			
			$description = '';
			$index = 0;
			if ($this->voucher_type == 'balance'){
				$data['amount'] = $this->get('amount');
				$data['is_belongs_to_invoice'] = false;
			}else{
				$total = 0;
				if (!empty($this->get('invoices'))){
					foreach ($this->get('invoices') as $invoice){
						$total = $total + $invoice['invoice']['remaining'];
						if($index==0)
						{
							$description.=  $invoice['id'];
						}else
						{
							$description.= ' , ' . $invoice['id'];
						}
						
						$index++;
						
						$this->invoice_ids[] = $invoice['invoice']['id'];
					}
					
				}
				
				$data['amount'] = $total;
				$data['description'] = $description;
				$data['is_belongs_to_invoice'] = true;
			}
			
			$data['amount_ar_words'] = Tafqeet::arablic($data['amount']);
			$data['amount_en_words'] = Tafqeet::arablic($data['amount']);
			
			$data['is_created_from_invoice'] = false;
			
			if (in_array($this->gateway_id,[1,3])){
			}else if ($this->gateway_id == 2){
				$data['user_account_id'] = $this->user_account_id;
				$data['organization_account_id'] = $this->organization_account_id;
			}elseif ($this->gateway_id == 6){
				$data['account'] = $this->account;
				$data['account_name'] = $this->account_name;
			}elseif ($this->gateway_id == 5){
				$data['bank_id'] = $this->bank_id;
				$data['account'] = $this->account;
				$data['account_name'] = $this->account_name;
			}elseif ($this->gateway_id == 4){
				$data['account'] = $this->account;
			}
			
			$data['payment_type'] = 'receipt';
			
			return $data;
			
		}
	}
