<div class="banner-section spad">
    <div class="container-fluid">
        <div class="row">
            @foreach($categories as $category)

                    <a href="{{ route('web.categories.show',$category->id) }}" class="col-lg-4 col-xs-6">
                        <div class="single-banner">
{{--                            <div class="row">--}}
{{--                                <div class="col-md-6">--}}
                                    <img src="{{$category->web_cover_url }}" alt=""  class="grid-image">
{{--                                </div>--}}
{{--                                <div class="col-md-6">--}}
{{--                                    <img src="{{$category->webImage(2) }}" alt=""  class="grid-image-50">--}}
{{--                                    <img src="{{$category->webImage(3) }}" alt=""  class="grid-image-50">--}}
{{--                                </div>--}}
{{--                            </div>--}}
                            <div class="inner-text">
                                <h4 class="grid-title">{{ $category->locale_name }}</h4>
                            </div>
                        </div>
                    </a>
            @endforeach
        </div>
    </div>
</div>