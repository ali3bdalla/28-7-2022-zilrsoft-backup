<section class="hero-section">
    <div class="hero-items owl-carousel owl-loaded owl-drag">


        <div class="owl-stage-outer">
            <div class="owl-stage"
                 style="transform: translate3d(-2880px, 0px, 0px); transition: all 0s ease 0s; width: 8640px;">
                {{--                @foreach($items as $item)--}}
                <div class="owl-item cloned" style="width: 1440px;">
                    <div class="single-hero-items set-bg" data-setbg="{{ asset('Web/template/img/side/1.jpeg') }}"
                         style="background-size: cover;background-image: url('{{ asset('Web/template/img/side/1.jpeg') }}');">
                    <div class="container">
                        <div class="row">
                            <div class="col-lg-5">
                                {{--                                <span>{{ $item->locale_name }}</span>--}}
                                {{--                                <h1>{{ $item->category->locale_name }}</h1>--}}
                                {{--                                <p>{{ $item->title }}</p>--}}
                                {{--                                <a href="{{ route('web.items.show',$item->id) }}" class="primary-btn">Shop Now</a>--}}
                            </div>
                        </div>
                        {{--                            <div class="off-card">--}}
                        {{--                                <h2>Sale <span>50%</span></h2>--}}
                        {{--                            </div>--}}
                    </div>
                </div>
            </div>


            <div class="owl-item cloned" style="width: 1440px;">
                <div class="single-hero-items set-bg" data-setbg="{{ asset('Web/template/img/side/2.jpeg') }}"
                     style="background-size: cover;background-image: url('{{ asset('Web/template/img/side/2.jpeg') }}');">
                <div class="container">
                    <div class="row">
                        <div class="col-lg-5">
                            {{--                            <span>{{ $item->locale_name }}</span>--}}
                            {{--                            <h1>{{ $item->category->locale_name }}</h1>--}}
                            {{--                            <p>{{ $item->title }}</p>--}}
                            {{--                            <a href="{{ route('web.items.show',$item->id) }}" class="primary-btn">Shop Now</a>--}}
                        </div>
                    </div>
                    {{--                            <div class="off-card">--}}
                    {{--                                <h2>Sale <span>50%</span></h2>--}}
                    {{--                            </div>--}}
                </div>
            </div>


            {{--                @endforeach--}}
        </div>
    </div>
    <div class="owl-nav">
{{--        <button type="button" role="presentation" class="owl-prev"><i class="ti-angle-left"></i></button>--}}
{{--        <button type="button" role="presentation" class="owl-next"><i class="ti-angle-right"></i></button>--}}
    </div>
    <div class="owl-dots disabled"></div>

</section>