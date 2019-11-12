<?php

namespace App\Http\Controllers;

use App\Expense;
use App\Filter;
use App\FilterValues;
use App\Http\Requests\CreateFilterRequest;
use Illuminate\Http\Request;

class ExpenseController extends Controller
{
	
	/**
	 * Display a listing of the resource.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function index()
	{
		
		$expenses = Expense::all();
		return view('expenses.index',compact('expenses'));
		//
	}
	
	/**
	 * Show the form for creating a new resource.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function create()
	{
		//
		return view('expenses.create');
	}
	
	/**
	 * Store a newly created resource in storage.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @return \Illuminate\Http\Response
	 */
	public function store(CreateFilterRequest $request)
	{
//		return $request->all();
		$data =  $request->only('name','ar_name');
		$data['creator_id'] = auth()->user()->id;
		$data['appear_in_sale'] = $request->has('appear_in_sale') && $request->filled("appear_in_sale") ? true :
			false;
		
		$data['appear_in_purchase'] = $request->has('appear_in_purchase') && $request->filled("appear_in_purchase")
			? true :
			false;
		
		auth()->user()->organization->expenses()->create($data);
		
		return  redirect(route('management.expenses.index'));
		//
	}
	
	/**
	 * Display the specified resource.
	 *
	 * @param  \App\Filter  $filter
	 * @return \Illuminate\Http\Response
	 */
	public function show(Filter $filter)
	{
		
		return view('expenses.show');
		//
	}
	
	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  \App\Filter  $filter
	 * @return \Illuminate\Http\Response
	 */
	public function edit(Filter $filter)
	{

//        return  $filter->values()->orderBy('id','desc')->get();
		
		// return;
		return view('expenses.edit',compact('filter'));
		//
	}
	
	/**
	 * Update the specified resource in storage.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @param  \App\Filter  $filter
	 * @return \Illuminate\Http\Response
	 */
	public function update(Request $request, Filter $filter)
	{
		
		
		
		$request->validate([
			'name'=>'required|string',
			'ar_name'=>'required|string'
		]);
		
		
		$filter->forceFill($request->only('name','ar_name'))->save();
		
		return $filter;
		//
	}
	
	public function destroy(Expense $expense)
	{
		$expense->delete();
		
		return back();
	}
	
	
}
