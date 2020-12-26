<script src="<?php echo e(asset('Web/template/js/jquery-3.3.1.min.js')); ?>"></script>
<script src="<?php echo e(asset('Web/template/js/bootstrap.min.js')); ?>"></script>
<script src="<?php echo e(asset('Web/template/js/jquery-ui.min.js')); ?>"></script>
<script src="<?php echo e(asset('Web/template/js/jquery.countdown.min.js')); ?>"></script>
<script src="<?php echo e(asset('Web/template/js/jquery.nice-select.min.js')); ?>"></script>
<script src="<?php echo e(asset('Web/template/js/jquery.zoom.min.js')); ?>"></script>
<script src="<?php echo e(asset('Web/template/js/jquery.dd.min.js')); ?>"></script>
<script src="<?php echo e(asset('Web/template/js/jquery.slicknav.js')); ?>"></script>
<script src="<?php echo e(asset('Web/template/js/owl.carousel.min.js')); ?>"></script>
<script src="<?php echo e(asset('Web/template/js/main.js')); ?>"></script>
<script src="<?php echo e(asset('Web/js/web.js')); ?>"></script>

<footer class="text-center footer-section">
    <div class="container">
        <div class="row">
            <div class="col-lg-3">
                <div class="footer-left">
                    <div class="footer-logo">
                        <a href="#"><img src="<?php echo e(asset('/images/logo_hd.png')); ?>" class="w-20" alt=""></a>
                    </div>
                    <ul>
                        <!-- <li><?php echo e(__('store.footer.address')); ?>: <?php echo e(config('app.msbrshop.address')); ?></li> -->
                        <li><?php echo e(__('store.footer.phone')); ?>: <?php echo e(config('app.msbrshop.phone_number')); ?></li>
                        <li><?php echo e(__('store.footer.email')); ?>: <?php echo e(config('app.msbrshop.email_address')); ?></li>
                    </ul>

                </div>
            </div>
            <div class="col-lg-3">
                <div class="footer-widget">
                    <!-- <h5><?php echo e(__('store.footer.information')); ?></h5> -->
                    <ul>
                        <li><a href="#"><?php echo e(__('store.footer.about_us')); ?></a></li>

                        <li><a href="#"><?php echo e(__('store.footer.contact')); ?></a></li>
                    </ul>
                </div>
            </div>
            <div class="col-lg-3">
                <div class="footer-widget">
                    <!-- <h5><?php echo e(__('store.footer.my_account')); ?></h5> -->
                    <ul>

                        <li><a href="#"><?php echo e(__('store.footer.my_account')); ?></a></li>

                        <li><a href="#"><?php echo e(__('store.footer.cart')); ?></a></li>
                        <li><a href="#"><?php echo e(__('store.footer.logout')); ?></a></li>
                        
                    </ul>
                </div>
            </div>
            <div class="col-lg-3">
                <div class="newslatter-item">
                    <!-- <h5><?php echo e(__('store.footer.join_news_letter')); ?></h5> -->
                    <p><?php echo e(__('store.footer.join_news_letter_bio')); ?></p>
                    <form action="#" class="subscribe-form">
                        <input type="text" placeholder="<?php echo e(__('store.footer.your_email')); ?>">
                        <button type="button"><?php echo e(__('store.footer.subscribe')); ?></button>
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

                        <?php echo e(__('store.footer.copyright_saved')); ?>



                    </div>
                    <div class="flex items-center justify-center payment-pic">
                        <img src="<?php echo e(asset('Web/template/img/payment-method.png')); ?>" alt="">
                    </div>
                </div>

            </div>
        </div>
    </div>
</footer><?php /**PATH /var/www/resources/views/layouts/web/footer.blade.php ENDPATH**/ ?>