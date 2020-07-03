<template>
    <div class="col-lg-4 col-sm-6">
        <div class="product-item">
            <div class="pi-pic">
                <img src="/Web/template/img/products/product-1.jpg" alt="">
                <div class="sale pp-sale">Sale</div>
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
                <div class="catagory-name">{{ itemData.category.name }}</div>
                <a href="#">
                    <h5>{{ itemData.locale_name }}</h5>
                </a>
                <div class="product-price">
                    {{  priceFormatter.formatted(itemData.price) }}
                    <span>{{ priceFormatter.formatted(itemData.price_with_tax)}}</span>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    const priceF =  require('../../formatter/price');
    export default {
        props:['item'],
        name: "cellItemComponent",
        data:function()
        {
          return {
              priceFormatter:priceF,
              itemData:[]
            }  ;
        },
        created() {
            this.itemData = this.item;
        }
    }
</script>

<style scoped>

</style>