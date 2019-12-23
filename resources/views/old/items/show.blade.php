@extends('layouts.master2')
@section('title',__('pages/items.view_item') . ' |  '. $item->locale_name)
@section('desctipion',$item->barcode)
@section('route',route('management.items.index'))

@section('translator')
    <script defer>
        window.translator = `@json(trans('pages/items'))`
    </script>
@stop



@section("content")

    <div class="">
        <div class="row">
            <div class="col-md-12">
                <article class="message  is-info">
                        <view-item-component

                                :item="{{$item}}"
                                :item-filters='@json($item->filters)'
                                :item-category='@json($item->category)'
                        >


                        </view-item-component>

                </article>
            </div>
        </div>
    </div>

{{--    --}}
{{--    <section class="content" style="">--}}



{{--        <div class="box">--}}
{{--            <div class="box-header">--}}
{{--                <h4>view item <i data-toggle="modal" data-target="#modal-info" class="fa fa-info-circle"></i></h4>--}}
{{--            </div>--}}


{{--            <ul class="nav nav-tabs">--}}
{{--                <!-- <li><a data-toggle="tab" href="#moreinfo">more info</a></li> -->--}}
{{--                <!-- <li><a data-toggle="tab" href="#menu2">e-store</a></li> -->--}}
{{--            </ul>--}}

{{--            <div class="tab-content">--}}
{{--                <div id="home" class="tab-pane fade in active">--}}

{{--                    <div class="panel panel-default">--}}
{{--                        <div class="panel-body">--}}


{{--                            <form class="form-horizontal" method="post" action="{{ route("management.items.create") }}"--}}
{{--                                  enctype="multipart/form-data">--}}

{{--                                @csrf--}}

{{--                                <div class="form-group ">--}}

{{--                                    <label class="col-sm-2 control-label"> creator name</label>--}}
{{--                                    <div class='@if($errors->has("name_ar")) has-error  @endif'>--}}
{{--                                        <div class="col-sm-5">--}}
{{--                                            <input class="form-control is-invalid" id="focusedInput" type="text"--}}
{{--                                                   value="{{ $item->creator->name }}" placeholder="arabic"--}}
{{--                                                   name="name_ar" disabled>--}}
{{--                                        </div>--}}
{{--                                    </div>--}}
{{--                                    <label class="col-sm-2 control-label"> created at</label>--}}
{{--                                    <div class="col-sm-3">--}}
{{--                                        <div class='@if($errors->has("name_en")) has-error  @endif'>--}}
{{--                                            <input class="form-control" id="focusedInput" type="text"--}}
{{--                                                   value="{{ $item->created_date }}" placeholder="english"--}}
{{--                                                   name="name_en" disabled="">--}}
{{--                                        </div>--}}
{{--                                    </div>--}}
{{--                                </div>--}}


{{--                                <div class="form-group ">--}}

{{--                                    <label class="col-sm-2 control-label"> item name</label>--}}
{{--                                    <div class='@if($errors->has("name_ar")) has-error  @endif'>--}}
{{--                                        <div class="col-sm-5">--}}
{{--                                            <input class="form-control is-invalid" id="focusedInput" type="text"--}}
{{--                                                   value="{{ $item->ar_name }}" placeholder="arabic"--}}
{{--                                                   name="name_ar" disabled>--}}
{{--                                        </div>--}}
{{--                                    </div>--}}
{{--                                    <div class="col-sm-5">--}}
{{--                                        <div class='@if($errors->has("name_en")) has-error  @endif'>--}}
{{--                                            <input class="form-control" id="focusedInput" type="text"--}}
{{--                                                   value="{{ $item->name }}" placeholder="english"--}}
{{--                                                   name="name_en" disabled="">--}}
{{--                                        </div>--}}
{{--                                    </div>--}}
{{--                                </div>--}}

{{--                                <!----}}
{{--                                  <div class="form-group">--}}
{{--                                    <label class="col-sm-2 control-label">english item name</label>--}}
{{--                                    <div class="col-sm-10">--}}
{{--                                      <input class="form-control" id="focusedInput" type="text"  placeholder="english">--}}
{{--                                    </div>--}}
{{--                                  </div>--}}
{{--                                  -->--}}


{{--                                <div class="form-group">--}}
{{--                                    <div class="col-sm-6 col-sm-offset-2">--}}
{{--                                        <div class='@if($errors->has("barcode")) has-error  @endif'>--}}
{{--                                            <input class="form-control noEnterSubmit" id="focusedInput" type="text"--}}
{{--                                                   value="{{ $item->barcode }}" placeholder="barcode"--}}
{{--                                                   name="barcode" disabled>--}}
{{--                                        </div>--}}
{{--                                    </div>--}}

