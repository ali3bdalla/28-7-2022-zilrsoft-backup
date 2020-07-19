<?php $__env->startSection('content'); ?>
    <!-- Product Shop Section Begin -->
    <section class="product-shop spad page-details">
        <div class="container">
            <div class="row">

                <div class="col-lg-9">
                    <div class="row">
                        <div class="col-lg-6">
                            <div class="product-details">
                                <div class="pd-title">
                                    <span><?php echo e($item->category->locale_name); ?></span>
                                    <h3><?php echo e($item->locale_name); ?></h3>
                                    <a href="#" class="heart-icon"><i class="icon_heart_alt"></i></a>
                                </div>
                                <div class="pd-desc">
                                    <p><?php echo e($item->locale_description); ?></p>
                                    <h4><?php echo e($item->web_online_price); ?> <span><?php echo e($item->price_with_tax); ?></span></h4>
                                </div>
                            </div>
                            <div class="product-pic-zoom">
                                <img class="product-big-img" src="<?php echo e(asset('Web/template/img/product-single/product-1.jpg')); ?>" alt="">
                                <!-- <div class="zoom-icon">
                                    <i class="fa fa-search-plus"></i>
                                </div> -->
                            </div>
                            <div class="product-thumbs">
                                <div class="product-thumbs-track ps-slider owl-carousel">
                                    <div class="pt active" data-imgbigurl="<?php echo e(asset('Web/template/img/product-single/product-1.jpg')); ?>"><img
                                                src="<?php echo e(asset('Web/template/img/product-single/product-1.jpg')); ?>" alt=""></div>
                                    <div class="pt" data-imgbigurl="<?php echo e(asset('Web/template/img/product-single/product-2.jpg')); ?>"><img
                                                src="<?php echo e(asset('Web/template/img/product-single/product-2.jpg')); ?>" alt=""></div>
                                    <div class="pt" data-imgbigurl="<?php echo e(asset('Web/template/img/product-single/product-3.jpg')); ?>"><img
                                                src="<?php echo e(asset('Web/template/img/product-single/product-3.jpg')); ?>" alt=""></div>
                                    <div class="pt" data-imgbigurl="<?php echo e(asset('Web/template/img/product-single/product-3.jpg')); ?>"><img
                                                src="<?php echo e(asset('Web/template/img/product-single/product-3.jpg')); ?>" alt=""></div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="product-details">


                                <div class="quantity">
                                    <div class="pro-qty">
                                        <input type="text" value="1">
                                    </div>
                                    <a href="#" class="primary-btn pd-cart">Add To Cart</a>
                                </div>












                                <div class="pd-share">
                                    <div class="p-code"><?php echo e($item->barcode); ?></div>
                                    <div class="pd-social">
                                        <a href="#"><i class="ti-facebook"></i></a>
                                        <a href="#"><i class="ti-twitter-alt"></i></a>
                                        <a href="#"><i class="ti-linkedin"></i></a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="product-tab">
                        <div class="tab-item">
                            <ul class="nav" role="tablist">



                                <li>
                                    <a  class="active" data-toggle="tab" href="#tab-1" role="tab">SPECIFICATIONS</a>
                                </li>
                            </ul>
                        </div>

                        <div class="specification-table">
                            <table>
                                <?php $__currentLoopData = $item->filters; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $filter): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <tr>
                                    <td class="p-catagory"><?php echo e($filter->filter->locale_name); ?></td>
                                    <td>

                                        <div class="p-price"> <?php echo e($filter->value->locale_name); ?></div>
                                    </td>
                                </tr>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </table>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- Product Shop Section End -->

    <!-- Related Products Section End -->
    <div class="related-products spad">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="section-title">
                        <h2>Related Products</h2>
                    </div>
                </div>
            </div>
            <div class="row">
                
                <?php $__currentLoopData = $relatedItems; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <div class="col-lg-3 col-sm-6">
                    <div class="product-item">
                        <div class="pi-pic">
                            <img src="<?php echo e(asset('Web/template/img/products/women-1.jpg')); ?>" alt="">
                            
                            <div class="icon">
                                <i class="icon_heart_alt"></i>
                            </div>
                            <ul>
                                <li class="w-icon active"><a href="#"><i class="icon_bag_alt"></i></a></li>
                                <li class="quick-view"><a href="<?php echo e(route('web.items.show',$item->id)); ?>">+ Quick View</a></li>
                                <li class="w-icon"><a href="#"><i class="fa fa-random"></i></a></li>
                            </ul>
                        </div>
                        <div class="pi-text">
                            <div class="catagory-name"><?php echo e($item->category->name); ?></div>
                            <a href="<?php echo e(route('web.items.show',$item->id)); ?>">
                                <h5><?php echo e($item->locale_name); ?></h5>
                            </a>
                            <div class="product-price">
                                <?php echo e($item->web_online_price); ?>

                                <span><?php echo e($item->web_old_online_price); ?></span>
                            </div>
                        </div>
                    </div>
                </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                


            </div>
        </div>
    </div>
    <!-- Related Products Section End -->

<?php $__env->stopSection(); ?>
<?php echo $__env->make('web::layouts.master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /usr/local/var/www/vhosts/zilrsoft/Modules/Web/Resources/views/items/show.blade.php ENDPATH**/ ?>