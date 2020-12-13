<?php
	
	namespace App\Http\Controllers\Api\Store;
	
	use App\Http\Controllers\Controller;
	use App\Models\User;
	use App\Models\UserGateways;
	use Illuminate\Http\Request;
	use Illuminate\Http\Response;
	
	class PaymentAccountController extends Controller
	{
		/**
		 * Display a listing of the resource.
		 *
		 * @param Request $request
		 * @param User $user
		 * @return void
		 */
		public function index(Request $request, User $user)
		{
			$userId = $user->id;
			
			if($request->user('client')) {
				$userId = $request->user('client')->id;
			}
			
			return UserGateways::where('user_id', $userId)->with('bank')->get();
		}
		
		/**
		 * Show the form for creating a new resource.
		 *
		 * @return Response
		 */
		public function create()
		{
			//
		}
		
		/**
		 * Store a newly created resource in storage.
		 *
		 * @param Request $request
		 * @param User $user
		 * @return void
		 */
		public function store(Request $request, User $user)
		{
			$userId = $user->id;
			
			if($request->user('client')) {
				$userId = $request->user('client')->id;
			}
			
			
			$request->validate(
				[
					'bank_id' => 'required|integer|exists:banks,id',
					'detail' => 'required|string',
				]
			);
			$data = $request->only('bank_id', 'detail');
			$data['organization_id'] = 1;
			$data['creator_id'] = 1;
			$data['user_id'] = $userId;
			
			$account = $user->gateways()->create($data);
			
//			return $account;
			return $account->load('bank');
			
		}
		
		/**
		 * Display the specified resource.
		 *
		 * @param UserGateways $userGateways
		 * @return Response
		 */
		public function show(UserGateways $userGateways)
		{
			//
		}
		
		/**
		 * Show the form for editing the specified resource.
		 *
		 * @param UserGateways $userGateways
		 * @return Response
		 */
		public function edit(UserGateways $userGateways)
		{
			//
		}
		
		/**
		 * Update the specified resource in storage.
		 *
		 * @param Request $request
		 * @param UserGateways $userGateways
		 * @return Response
		 */
		public function update(Request $request, UserGateways $userGateways)
		{
			//
		}
		
		/**
		 * Remove the specified resource from storage.
		 *
		 * @param UserGateways $userGateways
		 * @return Response
		 */
		public function destroy(UserGateways $userGateways)
		{
			//
		}
	}