{{--                                    --}}{{--<div class="col-sm-4">--}}
{{--                                    --}}{{--<div class='@if($errors->has("model")) has-error  @endif'>--}}
{{--                                    --}}{{--<input class="form-control" id="focusedInput" type="text"--}}
{{--                                    --}}{{--placeholder="model" value="{{ $item['item']['model'] }}"--}}
{{--                                    --}}{{--name="model" disabled="">--}}
{{--                                    --}}{{--</div>--}}

{{--                                    --}}{{--</div>--}}

{{--                                    <div class="col-sm-4">--}}
{{--                                        <div class='@if($errors->has("model")) has-error  @endif'>--}}
{{--                                            <input class="form-control" id="focusedInput" type="text"--}}
{{--                                                   placeholder="sales price" value="{{ $item->price }}"--}}
{{--                                                   name="price" disabled="">--}}
{{--                                        </div>--}}

{{--                                    </div>--}}


{{--                                </div>--}}

{{--                                <!----}}
{{--                                  <div class="form-group">--}}
{{--                                    <div class="col-sm-10">--}}
{{--                                      <input class="form-control" id="focusedInput" type="text"  placeholder="model" required>--}}
{{--                                    </div>--}}
{{--                                  </div> -->--}}


{{--                                <div class="form-group">--}}
{{--                                    <label class="col-sm-2 control-label"> Vat on sale </label>--}}
{{--                                    <div class="col-sm-2">--}}
{{--                                        --}}{{-- <div class="material-switch" id="vatOnSaleBtn">--}}
{{--                                            <input id="someSwitchOptionPrimary3" type="checkbox" data-isChecked="true"--}}
{{--                                                   checked name="has_vat_sale">--}}
{{--                                            <label for="someSwitchOptionPrimary3" class="label-primary"></label>--}}
{{--                                        </div>--}}
{{--                                        <br/> --}}
{{--                                        <div class='@if($errors->has("vat_sale")) has-error  @endif'>--}}
{{--                                            <input class="form-control" id="vatOnSaleInput" type="text"--}}
{{--                                                   value="{{ $item->vat_sale }}" placeholder="5%"--}}
{{--                                                   name="vat_sale" disabled="">--}}
{{--                                        </div>--}}
{{--                                    </div>--}}


{{--                                    <label class="col-sm-2 control-label">vat on purchase</label>--}}
{{--                                    <div class="col-sm-2">--}}
{{--                                        --}}{{-- <div class="material-switch" id="vatOnPurchaseBtn">--}}
{{--                                            <input id="someSwitchOptionPrimary2" type="checkbox" data-isChecked="true"--}}
{{--                                                   checked name="has_vat_purchase">--}}
{{--                                            <label for="someSwitchOptionPrimary2" class="label-primary"></label>--}}
{{--                                        </div>--}}
{{--                                        <br/> --}}
{{--                                        <div class='@if($errors->has("vat_purchase")) has-error  @endif'>--}}
{{--                                            <input class="form-control" id="vatOnPurchaseInput" type="text"--}}
{{--                                                   value="{{ $item->vat_purchase }}" placeholder="5%"--}}
{{--                                                   name="vat_purchase" disabled="">--}}
{{--                                        </div>--}}


{{--                                    </div>--}}
{{--                                    <label class="col-sm-2 control-label">Serial number </label>--}}

{{--                                    <div class="col-sm-2">--}}


{{--                                        <div class="material-switch" id="">--}}
{{--                                            <input id="someSwitchOptionPrimary1" type="checkbox" data-isChecked="false"--}}
{{--                                                   name="is_required_serail">--}}
{{--                                            <label for="someSwitchOptionPrimary1" class="label-primary"></label>--}}
{{--                                        </div>--}}

{{--                                    </div>--}}

{{--                                </div>--}}


{{--                                <div class="form-group">--}}
{{--                                    <label class="col-sm-2 control-label">Category</label>--}}
{{--                                    <div class="col-sm-10">--}}
{{--                                        <input class="form-control" id="vatOnPurchaseInput" type="text"--}}
{{--                                               value="{{ $item->category->name }}" placeholder="5%"--}}
{{--                                               name="vat_purchase" disabled="">--}}

{{--                                    </div>--}}

{{--                                </div>--}}


