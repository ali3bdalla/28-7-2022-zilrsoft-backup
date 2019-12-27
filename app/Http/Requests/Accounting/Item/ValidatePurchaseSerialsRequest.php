<?php
	
	namespace App\Http\Requests\Accounting\Item;
	
	use App\ItemSerials;
	use Illuminate\Foundation\Http\FormRequest;
	
	class ValidatePurchaseSerialsRequest extends FormRequest
	{
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
				//
//				'serials' => 'required|array',
//				'serials.*' => 'required|string|min:3|unique:item_serials,serial',
			];
		}
		
//		public function validatePurchase()
//		{
//			$this->validate(['serial' => ['required','string','exists:item_serials',
//				function ($attr,$value,$fail){
//					$serial = ItemSerials::where('serial',$value)->first();
//					if (empty($serial)){
//						$fail('no serial like this');
//					}else{
//						if (in_array($serial->current_status,['saled','r_purchase'])){
//							$fail('this serial is already used');
//						}
//					}
//
//				}
//			]
//			]);
//		}
//
//		public function validateSale()
//		{
//			$this->validate(['serial' => ['required','string','exists:item_serials',
//				function ($attr,$value,$fail){
//					$serial = ItemSerials::where('serial',$value)->first();
//					if (empty($serial)){
//						$fail('no serial like this');
//					}else{
//						if (in_array($serial->current_status,['saled','r_purchase'])){
//							$fail('this serial is already used');
//						}
//					}
//
//				}
//			]
//			]);
//		}
//
//
//		public function good()
//		{
//			$this->validate(['serial' => ['required','string','exists:item_serials',
//				function ($attr,$value,$fail){
//					$serial = ItemSerials::where('serial',$value)->first();
//					if (empty($serial)){
//						$fail('no serial like this');
//					}else{
//						if (in_array($serial->current_status,['saled','r_purchase'])){
//							$fail('this serial is already used');
//						}
//					}
//
//				}
//			]
//			]);
//		}
	
	
	}
