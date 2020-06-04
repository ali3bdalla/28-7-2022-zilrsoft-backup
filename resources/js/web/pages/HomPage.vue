<template>
    <div>

        <!-- START SECTION SHOP -->
        <div class="section small_pt pb-0">
            <div class="custom-container">
                <div class="row">
                    <div class="col-xl-3 d-none d-xl-block">
                        <div class="sale-banner">
                            <a class="hover_effect1" href="">
                                <img alt="shop_banner_img6" src="">
                            </a>
                        </div>
                    </div>
                    <div class="col-xl-9">
                        <div class="row">
                            <div class="col-12">
                                <div class="heading_tab_header">
                                    <div class="heading_s2">
                                        <h4>Exclusive Products</h4>
                                    </div>
                                    <div class="tab-style2">
                                        <button aria-expanded="false" class="navbar-toggler" data-target="#tabmenubar"
                                                data-toggle="collapse" type="button">
                                            <span class="ion-android-menu"></span>
                                        </button>
                                        <ul class="nav nav-tabs justify-content-center justify-content-md-end"
                                            id="tabmenubar" role="tablist">
                                            <li class="nav-item">
                                                <a aria-controls="arrival" aria-selected="true" class="nav-link active"
                                                   data-toggle="tab" href="#arrival" id="arrival-tab"
                                                   role="tab">New Arrival</a>
                                            </li>
                                            <li class="nav-item">
                                                <a aria-controls="sellers" aria-selected="false" class="nav-link"
                                                   data-toggle="tab"
                                                   href="#sellers" id="sellers-tab" role="tab">Best
                                                    Sellers</a>
                                            </li>
                                            <li class="nav-item">
                                                <a aria-controls="featured" aria-selected="false" class="nav-link"
                                                   data-toggle="tab"
                                                   href="#featured" id="featured-tab"
                                                   role="tab">Featured</a>
                                            </li>
                                            <li class="nav-item">
                                                <a aria-controls="special" aria-selected="false" class="nav-link"
                                                   data-toggle="tab"
                                                   href="#special" id="special-tab" role="tab">Special
                                                    Offer
                                                </a>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-12">
                                <div class="tab_slider">
                                    <div aria-labelledby="arrival-tab" class="tab-pane fade show active" id="arrival"
                                         role="tabpanel">
                                        <div class="product_slider carousel_slider owl-carousel owl-theme dot_style1"
                                             data-loop="true" data-margin="20"
                                             data-responsive='{"0":{"items": "1"}, "481":{"items": "2"}, "768":{"items": "3"}, "991":{"items": "3"}}'>
                                            @foreach($new_items as $item)
                                            <div class="item">
                                                <div class="product_wrap">
                                                    <div class="product_img">
                                                        <a href="shop-product-detail.html">
                                                            <img alt="el_img7"
                                                                 src="https://bestwebcreator.com/shopwise/demo/assets/images/el_img7.jpg">
                                                            <img alt="el_hover_img7"
                                                                 class="product_hover_img"
                                                                 src="https://bestwebcreator.com/shopwise/demo/assets/images/el_hover_img7.jpg">
                                                        </a>
                                                        <div class="product_action_box">
                                                            <ul class="list_none pr_action_btn">
                                                                <li class="add-to-cart"><a href="#"><i
                                                                        class="icon-basket-loaded"></i> Add To Cart</a>
                                                                </li>
                                                                <li>
                                                                    <a class="popup-ajax"
                                                                       href="//bestwebcreator.com/shopwise/demo/shop-compare.html"><i
                                                                            class="icon-shuffle"></i></a></li>
                                                                <li>
                                                                    <a class="popup-ajax"
                                                                       href="//bestwebcreator.com/shopwise/demo/shop-quick-view.html"><i
                                                                            class="icon-magnifier-add"></i></a>
                                                                </li>
                                                                <li><a href="#"><i class="icon-heart"></i></a></li>
                                                            </ul>
                                                        </div>
                                                    </div>
                                                    <div class="product_info">
                                                        <h6 class="product_title"><a
                                                                href="{{ route('view_item',$item->id) }}">{{
                                                            $item->formatted_title }}</a></h6>
                                                        <div class="product_price">
                                                            <span class="price">{{ $item->formatted_price }}</span>
                                                            {{--
                                                            <del>$55.25</del>
                                                            --}}
                                                            {{--
                                                            <div class="on_sale">--}}
                                                                {{-- <span>35% Off</span>--}}
                                                                {{--
                                                            </div>
                                                            --}}
                                                        </div>
                                                        {{--
                                                        <div class="rating_wrap">--}}
                                                            {{--
                                                            <div class="rating">--}}
                                                                {{--
                                                                <div class="product_rate" style="width:80%"></div>
                                                                --}}
                                                                {{--
                                                            </div>
                                                            --}}
                                                            {{-- <span class="rating_num">(21)</span>--}}
                                                            {{--
                                                        </div>
                                                        --}}
                                                        {{--
                                                        <div class="pr_desc">--}}
                                                            {{-- <p</p>--}}
                                                            {{--
                                                        </div>
                                                        --}}
                                                    </div>
                                                </div>
                                            </div>
                                            @endforeach
                                        </div>
                                    </div>
                                    <div aria-labelledby="sellers-tab" class="tab-pane fade" id="sellers"
                                         role="tabpanel">
                                        <div class="product_slider carousel_slider owl-carousel owl-theme dot_style1"
                                             data-loop="true" data-margin="20"
                                             data-responsive='{"0":{"items": "3"}, "481":{"items": "2"}, "768":{"items": "3"}, "991":{"items": "3"}}'>
                                            @foreach($best_items as $item)
                                            <div class="item">
                                                <div class="product_wrap">
                                                    <div class="product_img">
                                                        <a href="shop-product-detail.html">
                                                            <img alt="el_img7"
                                                                 src="https://bestwebcreator.com/shopwise/demo/assets/images/el_img7.jpg">
                                                            <img alt="el_hover_img7"
                                                                 class="product_hover_img"
                                                                 src="https://bestwebcreator.com/shopwise/demo/assets/images/el_hover_img7.jpg">
                                                        </a>
                                                        <div class="product_action_box">
                                                            <ul class="list_none pr_action_btn">
                                                                <li class="add-to-cart"><a href="#"><i
                                                                        class="icon-basket-loaded"></i> Add To Cart</a>
                                                                </li>
                                                                <li>
                                                                    <a class="popup-ajax"
                                                                       href="//bestwebcreator.com/shopwise/demo/shop-compare.html"><i
                                                                            class="icon-shuffle"></i></a></li>
                                                                <li>
                                                                    <a class="popup-ajax"
                                                                       href="//bestwebcreator.com/shopwise/demo/shop-quick-view.html"><i
                                                                            class="icon-magnifier-add"></i></a>
                                                                </li>
                                                                <li><a href="#"><i class="icon-heart"></i></a></li>
                                                            </ul>
                                                        </div>
                                                    </div>
                                                    <div class="product_info">
                                                        <h6 class="product_title"><a
                                                                href="{{ route('view_item',$item->id) }}">{{
                                                            $item->formatted_title }}</a></h6>
                                                        <div class="product_price">
                                                            <span class="price">{{ $item->formatted_price }}</span>
                                                            {{--
                                                            <del>$55.25</del>
                                                            --}}
                                                            {{--
                                                            <div class="on_sale">--}}
                                                                {{-- <span>35% Off</span>--}}
                                                                {{--
                                                            </div>
                                                            --}}
                                                        </div>
                                                        {{--
                                                        <div class="rating_wrap">--}}
                                                            {{--
                                                            <div class="rating">--}}
                                                                {{--
                                                                <div class="product_rate" style="width:80%"></div>
                                                                --}}
                                                                {{--
                                                            </div>
                                                            --}}
                                                            {{-- <span class="rating_num">(21)</span>--}}
                                                            {{--
                                                        </div>
                                                        --}}
                                                        {{--
                                                        <div class="pr_desc">--}}
                                                            {{-- <p</p>--}}
                                                            {{--
                                                        </div>
                                                        --}}
                                                    </div>
                                                </div>
                                            </div>
                                            @endforeach

                                        </div>
                                    </div>
                                    <div aria-labelledby="featured-tab" class="tab-pane fade" id="featured"
                                         role="tabpanel">
                                        <div class="product_slider carousel_slider owl-carousel owl-theme dot_style1"
                                             data-loop="true" data-margin="20"
                                             data-responsive='{"0":{"items": "1"}, "481":{"items": "2"}, "768":{"items": "3"}, "991":{"items": "3"}}'>
                                            @foreach($active_items as $item)
                                            <div class="item">
                                                <div class="product_wrap">
                                                    <div class="product_img">
                                                        <a href="shop-product-detail.html">
                                                            <img alt="el_img7"
                                                                 src="https://bestwebcreator.com/shopwise/demo/assets/images/el_img7.jpg">
                                                            <img alt="el_hover_img7"
                                                                 class="product_hover_img"
                                                                 src="https://bestwebcreator.com/shopwise/demo/assets/images/el_hover_img7.jpg">
                                                        </a>
                                                        <div class="product_action_box">
                                                            <ul class="list_none pr_action_btn">
                                                                <li class="add-to-cart"><a href="#"><i
                                                                        class="icon-basket-loaded"></i> Add To Cart</a>
                                                                </li>
                                                                <li>
                                                                    <a class="popup-ajax"
                                                                       href="//bestwebcreator.com/shopwise/demo/shop-compare.html"><i
                                                                            class="icon-shuffle"></i></a></li>
                                                                <li>
                                                                    <a class="popup-ajax"
                                                                       href="//bestwebcreator.com/shopwise/demo/shop-quick-view.html"><i
                                                                            class="icon-magnifier-add"></i></a>
                                                                </li>
                                                                <li><a href="#"><i class="icon-heart"></i></a></li>
                                                            </ul>
                                                        </div>
                                                    </div>
                                                    <div class="product_info">
                                                        <h6 class="product_title"><a
                                                                href="{{ route('view_item',$item->id) }}">{{
                                                            $item->formatted_title }}</a></h6>
                                                        <div class="product_price">
                                                            <span class="price">{{ $item->formatted_price }}</span>
                                                            {{--
                                                            <del>$55.25</del>
                                                            --}}
                                                            {{--
                                                            <div class="on_sale">--}}
                                                                {{-- <span>35% Off</span>--}}
                                                                {{--
                                                            </div>
                                                            --}}
                                                        </div>
                                                        {{--
                                                        <div class="rating_wrap">--}}
                                                            {{--
                                                            <div class="rating">--}}
                                                                {{--
                                                                <div class="product_rate" style="width:80%"></div>
                                                                --}}
                                                                {{--
                                                            </div>
                                                            --}}
                                                            {{-- <span class="rating_num">(21)</span>--}}
                                                            {{--
                                                        </div>
                                                        --}}
                                                        {{--
                                                        <div class="pr_desc">--}}
                                                            {{-- <p</p>--}}
                                                            {{--
                                                        </div>
                                                        --}}
                                                    </div>
                                                </div>
                                            </div>
                                            @endforeach
                                        </div>
                                    </div>
                                    <div aria-labelledby="special-tab" class="tab-pane fade" id="special"
                                         role="tabpanel">
                                        <div class="product_slider carousel_slider owl-carousel owl-theme dot_style1"
                                             data-loop="true" data-margin="20"
                                             data-responsive='{"0":{"items": "1"}, "481":{"items": "2"}, "768":{"items": "3"}, "991":{"items": "3"}}'>
                                            @foreach($package_items as $item)
                                            <div class="item">
                                                <div class="product_wrap">
                                                    <div class="product_img">
                                                        <a href="shop-product-detail.html">
                                                            <img alt="el_img7"
                                                                 src="{{ $item->formatted_main_image }}">
                                                            <img alt="el_hover_img7"
                                                                 class="product_hover_img"
                                                                 src="{{ $item->formatted_hover_image }}">
                                                        </a>
                                                        <div class="product_action_box">
                                                            <ul class="list_none pr_action_btn">
                                                                <li class="add-to-cart"><a href="#"><i
                                                                        class="icon-basket-loaded"></i> Add To Cart</a>
                                                                </li>
                                                                {{--
                                                                <li>--}}
                                                                    {{--
                                                                    <a --}}
                                                                       class="popup-ajax"
                                                                       href="//bestwebcreator.com/shopwise/demo/shop-compare.html" {{-->
                                                                        <i--
                                                                        class="icon-shuffle"
                                                                        {{-- }}></i></a></li>
                                                                --}}
                                                                <li>
                                                                    <a class="popup-ajax"
                                                                       href="//bestwebcreator.com/shopwise/demo/shop-quick-view.html"><i
                                                                            class="icon-magnifier-add"></i></a>
                                                                </li>
                                                                <li><a href="#"><i class="icon-heart"></i></a></li>
                                                            </ul>
                                                        </div>
                                                    </div>
                                                    <div class="product_info">
                                                        <h6 class="product_title"><a
                                                                href="{{ route('view_item',$item->id) }}">{{
                                                            $item->formatted_title }}</a></h6>
                                                        <div class="product_price">
                                                            <span class="price">{{ $item->formatted_price }}</span>
                                                            {{--
                                                            <del>$55.25</del>
                                                            --}}
                                                            {{--
                                                            <div class="on_sale">--}}
                                                                {{-- <span>35% Off</span>--}}
                                                                {{--
                                                            </div>
                                                            --}}
                                                        </div>
                                                        {{--
                                                        <div class="rating_wrap">--}}
                                                            {{--
                                                            <div class="rating">--}}
                                                                {{--
                                                                <div class="product_rate" style="width:80%"></div>
                                                                --}}
                                                                {{--
                                                            </div>
                                                            --}}
                                                            {{-- <span class="rating_num">(21)</span>--}}
                                                            {{--
                                                        </div>
                                                        --}}
                                                        {{--
                                                        <div class="pr_desc">--}}
                                                            {{-- <p</p>--}}
                                                            {{--
                                                        </div>
                                                        --}}
                                                    </div>
                                                </div>
                                            </div>
                                            @endforeach
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- END SECTION SHOP -->

        <!-- START SECTION BANNER -->
        <div class="section pb_20 small_pt">
            <div class="custom-container">
                <div class="row">
                    <div class="col-md-4">
                        <div class="sale-banner mb-3 mb-md-4">
                            <a class="hover_effect1" href="#">
                                <img alt="shop_banner_img7"
                                     src="https://bestwebcreator.com/shopwise/demo/assets/images/shop_banner_img7.jpg">
                            </a>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="sale-banner mb-3 mb-md-4">
                            <a class="hover_effect1" href="#">
                                <img alt="shop_banner_img8"
                                     src="https://bestwebcreator.com/shopwise/demo/assets/images/shop_banner_img8.jpg">
                            </a>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="sale-banner mb-3 mb-md-4">
                            <a class="hover_effect1" href="#">
                                <img alt="shop_banner_img9"
                                     src="https://bestwebcreator.com/shopwise/demo/assets/images/shop_banner_img9.jpg">
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- END SECTION BANNER -->

        <!-- START SECTION SHOP -->
        <div class="section pt-0 pb-0">
            <div class="custom-container">
                <div class="row">
                    <div class="col-md-12">
                        <div class="heading_tab_header">
                            <div class="heading_s2">
                                <h4>Deal Of The Day</h4>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <div class="product_slider carousel_slider owl-carousel owl-theme nav_style3"
                             data-dots="false"
                             data-loop="true" data-margin="30" data-nav="true"
                             data-responsive='{"0":{"items": "1"}, "650":{"items": "2"}, "1199":{"items": "2"}}'>
                            <div class="item">
                                <div class="deal_wrap">
                                    <div class="product_img">
                                        <a href="shop-product-detail.html">
                                            <img alt="el_img1"
                                                 src="https://bestwebcreator.com/shopwise/demo/assets/images/el_img1.jpg">
                                        </a>
                                    </div>
                                    <div class="deal_content">
                                        <div class="product_info">
                                            <h5 class="product_title"><a href="shop-product-detail.html">Red & Black
                                                Headphone</a></h5>
                                            <div class="product_price">
                                                <span class="price">$45.00</span>
                                                <del>$55.25</del>
                                            </div>
                                        </div>
                                        <div class="deal_progress">
                                            <span class="stock-sold">Already Sold: <strong>6</strong></span>
                                            <span class="stock-available">Available: <strong>8</strong></span>
                                            <div class="progress">
                                                <div aria-valuemax="100" aria-valuemin="0" aria-valuenow="46"
                                                     class="progress-bar" role="progressbar" style="width:46%"> 46%
                                                </div>
                                            </div>
                                        </div>
                                        <div class="countdown_time countdown_style4 mb-4"
                                             data-time="2020/06/02 12:30:15"></div>
                                    </div>
                                </div>
                            </div>
                            <div class="item">
                                <div class="deal_wrap">
                                    <div class="product_img">
                                        <a href="shop-product-detail.html">
                                            <img alt="el_img2"
                                                 src="https://bestwebcreator.com/shopwise/demo/assets/images/el_img2.jpg">
                                        </a>
                                    </div>
                                    <div class="deal_content">
                                        <div class="product_info">
                                            <h5 class="product_title"><a href="shop-product-detail.html">Smart Watch
                                                External</a></h5>
                                            <div class="product_price">
                                                <span class="price">$55.00</span>
                                                <del>$95.00</del>
                                            </div>
                                        </div>
                                        <div class="deal_progress">
                                            <span class="stock-sold">Already Sold: <strong>4</strong></span>
                                            <span class="stock-available">Available: <strong>22</strong></span>
                                            <div class="progress">
                                                <div aria-valuemax="100" aria-valuemin="0" aria-valuenow="26"
                                                     class="progress-bar" role="progressbar" style="width:26%"> 26%
                                                </div>
                                            </div>
                                        </div>
                                        <div class="countdown_time countdown_style4 mb-4"
                                             data-time="2020/06/02 12:30:15"></div>
                                    </div>
                                </div>
                            </div>
                            <div class="item">
                                <div class="deal_wrap">
                                    <div class="product_img">
                                        <a href="shop-product-detail.html">
                                            <img alt="el_img3"
                                                 src="https://bestwebcreator.com/shopwise/demo/assets/images/el_img3.jpg">
                                        </a>
                                    </div>
                                    <div class="deal_content">
                                        <div class="product_info">
                                            <h5 class="product_title"><a href="shop-product-detail.html">Nikon HD
                                                camera</a></h5>
                                            <div class="product_price">
                                                <span class="price">$68.00</span>
                                                <del>$99.25</del>
                                            </div>
                                        </div>
                                        <div class="deal_progress">
                                            <span class="stock-sold">Already Sold: <strong>16</strong></span>
                                            <span class="stock-available">Available: <strong>20</strong></span>
                                            <div class="progress">
                                                <div aria-valuemax="100" aria-valuemin="0" aria-valuenow="28"
                                                     class="progress-bar" role="progressbar" style="width:28%"> 28%
                                                </div>
                                            </div>
                                        </div>
                                        <div class="countdown_time countdown_style4 mb-4"
                                             data-time="2020/06/02 12:30:15"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- END SECTION SHOP -->

        <!-- START SECTION SHOP -->
        <div class="section small_pt small_pb">
            <div class="custom-container">
                <div class="row">
                    <div class="col-xl-3 d-none d-xl-block">
                        <div class="sale-banner">
                            <a class="hover_effect1" href="#">
                                <img alt="shop_banner_img10"
                                     src="https://bestwebcreator.com/shopwise/demo/assets/images/shop_banner_img10.jpg">
                            </a>
                        </div>
                    </div>
                    <div class="col-xl-9">
                        <div class="row">
                            <div class="col-12">
                                <div class="heading_tab_header">
                                    <div class="heading_s2">
                                        <h4>Trending products</h4>
                                    </div>
                                    <div class="view_all">
                                        <a class="text_default" href="#"><i class="linearicons-power"></i>
                                            <span>View All</span></a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-12">
                                <div class="product_slider carousel_slider owl-carousel owl-theme dot_style1"
                                     data-loop="true" data-margin="20"
                                     data-responsive='{"0":{"items": "1"}, "481":{"items": "2"}, "768":{"items": "3"}, "991":{"items": "4"}}'>
                                    <div class="item">
                                        <div class="product_wrap">
                                            <div class="product_img">
                                                <a href="shop-product-detail.html">
                                                    <img alt="el_img2"
                                                         src="https://bestwebcreator.com/shopwise/demo/assets/images/el_img2.jpg">
                                                    <img alt="el_hover_img2"
                                                         class="product_hover_img"
                                                         src="https://bestwebcreator.com/shopwise/demo/assets/images/el_hover_img2.jpg">
                                                </a>
                                                <div class="product_action_box">
                                                    <ul class="list_none pr_action_btn">
                                                        <li class="add-to-cart"><a href="#"><i
                                                                class="icon-basket-loaded"></i> Add To Cart</a>
                                                        </li>
                                                        <li>
                                                            <a class="popup-ajax"
                                                               href="//bestwebcreator.com/shopwise/demo/shop-compare.html"><i class="icon-shuffle"></i></a>
                                                        </li>
                                                        <li>
                                                            <a class="popup-ajax"
                                                               href="//bestwebcreator.com/shopwise/demo/shop-quick-view.html"><i class="icon-magnifier-add"></i></a>
                                                        </li>
                                                        <li><a href="#"><i class="icon-heart"></i></a></li>
                                                    </ul>
                                                </div>
                                            </div>
                                            <div class="product_info">
                                                <h6 class="product_title"><a href="shop-product-detail.html">Smart
                                                    Watch
                                                    External</a></h6>
                                                <div class="product_price">
                                                    <span class="price">$55.00</span>
                                                    <del>$95.00</del>
                                                    <div class="on_sale">
                                                        <span>25% Off</span>
                                                    </div>
                                                </div>
                                                <div class="rating_wrap">
                                                    <div class="rating">
                                                        <div class="product_rate" style="width:68%"></div>
                                                    </div>
                                                    <span class="rating_num">(15)</span>
                                                </div>
                                                <div class="pr_desc">
                                                    <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit.
                                                        Phasellus blandit massa enim. Nullam id varius nunc id
                                                        varius
                                                        nunc.</p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="item">
                                        <div class="product_wrap">
                                            <div class="product_img">
                                                <a href="shop-product-detail.html">
                                                    <img alt="el_img5"
                                                         src="https://bestwebcreator.com/shopwise/demo/assets/images/el_img5.jpg">
                                                    <img alt="el_hover_img5"
                                                         class="product_hover_img"
                                                         src="https://bestwebcreator.com/shopwise/demo/assets/images/el_hover_img5.jpg">
                                                </a>
                                                <div class="product_action_box">
                                                    <ul class="list_none pr_action_btn">
                                                        <li class="add-to-cart"><a href="#"><i
                                                                class="icon-basket-loaded"></i> Add To Cart</a>
                                                        </li>
                                                        <li>
                                                            <a class="popup-ajax"
                                                               href="//bestwebcreator.com/shopwise/demo/shop-compare.html"><i class="icon-shuffle"></i></a>
                                                        </li>
                                                        <li>
                                                            <a class="popup-ajax"
                                                               href="//bestwebcreator.com/shopwise/demo/shop-quick-view.html"><i class="icon-magnifier-add"></i></a>
                                                        </li>
                                                        <li><a href="#"><i class="icon-heart"></i></a></li>
                                                    </ul>
                                                </div>
                                            </div>
                                            <div class="product_info">
                                                <h6 class="product_title"><a href="shop-product-detail.html">Smart
                                                    Televisions</a></h6>
                                                <div class="product_price">
                                                    <span class="price">$45.00</span>
                                                    <del>$55.25</del>
                                                    <div class="on_sale">
                                                        <span>35% Off</span>
                                                    </div>
                                                </div>
                                                <div class="rating_wrap">
                                                    <div class="rating">
                                                        <div class="product_rate" style="width:80%"></div>
                                                    </div>
                                                    <span class="rating_num">(21)</span>
                                                </div>
                                                <div class="pr_desc">
                                                    <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit.
                                                        Phasellus blandit massa enim. Nullam id varius nunc id
                                                        varius
                                                        nunc.</p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="item">
                                        <div class="product_wrap">
                                            <div class="product_img">
                                                <a href="shop-product-detail.html">
                                                    <img alt="el_img9"
                                                         src="https://bestwebcreator.com/shopwise/demo/assets/images/el_img9.jpg">
                                                    <img alt="el_hover_img9"
                                                         class="product_hover_img"
                                                         src="https://bestwebcreator.com/shopwise/demo/assets/images/el_hover_img9.jpg">
                                                </a>
                                                <div class="product_action_box">
                                                    <ul class="list_none pr_action_btn">
                                                        <li class="add-to-cart"><a href="#"><i
                                                                class="icon-basket-loaded"></i> Add To Cart</a>
                                                        </li>
                                                        <li>
                                                            <a class="popup-ajax"
                                                               href="//bestwebcreator.com/shopwise/demo/shop-compare.html"><i class="icon-shuffle"></i></a>
                                                        </li>
                                                        <li>
                                                            <a class="popup-ajax"
                                                               href="//bestwebcreator.com/shopwise/demo/shop-quick-view.html"><i class="icon-magnifier-add"></i></a>
                                                        </li>
                                                        <li><a href="#"><i class="icon-heart"></i></a></li>
                                                    </ul>
                                                </div>
                                            </div>
                                            <div class="product_info">
                                                <h6 class="product_title"><a href="shop-product-detail.html">oppo
                                                    Reno3
                                                    Pro</a></h6>
                                                <div class="product_price">
                                                    <span class="price">$68.00</span>
                                                    <del>$99.00</del>
                                                    <div class="on_sale">
                                                        <span>20% Off</span>
                                                    </div>
                                                </div>
                                                <div class="rating_wrap">
                                                    <div class="rating">
                                                        <div class="product_rate" style="width:87%"></div>
                                                    </div>
                                                    <span class="rating_num">(25)</span>
                                                </div>
                                                <div class="pr_desc">
                                                    <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit.
                                                        Phasellus blandit massa enim. Nullam id varius nunc id
                                                        varius
                                                        nunc.</p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="item">
                                        <div class="product_wrap">
                                            <div class="product_img">
                                                <a href="shop-product-detail.html">
                                                    <img alt="el_img7"
                                                         src="https://bestwebcreator.com/shopwise/demo/assets/images/el_img7.jpg">
                                                    <img alt="el_hover_img7"
                                                         class="product_hover_img"
                                                         src="https://bestwebcreator.com/shopwise/demo/assets/images/el_hover_img7.jpg">
                                                </a>
                                                <div class="product_action_box">
                                                    <ul class="list_none pr_action_btn">
                                                        <li class="add-to-cart"><a href="#"><i
                                                                class="icon-basket-loaded"></i> Add To Cart</a>
                                                        </li>
                                                        <li>
                                                            <a class="popup-ajax"
                                                               href="//bestwebcreator.com/shopwise/demo/shop-compare.html"><i class="icon-shuffle"></i></a>
                                                        </li>
                                                        <li>
                                                            <a class="popup-ajax"
                                                               href="//bestwebcreator.com/shopwise/demo/shop-quick-view.html"><i class="icon-magnifier-add"></i></a>
                                                        </li>
                                                        <li><a href="#"><i class="icon-heart"></i></a></li>
                                                    </ul>
                                                </div>
                                            </div>
                                            <div class="product_info">
                                                <h6 class="product_title"><a href="shop-product-detail.html">Audio
                                                    Theaters</a></h6>
                                                <div class="product_price">
                                                    <span class="price">$45.00</span>
                                                    <del>$55.25</del>
                                                    <div class="on_sale">
                                                        <span>35% Off</span>
                                                    </div>
                                                </div>
                                                <div class="rating_wrap">
                                                    <div class="rating">
                                                        <div class="product_rate" style="width:80%"></div>
                                                    </div>
                                                    <span class="rating_num">(21)</span>
                                                </div>
                                                <div class="pr_desc">
                                                    <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit.
                                                        Phasellus blandit massa enim. Nullam id varius nunc id
                                                        varius
                                                        nunc.</p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="item">
                                        <div class="product_wrap">
                                            <div class="product_img">
                                                <a href="shop-product-detail.html">
                                                    <img alt="el_img5"
                                                         src="https://bestwebcreator.com/shopwise/demo/assets/images/el_img5.jpg">
                                                    <img alt="el_hover_img5"
                                                         class="product_hover_img"
                                                         src="https://bestwebcreator.com/shopwise/demo/assets/images/el_hover_img5.jpg">
                                                </a>
                                                <div class="product_action_box">
                                                    <ul class="list_none pr_action_btn">
                                                        <li class="add-to-cart"><a href="#"><i
                                                                class="icon-basket-loaded"></i> Add To Cart</a>
                                                        </li>
                                                        <li>
                                                            <a class="popup-ajax"
                                                               href="//bestwebcreator.com/shopwise/demo/shop-compare.html"><i class="icon-shuffle"></i></a>
                                                        </li>
                                                        <li>
                                                            <a class="popup-ajax"
                                                               href="//bestwebcreator.com/shopwise/demo/shop-quick-view.html"><i class="icon-magnifier-add"></i></a>
                                                        </li>
                                                        <li><a href="#"><i class="icon-heart"></i></a></li>
                                                    </ul>
                                                </div>
                                            </div>
                                            <div class="product_info">
                                                <h6 class="product_title"><a href="shop-product-detail.html">Smart
                                                    Televisions</a></h6>
                                                <div class="product_price">
                                                    <span class="price">$45.00</span>
                                                    <del>$55.25</del>
                                                    <div class="on_sale">
                                                        <span>35% Off</span>
                                                    </div>
                                                </div>
                                                <div class="rating_wrap">
                                                    <div class="rating">
                                                        <div class="product_rate" style="width:80%"></div>
                                                    </div>
                                                    <span class="rating_num">(21)</span>
                                                </div>
                                                <div class="pr_desc">
                                                    <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit.
                                                        Phasellus blandit massa enim. Nullam id varius nunc id
                                                        varius
                                                        nunc.</p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="item">
                                        <div class="product_wrap">
                                            <div class="product_img">
                                                <a href="shop-product-detail.html">
                                                    <img alt="el_img12"
                                                         src="https://bestwebcreator.com/shopwise/demo/assets/images/el_img12.jpg">
                                                    <img alt="el_hover_img12"
                                                         class="product_hover_img"
                                                         src="https://bestwebcreator.com/shopwise/demo/assets/images/el_hover_img12.jpg">
                                                </a>
                                                <div class="product_action_box">
                                                    <ul class="list_none pr_action_btn">
                                                        <li class="add-to-cart"><a href="#"><i
                                                                class="icon-basket-loaded"></i> Add To Cart</a>
                                                        </li>
                                                        <li>
                                                            <a class="popup-ajax"
                                                               href="//bestwebcreator.com/shopwise/demo/shop-compare.html"><i class="icon-shuffle"></i></a>
                                                        </li>
                                                        <li>
                                                            <a class="popup-ajax"
                                                               href="//bestwebcreator.com/shopwise/demo/shop-quick-view.html"><i class="icon-magnifier-add"></i></a>
                                                        </li>
                                                        <li><a href="#"><i class="icon-heart"></i></a></li>
                                                    </ul>
                                                </div>
                                            </div>
                                            <div class="product_info">
                                                <h6 class="product_title"><a href="shop-product-detail.html">Sound
                                                    Equipment for Low</a></h6>
                                                <div class="product_price">
                                                    <span class="price">$45.00</span>
                                                    <del>$55.25</del>
                                                    <div class="on_sale">
                                                        <span>35% Off</span>
                                                    </div>
                                                </div>
                                                <div class="rating_wrap">
                                                    <div class="rating">
                                                        <div class="product_rate" style="width:80%"></div>
                                                    </div>
                                                    <span class="rating_num">(21)</span>
                                                </div>
                                                <div class="pr_desc">
                                                    <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit.
                                                        Phasellus blandit massa enim. Nullam id varius nunc id
                                                        varius
                                                        nunc.</p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- END SECTION SHOP -->

        <!-- START SECTION CLIENT LOGO -->
        <div class="section pt-0 small_pb">
            <div class="custom-container">
                <div class="row">
                    <div class="col-md-12">
                        <div class="heading_tab_header">
                            <div class="heading_s2">
                                <h4>Our Brands</h4>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-12">
                        <div class="client_logo carousel_slider owl-carousel owl-theme nav_style3" data-autoplay="true"
                             data-dots="false" data-loop="true" data-margin="30" data-nav="true"
                             data-responsive='{"0":{"items": "2"}, "480":{"items": "3"}, "767":{"items": "4"}, "991":{"items": "5"}, "1199":{"items": "6"}}'>
                            <div class="item">
                                <div class="cl_logo">
                                    <img alt="cl_logo"
                                         src="https://bestwebcreator.com/shopwise/demo/assets/images/cl_logo1.png"/>
                                </div>
                            </div>
                            <div class="item">
                                <div class="cl_logo">
                                    <img alt="cl_logo"
                                         src="https://bestwebcreator.com/shopwise/demo/assets/images/cl_logo2.png"/>
                                </div>
                            </div>
                            <div class="item">
                                <div class="cl_logo">
                                    <img alt="cl_logo"
                                         src="https://bestwebcreator.com/shopwise/demo/assets/images/cl_logo3.png"/>
                                </div>
                            </div>
                            <div class="item">
                                <div class="cl_logo">
                                    <img alt="cl_logo"
                                         src="https://bestwebcreator.com/shopwise/demo/assets/images/cl_logo4.png"/>
                                </div>
                            </div>
                            <div class="item">
                                <div class="cl_logo">
                                    <img alt="cl_logo"
                                         src="https://bestwebcreator.com/shopwise/demo/assets/images/cl_logo5.png"/>
                                </div>
                            </div>
                            <div class="item">
                                <div class="cl_logo">
                                    <img alt="cl_logo"
                                         src="https://bestwebcreator.com/shopwise/demo/assets/images/cl_logo6.png"/>
                                </div>
                            </div>
                            <div class="item">
                                <div class="cl_logo">
                                    <img alt="cl_logo"
                                         src="https://bestwebcreator.com/shopwise/demo/assets/images/cl_logo7.png"/>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- END SECTION CLIENT LOGO -->

        <!-- START SECTION SHOP -->
        <div class="section pt-0 pb_20">
            <div class="custom-container">
                <div class="row">
                    <div class="col-lg-4">
                        <div class="row">
                            <div class="col-12">
                                <div class="heading_tab_header">
                                    <div class="heading_s2">
                                        <h4>Featured Products</h4>
                                    </div>
                                    <div class="view_all">
                                        <a class="text_default" href="#"><span>View All</span></a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-12">
                                <div class="product_slider carousel_slider product_list owl-carousel owl-theme nav_style5"
                                     data-dots="false" data-loop="true" data-margin="20" data-nav="true"
                                     data-responsive='{"0":{"items": "1"}, "380":{"items": "1"}, "640":{"items": "2"}, "991":{"items": "1"}}'>
                                    <div class="item">
                                        <div class="product_wrap">
                                            <div class="product_img">
                                                <a href="shop-product-detail.html">
                                                    <img alt="el_img2"
                                                         src="https://bestwebcreator.com/shopwise/demo/assets/images/el_img2.jpg">
                                                    <img alt="el_hover_img2"
                                                         class="product_hover_img"
                                                         src="https://bestwebcreator.com/shopwise/demo/assets/images/el_hover_img2.jpg">
                                                </a>
                                            </div>
                                            <div class="product_info">
                                                <h6 class="product_title"><a href="shop-product-detail.html">Smart
                                                    Watch
                                                    External</a></h6>
                                                <div class="product_price">
                                                    <span class="price">$55.00</span>
                                                    <del>$95.00</del>
                                                    <div class="on_sale">
                                                        <span>25% Off</span>
                                                    </div>
                                                </div>
                                                <div class="rating_wrap">
                                                    <div class="rating">
                                                        <div class="product_rate" style="width:68%"></div>
                                                    </div>
                                                    <span class="rating_num">(15)</span>
                                                </div>
                                                <div class="pr_desc">
                                                    <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit.
                                                        Phasellus blandit massa enim. Nullam id varius nunc id
                                                        varius
                                                        nunc.</p>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="product_wrap">
                                            <div class="product_img">
                                                <a href="shop-product-detail.html">
                                                    <img alt="el_img1"
                                                         src="https://bestwebcreator.com/shopwise/demo/assets/images/el_img1.jpg">
                                                    <img alt="el_hover_img1"
                                                         class="product_hover_img"
                                                         src="https://bestwebcreator.com/shopwise/demo/assets/images/el_hover_img1.jpg">
                                                </a>
                                            </div>
                                            <div class="product_info">
                                                <h6 class="product_title"><a href="shop-product-detail.html">Red
                                                    &amp;
                                                    Black Headphone</a></h6>
                                                <div class="product_price">
                                                    <span class="price">$45.00</span>
                                                    <del>$55.25</del>
                                                    <div class="on_sale">
                                                        <span>35% Off</span>
                                                    </div>
                                                </div>
                                                <div class="rating_wrap">
                                                    <div class="rating">
                                                        <div class="product_rate" style="width:80%"></div>
                                                    </div>
                                                    <span class="rating_num">(21)</span>
                                                </div>
                                                <div class="pr_desc">
                                                    <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit.
                                                        Phasellus blandit massa enim. Nullam id varius nunc id
                                                        varius
                                                        nunc.</p>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="product_wrap">
                                            <span class="pr_flash">New</span>
                                            <div class="product_img">
                                                <a href="shop-product-detail.html">
                                                    <img alt="el_img3"
                                                         src="https://bestwebcreator.com/shopwise/demo/assets/images/el_img3.jpg">
                                                    <img alt="el_hover_img3"
                                                         class="product_hover_img"
                                                         src="https://bestwebcreator.com/shopwise/demo/assets/images/el_hover_img3.jpg">
                                                </a>
                                            </div>
                                            <div class="product_info">
                                                <h6 class="product_title"><a href="shop-product-detail.html">Nikon
                                                    HD
                                                    camera</a></h6>
                                                <div class="product_price">
                                                    <span class="price">$68.00</span>
                                                    <del>$99.00</del>
                                                </div>
                                                <div class="rating_wrap">
                                                    <div class="rating">
                                                        <div class="product_rate" style="width:87%"></div>
                                                    </div>
                                                    <span class="rating_num">(25)</span>
                                                </div>
                                                <div class="pr_desc">
                                                    <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit.
                                                        Phasellus blandit massa enim. Nullam id varius nunc id
                                                        varius
                                                        nunc.</p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="item">
                                        <div class="product_wrap">
                                            <div class="product_img">
                                                <a href="shop-product-detail.html">
                                                    <img alt="el_img5"
                                                         src="https://bestwebcreator.com/shopwise/demo/assets/images/el_img5.jpg">
                                                    <img alt="el_hover_img5"
                                                         class="product_hover_img"
                                                         src="https://bestwebcreator.com/shopwise/demo/assets/images/el_hover_img5.jpg">
                                                </a>
                                            </div>
                                            <div class="product_info">
                                                <h6 class="product_title"><a href="shop-product-detail.html">Smart
                                                    Televisions</a></h6>
                                                <div class="product_price">
                                                    <span class="price">$45.00</span>
                                                    <del>$55.25</del>
                                                    <div class="on_sale">
                                                        <span>35% Off</span>
                                                    </div>
                                                </div>
                                                <div class="rating_wrap">
                                                    <div class="rating">
                                                        <div class="product_rate" style="width:80%"></div>
                                                    </div>
                                                    <span class="rating_num">(21)</span>
                                                </div>
                                                <div class="pr_desc">
                                                    <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit.
                                                        Phasellus blandit massa enim. Nullam id varius nunc id
                                                        varius
                                                        nunc.</p>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="product_wrap">
                                            <div class="product_img">
                                                <a href="shop-product-detail.html">
                                                    <img alt="el_img9"
                                                         src="https://bestwebcreator.com/shopwise/demo/assets/images/el_img9.jpg">
                                                    <img alt="el_hover_img9"
                                                         class="product_hover_img"
                                                         src="https://bestwebcreator.com/shopwise/demo/assets/images/el_hover_img9.jpg">
                                                </a>
                                            </div>
                                            <div class="product_info">
                                                <h6 class="product_title"><a href="shop-product-detail.html">oppo
                                                    Reno3
                                                    Pro</a></h6>
                                                <div class="product_price">
                                                    <span class="price">$68.00</span>
                                                    <del>$99.00</del>
                                                    <div class="on_sale">
                                                        <span>20% Off</span>
                                                    </div>
                                                </div>
                                                <div class="rating_wrap">
                                                    <div class="rating">
                                                        <div class="product_rate" style="width:87%"></div>
                                                    </div>
                                                    <span class="rating_num">(25)</span>
                                                </div>
                                                <div class="pr_desc">
                                                    <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit.
                                                        Phasellus blandit massa enim. Nullam id varius nunc id
                                                        varius
                                                        nunc.</p>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="product_wrap">
                                            <div class="product_img">
                                                <a href="shop-product-detail.html">
                                                    <img alt="el_img7"
                                                         src="https://bestwebcreator.com/shopwise/demo/assets/images/el_img7.jpg">
                                                    <img alt="el_hover_img7"
                                                         class="product_hover_img"
                                                         src="https://bestwebcreator.com/shopwise/demo/assets/images/el_hover_img7.jpg">
                                                </a>
                                            </div>
                                            <div class="product_info">
                                                <h6 class="product_title"><a href="shop-product-detail.html">Audio
                                                    Theaters</a></h6>
                                                <div class="product_price">
                                                    <span class="price">$45.00</span>
                                                    <del>$55.25</del>
                                                    <div class="on_sale">
                                                        <span>35% Off</span>
                                                    </div>
                                                </div>
                                                <div class="rating_wrap">
                                                    <div class="rating">
                                                        <div class="product_rate" style="width:80%"></div>
                                                    </div>
                                                    <span class="rating_num">(21)</span>
                                                </div>
                                                <div class="pr_desc">
                                                    <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit.
                                                        Phasellus blandit massa enim. Nullam id varius nunc id
                                                        varius
                                                        nunc.</p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4">
                        <div class="row">
                            <div class="col-12">
                                <div class="heading_tab_header">
                                    <div class="heading_s2">
                                        <h4>Top Rated Products</h4>
                                    </div>
                                    <div class="view_all">
                                        <a class="text_default" href="#"><span>View All</span></a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-12">
                                <div class="product_slider carousel_slider product_list owl-carousel owl-theme nav_style5"
                                     data-dots="false" data-loop="true" data-margin="20" data-nav="true"
                                     data-responsive='{"0":{"items": "1"}, "380":{"items": "1"}, "640":{"items": "2"}, "991":{"items": "1"}}'>
                                    <div class="item">
                                        <div class="product_wrap">
                                            <div class="product_img">
                                                <a href="shop-product-detail.html">
                                                    <img alt="el_img5"
                                                         src="https://bestwebcreator.com/shopwise/demo/assets/images/el_img5.jpg">
                                                    <img alt="el_hover_img5"
                                                         class="product_hover_img"
                                                         src="https://bestwebcreator.com/shopwise/demo/assets/images/el_hover_img5.jpg">
                                                </a>
                                            </div>
                                            <div class="product_info">
                                                <h6 class="product_title"><a href="shop-product-detail.html">Smart
                                                    Televisions</a></h6>
                                                <div class="product_price">
                                                    <span class="price">$45.00</span>
                                                    <del>$55.25</del>
                                                    <div class="on_sale">
                                                        <span>35% Off</span>
                                                    </div>
                                                </div>
                                                <div class="rating_wrap">
                                                    <div class="rating">
                                                        <div class="product_rate" style="width:80%"></div>
                                                    </div>
                                                    <span class="rating_num">(21)</span>
                                                </div>
                                                <div class="pr_desc">
                                                    <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit.
                                                        Phasellus blandit massa enim. Nullam id varius nunc id
                                                        varius
                                                        nunc.</p>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="product_wrap">
                                            <div class="product_img">
                                                <a href="shop-product-detail.html">
                                                    <img alt="el_img12"
                                                         src="https://bestwebcreator.com/shopwise/demo/assets/images/el_img12.jpg">
                                                    <img alt="el_hover_img12"
                                                         class="product_hover_img"
                                                         src="https://bestwebcreator.com/shopwise/demo/assets/images/el_hover_img12.jpg">
                                                </a>
                                            </div>
                                            <div class="product_info">
                                                <h6 class="product_title"><a href="shop-product-detail.html">Sound
                                                    Equipment for Low</a></h6>
                                                <div class="product_price">
                                                    <span class="price">$45.00</span>
                                                    <del>$55.25</del>
                                                    <div class="on_sale">
                                                        <span>35% Off</span>
                                                    </div>
                                                </div>
                                                <div class="rating_wrap">
                                                    <div class="rating">
                                                        <div class="product_rate" style="width:80%"></div>
                                                    </div>
                                                    <span class="rating_num">(21)</span>
                                                </div>
                                                <div class="pr_desc">
                                                    <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit.
                                                        Phasellus blandit massa enim. Nullam id varius nunc id
                                                        varius
                                                        nunc.</p>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="product_wrap">
                                            <div class="product_img">
                                                <a href="shop-product-detail.html">
                                                    <img alt="el_img4"
                                                         src="https://bestwebcreator.com/shopwise/demo/assets/images/el_img4.jpg">
                                                    <img alt="el_hover_img4"
                                                         class="product_hover_img"
                                                         src="https://bestwebcreator.com/shopwise/demo/assets/images/el_hover_img4.jpg">
                                                </a>
                                            </div>
                                            <div class="product_info">
                                                <h6 class="product_title"><a href="shop-product-detail.html">Audio
                                                    Equipment</a></h6>
                                                <div class="product_price">
                                                    <span class="price">$69.00</span>
                                                    <del>$89.00</del>
                                                    <div class="on_sale">
                                                        <span>20% Off</span>
                                                    </div>
                                                </div>
                                                <div class="rating_wrap">
                                                    <div class="rating">
                                                        <div class="product_rate" style="width:70%"></div>
                                                    </div>
                                                    <span class="rating_num">(22)</span>
                                                </div>
                                                <div class="pr_desc">
                                                    <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit.
                                                        Phasellus blandit massa enim. Nullam id varius nunc id
                                                        varius
                                                        nunc.</p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="item">
                                        <div class="product_wrap">
                                            <span class="pr_flash bg-danger">Hot</span>
                                            <div class="product_img">
                                                <a href="shop-product-detail.html">
                                                    <img alt="el_img6"
                                                         src="https://bestwebcreator.com/shopwise/demo/assets/images/el_img6.jpg">
                                                    <img alt="el_hover_img6"
                                                         class="product_hover_img"
                                                         src="https://bestwebcreator.com/shopwise/demo/assets/images/el_hover_img6.jpg">
                                                </a>
                                            </div>
                                            <div class="product_info">
                                                <h6 class="product_title"><a href="shop-product-detail.html">Samsung
                                                    Smart Phone</a></h6>
                                                <div class="product_price">
                                                    <span class="price">$55.00</span>
                                                    <del>$95.00</del>
                                                    <div class="on_sale">
                                                        <span>25% Off</span>
                                                    </div>
                                                </div>
                                                <div class="rating_wrap">
                                                    <div class="rating">
                                                        <div class="product_rate" style="width:68%"></div>
                                                    </div>
                                                    <span class="rating_num">(15)</span>
                                                </div>
                                                <div class="pr_desc">
                                                    <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit.
                                                        Phasellus blandit massa enim. Nullam id varius nunc id
                                                        varius
                                                        nunc.</p>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="product_wrap">
                                            <span class="pr_flash bg-danger">Hot</span>
                                            <div class="product_img">
                                                <a href="shop-product-detail.html">
                                                    <img alt="el_img8"
                                                         src="https://bestwebcreator.com/shopwise/demo/assets/images/el_img8.jpg">
                                                    <img alt="el_hover_img8"
                                                         class="product_hover_img"
                                                         src="https://bestwebcreator.com/shopwise/demo/assets/images/el_hover_img8.jpg">
                                                </a>
                                            </div>
                                            <div class="product_info">
                                                <h6 class="product_title"><a href="shop-product-detail.html">Surveillance
                                                    camera</a></h6>
                                                <div class="product_price">
                                                    <span class="price">$55.00</span>
                                                    <del>$95.00</del>
                                                    <div class="on_sale">
                                                        <span>25% Off</span>
                                                    </div>
                                                </div>
                                                <div class="rating_wrap">
                                                    <div class="rating">
                                                        <div class="product_rate" style="width:68%"></div>
                                                    </div>
                                                    <span class="rating_num">(15)</span>
                                                </div>
                                                <div class="pr_desc">
                                                    <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit.
                                                        Phasellus blandit massa enim. Nullam id varius nunc id
                                                        varius
                                                        nunc.</p>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="product_wrap">
                                            <span class="pr_flash bg-success">Sale</span>
                                            <div class="product_img">
                                                <a href="shop-product-detail.html">
                                                    <img alt="el_img10"
                                                         src="https://bestwebcreator.com/shopwise/demo/assets/images/el_img10.jpg">
                                                    <img alt="el_hover_img10"
                                                         class="product_hover_img"
                                                         src="https://bestwebcreator.com/shopwise/demo/assets/images/el_hover_img10.jpg">
                                                </a>
                                            </div>
                                            <div class="product_info">
                                                <h6 class="product_title"><a href="shop-product-detail.html">classical
                                                    Headphone</a></h6>
                                                <div class="product_price">
                                                    <span class="price">$68.00</span>
                                                    <del>$99.00</del>
                                                    <div class="on_sale">
                                                        <span>20% Off</span>
                                                    </div>
                                                </div>
                                                <div class="rating_wrap">
                                                    <div class="rating">
                                                        <div class="product_rate" style="width:87%"></div>
                                                    </div>
                                                    <span class="rating_num">(25)</span>
                                                </div>
                                                <div class="pr_desc">
                                                    <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit.
                                                        Phasellus blandit massa enim. Nullam id varius nunc id
                                                        varius
                                                        nunc.</p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4">
                        <div class="row">
                            <div class="col-12">
                                <div class="heading_tab_header">
                                    <div class="heading_s2">
                                        <h4>On Sale Products</h4>
                                    </div>
                                    <div class="view_all">
                                        <a class="text_default" href="#"><span>View All</span></a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-12">
                                <div class="product_slider carousel_slider product_list owl-carousel owl-theme nav_style5"
                                     data-dots="false" data-loop="true" data-margin="20" data-nav="true"
                                     data-responsive='{"0":{"items": "1"}, "380":{"items": "1"}, "640":{"items": "2"}, "991":{"items": "1"}}'>
                                    <div class="item">
                                        <div class="product_wrap">
                                            <div class="product_img">
                                                <a href="shop-product-detail.html">
                                                    <img alt="el_img11"
                                                         src="https://bestwebcreator.com/shopwise/demo/assets/images/el_img11.jpg">
                                                    <img alt="el_hover_img11"
                                                         class="product_hover_img"
                                                         src="https://bestwebcreator.com/shopwise/demo/assets/images/el_hover_img11.jpg">
                                                </a>
                                            </div>
                                            <div class="product_info">
                                                <h6 class="product_title"><a href="shop-product-detail.html">Basics
                                                    High-Speed HDMI</a></h6>
                                                <div class="product_price">
                                                    <span class="price">$69.00</span>
                                                    <del>$89.00</del>
                                                    <div class="on_sale">
                                                        <span>20% Off</span>
                                                    </div>
                                                </div>
                                                <div class="rating_wrap">
                                                    <div class="rating">
                                                        <div class="product_rate" style="width:70%"></div>
                                                    </div>
                                                    <span class="rating_num">(22)</span>
                                                </div>
                                                <div class="pr_desc">
                                                    <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit.
                                                        Phasellus blandit massa enim. Nullam id varius nunc id
                                                        varius
                                                        nunc.</p>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="product_wrap">
                                            <div class="product_img">
                                                <a href="shop-product-detail.html">
                                                    <img alt="el_img7"
                                                         src="https://bestwebcreator.com/shopwise/demo/assets/images/el_img7.jpg">
                                                    <img alt="el_hover_img7"
                                                         class="product_hover_img"
                                                         src="https://bestwebcreator.com/shopwise/demo/assets/images/el_hover_img7.jpg">
                                                </a>
                                            </div>
                                            <div class="product_info">
                                                <h6 class="product_title"><a href="shop-product-detail.html">Audio
                                                    Theaters</a></h6>
                                                <div class="product_price">
                                                    <span class="price">$45.00</span>
                                                    <del>$55.25</del>
                                                    <div class="on_sale">
                                                        <span>35% Off</span>
                                                    </div>
                                                </div>
                                                <div class="rating_wrap">
                                                    <div class="rating">
                                                        <div class="product_rate" style="width:80%"></div>
                                                    </div>
                                                    <span class="rating_num">(21)</span>
                                                </div>
                                                <div class="pr_desc">

                                                    <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit.
                                                        Phasellus blandit massa enim. Nullam id varius nunc id
                                                        varius
                                                        nunc.</p>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="product_wrap">
                                            <span class="pr_flash bg-danger">Hot</span>
                                            <div class="product_img">
                                                <a href="shop-product-detail.html">
                                                    <img alt="el_img8"
                                                         src="https://bestwebcreator.com/shopwise/demo/assets/images/el_img8.jpg">
                                                    <img alt="el_hover_img8"
                                                         class="product_hover_img"
                                                         src="https://bestwebcreator.com/shopwise/demo/assets/images/el_hover_img8.jpg">
                                                </a>
                                            </div>
                                            <div class="product_info">
                                                <h6 class="product_title"><a href="shop-product-detail.html">Surveillance
                                                    camera</a></h6>
                                                <div class="product_price">
                                                    <span class="price">$55.00</span>
                                                    <del>$95.00</del>
                                                    <div class="on_sale">
                                                        <span>25% Off</span>
                                                    </div>
                                                </div>
                                                <div class="rating_wrap">
                                                    <div class="rating">
                                                        <div class="product_rate" style="width:68%"></div>
                                                    </div>
                                                    <span class="rating_num">(15)</span>
                                                </div>
                                                <div class="pr_desc">
                                                    <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit.
                                                        Phasellus blandit massa enim. Nullam id varius nunc id
                                                        varius
                                                        nunc.</p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="item">
                                        <div class="product_wrap">
                                            <div class="product_img">
                                                <a href="shop-product-detail.html">
                                                    <img alt="el_img5"
                                                         src="https://bestwebcreator.com/shopwise/demo/assets/images/el_img5.jpg">
                                                    <img alt="el_hover_img5"
                                                         class="product_hover_img"
                                                         src="https://bestwebcreator.com/shopwise/demo/assets/images/el_hover_img5.jpg">
                                                </a>
                                            </div>
                                            <div class="product_info">
                                                <h6 class="product_title"><a href="shop-product-detail.html">Smart
                                                    Televisions</a></h6>
                                                <div class="product_price">
                                                    <span class="price">$45.00</span>
                                                    <del>$55.25</del>
                                                    <div class="on_sale">
                                                        <span>35% Off</span>
                                                    </div>
                                                </div>
                                                <div class="rating_wrap">
                                                    <div class="rating">
                                                        <div class="product_rate" style="width:80%"></div>
                                                    </div>
                                                    <span class="rating_num">(21)</span>
                                                </div>
                                                <div class="pr_desc">
                                                    <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit.
                                                        Phasellus blandit massa enim. Nullam id varius nunc id
                                                        varius
                                                        nunc.</p>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="product_wrap">
                                            <div class="product_img">
                                                <a href="shop-product-detail.html">
                                                    <img alt="el_img9"
                                                         src="https://bestwebcreator.com/shopwise/demo/assets/images/el_img9.jpg">
                                                    <img alt="el_hover_img9"
                                                         class="product_hover_img"
                                                         src="https://bestwebcreator.com/shopwise/demo/assets/images/el_hover_img9.jpg">
                                                </a>
                                            </div>
                                            <div class="product_info">
                                                <h6 class="product_title"><a href="shop-product-detail.html">oppo
                                                    Reno3
                                                    Pro</a></h6>
                                                <div class="product_price">
                                                    <span class="price">$68.00</span>
                                                    <del>$99.00</del>
                                                    <div class="on_sale">
                                                        <span>20% Off</span>
                                                    </div>
                                                </div>
                                                <div class="rating_wrap">
                                                    <div class="rating">
                                                        <div class="product_rate" style="width:87%"></div>
                                                    </div>
                                                    <span class="rating_num">(25)</span>
                                                </div>
                                                <div class="pr_desc">
                                                    <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit.
                                                        Phasellus blandit massa enim. Nullam id varius nunc id
                                                        varius
                                                        nunc.</p>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="product_wrap">
                                            <div class="product_img">
                                                <a href="shop-product-detail.html">
                                                    <img alt="el_img1"
                                                         src="https://bestwebcreator.com/shopwise/demo/assets/images/el_img1.jpg">
                                                    <img alt="el_hover_img1"
                                                         class="product_hover_img"
                                                         src="https://bestwebcreator.com/shopwise/demo/assets/images/el_hover_img1.jpg">
                                                </a>
                                            </div>
                                            <div class="product_info">
                                                <h6 class="product_title"><a href="shop-product-detail.html">Red
                                                    &amp;
                                                    Black Headphone</a></h6>
                                                <div class="product_price">
                                                    <span class="price">$45.00</span>
                                                    <del>$55.25</del>
                                                    <div class="on_sale">
                                                        <span>35% Off</span>
                                                    </div>
                                                </div>
                                                <div class="rating_wrap">
                                                    <div class="rating">
                                                        <div class="product_rate" style="width:80%"></div>
                                                    </div>
                                                    <span class="rating_num">(21)</span>
                                                </div>
                                                <div class="pr_desc">
                                                    <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit.
                                                        Phasellus blandit massa enim. Nullam id varius nunc id
                                                        varius
                                                        nunc.</p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- END SECTION SHOP -->

        <!-- START SECTION SUBSCRIBE NEWSLETTER -->
        <div class="section bg_default small_pt small_pb">
            <div class="custom-container">
                <div class="row align-items-center">
                    <div class="col-md-6">
                        <div class="newsletter_text text_white">
                            <h3>Join Our Newsletter Now</h3>
                            <p> Register now to get updates on promotions. </p>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="newsletter_form2 rounded_input">
                            <form>
                                <input class="form-control" placeholder="Enter Email Address" required=""
                                       type="text">
                                <button class="btn btn-dark btn-radius" name="submit" type="submit" value="Submit">
                                    Subscribe
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- START SECTION SUBSCRIBE NEWSLETTER -->

    </div>
</template>

<script>
    export default {
        name: "HomPage.vue"
    }
</script>

<style scoped>

</style>
