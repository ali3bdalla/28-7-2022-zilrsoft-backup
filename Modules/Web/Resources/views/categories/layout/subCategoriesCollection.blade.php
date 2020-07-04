<div class="banner-section spad">
    <div class="container-fluid">
        <div class="row">
            @foreach($categories as $category)

                    <a href="{{ route('web.categories.show',$category->id) }}" class="col-lg-4 col-6 col-xs-6 ">
                        <div class="card sub-category-cell">
                            <div class="grid-image">
                                <img src="{{$category->web_cover_url }}" alt=""  class="grid-image card-img">

                            </div>
                            <div class="card-body">
                               <span> {{ $category->locale_name }}</span>
                            </div>
                        </div>
                    </a>
            @endforeach
        </div>
    </div>
</div>