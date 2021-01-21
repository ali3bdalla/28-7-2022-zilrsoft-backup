<div class="box-body">
    <h3 class="mb-3">بيانات المرسل</h3>
    <div class="row">
        <div class="col-md-6">
            <div class="form-group">
                <label> الشركة</label>
                <input type="text" class="form-control"  value="{{ auth()->user()->organization->title_ar }}" readonly/>
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group">
                <label> المستخدم</label>
                <input type="text" class="form-control"  value="{{ auth()->user()->locale_name }}" readonly/>

            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-6">
            <div class="form-group">
                <label>المدينة</label>
                <input type="text" class="form-control"  value="{{ auth()->user()->organization->city_ar }}" readonly/>
            </div>
        </div>

        <div class="col-md-6">
            <div class="form-group">
                <label>رقم الهاتف </label>
                <input type="text" class="form-control"  value="{{ auth()->user()->organization->phone_number }}" readonly />

            </div>
        </div>

    </div>


    <div class="row">
        <div class="col-md-12">
            <div class="form-group">
                <label>الوصف</label>
                <textarea type="text" class="form-control" 
                   readonly>{{ auth()->user()->organization->address_ar }}</textarea>
                
            </div>
        </div>


    </div>




    <hr>

</div>
