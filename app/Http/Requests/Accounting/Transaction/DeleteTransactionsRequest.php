<?php

namespace App\Http\Requests\Accounting\Transaction;

use App\TransactionsContainer;
use Illuminate\Database\QueryException;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\DB;

class DeleteTransactionsRequest extends FormRequest
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
        ];
    }

    public function erase(TransactionsContainer $transactionsContainer)
    {

        DB::beginTransaction();
        try{
            $response = ['message' =>  "{$transactionsContainer->id} has been deleted"];
            $transactions = $transactionsContainer->transactions()->withoutGlobalScope('pendingTransactionScope')->get();
            if($transactions->count() > 0)
                foreach ($transactions as $transaction)
                    $transaction->delete();

            $transactionsContainer->delete();
            DB::commit();
            return response($response,200);
        }catch (QueryException $e)
        {
            DB::rollBack();
            return response(['message' => $e->getMessage()],500);
        } catch (\Exception $e) {
            DB::rollBack();
            return response(['message' => $e->getMessage()],500);
        }


    }
}
