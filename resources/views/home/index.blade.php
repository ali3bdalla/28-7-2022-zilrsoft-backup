@extends('layouts.master')

@section('content')
    @if(auth()->user()->organization->has_quickbooks)
        <div class="row">
            <div class="col-lg-3 col-6">
                <div class="small-box bg-info">
                    <div class="inner">
                        <h3>{{ $quickBooksInvoices->count() }} فاتورة </h3>
                        <p>فواتير كويكبوكس {{$todayDate}}</p>
                    </div>
                </div>
            </div>

            <div class="col-lg-3 col-6">

                <div class="small-box bg-success">
                    <div class="inner">
                        <h3>{{ $localInvoices->count() }} فاتورة </h3>
                        <p>فواتير اللوكال {{$todayDate}}</p>
                    </div>
                </div>
            </div>

            <div class="col-lg-3 col-6">

                <div class="small-box bg-warning">
                    <div class="inner">
                        <h3>{{ moneyFormatter($quickBooksInvoices->sum("TotalAmt")) }}  </h3>
                        <p>نهائي كويكبوكس {{$todayDate}}</p>
                    </div>
                </div>
            </div>

            <div class="col-lg-3 col-6">

                <div class="small-box bg-danger">
                    <div class="inner">
                        <h3>{{ moneyFormatter($localInvoices->sum()) }}  </h3>
                        <p>نهائي اللوكال {{$todayDate}}</p>
                    </div>

                </div>
            </div>

        </div>
    @endif

    <div class="panel">
        <div class="panel panel-primary">
            <div class="panel-heading">
                <h3 class="card-title">الاعدادات</h3>
            </div>
            <div class="panel-body">
                <form action="{{ route("dashboard.change_settings") }}" method="post">
                    @csrf
                    <div class="form-group">
                        <label for="inputEstimatedBudget">السنة المالية</label>
                        <select name="active_year" class="form-control">
                            <option value="">
                                جميع السنوات المالية
                            </option>
                            @foreach($workingYears  as $workingYear)

                                <option value="{{$workingYear}}" @if($workingYear ===  $activeYear) selected @endif>
                                    {{$workingYear}}
                                </option>
                            @endforeach
                        </select>
                        @error('active_year')
                            {{$message}}
                        @enderror
                    </div>
                    <div class="form-group">
                        <button type="submit" class="btn btn-primary">تعديل الاعدادات</button>
                    </div>
                </form>
            </div>
            <!-- /.card-body -->
        </div>
    </div>
@endsection
