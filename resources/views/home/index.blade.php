@extends('layouts.master')

@section('content')


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