{{--                                @if(!empty($item->filters))--}}
{{--                                <!-- <div class="form-group"> -->--}}
{{--                                    <div class="row">--}}
{{--                                        <h5 class="col-md-offset-2 ">&nbsp;&nbsp;filters</h5>--}}
{{--                                    </div>--}}

{{--                                    <!-- </div> -->--}}
{{--                                    @foreach($item->filters as $filter)--}}

{{--                                        <div class="form-group">--}}
{{--                                            <label class="col-sm-2 control-label">{{ $filter->name}}</label>--}}
{{--                                            <div class="col-sm-10">--}}
{{--                                                <input class="form-control" id="vatOnPurchaseInput" type="text"--}}
{{--                                                       value="{{  $filter->value->name }}" disabled="">--}}

{{--                                            </div>--}}

{{--                                        </div>--}}

{{--                                @endforeach--}}

{{--                            @endif--}}

{{--                            --}}{{-- <div class="form-group">--}}
{{--                                <label class="col-sm-2 control-label">e-store</label>--}}
{{--                                <div class="col-sm-7">--}}
{{--                                    <div class="material-switch" id="eastoreshowHide">--}}
{{--                                        <input id="someSwitchOptionPrimary" name="hasEStore" type="checkbox"--}}
{{--                                               data-isChecked="false">--}}
{{--                                        <label for="someSwitchOptionPrimary" class="label-primary"></label>--}}
{{--                                    </div>--}}
{{--                                </div>--}}

{{--                            </div> --}}


{{--                            <!-- estore area -->--}}

{{--                                <div id="eastorefiledsArea" style="display:none">--}}
{{--                                    <div class="form-group">--}}
{{--                                        <label class="col-sm-2 control-label">arabic description</label>--}}
{{--                                        <div class="col-sm-10">--}}
{{--                                    <textarea class="form-control" id="focusedInput" type="text"--}}
{{--                                              placeholder="arabic" name="description_ar"--}}
{{--                                              style="    height: 34px;"></textarea>--}}
{{--                                        </div>--}}
{{--                                    </div>--}}

{{--                                    <div class="form-group">--}}
{{--                                        <label class="col-sm-2 control-label">english description</label>--}}
{{--                                        <div class="col-sm-10">--}}
{{--                                    <textarea class="form-control" id="focusedInput" type="text"--}}
{{--                                              placeholder="english" name="description_en"--}}
{{--                                              style="    height: 34px;"></textarea>--}}
{{--                                        </div>--}}
{{--                                    </div>--}}


{{--                                    <div class="form-group">--}}
{{--                                        <label class="col-sm-2 control-label">Tags</label>--}}
{{--                                        <div class="col-sm-10">--}}
{{--                                            <input class="form-control" id="focusedInput" type="Tags"--}}
{{--                                                   placeholder="arabic" name="tags">--}}
{{--                                        </div>--}}
{{--                                    </div>--}}


{{--                                    <div class="form-group">--}}
{{--                                        <label class="col-sm-2 control-label">Weight</label>--}}
{{--                                        <div class="col-sm-10">--}}
{{--                                            <input class="form-control" id="focusedInput" type="text"--}}
{{--                                                   placeholder="Weight" name="weight">--}}
{{--                                        </div>--}}
{{--                                    </div>--}}


{{--                                    <div class="form-group">--}}
{{--                                        <label--}}
{{--                                            class="col-sm-2 col-xs-3 control-label dimensionsStyle">Dimensions</label>--}}
{{--                                        <div class="col-sm-10 col-xs-9">--}}

