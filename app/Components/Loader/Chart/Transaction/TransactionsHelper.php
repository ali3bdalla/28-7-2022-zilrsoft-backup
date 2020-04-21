<?php
	
	
	namespace App\Components\Loader\Chart\Transaction;
	
	
	use App\Transaction;
	use Carbon\Carbon;
	use Illuminate\Database\Eloquent\Builder;
	
	trait TransactionsHelper
	{
		private function loadGlobalTransactions()
		{
			
			
			if (!$this->report_response){
				$query = Transaction::where([
					['creditable_type',get_class($this->account)],
					['creditable_id',$this->account->id]
				]);
				$query = $this->dispatchParams($query);
				
				$query = $query->OrWhere([
					['debitable_type',get_class($this->account)],
					['debitable_id',$this->account->id]
				]);
				$query = $this->dispatchParams($query);
				$query = $query->with('user','invoice');
				$this->db_rows = $query->orderBy('id','asc')->paginate($this->getPerPage());
			}else{
				$query = Transaction::where([
					['creditable_type',get_class($this->account)],
					['creditable_id',$this->account->id]
				]);
				$query = $this->dispatchParams($query);
				
				$this->total_credit_amount = $query->sum('amount');
				
				$query2 = Transaction::where([
					['debitable_type',get_class($this->account)],
					['debitable_id',$this->account->id]
				]);
				$query2 = $this->dispatchParams($query2);
				$this->total_debit_amount = $query2->sum('amount');
			}
			
			
		}
		
		private function dispatchParams(Builder $query):Builder
		{
			if ($this->request->has('id')){
				$query = $query->where('id',$this->request->input('id'));
			}
			if ($this->request->has('startDate')
				&& $this->request->filled('startDate')
				&& $this->request->has('endDate')
				&& $this->request->filled('endDate')){
				
				$_startDate = Carbon::parse($this->request->input("startDate"))->toDateString();
				$_endDate = Carbon::parse($this->request->input("endDate"))->toDateString();

//				if ($_endDate === $_startDate){
//					$query = $query->whereDate('created_at',$_startDate);
//
//				}else{
				$query = $query->whereBetween('created_at',[$_startDate,$_endDate]);

//				}
//
			}
			
			return $query;
		}
		
	}