<?php

namespace App\Http\Controllers\App\Web;

use App\Http\Controllers\Controller;
use App\Http\Requests\Accounting\Filter\CreateFilterValueRequest;
use App\Http\Requests\Accounting\Filter\UpdateFilterValueRequest;
use App\Http\Requests\Accounting\Filter\ValuesDataTableRequest;
use App\Models\Filter;
use App\Models\FilterValues;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Http\Response;

class FilterValuesController extends Controller
{

    /**
     * ItemController constructor.
     */
    public function __construct()
    {
        $this->middleware(['permission:create filter|edit filter|view filter|delete filter']);
    }

    /**
     * @param ValuesDataTableRequest $request
     *
     * @param Filter $filter
     *
     * @return LengthAwarePaginator
     */
    public function datatable(ValuesDataTableRequest $request, Filter $filter): LengthAwarePaginator
    {
        return $request->data($filter);
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param CreateFilterValueRequest $request
     *
     * @return Response
     */
    public function store(CreateFilterValueRequest $request): Response
    {
        $this->middleware(['permission:edit filter|create filter']);

        return $request->save();
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param UpdateFilterValueRequest $request
     * @param FilterValues $filterValue
     * @return Response
     */
    public function update(UpdateFilterValueRequest $request, FilterValues $filterValue): Response
    {
        $this->middleware(['permission:edit filter|create filter']);

        $data = $request->only('filter_id', 'name', 'ar_name');
        $filterValue->update($data);
        return $filterValue->fresh();

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param FilterValues $filterValue
     * @return void
     */
    public function destroy(FilterValues $filterValue): void
    {
        $this->middleware(['edit filter']);
        $filterValue->delete();
    }
}
