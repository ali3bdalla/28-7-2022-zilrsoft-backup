<template>
    <div class="product-item">
       <div class="row">
           <div class="col-7">
               <div class="pi-text">
                   <div class="catagory-name">{{ itemData.category.name }}</div>
                   <a href="#">
                       <h5>{{ itemData.locale_name }}</h5>
                   </a>
                   <div class="product-price">
                       {{ priceFormatter.formatted(itemData.price) }}
                       <span>{{ priceFormatter.formatted(itemData.price_with_tax)}}</span>
                   </div>
                   <div class="product-buttons">
                        <ul>
                          <li class="w-icon active"><a href="#"><i class="icon_bag_alt"></i></a></li>
                          <li class="quick-view"><a href="#"><i class="fa fa-heart"></i> </a></li>
                          <li class="w-icon"><a href="#"><i class="fa fa-random"></i></a></li>
                        </ul>
                   </div>
               </div>
           </div>

           <div class="col-5">
               <div class="pi-pic">
                   <img src="/Web/template/img/products/product-2.jpg" alt="">
               </div>
           </div>

       </div>

    </div>
</template>

<script>
    const priceF = require('../../formatter/price');
    export default {
        props: ['item'],
        name: "cellItemComponent",
        data: function () {
            return {
                priceFormatter: priceF,
                itemData: []
            };
        },
        created() {
            this.itemData = this.item;
        }
    }
</script>

<style scoped>
    .product-item {
        margin-bottom: 5px;
        background-color: #fff;
        border-bottom: 1px solid #eee;
        padding: 5px;
    }

    .pi-pic img {
        background-color: white;
    }

    .product-buttons {
        padding-top: 10px;
    }

    .product-buttons ul {
        list-style: none;
        display: inline-flex;
        align-items: center;
        justify-items: legacy;
    }
    .product-buttons ul li
    {

        margin: 5px 20px;
    }


    .product-buttons ul li a
    {
        color: #777777;
    }


    .product-buttons ul li a i {
        font-size: 30px;
    }

</style>