<script src="{{ asset('web_assets/template/js/jquery-3.3.1.min.js')}}"></script>
<script src="{{ asset('web_assets/template/js/bootstrap.min.js')}}"></script>
<script src="{{ asset('web_assets/template/js/jquery-ui.min.js')}}"></script>
<script src="{{ asset('web_assets/template/js/jquery.countdown.min.js')}}"></script>
<script src="{{ asset('web_assets/template/js/jquery.nice-select.min.js')}}"></script>
<script src="{{ asset('web_assets/template/js/jquery.zoom.min.js')}}"></script>
<script src="{{ asset('web_assets/template/js/jquery.dd.min.js')}}"></script>
<script src="{{ asset('web_assets/template/js/jquery.slicknav.js')}}"></script>
<script src="{{ asset('web_assets/template/js/owl.carousel.min.js')}}"></script>
<script src="{{ asset('web_assets/template/js/main.js')}}"></script>
<script src="{{ asset('web_assets/js/web.js') }}"></script>

<footer class="text-center footer-section">
    <div class="container">
        <div class="row">
            <div class="col-lg-3">
                <div class="footer-left">
                    <div class="footer-logo">
                        <a href="#"><img src="{{ asset('/images/logo_hd.png')}}" class="w-20" alt=""></a>
                    </div>
                    <ul>
                        <!-- <li>{{ __('store.footer.address')}}: {{ config('app.msbrshop.address') }}</li> -->
                        <li>{{ __('store.footer.phone')}}: {{ config('app.msbrshop.phone_number') }}</li>
                        <li>{{ __('store.footer.email')}}: {{ config('app.msbrshop.email_address') }}</li>
                    </ul>

                </div>
            </div>
            <div class="col-lg-3">
                <div class="footer-widget">
                    <!-- <h5>{{ __('store.footer.information') }}</h5> -->
                    <ul>
                        <li><a href="#">{{ __('store.footer.about_us') }}</a></li>

                        <li><a href="#">{{ __('store.footer.contact') }}</a></li>
                    </ul>
                </div>
            </div>
            <div class="col-lg-3">
                <div class="footer-widget">
                    <!-- <h5>{{ __('store.footer.my_account') }}</h5> -->
                    <ul>

                        <li><a href="#">{{ __('store.footer.my_account') }}</a></li>
{{--                        <li><a href="#">{{ __('store.footer.contact') }}</a></li>--}}
                        <li><a href="#">{{ __('store.footer.cart') }}</a></li>
                        <li><a href="#">{{ __('store.footer.logout') }}</a></li>
                        {{--                        <li><a href="#">Shop</a></li>--}}
                    </ul>
                </div>
            </div>
            <div class="col-lg-3">
                <div class="newslatter-item">
                    <!-- <h5>{{ __('store.footer.join_news_letter') }}</h5> -->
                    <p>{{ __('store.footer.join_news_letter_bio') }}</p>
                    <form action="#" class="subscribe-form">
                        <input type="text" placeholder="{{ __('store.footer.your_email') }}">
                        <button type="button">{{ __('store.footer.subscribe') }}</button>
                    </form>
                </div>

                <div class="footer-left">
                    <div class="footer-social">
                        <a href="#"><i class="fa fa-facebook"></i></a>
                        <a href="#"><i class="fa fa-instagram"></i></a>
                        <a href="#"><i class="fa fa-twitter"></i></a>
                        <a href="#"><i class="fa fa-pinterest"></i></a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="copyright-reserved">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="copyright-text">

                        {{ __('store.footer.copyright_saved') }}


                    </div>
                    <div class="flex items-center justify-center payment-pic">
                        <img src="{{ asset('web_assets/template/img/payment-method.png') }}" alt="">
                    </div>
                </div>

            </div>
        </div>
    </div>
</footer>