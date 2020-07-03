<section class="women-banner spad">
<div class="product-slider owl-carousel owl-loaded owl-drag">
    <div class="owl-stage-outer">
        <div class="owl-stage"
             style="transform: translate3d(-1157px, 0px, 0px); transition: all 1.2s ease 0s; width: 2895px;">
            @foreach($items as $key => $item)

                <div class="owl-item cloned" style="width: 264.443px; margin-right: 25px;">
                    <div class="product-item">
                        <div class="pi-pic">
                            <img src="{{ $item->web_master_image_url}}" alt="">
                            <div class="icon">
                                <i class="icon_heart_alt"></i>
                            </div>
                            <ul>
                                <li class="w-icon active"><a href="#"><i class="icon_bag_alt"></i></a></li>
                                <li class="quick-view"><a href="#">+ Quick View</a></li>
                                <li class="w-icon"><a href="#"><i class="fa fa-random"></i></a></li>
                            </ul>
                        </div>
                        <div class="pi-text">
                            <div class="catagory-name">{{ $item->category->web_name }}</div>
                            <a href="#">
                                <h5>{{ $item->web_name }}</h5>
                            </a>
                            <div class="product-price">
                                {{ $item->web_online_price }}
                            </div>
                        </div>
                    </div>
                </div>

            @endforeach
        </div>
    </div>

</div>
</section>