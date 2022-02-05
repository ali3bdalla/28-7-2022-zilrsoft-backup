<div class="list-group">
    <div class="list-group-item text-center">
        <div class="row">
            <div class="col-md-6"><label>المجموع</label></div>
            <div class="col-md-6">{{ $this->total }}</div>
        </div>
    </div>
    <div class="list-group-item text-center">
        <div class="row">
            <div class="col-md-6"><label>الصافي</label></div>
            <div class="col-md-6">{{$this->subtotal}}</div>
        </div>
    </div>
    <div class="list-group-item text-center">
        <div class="row">
            <div class="col-md-6"><label> الضريبة</label></div>
            <div class="col-md-6">{{$this->tax}}</div>
        </div>
    </div>
    <div class="list-group-item text-center">
        <div class="row">
            <div class="col-md-6"><label>النهائي</label></div>
            <div class="col-md-6">{{$this->net}}</div>
        </div>
    </div>
</div>