{{--                                            <a class="showAreaBtn" data-target="Dimensionsfields"> <i--}}
{{--                                                    class="fa fa-plus-circle" style="    margin-top: 8px;--}}
{{--font-size: 20px;--}}
{{--color: #2f2f2f;"></i></a>--}}
{{--                                            <i class="fa fa-minus-circle hideAreaBtn" data-target="Dimensionsfields"--}}
{{--                                               style="display:none;margin-top: 8px;--}}
{{--font-size: 20px;--}}
{{--color: #2f2f2f;"></i>--}}
{{--                                            <!-- <input class="form-control" id="focusedInput" type="text"  placeholder="length"> -->--}}


{{--                                        </div>--}}
{{--                                    </div>--}}

{{--                                    <div id="Dimensionsfields" style="display:none">--}}
{{--                                        <div class="form-group">--}}
{{--                                            <label class="col-sm-2 control-label"></label>--}}
{{--                                            <div class="col-sm-10">--}}
{{--                                                <input class="form-control" id="focusedInput" type="text"--}}
{{--                                                       placeholder="length" name="length">--}}

{{--                                            </div>--}}
{{--                                        </div>--}}


{{--                                        <div class="form-group">--}}
{{--                                            <label class="col-sm-2 control-label"></label>--}}
{{--                                            <div class="col-sm-10">--}}
{{--                                                <input class="form-control" id="focusedInput" type="text"--}}
{{--                                                       placeholder="width" name="width">--}}

{{--                                            </div>--}}
{{--                                        </div>--}}


{{--                                        <div class="form-group">--}}
{{--                                            <label class="col-sm-2 control-label"></label>--}}
{{--                                            <div class="col-sm-10">--}}
{{--                                                <input class="form-control" id="focusedInput" type="text"--}}
{{--                                                       placeholder="height" name="height">--}}


{{--                                            </div>--}}
{{--                                        </div>--}}
{{--                                    </div>--}}


{{--                                    <div class="form-group">--}}
{{--                                        <label class="col-sm-2 control-label">shipping cost share</label>--}}
{{--                                        <div class="col-sm-10">--}}
{{--                                            <input class="form-control" id="focusedInput" type="text"--}}
{{--                                                   placeholder="shipping cost share" name="shipping_cost_share">--}}


{{--                                        </div>--}}
{{--                                    </div>--}}

{{--                                    <div class="form-group">--}}
{{--                                        <label class="col-sm-2 control-label">e-store price</label>--}}
{{--                                        <div class="col-sm-10">--}}
{{--                                            <input class="form-control" id="focusedInput" type="Tags"--}}
{{--                                                   placeholder="e-store price" name="e_price">--}}
{{--                                        </div>--}}
{{--                                    </div>--}}

{{--                                    <div class="form-group">--}}
{{--                                        <label class="col-sm-2 control-label"> </label>--}}
{{--                                        <div class="col-sm-10">--}}
{{--                                            <input class="form-control" id="focusedInput" type="file"--}}
{{--                                                   name="attachments[]">--}}


{{--                                        </div>--}}
{{--                                    </div>--}}


{{--                                </div>--}}
{{--                                <hr>--}}
{{--                                <div class="form-group text-center">--}}
{{--                                    @include("System.Products.subtable", ["item"=>$item])--}}
{{--                                    --}}{{--<label class="col-sm-2 control-label"> </label>--}}
{{--                                    --}}{{--<div class="col-sm-10">--}}
{{--                                    --}}{{--<div class="row">--}}
{{--                                    --}}{{--<div class="col-md-2 col-md-offset-3 col-xs-12" style="text-align:right">--}}
{{--                                    --}}{{--<button class="btn btn-primary btn-block  btn-sm"  type="submit" style="margin-right:10px;margin-bottom:14px">Save  <i class="fa fa-save"></i></button>--}}
{{--                                    --}}{{--</div>--}}

{{--                                    --}}{{--<div class="col-md-2 col-xs-12">--}}
{{--                                    --}}{{--<button class="btn btn-default  btn-block   btn-sm">cancel  <i class="fa fa-remove"></i></button>--}}

{{--                                    --}}{{--</div>--}}
{{--                                    --}}{{--</div>--}}



{{--                                    --}}{{--</div>--}}
{{--                                </div>--}}


{{--                            </form>--}}


{{--                        </div>--}}


{{--                    </div>--}}
{{--                </div>--}}


{{--                <div id="moreinfo" class="tab-pane fade">--}}

{{--                    <div class="panel panel-default">--}}
{{--                        <div class="panel-body">--}}
{{--                            <form class="form-horizontal">--}}

{{--                                <div class="form-group">--}}
{{--                                    <label class="col-sm-2 control-label">additional item number</label>--}}
{{--                                    <div class="col-sm-10">--}}
{{--                                        <input class="form-control" id="focusedInput" type="text" placeholder="arabic">--}}
{{--                                    </div>--}}
{{--                                </div>--}}


{{--                                <div class="form-group">--}}
{{--                                    <label class="col-sm-2 control-label">english item name</label>--}}
{{--                                    <div class="col-sm-10">--}}
{{--                                        <input class="form-control" id="focusedInput" type="text" placeholder="english">--}}
{{--                                    </div>--}}
{{--                                </div>--}}


{{--                                <div class="form-group">--}}
{{--                                    <label class="col-sm-2 control-label">UPC/EAN/ISBN</label>--}}
{{--                                    <div class="col-sm-10">--}}
{{--                                        <input class="form-control" id="focusedInput" type="text" placeholder="barcode">--}}
{{--                                    </div>--}}
{{--                                </div>--}}


{{--                                <div class="form-group">--}}
{{--                                    <label class="col-sm-2 control-label">model number</label>--}}
{{--                                    <div class="col-sm-10">--}}
{{--                                        <input class="form-control" id="focusedInput" type="text" placeholder="model"--}}
{{--                                               required>--}}
{{--                                    </div>--}}
{{--                                </div>--}}


{{--                                <div class="form-group">--}}
{{--                                    <label class="col-sm-2 control-label">Category</label>--}}
{{--                                    <div class="col-sm-7">--}}
{{--                                        <select class="form-control">--}}
{{--                                            <option>none</option>--}}
{{--                                            <option>HP</option>--}}
{{--                                            <option>Apple</option>--}}
{{--                                            <option>none</option>--}}
{{--                                            <option>none</option>--}}
{{--                                            <!-- <input class="form-control" id="focusedInput" type="text"  placeholder="brand" value="none"> -->--}}
{{--                                        </select>--}}
{{--                                    </div>--}}
{{--                                    <div class="col-sm-3">--}}
{{--                                        <a href="" class="setOnSameHieghtWithInout">add new category</a>--}}
{{--                                    </div>--}}
{{--                                </div>--}}


{{--                                <div class="form-group">--}}
{{--                                    <label class="col-sm-2 control-label">brand</label>--}}
{{--                                    <div class="col-sm-7">--}}
{{--                                        <select class="form-control">--}}
{{--                                            <option>none</option>--}}
{{--                                            <option>HP</option>--}}
{{--                                            <option>Apple</option>--}}
{{--                                            <option>none</option>--}}
{{--                                            <option>none</option>--}}
{{--                                            <!-- <input class="form-control" id="focusedInput" type="text"  placeholder="brand" value="none"> -->--}}
{{--                                        </select>--}}
{{--                                    </div>--}}
{{--                                    <div class="col-sm-3">--}}
{{--                                        <a href="" class="setOnSameHieghtWithInout">add new brand name</a>--}}
{{--                                    </div>--}}
{{--                                </div>--}}


{{--                                <div class="form-group">--}}
{{--                                    <label class="col-sm-2 control-label">Unit</label>--}}
{{--                                    <div class="col-sm-7">--}}
{{--                                        <select class="form-control">--}}
{{--                                            <option>PC</option>--}}
{{--                                            <option>Cartoon</option>--}}
{{--                                            <option>Apple</option>--}}
{{--                                            <option>none</option>--}}
{{--                                            <option>none</option>--}}
{{--                                            <!-- <input class="form-control" id="focusedInput" type="text"  placeholder="brand" value="none"> -->--}}
{{--                                        </select>--}}
{{--                                    </div>--}}
{{--                                    <div class="col-sm-3">--}}
{{--                                        <a href="" class="setOnSameHieghtWithInout">add new unit</a>--}}
{{--                                    </div>--}}
{{--                                </div>--}}


{{--                                <div class="form-group">--}}
{{--                                    <label class="col-sm-2 control-label">type</label>--}}
{{--                                    <div class="col-sm-7">--}}
{{--                                        <select class="form-control">--}}
{{--                                            <option>Service</option>--}}
{{--                                            <option>Cartoon</option>--}}
{{--                                            <option>Apple</option>--}}
{{--                                            <option>none</option>--}}
{{--                                            <option>none</option>--}}
{{--                                            <!-- <input class="form-control" id="focusedInput" type="text"  placeholder="brand" value="none"> -->--}}
{{--                                        </select>--}}
{{--                                    </div>--}}
{{--                                    <div class="col-sm-3">--}}
{{--                                        <a href="" class="setOnSameHieghtWithInout">add new type</a>--}}
{{--                                    </div>--}}

{{--                                </div>--}}


{{--                                <div class="form-group">--}}
{{--                                    <label class="col-sm-2 control-label">e-store</label>--}}
{{--                                    <div class="col-sm-7">--}}
{{--                                        <button class="btn btn-primary btn-sm">On</button>--}}
{{--                                    </div>--}}

{{--                                </div>--}}


{{--                            </form>--}}


{{--                        </div>--}}


{{--                    </div>--}}
{{--                </div>--}}


{{--                <!----}}
{{--                  <div id="menu2" class="tab-pane fade">--}}
{{--                    <h3>Menu 2</h3>--}}
{{--                    <p>Some content in menu 2.</p>--}}
{{--                  </div> -->--}}
{{--            </div>--}}
{{--        </div>--}}
{{--    </section>--}}

@endsection



