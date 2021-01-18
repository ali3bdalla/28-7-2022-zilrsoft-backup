@extends('accounting.layout.master')

@section('title')




@section('content')



    <div class="box box-primary">
        <div class="box-header">
            <h3 class="box-title">انشاء بوليصة</h3>
        </div>
        <div class="box-body">
            <!-- Date dd/mm/yyyy -->
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label>الاسم الاول</label>
                        <input type="text" class="form-control" name="first_name" value="{{ old('first_name') }}" />
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label>الاسم الثاني</label>
                        <input type="text" class="form-control" name="last_name" value="{{ old('last_name') }}"/>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label>المدينة</label>
                        <select class="form-control">
                            @foreach ($citites as $city)
                                <option>{{ $city->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>




        </div>
        <!-- /.box-body -->
    </div>
    <!-- /.box -->




@endsection



@section('after_content')

@endsection
