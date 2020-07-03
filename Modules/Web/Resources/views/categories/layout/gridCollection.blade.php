

<div class="banner-section spad">
    <div class="container-fluid">
        <div class="row">
            @foreach($categories as $category)
                <a href="{{ route('web.categories.show',$category->id) }}" class="col-lg-4">
                    <div class="single-banner">
                        <img src="{{$category->web_cover_url }}" alt=""  class="grid-image">
                        <div class="inner-text">
                            <h4 class="grid-title">{{ $category->locale_name }}</h4>
                        </div>
                    </div>
                </a>
            @endforeach
        </div>
    </div>
</div>