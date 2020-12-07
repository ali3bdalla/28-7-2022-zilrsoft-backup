<template>
  <web-layout>
    <section class="shopping-cart spad cart">
      <div class="container">
        <div class="row">
          <div class="col-lg-12">
            <CartItems
                :active-page="activePage"
                @orderItems="updateOrderItems"
            />
          </div>
          <div class="col-lg-12">
            <cart-shipping-address
                v-if="activePage === 'checkout'"
                :shippingAddressId="shippingAddressId"
                @updateShippingId="updateShippingId"
            />
          </div>
          <div class="col-lg-4 offset-lg-8 mt-5">
            <div class="proceed-checkout">
              <CartButton
                  :active-page="activePage"
                  :order-items="orderItems"
                  @changeActivePage="changeActivePage"
                  @sendOrder="sendOrder"
              />
            </div>
          </div>
        </div>
      </div>
    </section>
  </web-layout>
</template>

<script>
import WebLayout from "../../../Layouts/WebAppLayout";
import CartItems from "./CartItems";
import CartButton from "./CartButton";
import CartShippingAddress from "./CartShippingAddress.vue";

export default {
  name: "Index",

  data() {
    return {
      activePage: "cart",
      orderItems: [],
      shippingAddressId: 0,
    };
  },
  components: {
    CartButton,
    CartItems,
    WebLayout,
    CartShippingAddress,
  },

//   created() {
//     // axios.get('https://api.alaalimshop.com/user/product').then(response => {
//       let response = JSON.parse(`{
//   "current_page": 1,
//   "data": [
//     {
//       "id": 9,
//       "category_id": 1,
//       "seller_id": 20,
//       "title": "Modi harum proident",
//       "description": "Veniam id cupiditat",
//       "views": 0,
//       "orders_count": 0,
//       "available_qty": 0,
//       "price": 61,
//       "tax": 67,
//       "status": "active",
//       "deleted_at": null,
//       "created_at": "2020-12-05T21:55:52.000000Z",
//       "updated_at": "2020-12-05T21:55:52.000000Z",
//       "shipping_offers": [
//         {
//           "id": 8,
//           "shipper_id": 20,
//           "shipper_type": "App\\\\Orm\\\\Seller",
//           "title": "شحن سريع",
//           "price": 34344,
//           "status": "active",
//           "deleted_at": null,
//           "created_at": "2020-12-05T21:56:39.000000Z",
//           "updated_at": "2020-12-05T21:56:39.000000Z"
//         },
//         {
//           "id": 9,
//           "shipper_id": 20,
//           "shipper_type": "App\\\\Orm\\\\Seller",
//           "title": "عادي",
//           "price": 34,
//           "status": "active",
//           "deleted_at": null,
//           "created_at": "2020-12-05T21:57:11.000000Z",
//           "updated_at": "2020-12-05T21:57:11.000000Z"
//         }
//       ],
//       "images": [
//         {
//           "id": 50,
//           "product_id": 9,
//           "image": "https://api.alaalimshop.com/public/storage/products/1qItwLQeUWb2sVs35dNqZxcajglC14zIaSpIHILA.jpg",
//           "created_at": "2020-12-05T21:55:52.000000Z",
//           "updated_at": "2020-12-05T21:55:52.000000Z"
//         },
//         {
//           "id": 51,
//           "product_id": 9,
//           "image": "https://api.alaalimshop.com/public/storage/products/62Vwcy7pfS5fkz7K6KkcT1xCQ9uzwkKXfBUoeeek.jpg",
//           "created_at": "2020-12-05T21:55:52.000000Z",
//           "updated_at": "2020-12-05T21:55:52.000000Z"
//         },
//         {
//           "id": 52,
//           "product_id": 9,
//           "image": "https://api.alaalimshop.com/public/storage/products/K2caeXgAMWsoPmPAU0YLjRwSA9FJmdW0ad84ZtIf.jpg",
//           "created_at": "2020-12-05T21:55:52.000000Z",
//           "updated_at": "2020-12-05T21:55:52.000000Z"
//         },
//         {
//           "id": 53,
//           "product_id": 9,
//           "image": "https://api.alaalimshop.com/public/storage/products/SjlJgvt5WvDwHgYWxRrDsEU68sOfHudZHNFJjeCE.jpg",
//           "created_at": "2020-12-05T21:55:52.000000Z",
//           "updated_at": "2020-12-05T21:55:52.000000Z"
//         },
//         {
//           "id": 55,
//           "product_id": 9,
//           "image": "https://api.alaalimshop.com/public/storage/products/57JNLNoQtjThNVI5GuywC8exyrlJDRMqLzocFIrq.jpg",
//           "created_at": "2020-12-06T08:44:26.000000Z",
//           "updated_at": "2020-12-06T08:44:26.000000Z"
//         }
//       ],
//       "category": {
//         "id": 1,
//         "parent_id": 0,
//         "title": "shoes",
//         "description": "Cumque quasi itaque ea illo error blanditiis.",
//         "products_images_count": 5,
//         "image": "https://api.alaalimshop.com/public/storage/categories/18x7m2cf2LyjSh169gscmihSusHMIU4SbKK9SYQx.jpg",
//         "products_count": 5,
//         "deleted_at": null,
//         "created_at": "2020-12-02T09:19:14.000000Z",
//         "updated_at": "2020-12-05T21:55:52.000000Z",
//         "parent_name": "",
//         "parent": null
//       },
//       "seller": {
//         "id": 20,
//         "seller_id": 20,
//         "full_name": "Ut magna consequatur",
//         "shop_name": "aziz",
//         "shop_image": "https://api.alaalimshop.com/storage/app/public/shops/HZBTKpQbhSvZidlxdRfTMyp4RHw7NhWvy3XPThMM.jpg",
//         "phone_number": "+1 (417) 329-2578",
//         "username": "amel",
//         "contact_phone_number": "+1 (417) 329-2578",
//         "email": "gezehizoc@mailinator.com",
//         "identity": "Incididunt similique",
//         "country_id": 887,
//         "state_id": 73,
//         "city": "Quo sit voluptas tem",
//         "area": null,
//         "lat": 90,
//         "lng": 89,
//         "status": "approved",
//         "allow_chatting": "on",
//         "display_contact_phone": "on",
//         "display_banking_info": "on",
//         "display_delivery_option": "on",
//         "display_products": "on",
//         "display_products_views": "on",
//         "display_other_branches": "on",
//         "display_products_prices": "on",
//         "display_news": 1,
//         "display_sliders": 1,
//         "activation_code": null,
//         "deleted_at": null,
//         "created_at": "2020-12-05T21:54:06.000000Z",
//         "updated_at": "2020-12-05T21:54:06.000000Z",
//         "additional_phone_numbers": [
//
//         ]
//       },
//       "fields": [
//         {
//           "id": 33,
//           "product_id": 9,
//           "field_id": 1,
//           "value": "a",
//           "deleted_at": null,
//           "created_at": "2020-12-05T21:55:52.000000Z",
//           "updated_at": "2020-12-05T21:55:52.000000Z",
//           "field": {
//             "id": 1,
//             "category_id": 1,
//             "title": "Prof. Danika Hauck II",
//             "deleted_at": null,
//             "created_at": "2020-12-02T09:19:14.000000Z",
//             "updated_at": "2020-12-02T09:19:14.000000Z"
//           }
//         },
//         {
//           "id": 34,
//           "product_id": 9,
//           "field_id": 2,
//           "value": "b",
//           "deleted_at": null,
//           "created_at": "2020-12-05T21:55:52.000000Z",
//           "updated_at": "2020-12-05T21:55:52.000000Z",
//           "field": {
//             "id": 2,
//             "category_id": 1,
//             "title": "Estevan Daugherty",
//             "deleted_at": null,
//             "created_at": "2020-12-02T09:19:14.000000Z",
//             "updated_at": "2020-12-02T09:19:14.000000Z"
//           }
//         },
//         {
//           "id": 35,
//           "product_id": 9,
//           "field_id": 3,
//           "value": "v",
//           "deleted_at": null,
//           "created_at": "2020-12-05T21:55:52.000000Z",
//           "updated_at": "2020-12-05T21:55:52.000000Z",
//           "field": {
//             "id": 3,
//             "category_id": 1,
//             "title": "Ayana Gaylord",
//             "deleted_at": null,
//             "created_at": "2020-12-02T09:19:14.000000Z",
//             "updated_at": "2020-12-02T09:19:14.000000Z"
//           }
//         },
//         {
//           "id": 36,
//           "product_id": 9,
//           "field_id": 4,
//           "value": "c",
//           "deleted_at": null,
//           "created_at": "2020-12-05T21:55:52.000000Z",
//           "updated_at": "2020-12-05T21:55:52.000000Z",
//           "field": {
//             "id": 4,
//             "category_id": 1,
//             "title": "Rylee Howe",
//             "deleted_at": null,
//             "created_at": "2020-12-02T09:19:14.000000Z",
//             "updated_at": "2020-12-02T09:19:14.000000Z"
//           }
//         },
//         {
//           "id": 37,
//           "product_id": 9,
//           "field_id": 5,
//           "value": "d",
//           "deleted_at": null,
//           "created_at": "2020-12-05T21:55:52.000000Z",
//           "updated_at": "2020-12-05T21:55:52.000000Z",
//           "field": {
//             "id": 5,
//             "category_id": 1,
//             "title": "Prof. Cristian Aufderhar",
//             "deleted_at": null,
//             "created_at": "2020-12-02T09:19:14.000000Z",
//             "updated_at": "2020-12-02T09:19:14.000000Z"
//           }
//         }
//       ],
//       "attributes": [
//         {
//           "id": 208,
//           "attribute_id": 13,
//           "attribute_value_id": 63,
//           "product_id": 9,
//           "deleted_at": null,
//           "created_at": "2020-12-05T22:12:05.000000Z",
//           "updated_at": "2020-12-05T22:12:05.000000Z",
//           "attribute": {
//             "id": 13,
//             "title": "Florida Kemmer",
//             "deleted_at": null,
//             "created_at": "2020-12-02T09:19:06.000000Z",
//             "updated_at": "2020-12-02T09:19:06.000000Z"
//           },
//           "value": {
//             "id": 63,
//             "title": "Ike Gibson",
//             "attribute_id": 13,
//             "deleted_at": null,
//             "created_at": "2020-12-02T09:19:07.000000Z",
//             "updated_at": "2020-12-02T09:19:07.000000Z"
//           }
//         },
//         {
//           "id": 209,
//           "attribute_id": 23,
//           "attribute_value_id": 111,
//           "product_id": 9,
//           "deleted_at": null,
//           "created_at": "2020-12-05T22:12:12.000000Z",
//           "updated_at": "2020-12-05T22:12:12.000000Z",
//           "attribute": {
//             "id": 23,
//             "title": "Mr. Dominic Gutkowski",
//             "deleted_at": null,
//             "created_at": "2020-12-02T09:19:06.000000Z",
//             "updated_at": "2020-12-02T09:19:06.000000Z"
//           },
//           "value": {
//             "id": 111,
//             "title": "Dr. Sister Bosco PhD",
//             "attribute_id": 23,
//             "deleted_at": null,
//             "created_at": "2020-12-02T09:19:07.000000Z",
//             "updated_at": "2020-12-02T09:19:07.000000Z"
//           }
//         },
//         {
//           "id": 210,
//           "attribute_id": 1,
//           "attribute_value_id": 1,
//           "product_id": 9,
//           "deleted_at": null,
//           "created_at": "2020-12-05T22:12:12.000000Z",
//           "updated_at": "2020-12-05T22:12:12.000000Z",
//           "attribute": {
//             "id": 1,
//             "title": "Colleen Langworth MD",
//             "deleted_at": null,
//             "created_at": "2020-12-02T09:19:06.000000Z",
//             "updated_at": "2020-12-02T09:19:06.000000Z"
//           },
//           "value": {
//             "id": 1,
//             "title": "Mohamed Donnelly",
//             "attribute_id": 1,
//             "deleted_at": null,
//             "created_at": "2020-12-02T09:19:06.000000Z",
//             "updated_at": "2020-12-02T09:19:06.000000Z"
//           }
//         }
//       ]
//     },
//     {
//       "id": 8,
//       "category_id": 1,
//       "seller_id": 8,
//       "title": "سماعات انفنيكس",
//       "description": "سماعات ممتازة بجودة عالية و صوت نقي و جودة عالية",
//       "views": 0,
//       "orders_count": 0,
//       "available_qty": 0,
//       "price": 6800,
//       "tax": 800,
//       "status": "active",
//       "deleted_at": null,
//       "created_at": "2020-12-05T21:20:00.000000Z",
//       "updated_at": "2020-12-05T21:20:00.000000Z",
//       "shipping_offers": [
//         {
//           "id": 2,
//           "shipper_id": 8,
//           "shipper_type": "App\\\\Orm\\\\Seller",
//           "title": "shipping offer",
//           "price": 2900,
//           "status": "active",
//           "deleted_at": null,
//           "created_at": "2020-12-02T19:25:30.000000Z",
//           "updated_at": "2020-12-02T19:25:30.000000Z"
//         },
//         {
//           "id": 10,
//           "shipper_id": 8,
//           "shipper_type": "App\\\\Orm\\\\Seller",
//           "title": "عرض متوسط",
//           "price": 600,
//           "status": "active",
//           "deleted_at": null,
//           "created_at": "2020-12-05T22:52:17.000000Z",
//           "updated_at": "2020-12-05T22:52:17.000000Z"
//         },
//         {
//           "id": 11,
//           "shipper_id": 8,
//           "shipper_type": "App\\\\Orm\\\\Seller",
//           "title": "عرض سريع",
//           "price": 1200,
//           "status": "active",
//           "deleted_at": null,
//           "created_at": "2020-12-05T22:52:37.000000Z",
//           "updated_at": "2020-12-05T22:52:37.000000Z"
//         },
//         {
//           "id": 12,
//           "shipper_id": 8,
//           "shipper_type": "App\\\\Orm\\\\Seller",
//           "title": "حنب بيتك",
//           "price": 2000,
//           "status": "active",
//           "deleted_at": null,
//           "created_at": "2020-12-05T22:53:00.000000Z",
//           "updated_at": "2020-12-05T22:53:00.000000Z"
//         },
//         [
//
//         ]
//       ],
//       "images": [
//         {
//           "id": 44,
//           "product_id": 8,
//           "image": "https://api.alaalimshop.com/public/storage/products/p8cVNQNTeXl1LXL0ymwHWZurDktIVVDAuq431Ekr.jpg",
//           "created_at": "2020-12-05T21:20:00.000000Z",
//           "updated_at": "2020-12-05T21:20:00.000000Z"
//         },
//         {
//           "id": 45,
//           "product_id": 8,
//           "image": "https://api.alaalimshop.com/public/storage/products/nNSPbgz8FP9VKoudMDSUQ5L7bBbZgv6URSMpyshq.jpg",
//           "created_at": "2020-12-05T21:20:00.000000Z",
//           "updated_at": "2020-12-05T21:20:00.000000Z"
//         },
//         {
//           "id": 46,
//           "product_id": 8,
//           "image": "https://api.alaalimshop.com/public/storage/products/ueSpaSZWUUSrcLH5dbKruo3Fl1pCNfnuINEkKzps.jpg",
//           "created_at": "2020-12-05T21:20:00.000000Z",
//           "updated_at": "2020-12-05T21:20:00.000000Z"
//         },
//         {
//           "id": 47,
//           "product_id": 8,
//           "image": "https://api.alaalimshop.com/public/storage/products/sVoIsGFCli1jpfqT7Jh3eHCyyg2MFuJ9p0S3KDbR.jpg",
//           "created_at": "2020-12-05T21:20:00.000000Z",
//           "updated_at": "2020-12-05T21:20:00.000000Z"
//         },
//         {
//           "id": 48,
//           "product_id": 8,
//           "image": "https://api.alaalimshop.com/public/storage/products/0ON5emelvkOLUYUts9CzxYVIriRstxyG23uNgOKy.jpg",
//           "created_at": "2020-12-05T21:20:00.000000Z",
//           "updated_at": "2020-12-05T21:20:00.000000Z"
//         }
//       ],
//       "category": {
//         "id": 1,
//         "parent_id": 0,
//         "title": "shoes",
//         "description": "Cumque quasi itaque ea illo error blanditiis.",
//         "products_images_count": 5,
//         "image": "https://api.alaalimshop.com/public/storage/categories/18x7m2cf2LyjSh169gscmihSusHMIU4SbKK9SYQx.jpg",
//         "products_count": 5,
//         "deleted_at": null,
//         "created_at": "2020-12-02T09:19:14.000000Z",
//         "updated_at": "2020-12-05T21:55:52.000000Z",
//         "parent_name": "",
//         "parent": null
//       },
//       "seller": {
//         "id": 8,
//         "seller_id": 8,
//         "full_name": "almunzir99",
//         "shop_name": "DJ",
//         "shop_image": "https://api.alaalimshop.com/storage/app/public/shops/c1A2N8faeqJo1FEtNSeSefWhkRdZRnBJJ9ienwTS.jpg",
//         "phone_number": "0124647018",
//         "username": "almunzir99",
//         "contact_phone_number": "0124647018",
//         "email": null,
//         "identity": null,
//         "country_id": 887,
//         "state_id": 10,
//         "city": "none",
//         "area": null,
//         "lat": 15,
//         "lng": 45,
//         "status": "approved",
//         "allow_chatting": "on",
//         "display_contact_phone": "on",
//         "display_banking_info": "on",
//         "display_delivery_option": "on",
//         "display_products": "on",
//         "display_products_views": "on",
//         "display_other_branches": "on",
//         "display_products_prices": "on",
//         "display_news": 1,
//         "display_sliders": 1,
//         "activation_code": null,
//         "deleted_at": null,
//         "created_at": "2020-12-02T19:09:52.000000Z",
//         "updated_at": "2020-12-04T01:43:21.000000Z",
//         "additional_phone_numbers": [
//
//         ]
//       },
//       "fields": [
//         {
//           "id": 28,
//           "product_id": 8,
//           "field_id": 1,
//           "value": "لا يوجد",
//           "deleted_at": null,
//           "created_at": "2020-12-05T21:20:00.000000Z",
//           "updated_at": "2020-12-05T21:20:00.000000Z",
//           "field": {
//             "id": 1,
//             "category_id": 1,
//             "title": "Prof. Danika Hauck II",
//             "deleted_at": null,
//             "created_at": "2020-12-02T09:19:14.000000Z",
//             "updated_at": "2020-12-02T09:19:14.000000Z"
//           }
//         },
//         {
//           "id": 29,
//           "product_id": 8,
//           "field_id": 2,
//           "value": "لا يوجد",
//           "deleted_at": null,
//           "created_at": "2020-12-05T21:20:00.000000Z",
//           "updated_at": "2020-12-05T21:20:00.000000Z",
//           "field": {
//             "id": 2,
//             "category_id": 1,
//             "title": "Estevan Daugherty",
//             "deleted_at": null,
//             "created_at": "2020-12-02T09:19:14.000000Z",
//             "updated_at": "2020-12-02T09:19:14.000000Z"
//           }
//         },
//         {
//           "id": 30,
//           "product_id": 8,
//           "field_id": 3,
//           "value": "لا يوجد",
//           "deleted_at": null,
//           "created_at": "2020-12-05T21:20:00.000000Z",
//           "updated_at": "2020-12-05T21:20:00.000000Z",
//           "field": {
//             "id": 3,
//             "category_id": 1,
//             "title": "Ayana Gaylord",
//             "deleted_at": null,
//             "created_at": "2020-12-02T09:19:14.000000Z",
//             "updated_at": "2020-12-02T09:19:14.000000Z"
//           }
//         },
//         {
//           "id": 31,
//           "product_id": 8,
//           "field_id": 4,
//           "value": "لا يوجد",
//           "deleted_at": null,
//           "created_at": "2020-12-05T21:20:00.000000Z",
//           "updated_at": "2020-12-05T21:20:00.000000Z",
//           "field": {
//             "id": 4,
//             "category_id": 1,
//             "title": "Rylee Howe",
//             "deleted_at": null,
//             "created_at": "2020-12-02T09:19:14.000000Z",
//             "updated_at": "2020-12-02T09:19:14.000000Z"
//           }
//         },
//         {
//           "id": 32,
//           "product_id": 8,
//           "field_id": 5,
//           "value": "لا يوجد",
//           "deleted_at": null,
//           "created_at": "2020-12-05T21:20:00.000000Z",
//           "updated_at": "2020-12-05T21:20:00.000000Z",
//           "field": {
//             "id": 5,
//             "category_id": 1,
//             "title": "Prof. Cristian Aufderhar",
//             "deleted_at": null,
//             "created_at": "2020-12-02T09:19:14.000000Z",
//             "updated_at": "2020-12-02T09:19:14.000000Z"
//           }
//         }
//       ],
//       "attributes": [
//         {
//           "id": 153,
//           "attribute_id": 1,
//           "attribute_value_id": 1,
//           "product_id": 8,
//           "deleted_at": null,
//           "created_at": "2020-12-05T21:20:00.000000Z",
//           "updated_at": "2020-12-05T21:20:00.000000Z",
//           "attribute": {
//             "id": 1,
//             "title": "Colleen Langworth MD",
//             "deleted_at": null,
//             "created_at": "2020-12-02T09:19:06.000000Z",
//             "updated_at": "2020-12-02T09:19:06.000000Z"
//           },
//           "value": {
//             "id": 1,
//             "title": "Mohamed Donnelly",
//             "attribute_id": 1,
//             "deleted_at": null,
//             "created_at": "2020-12-02T09:19:06.000000Z",
//             "updated_at": "2020-12-02T09:19:06.000000Z"
//           }
//         },
//         {
//           "id": 154,
//           "attribute_id": 1,
//           "attribute_value_id": 2,
//           "product_id": 8,
//           "deleted_at": null,
//           "created_at": "2020-12-05T21:20:00.000000Z",
//           "updated_at": "2020-12-05T21:20:00.000000Z",
//           "attribute": {
//             "id": 1,
//             "title": "Colleen Langworth MD",
//             "deleted_at": null,
//             "created_at": "2020-12-02T09:19:06.000000Z",
//             "updated_at": "2020-12-02T09:19:06.000000Z"
//           },
//           "value": {
//             "id": 2,
//             "title": "Zola Aufderhar",
//             "attribute_id": 1,
//             "deleted_at": null,
//             "created_at": "2020-12-02T09:19:06.000000Z",
//             "updated_at": "2020-12-02T09:19:06.000000Z"
//           }
//         },
//         {
//           "id": 155,
//           "attribute_id": 1,
//           "attribute_value_id": 4,
//           "product_id": 8,
//           "deleted_at": null,
//           "created_at": "2020-12-05T21:20:00.000000Z",
//           "updated_at": "2020-12-05T21:20:00.000000Z",
//           "attribute": {
//             "id": 1,
//             "title": "Colleen Langworth MD",
//             "deleted_at": null,
//             "created_at": "2020-12-02T09:19:06.000000Z",
//             "updated_at": "2020-12-02T09:19:06.000000Z"
//           },
//           "value": {
//             "id": 4,
//             "title": "Prof. Shanon Hettinger",
//             "attribute_id": 1,
//             "deleted_at": null,
//             "created_at": "2020-12-02T09:19:06.000000Z",
//             "updated_at": "2020-12-02T09:19:06.000000Z"
//           }
//         },
//         {
//           "id": 156,
//           "attribute_id": 1,
//           "attribute_value_id": 5,
//           "product_id": 8,
//           "deleted_at": null,
//           "created_at": "2020-12-05T21:20:00.000000Z",
//           "updated_at": "2020-12-05T21:20:00.000000Z",
//           "attribute": {
//             "id": 1,
//             "title": "Colleen Langworth MD",
//             "deleted_at": null,
//             "created_at": "2020-12-02T09:19:06.000000Z",
//             "updated_at": "2020-12-02T09:19:06.000000Z"
//           },
//           "value": {
//             "id": 5,
//             "title": "Prof. Raven Goodwin",
//             "attribute_id": 1,
//             "deleted_at": null,
//             "created_at": "2020-12-02T09:19:06.000000Z",
//             "updated_at": "2020-12-02T09:19:06.000000Z"
//           }
//         },
//         {
//           "id": 157,
//           "attribute_id": 10,
//           "attribute_value_id": 46,
//           "product_id": 8,
//           "deleted_at": null,
//           "created_at": "2020-12-05T21:20:00.000000Z",
//           "updated_at": "2020-12-05T21:20:00.000000Z",
//           "attribute": {
//             "id": 10,
//             "title": "Idella Mosciski",
//             "deleted_at": null,
//             "created_at": "2020-12-02T09:19:06.000000Z",
//             "updated_at": "2020-12-02T09:19:06.000000Z"
//           },
//           "value": {
//             "id": 46,
//             "title": "Dr. Eldora Schuppe",
//             "attribute_id": 10,
//             "deleted_at": null,
//             "created_at": "2020-12-02T09:19:07.000000Z",
//             "updated_at": "2020-12-02T09:19:07.000000Z"
//           }
//         },
//         {
//           "id": 158,
//           "attribute_id": 10,
//           "attribute_value_id": 47,
//           "product_id": 8,
//           "deleted_at": null,
//           "created_at": "2020-12-05T21:20:00.000000Z",
//           "updated_at": "2020-12-05T21:20:00.000000Z",
//           "attribute": {
//             "id": 10,
//             "title": "Idella Mosciski",
//             "deleted_at": null,
//             "created_at": "2020-12-02T09:19:06.000000Z",
//             "updated_at": "2020-12-02T09:19:06.000000Z"
//           },
//           "value": {
//             "id": 47,
//             "title": "Kali Krajcik",
//             "attribute_id": 10,
//             "deleted_at": null,
//             "created_at": "2020-12-02T09:19:07.000000Z",
//             "updated_at": "2020-12-02T09:19:07.000000Z"
//           }
//         },
//         {
//           "id": 159,
//           "attribute_id": 10,
//           "attribute_value_id": 49,
//           "product_id": 8,
//           "deleted_at": null,
//           "created_at": "2020-12-05T21:20:00.000000Z",
//           "updated_at": "2020-12-05T21:20:00.000000Z",
//           "attribute": {
//             "id": 10,
//             "title": "Idella Mosciski",
//             "deleted_at": null,
//             "created_at": "2020-12-02T09:19:06.000000Z",
//             "updated_at": "2020-12-02T09:19:06.000000Z"
//           },
//           "value": {
//             "id": 49,
//             "title": "Annette Toy",
//             "attribute_id": 10,
//             "deleted_at": null,
//             "created_at": "2020-12-02T09:19:07.000000Z",
//             "updated_at": "2020-12-02T09:19:07.000000Z"
//           }
//         },
//         {
//           "id": 160,
//           "attribute_id": 10,
//           "attribute_value_id": 50,
//           "product_id": 8,
//           "deleted_at": null,
//           "created_at": "2020-12-05T21:20:00.000000Z",
//           "updated_at": "2020-12-05T21:20:00.000000Z",
//           "attribute": {
//             "id": 10,
//             "title": "Idella Mosciski",
//             "deleted_at": null,
//             "created_at": "2020-12-02T09:19:06.000000Z",
//             "updated_at": "2020-12-02T09:19:06.000000Z"
//           },
//           "value": {
//             "id": 50,
//             "title": "Rosemarie Wunsch",
//             "attribute_id": 10,
//             "deleted_at": null,
//             "created_at": "2020-12-02T09:19:07.000000Z",
//             "updated_at": "2020-12-02T09:19:07.000000Z"
//           }
//         },
//         {
//           "id": 161,
//           "attribute_id": 3,
//           "attribute_value_id": 11,
//           "product_id": 8,
//           "deleted_at": null,
//           "created_at": "2020-12-05T21:20:00.000000Z",
//           "updated_at": "2020-12-05T21:20:00.000000Z",
//           "attribute": {
//             "id": 3,
//             "title": "Dr. Kellen Erdman PhD",
//             "deleted_at": null,
//             "created_at": "2020-12-02T09:19:06.000000Z",
//             "updated_at": "2020-12-02T09:19:06.000000Z"
//           },
//           "value": {
//             "id": 11,
//             "title": "Kevon Mitchell",
//             "attribute_id": 3,
//             "deleted_at": null,
//             "created_at": "2020-12-02T09:19:06.000000Z",
//             "updated_at": "2020-12-02T09:19:06.000000Z"
//           }
//         },
//         {
//           "id": 162,
//           "attribute_id": 3,
//           "attribute_value_id": 12,
//           "product_id": 8,
//           "deleted_at": null,
//           "created_at": "2020-12-05T21:20:00.000000Z",
//           "updated_at": "2020-12-05T21:20:00.000000Z",
//           "attribute": {
//             "id": 3,
//             "title": "Dr. Kellen Erdman PhD",
//             "deleted_at": null,
//             "created_at": "2020-12-02T09:19:06.000000Z",
//             "updated_at": "2020-12-02T09:19:06.000000Z"
//           },
//           "value": {
//             "id": 12,
//             "title": "Dr. Arden Batz",
//             "attribute_id": 3,
//             "deleted_at": null,
//             "created_at": "2020-12-02T09:19:06.000000Z",
//             "updated_at": "2020-12-02T09:19:06.000000Z"
//           }
//         },
//         {
//           "id": 163,
//           "attribute_id": 3,
//           "attribute_value_id": 13,
//           "product_id": 8,
//           "deleted_at": null,
//           "created_at": "2020-12-05T21:20:00.000000Z",
//           "updated_at": "2020-12-05T21:20:00.000000Z",
//           "attribute": {
//             "id": 3,
//             "title": "Dr. Kellen Erdman PhD",
//             "deleted_at": null,
//             "created_at": "2020-12-02T09:19:06.000000Z",
//             "updated_at": "2020-12-02T09:19:06.000000Z"
//           },
//           "value": {
//             "id": 13,
//             "title": "Mrs. Zaria Jacobs DVM",
//             "attribute_id": 3,
//             "deleted_at": null,
//             "created_at": "2020-12-02T09:19:06.000000Z",
//             "updated_at": "2020-12-02T09:19:06.000000Z"
//           }
//         },
//         {
//           "id": 164,
//           "attribute_id": 3,
//           "attribute_value_id": 15,
//           "product_id": 8,
//           "deleted_at": null,
//           "created_at": "2020-12-05T21:20:00.000000Z",
//           "updated_at": "2020-12-05T21:20:00.000000Z",
//           "attribute": {
//             "id": 3,
//             "title": "Dr. Kellen Erdman PhD",
//             "deleted_at": null,
//             "created_at": "2020-12-02T09:19:06.000000Z",
//             "updated_at": "2020-12-02T09:19:06.000000Z"
//           },
//           "value": {
//             "id": 15,
//             "title": "Dr. Amber Mayert II",
//             "attribute_id": 3,
//             "deleted_at": null,
//             "created_at": "2020-12-02T09:19:06.000000Z",
//             "updated_at": "2020-12-02T09:19:06.000000Z"
//           }
//         },
//         {
//           "id": 165,
//           "attribute_id": 5,
//           "attribute_value_id": 21,
//           "product_id": 8,
//           "deleted_at": null,
//           "created_at": "2020-12-05T21:20:00.000000Z",
//           "updated_at": "2020-12-05T21:20:00.000000Z",
//           "attribute": {
//             "id": 5,
//             "title": "Miss Ebba Murphy",
//             "deleted_at": null,
//             "created_at": "2020-12-02T09:19:06.000000Z",
//             "updated_at": "2020-12-02T09:19:06.000000Z"
//           },
//           "value": {
//             "id": 21,
//             "title": "Caroline Dach",
//             "attribute_id": 5,
//             "deleted_at": null,
//             "created_at": "2020-12-02T09:19:06.000000Z",
//             "updated_at": "2020-12-02T09:19:06.000000Z"
//           }
//         },
//         {
//           "id": 166,
//           "attribute_id": 5,
//           "attribute_value_id": 22,
//           "product_id": 8,
//           "deleted_at": null,
//           "created_at": "2020-12-05T21:20:00.000000Z",
//           "updated_at": "2020-12-05T21:20:00.000000Z",
//           "attribute": {
//             "id": 5,
//             "title": "Miss Ebba Murphy",
//             "deleted_at": null,
//             "created_at": "2020-12-02T09:19:06.000000Z",
//             "updated_at": "2020-12-02T09:19:06.000000Z"
//           },
//           "value": {
//             "id": 22,
//             "title": "Freddie Ward",
//             "attribute_id": 5,
//             "deleted_at": null,
//             "created_at": "2020-12-02T09:19:06.000000Z",
//             "updated_at": "2020-12-02T09:19:06.000000Z"
//           }
//         },
//         {
//           "id": 167,
//           "attribute_id": 5,
//           "attribute_value_id": 23,
//           "product_id": 8,
//           "deleted_at": null,
//           "created_at": "2020-12-05T21:20:00.000000Z",
//           "updated_at": "2020-12-05T21:20:00.000000Z",
//           "attribute": {
//             "id": 5,
//             "title": "Miss Ebba Murphy",
//             "deleted_at": null,
//             "created_at": "2020-12-02T09:19:06.000000Z",
//             "updated_at": "2020-12-02T09:19:06.000000Z"
//           },
//           "value": {
//             "id": 23,
//             "title": "Marquise Rippin",
//             "attribute_id": 5,
//             "deleted_at": null,
//             "created_at": "2020-12-02T09:19:06.000000Z",
//             "updated_at": "2020-12-02T09:19:06.000000Z"
//           }
//         },
//         {
//           "id": 168,
//           "attribute_id": 5,
//           "attribute_value_id": 25,
//           "product_id": 8,
//           "deleted_at": null,
//           "created_at": "2020-12-05T21:20:00.000000Z",
//           "updated_at": "2020-12-05T21:20:00.000000Z",
//           "attribute": {
//             "id": 5,
//             "title": "Miss Ebba Murphy",
//             "deleted_at": null,
//             "created_at": "2020-12-02T09:19:06.000000Z",
//             "updated_at": "2020-12-02T09:19:06.000000Z"
//           },
//           "value": {
//             "id": 25,
//             "title": "Nina Dicki DDS",
//             "attribute_id": 5,
//             "deleted_at": null,
//             "created_at": "2020-12-02T09:19:06.000000Z",
//             "updated_at": "2020-12-02T09:19:06.000000Z"
//           }
//         },
//         {
//           "id": 169,
//           "attribute_id": 7,
//           "attribute_value_id": 31,
//           "product_id": 8,
//           "deleted_at": null,
//           "created_at": "2020-12-05T21:20:00.000000Z",
//           "updated_at": "2020-12-05T21:20:00.000000Z",
//           "attribute": {
//             "id": 7,
//             "title": "Emerald Kihn",
//             "deleted_at": null,
//             "created_at": "2020-12-02T09:19:06.000000Z",
//             "updated_at": "2020-12-02T09:19:06.000000Z"
//           },
//           "value": {
//             "id": 31,
//             "title": "Dr. Tomasa Veum",
//             "attribute_id": 7,
//             "deleted_at": null,
//             "created_at": "2020-12-02T09:19:06.000000Z",
//             "updated_at": "2020-12-02T09:19:06.000000Z"
//           }
//         },
//         {
//           "id": 170,
//           "attribute_id": 7,
//           "attribute_value_id": 32,
//           "product_id": 8,
//           "deleted_at": null,
//           "created_at": "2020-12-05T21:20:00.000000Z",
//           "updated_at": "2020-12-05T21:20:00.000000Z",
//           "attribute": {
//             "id": 7,
//             "title": "Emerald Kihn",
//             "deleted_at": null,
//             "created_at": "2020-12-02T09:19:06.000000Z",
//             "updated_at": "2020-12-02T09:19:06.000000Z"
//           },
//           "value": {
//             "id": 32,
//             "title": "Percival Koelpin",
//             "attribute_id": 7,
//             "deleted_at": null,
//             "created_at": "2020-12-02T09:19:06.000000Z",
//             "updated_at": "2020-12-02T09:19:06.000000Z"
//           }
//         },
//         {
//           "id": 171,
//           "attribute_id": 7,
//           "attribute_value_id": 34,
//           "product_id": 8,
//           "deleted_at": null,
//           "created_at": "2020-12-05T21:20:00.000000Z",
//           "updated_at": "2020-12-05T21:20:00.000000Z",
//           "attribute": {
//             "id": 7,
//             "title": "Emerald Kihn",
//             "deleted_at": null,
//             "created_at": "2020-12-02T09:19:06.000000Z",
//             "updated_at": "2020-12-02T09:19:06.000000Z"
//           },
//           "value": {
//             "id": 34,
//             "title": "Janice Daugherty",
//             "attribute_id": 7,
//             "deleted_at": null,
//             "created_at": "2020-12-02T09:19:06.000000Z",
//             "updated_at": "2020-12-02T09:19:06.000000Z"
//           }
//         },
//         {
//           "id": 172,
//           "attribute_id": 7,
//           "attribute_value_id": 35,
//           "product_id": 8,
//           "deleted_at": null,
//           "created_at": "2020-12-05T21:20:00.000000Z",
//           "updated_at": "2020-12-05T21:20:00.000000Z",
//           "attribute": {
//             "id": 7,
//             "title": "Emerald Kihn",
//             "deleted_at": null,
//             "created_at": "2020-12-02T09:19:06.000000Z",
//             "updated_at": "2020-12-02T09:19:06.000000Z"
//           },
//           "value": {
//             "id": 35,
//             "title": "Eileen Cremin PhD",
//             "attribute_id": 7,
//             "deleted_at": null,
//             "created_at": "2020-12-02T09:19:06.000000Z",
//             "updated_at": "2020-12-02T09:19:06.000000Z"
//           }
//         },
//         {
//           "id": 173,
//           "attribute_id": 9,
//           "attribute_value_id": 41,
//           "product_id": 8,
//           "deleted_at": null,
//           "created_at": "2020-12-05T21:20:00.000000Z",
//           "updated_at": "2020-12-05T21:20:00.000000Z",
//           "attribute": {
//             "id": 9,
//             "title": "Prof. Kiana Schoen",
//             "deleted_at": null,
//             "created_at": "2020-12-02T09:19:06.000000Z",
//             "updated_at": "2020-12-02T09:19:06.000000Z"
//           },
//           "value": {
//             "id": 41,
//             "title": "Marcelle Schinner",
//             "attribute_id": 9,
//             "deleted_at": null,
//             "created_at": "2020-12-02T09:19:07.000000Z",
//             "updated_at": "2020-12-02T09:19:07.000000Z"
//           }
//         },
//         {
//           "id": 174,
//           "attribute_id": 9,
//           "attribute_value_id": 42,
//           "product_id": 8,
//           "deleted_at": null,
//           "created_at": "2020-12-05T21:20:00.000000Z",
//           "updated_at": "2020-12-05T21:20:00.000000Z",
//           "attribute": {
//             "id": 9,
//             "title": "Prof. Kiana Schoen",
//             "deleted_at": null,
//             "created_at": "2020-12-02T09:19:06.000000Z",
//             "updated_at": "2020-12-02T09:19:06.000000Z"
//           },
//           "value": {
//             "id": 42,
//             "title": "Andres Terry",
//             "attribute_id": 9,
//             "deleted_at": null,
//             "created_at": "2020-12-02T09:19:07.000000Z",
//             "updated_at": "2020-12-02T09:19:07.000000Z"
//           }
//         },
//         {
//           "id": 175,
//           "attribute_id": 9,
//           "attribute_value_id": 44,
//           "product_id": 8,
//           "deleted_at": null,
//           "created_at": "2020-12-05T21:20:00.000000Z",
//           "updated_at": "2020-12-05T21:20:00.000000Z",
//           "attribute": {
//             "id": 9,
//             "title": "Prof. Kiana Schoen",
//             "deleted_at": null,
//             "created_at": "2020-12-02T09:19:06.000000Z",
//             "updated_at": "2020-12-02T09:19:06.000000Z"
//           },
//           "value": {
//             "id": 44,
//             "title": "Dr. Adella Runolfsdottir V",
//             "attribute_id": 9,
//             "deleted_at": null,
//             "created_at": "2020-12-02T09:19:07.000000Z",
//             "updated_at": "2020-12-02T09:19:07.000000Z"
//           }
//         },
//         {
//           "id": 176,
//           "attribute_id": 9,
//           "attribute_value_id": 45,
//           "product_id": 8,
//           "deleted_at": null,
//           "created_at": "2020-12-05T21:20:00.000000Z",
//           "updated_at": "2020-12-05T21:20:00.000000Z",
//           "attribute": {
//             "id": 9,
//             "title": "Prof. Kiana Schoen",
//             "deleted_at": null,
//             "created_at": "2020-12-02T09:19:06.000000Z",
//             "updated_at": "2020-12-02T09:19:06.000000Z"
//           },
//           "value": {
//             "id": 45,
//             "title": "Esther Heathcote",
//             "attribute_id": 9,
//             "deleted_at": null,
//             "created_at": "2020-12-02T09:19:07.000000Z",
//             "updated_at": "2020-12-02T09:19:07.000000Z"
//           }
//         },
//         {
//           "id": 177,
//           "attribute_id": 14,
//           "attribute_value_id": 66,
//           "product_id": 8,
//           "deleted_at": null,
//           "created_at": "2020-12-05T21:20:00.000000Z",
//           "updated_at": "2020-12-05T21:20:00.000000Z",
//           "attribute": {
//             "id": 14,
//             "title": "Ms. Kimberly Klocko PhD",
//             "deleted_at": null,
//             "created_at": "2020-12-02T09:19:06.000000Z",
//             "updated_at": "2020-12-02T09:19:06.000000Z"
//           },
//           "value": {
//             "id": 66,
//             "title": "Jessy Rodriguez II",
//             "attribute_id": 14,
//             "deleted_at": null,
//             "created_at": "2020-12-02T09:19:07.000000Z",
//             "updated_at": "2020-12-02T09:19:07.000000Z"
//           }
//         },
//         {
//           "id": 178,
//           "attribute_id": 14,
//           "attribute_value_id": 67,
//           "product_id": 8,
//           "deleted_at": null,
//           "created_at": "2020-12-05T21:20:00.000000Z",
//           "updated_at": "2020-12-05T21:20:00.000000Z",
//           "attribute": {
//             "id": 14,
//             "title": "Ms. Kimberly Klocko PhD",
//             "deleted_at": null,
//             "created_at": "2020-12-02T09:19:06.000000Z",
//             "updated_at": "2020-12-02T09:19:06.000000Z"
//           },
//           "value": {
//             "id": 67,
//             "title": "Milford Bahringer",
//             "attribute_id": 14,
//             "deleted_at": null,
//             "created_at": "2020-12-02T09:19:07.000000Z",
//             "updated_at": "2020-12-02T09:19:07.000000Z"
//           }
//         },
//         {
//           "id": 179,
//           "attribute_id": 14,
//           "attribute_value_id": 69,
//           "product_id": 8,
//           "deleted_at": null,
//           "created_at": "2020-12-05T21:20:00.000000Z",
//           "updated_at": "2020-12-05T21:20:00.000000Z",
//           "attribute": {
//             "id": 14,
//             "title": "Ms. Kimberly Klocko PhD",
//             "deleted_at": null,
//             "created_at": "2020-12-02T09:19:06.000000Z",
//             "updated_at": "2020-12-02T09:19:06.000000Z"
//           },
//           "value": {
//             "id": 69,
//             "title": "Roberta Lind",
//             "attribute_id": 14,
//             "deleted_at": null,
//             "created_at": "2020-12-02T09:19:07.000000Z",
//             "updated_at": "2020-12-02T09:19:07.000000Z"
//           }
//         },
//         {
//           "id": 180,
//           "attribute_id": 14,
//           "attribute_value_id": 70,
//           "product_id": 8,
//           "deleted_at": null,
//           "created_at": "2020-12-05T21:20:00.000000Z",
//           "updated_at": "2020-12-05T21:20:00.000000Z",
//           "attribute": {
//             "id": 14,
//             "title": "Ms. Kimberly Klocko PhD",
//             "deleted_at": null,
//             "created_at": "2020-12-02T09:19:06.000000Z",
//             "updated_at": "2020-12-02T09:19:06.000000Z"
//           },
//           "value": {
//             "id": 70,
//             "title": "Imogene Reilly",
//             "attribute_id": 14,
//             "deleted_at": null,
//             "created_at": "2020-12-02T09:19:07.000000Z",
//             "updated_at": "2020-12-02T09:19:07.000000Z"
//           }
//         },
//         {
//           "id": 185,
//           "attribute_id": 13,
//           "attribute_value_id": 62,
//           "product_id": 8,
//           "deleted_at": null,
//           "created_at": "2020-12-05T21:20:00.000000Z",
//           "updated_at": "2020-12-05T21:20:00.000000Z",
//           "attribute": {
//             "id": 13,
//             "title": "Florida Kemmer",
//             "deleted_at": null,
//             "created_at": "2020-12-02T09:19:06.000000Z",
//             "updated_at": "2020-12-02T09:19:06.000000Z"
//           },
//           "value": {
//             "id": 62,
//             "title": "Dr. Janessa Ziemann II",
//             "attribute_id": 13,
//             "deleted_at": null,
//             "created_at": "2020-12-02T09:19:07.000000Z",
//             "updated_at": "2020-12-02T09:19:07.000000Z"
//           }
//         },
//         {
//           "id": 186,
//           "attribute_id": 13,
//           "attribute_value_id": 64,
//           "product_id": 8,
//           "deleted_at": null,
//           "created_at": "2020-12-05T21:20:00.000000Z",
//           "updated_at": "2020-12-05T21:20:00.000000Z",
//           "attribute": {
//             "id": 13,
//             "title": "Florida Kemmer",
//             "deleted_at": null,
//             "created_at": "2020-12-02T09:19:06.000000Z",
//             "updated_at": "2020-12-02T09:19:06.000000Z"
//           },
//           "value": {
//             "id": 64,
//             "title": "Dr. Antwon Hettinger",
//             "attribute_id": 13,
//             "deleted_at": null,
//             "created_at": "2020-12-02T09:19:07.000000Z",
//             "updated_at": "2020-12-02T09:19:07.000000Z"
//           }
//         },
//         {
//           "id": 187,
//           "attribute_id": 13,
//           "attribute_value_id": 65,
//           "product_id": 8,
//           "deleted_at": null,
//           "created_at": "2020-12-05T21:20:00.000000Z",
//           "updated_at": "2020-12-05T21:20:00.000000Z",
//           "attribute": {
//             "id": 13,
//             "title": "Florida Kemmer",
//             "deleted_at": null,
//             "created_at": "2020-12-02T09:19:06.000000Z",
//             "updated_at": "2020-12-02T09:19:06.000000Z"
//           },
//           "value": {
//             "id": 65,
//             "title": "Prof. Mackenzie Lockman",
//             "attribute_id": 13,
//             "deleted_at": null,
//             "created_at": "2020-12-02T09:19:07.000000Z",
//             "updated_at": "2020-12-02T09:19:07.000000Z"
//           }
//         }
//       ]
//     },
//     {
//       "id": 7,
//       "category_id": 1,
//       "seller_id": 8,
//       "title": "يم",
//       "description": "اسنةرن هى رن كل لو كل شذى",
//       "views": 0,
//       "orders_count": 1,
//       "available_qty": 0,
//       "price": 5088,
//       "tax": 125,
//       "status": "active",
//       "deleted_at": null,
//       "created_at": "2020-12-05T21:16:10.000000Z",
//       "updated_at": "2020-12-06T09:29:45.000000Z",
//       "shipping_offers": [
//         {
//           "id": 2,
//           "shipper_id": 8,
//           "shipper_type": "App\\\\Orm\\\\Seller",
//           "title": "shipping offer",
//           "price": 2900,
//           "status": "active",
//           "deleted_at": null,
//           "created_at": "2020-12-02T19:25:30.000000Z",
//           "updated_at": "2020-12-02T19:25:30.000000Z"
//         },
//         {
//           "id": 10,
//           "shipper_id": 8,
//           "shipper_type": "App\\\\Orm\\\\Seller",
//           "title": "عرض متوسط",
//           "price": 600,
//           "status": "active",
//           "deleted_at": null,
//           "created_at": "2020-12-05T22:52:17.000000Z",
//           "updated_at": "2020-12-05T22:52:17.000000Z"
//         },
//         {
//           "id": 11,
//           "shipper_id": 8,
//           "shipper_type": "App\\\\Orm\\\\Seller",
//           "title": "عرض سريع",
//           "price": 1200,
//           "status": "active",
//           "deleted_at": null,
//           "created_at": "2020-12-05T22:52:37.000000Z",
//           "updated_at": "2020-12-05T22:52:37.000000Z"
//         },
//         {
//           "id": 12,
//           "shipper_id": 8,
//           "shipper_type": "App\\\\Orm\\\\Seller",
//           "title": "حنب بيتك",
//           "price": 2000,
//           "status": "active",
//           "deleted_at": null,
//           "created_at": "2020-12-05T22:53:00.000000Z",
//           "updated_at": "2020-12-05T22:53:00.000000Z"
//         },
//         [
//
//         ]
//       ],
//       "images": [
//         {
//           "id": 39,
//           "product_id": 7,
//           "image": "https://api.alaalimshop.com/public/storage/products/70cJGaJ3HrcvlaIW5ZYym3NBUMJRDiQjucjUHidy.jpg",
//           "created_at": "2020-12-05T21:16:10.000000Z",
//           "updated_at": "2020-12-05T21:16:10.000000Z"
//         },
//         {
//           "id": 40,
//           "product_id": 7,
//           "image": "https://api.alaalimshop.com/public/storage/products/pP9vymmwfjAi0hheOSLaRozTsH5VTaUHEmvdsPFe.jpg",
//           "created_at": "2020-12-05T21:16:10.000000Z",
//           "updated_at": "2020-12-05T21:16:10.000000Z"
//         },
//         {
//           "id": 41,
//           "product_id": 7,
//           "image": "https://api.alaalimshop.com/public/storage/products/sZpNZTl0UWmegkXiopB9iUvscI9LFOL1XWeChqgy.jpg",
//           "created_at": "2020-12-05T21:16:10.000000Z",
//           "updated_at": "2020-12-05T21:16:10.000000Z"
//         },
//         {
//           "id": 42,
//           "product_id": 7,
//           "image": "https://api.alaalimshop.com/public/storage/products/q9NAYEJsIENmw3zptt27xle1woL1OWc31it9KsIo.jpg",
//           "created_at": "2020-12-05T21:16:10.000000Z",
//           "updated_at": "2020-12-05T21:16:10.000000Z"
//         },
//         {
//           "id": 43,
//           "product_id": 7,
//           "image": "https://api.alaalimshop.com/public/storage/products/KA6EcAcHP5Ibx6kjyQlAJ7hvpF5R0LxEZsmgsYX6.jpg",
//           "created_at": "2020-12-05T21:16:10.000000Z",
//           "updated_at": "2020-12-05T21:16:10.000000Z"
//         }
//       ],
//       "category": {
//         "id": 1,
//         "parent_id": 0,
//         "title": "shoes",
//         "description": "Cumque quasi itaque ea illo error blanditiis.",
//         "products_images_count": 5,
//         "image": "https://api.alaalimshop.com/public/storage/categories/18x7m2cf2LyjSh169gscmihSusHMIU4SbKK9SYQx.jpg",
//         "products_count": 5,
//         "deleted_at": null,
//         "created_at": "2020-12-02T09:19:14.000000Z",
//         "updated_at": "2020-12-05T21:55:52.000000Z",
//         "parent_name": "",
//         "parent": null
//       },
//       "seller": {
//         "id": 8,
//         "seller_id": 8,
//         "full_name": "almunzir99",
//         "shop_name": "DJ",
//         "shop_image": "https://api.alaalimshop.com/storage/app/public/shops/c1A2N8faeqJo1FEtNSeSefWhkRdZRnBJJ9ienwTS.jpg",
//         "phone_number": "0124647018",
//         "username": "almunzir99",
//         "contact_phone_number": "0124647018",
//         "email": null,
//         "identity": null,
//         "country_id": 887,
//         "state_id": 10,
//         "city": "none",
//         "area": null,
//         "lat": 15,
//         "lng": 45,
//         "status": "approved",
//         "allow_chatting": "on",
//         "display_contact_phone": "on",
//         "display_banking_info": "on",
//         "display_delivery_option": "on",
//         "display_products": "on",
//         "display_products_views": "on",
//         "display_other_branches": "on",
//         "display_products_prices": "on",
//         "display_news": 1,
//         "display_sliders": 1,
//         "activation_code": null,
//         "deleted_at": null,
//         "created_at": "2020-12-02T19:09:52.000000Z",
//         "updated_at": "2020-12-04T01:43:21.000000Z",
//         "additional_phone_numbers": [
//
//         ]
//       },
//       "fields": [
//         {
//           "id": 23,
//           "product_id": 7,
//           "field_id": 1,
//           "value": "لك",
//           "deleted_at": null,
//           "created_at": "2020-12-05T21:16:10.000000Z",
//           "updated_at": "2020-12-05T21:16:10.000000Z",
//           "field": {
//             "id": 1,
//             "category_id": 1,
//             "title": "Prof. Danika Hauck II",
//             "deleted_at": null,
//             "created_at": "2020-12-02T09:19:14.000000Z",
//             "updated_at": "2020-12-02T09:19:14.000000Z"
//           }
//         },
//         {
//           "id": 24,
//           "product_id": 7,
//           "field_id": 2,
//           "value": "حق",
//           "deleted_at": null,
//           "created_at": "2020-12-05T21:16:10.000000Z",
//           "updated_at": "2020-12-05T21:16:10.000000Z",
//           "field": {
//             "id": 2,
//             "category_id": 1,
//             "title": "Estevan Daugherty",
//             "deleted_at": null,
//             "created_at": "2020-12-02T09:19:14.000000Z",
//             "updated_at": "2020-12-02T09:19:14.000000Z"
//           }
//         },
//         {
//           "id": 25,
//           "product_id": 7,
//           "field_id": 3,
//           "value": "ناس",
//           "deleted_at": null,
//           "created_at": "2020-12-05T21:16:10.000000Z",
//           "updated_at": "2020-12-05T21:16:10.000000Z",
//           "field": {
//             "id": 3,
//             "category_id": 1,
//             "title": "Ayana Gaylord",
//             "deleted_at": null,
//             "created_at": "2020-12-02T09:19:14.000000Z",
//             "updated_at": "2020-12-02T09:19:14.000000Z"
//           }
//         },
//         {
//           "id": 26,
//           "product_id": 7,
//           "field_id": 4,
//           "value": "لو",
//           "deleted_at": null,
//           "created_at": "2020-12-05T21:16:10.000000Z",
//           "updated_at": "2020-12-05T21:16:10.000000Z",
//           "field": {
//             "id": 4,
//             "category_id": 1,
//             "title": "Rylee Howe",
//             "deleted_at": null,
//             "created_at": "2020-12-02T09:19:14.000000Z",
//             "updated_at": "2020-12-02T09:19:14.000000Z"
//           }
//         },
//         {
//           "id": 27,
//           "product_id": 7,
//           "field_id": 5,
//           "value": "حتى",
//           "deleted_at": null,
//           "created_at": "2020-12-05T21:16:10.000000Z",
//           "updated_at": "2020-12-05T21:16:10.000000Z",
//           "field": {
//             "id": 5,
//             "category_id": 1,
//             "title": "Prof. Cristian Aufderhar",
//             "deleted_at": null,
//             "created_at": "2020-12-02T09:19:14.000000Z",
//             "updated_at": "2020-12-02T09:19:14.000000Z"
//           }
//         }
//       ],
//       "attributes": [
//         {
//           "id": 145,
//           "attribute_id": 9,
//           "attribute_value_id": 44,
//           "product_id": 7,
//           "deleted_at": null,
//           "created_at": "2020-12-05T21:16:10.000000Z",
//           "updated_at": "2020-12-05T21:16:10.000000Z",
//           "attribute": {
//             "id": 9,
//             "title": "Prof. Kiana Schoen",
//             "deleted_at": null,
//             "created_at": "2020-12-02T09:19:06.000000Z",
//             "updated_at": "2020-12-02T09:19:06.000000Z"
//           },
//           "value": {
//             "id": 44,
//             "title": "Dr. Adella Runolfsdottir V",
//             "attribute_id": 9,
//             "deleted_at": null,
//             "created_at": "2020-12-02T09:19:07.000000Z",
//             "updated_at": "2020-12-02T09:19:07.000000Z"
//           }
//         },
//         {
//           "id": 146,
//           "attribute_id": 9,
//           "attribute_value_id": 43,
//           "product_id": 7,
//           "deleted_at": null,
//           "created_at": "2020-12-05T21:16:10.000000Z",
//           "updated_at": "2020-12-05T21:16:10.000000Z",
//           "attribute": {
//             "id": 9,
//             "title": "Prof. Kiana Schoen",
//             "deleted_at": null,
//             "created_at": "2020-12-02T09:19:06.000000Z",
//             "updated_at": "2020-12-02T09:19:06.000000Z"
//           },
//           "value": {
//             "id": 43,
//             "title": "Prof. Alda Feil",
//             "attribute_id": 9,
//             "deleted_at": null,
//             "created_at": "2020-12-02T09:19:07.000000Z",
//             "updated_at": "2020-12-02T09:19:07.000000Z"
//           }
//         },
//         {
//           "id": 147,
//           "attribute_id": 9,
//           "attribute_value_id": 41,
//           "product_id": 7,
//           "deleted_at": null,
//           "created_at": "2020-12-05T21:16:10.000000Z",
//           "updated_at": "2020-12-05T21:16:10.000000Z",
//           "attribute": {
//             "id": 9,
//             "title": "Prof. Kiana Schoen",
//             "deleted_at": null,
//             "created_at": "2020-12-02T09:19:06.000000Z",
//             "updated_at": "2020-12-02T09:19:06.000000Z"
//           },
//           "value": {
//             "id": 41,
//             "title": "Marcelle Schinner",
//             "attribute_id": 9,
//             "deleted_at": null,
//             "created_at": "2020-12-02T09:19:07.000000Z",
//             "updated_at": "2020-12-02T09:19:07.000000Z"
//           }
//         },
//         {
//           "id": 148,
//           "attribute_id": 7,
//           "attribute_value_id": 34,
//           "product_id": 7,
//           "deleted_at": null,
//           "created_at": "2020-12-05T21:16:10.000000Z",
//           "updated_at": "2020-12-05T21:16:10.000000Z",
//           "attribute": {
//             "id": 7,
//             "title": "Emerald Kihn",
//             "deleted_at": null,
//             "created_at": "2020-12-02T09:19:06.000000Z",
//             "updated_at": "2020-12-02T09:19:06.000000Z"
//           },
//           "value": {
//             "id": 34,
//             "title": "Janice Daugherty",
//             "attribute_id": 7,
//             "deleted_at": null,
//             "created_at": "2020-12-02T09:19:06.000000Z",
//             "updated_at": "2020-12-02T09:19:06.000000Z"
//           }
//         },
//         {
//           "id": 149,
//           "attribute_id": 7,
//           "attribute_value_id": 35,
//           "product_id": 7,
//           "deleted_at": null,
//           "created_at": "2020-12-05T21:16:10.000000Z",
//           "updated_at": "2020-12-05T21:16:10.000000Z",
//           "attribute": {
//             "id": 7,
//             "title": "Emerald Kihn",
//             "deleted_at": null,
//             "created_at": "2020-12-02T09:19:06.000000Z",
//             "updated_at": "2020-12-02T09:19:06.000000Z"
//           },
//           "value": {
//             "id": 35,
//             "title": "Eileen Cremin PhD",
//             "attribute_id": 7,
//             "deleted_at": null,
//             "created_at": "2020-12-02T09:19:06.000000Z",
//             "updated_at": "2020-12-02T09:19:06.000000Z"
//           }
//         }
//       ]
//     },
//     {
//       "id": 6,
//       "category_id": 4,
//       "seller_id": 8,
//       "title": "do",
//       "description": "school",
//       "views": 0,
//       "orders_count": 2,
//       "available_qty": 0,
//       "price": 2500,
//       "tax": 250,
//       "status": "active",
//       "deleted_at": null,
//       "created_at": "2020-12-05T08:01:20.000000Z",
//       "updated_at": "2020-12-05T21:14:57.000000Z",
//       "shipping_offers": [
//         {
//           "id": 2,
//           "shipper_id": 8,
//           "shipper_type": "App\\\\Orm\\\\Seller",
//           "title": "shipping offer",
//           "price": 2900,
//           "status": "active",
//           "deleted_at": null,
//           "created_at": "2020-12-02T19:25:30.000000Z",
//           "updated_at": "2020-12-02T19:25:30.000000Z"
//         },
//         {
//           "id": 10,
//           "shipper_id": 8,
//           "shipper_type": "App\\\\Orm\\\\Seller",
//           "title": "عرض متوسط",
//           "price": 600,
//           "status": "active",
//           "deleted_at": null,
//           "created_at": "2020-12-05T22:52:17.000000Z",
//           "updated_at": "2020-12-05T22:52:17.000000Z"
//         },
//         {
//           "id": 11,
//           "shipper_id": 8,
//           "shipper_type": "App\\\\Orm\\\\Seller",
//           "title": "عرض سريع",
//           "price": 1200,
//           "status": "active",
//           "deleted_at": null,
//           "created_at": "2020-12-05T22:52:37.000000Z",
//           "updated_at": "2020-12-05T22:52:37.000000Z"
//         },
//         {
//           "id": 12,
//           "shipper_id": 8,
//           "shipper_type": "App\\\\Orm\\\\Seller",
//           "title": "حنب بيتك",
//           "price": 2000,
//           "status": "active",
//           "deleted_at": null,
//           "created_at": "2020-12-05T22:53:00.000000Z",
//           "updated_at": "2020-12-05T22:53:00.000000Z"
//         },
//         [
//
//         ]
//       ],
//       "images": [
//         {
//           "id": 27,
//           "product_id": 6,
//           "image": "https://api.alaalimshop.com/public/storage/products/cSMrGmpIXxUBlXCUvAYeHVhR0Yr8RLrRIyAg0fIX.jpg",
//           "created_at": "2020-12-05T08:01:20.000000Z",
//           "updated_at": "2020-12-05T08:01:20.000000Z"
//         },
//         {
//           "id": 28,
//           "product_id": 6,
//           "image": "https://api.alaalimshop.com/public/storage/products/fT1bP8praAZEHw1Mjv1v2OWvXkPYHJ3uL9s5199L.jpg",
//           "created_at": "2020-12-05T08:01:20.000000Z",
//           "updated_at": "2020-12-05T08:01:20.000000Z"
//         },
//         {
//           "id": 29,
//           "product_id": 6,
//           "image": "https://api.alaalimshop.com/public/storage/products/825JAnW5vmYkTdouOts1nZJwmSbt2YfBaPsOJ2at.jpg",
//           "created_at": "2020-12-05T08:01:20.000000Z",
//           "updated_at": "2020-12-05T08:01:20.000000Z"
//         },
//         {
//           "id": 30,
//           "product_id": 6,
//           "image": "https://api.alaalimshop.com/public/storage/products/mIH9rmGeLor2mmR8chES4ZZLtaMVDQ8K3MGazNEy.jpg",
//           "created_at": "2020-12-05T08:01:20.000000Z",
//           "updated_at": "2020-12-05T08:01:20.000000Z"
//         }
//       ],
//       "category": {
//         "id": 4,
//         "parent_id": 2,
//         "title": "test",
//         "description": "test",
//         "products_images_count": 4,
//         "image": "https://api.alaalimshop.com/public/storage/categories/AzjPL2yu4pT26NbaAOcqZnb024OkV2aYnpO7O2tB.png",
//         "products_count": 2,
//         "deleted_at": null,
//         "created_at": "2020-12-03T09:18:54.000000Z",
//         "updated_at": "2020-12-05T08:01:20.000000Z",
//         "parent_name": "clothes",
//         "parent": {
//           "id": 2,
//           "parent_id": 0,
//           "title": "clothes",
//           "description": "Iste dicta ipsa exercitationem sunt ea tempore.",
//           "products_images_count": 6,
//           "image": "https://api.alaalimshop.com/public/storage/categories/2M1HVkEBFfTgUw0NeymFK1PI8LXpHH6pqE44OAlz.png",
//           "products_count": 2,
//           "deleted_at": null,
//           "created_at": "2020-12-02T09:19:14.000000Z",
//           "updated_at": "2020-12-05T15:18:15.000000Z",
//           "parent_name": "",
//           "parent": null
//         }
//       },
//       "seller": {
//         "id": 8,
//         "seller_id": 8,
//         "full_name": "almunzir99",
//         "shop_name": "DJ",
//         "shop_image": "https://api.alaalimshop.com/storage/app/public/shops/c1A2N8faeqJo1FEtNSeSefWhkRdZRnBJJ9ienwTS.jpg",
//         "phone_number": "0124647018",
//         "username": "almunzir99",
//         "contact_phone_number": "0124647018",
//         "email": null,
//         "identity": null,
//         "country_id": 887,
//         "state_id": 10,
//         "city": "none",
//         "area": null,
//         "lat": 15,
//         "lng": 45,
//         "status": "approved",
//         "allow_chatting": "on",
//         "display_contact_phone": "on",
//         "display_banking_info": "on",
//         "display_delivery_option": "on",
//         "display_products": "on",
//         "display_products_views": "on",
//         "display_other_branches": "on",
//         "display_products_prices": "on",
//         "display_news": 1,
//         "display_sliders": 1,
//         "activation_code": null,
//         "deleted_at": null,
//         "created_at": "2020-12-02T19:09:52.000000Z",
//         "updated_at": "2020-12-04T01:43:21.000000Z",
//         "additional_phone_numbers": [
//
//         ]
//       },
//       "fields": [
//
//       ],
//       "attributes": [
//         {
//           "id": 115,
//           "attribute_id": 3,
//           "attribute_value_id": 11,
//           "product_id": 6,
//           "deleted_at": null,
//           "created_at": "2020-12-05T08:01:20.000000Z",
//           "updated_at": "2020-12-05T08:01:20.000000Z",
//           "attribute": {
//             "id": 3,
//             "title": "Dr. Kellen Erdman PhD",
//             "deleted_at": null,
//             "created_at": "2020-12-02T09:19:06.000000Z",
//             "updated_at": "2020-12-02T09:19:06.000000Z"
//           },
//           "value": {
//             "id": 11,
//             "title": "Kevon Mitchell",
//             "attribute_id": 3,
//             "deleted_at": null,
//             "created_at": "2020-12-02T09:19:06.000000Z",
//             "updated_at": "2020-12-02T09:19:06.000000Z"
//           }
//         },
//         {
//           "id": 116,
//           "attribute_id": 3,
//           "attribute_value_id": 12,
//           "product_id": 6,
//           "deleted_at": null,
//           "created_at": "2020-12-05T08:01:20.000000Z",
//           "updated_at": "2020-12-05T08:01:20.000000Z",
//           "attribute": {
//             "id": 3,
//             "title": "Dr. Kellen Erdman PhD",
//             "deleted_at": null,
//             "created_at": "2020-12-02T09:19:06.000000Z",
//             "updated_at": "2020-12-02T09:19:06.000000Z"
//           },
//           "value": {
//             "id": 12,
//             "title": "Dr. Arden Batz",
//             "attribute_id": 3,
//             "deleted_at": null,
//             "created_at": "2020-12-02T09:19:06.000000Z",
//             "updated_at": "2020-12-02T09:19:06.000000Z"
//           }
//         },
//         {
//           "id": 118,
//           "attribute_id": 4,
//           "attribute_value_id": 16,
//           "product_id": 6,
//           "deleted_at": null,
//           "created_at": "2020-12-05T08:01:20.000000Z",
//           "updated_at": "2020-12-05T08:01:20.000000Z",
//           "attribute": {
//             "id": 4,
//             "title": "Neil Mayert",
//             "deleted_at": null,
//             "created_at": "2020-12-02T09:19:06.000000Z",
//             "updated_at": "2020-12-02T09:19:06.000000Z"
//           },
//           "value": {
//             "id": 16,
//             "title": "Paolo Bahringer",
//             "attribute_id": 4,
//             "deleted_at": null,
//             "created_at": "2020-12-02T09:19:06.000000Z",
//             "updated_at": "2020-12-02T09:19:06.000000Z"
//           }
//         },
//         {
//           "id": 119,
//           "attribute_id": 4,
//           "attribute_value_id": 17,
//           "product_id": 6,
//           "deleted_at": null,
//           "created_at": "2020-12-05T08:01:20.000000Z",
//           "updated_at": "2020-12-05T08:01:20.000000Z",
//           "attribute": {
//             "id": 4,
//             "title": "Neil Mayert",
//             "deleted_at": null,
//             "created_at": "2020-12-02T09:19:06.000000Z",
//             "updated_at": "2020-12-02T09:19:06.000000Z"
//           },
//           "value": {
//             "id": 17,
//             "title": "Prof. Macey Huels MD",
//             "attribute_id": 4,
//             "deleted_at": null,
//             "created_at": "2020-12-02T09:19:06.000000Z",
//             "updated_at": "2020-12-02T09:19:06.000000Z"
//           }
//         }
//       ]
//     },
//     {
//       "id": 5,
//       "category_id": 2,
//       "seller_id": 17,
//       "title": "Nulla culpa digniss",
//       "description": "Nulla laboris iusto",
//       "views": 0,
//       "orders_count": 0,
//       "available_qty": 0,
//       "price": 59,
//       "tax": 53,
//       "status": "active",
//       "deleted_at": null,
//       "created_at": "2020-12-04T20:11:58.000000Z",
//       "updated_at": "2020-12-04T20:11:58.000000Z",
//       "shipping_offers": [
//
//       ],
//       "images": [
//         {
//           "id": 21,
//           "product_id": 5,
//           "image": "https://api.alaalimshop.com/public/storage/products/mM8KRjLgsDSo4RR2HkFdVRWvTboYdDQXf01hDq1a.jpg",
//           "created_at": "2020-12-04T20:11:58.000000Z",
//           "updated_at": "2020-12-04T20:11:58.000000Z"
//         },
//         {
//           "id": 22,
//           "product_id": 5,
//           "image": "https://api.alaalimshop.com/public/storage/products/6W4VcGG2yq7bx02LSepLrrfJiymMt1QTwvl1LEa9.jpg",
//           "created_at": "2020-12-04T20:11:58.000000Z",
//           "updated_at": "2020-12-04T20:11:58.000000Z"
//         },
//         {
//           "id": 23,
//           "product_id": 5,
//           "image": "https://api.alaalimshop.com/public/storage/products/Pur9YBkp5X3DiMXBGrFs2jJPoCCpwMJ1GbIaicZI.jpg",
//           "created_at": "2020-12-04T20:11:58.000000Z",
//           "updated_at": "2020-12-04T20:11:58.000000Z"
//         },
//         {
//           "id": 24,
//           "product_id": 5,
//           "image": "https://api.alaalimshop.com/public/storage/products/Ov3UjrCmg5ZRRm9dYPMVgbQurf4zCKaUqKh7N7uU.jpg",
//           "created_at": "2020-12-04T20:11:58.000000Z",
//           "updated_at": "2020-12-04T20:11:58.000000Z"
//         },
//         {
//           "id": 25,
//           "product_id": 5,
//           "image": "https://api.alaalimshop.com/public/storage/products/o2xrqChqumAd5z4cEXbnWW4EoDymUEqSOZt6J1hV.jpg",
//           "created_at": "2020-12-04T20:11:58.000000Z",
//           "updated_at": "2020-12-04T20:11:58.000000Z"
//         },
//         {
//           "id": 26,
//           "product_id": 5,
//           "image": "https://api.alaalimshop.com/public/storage/products/HVMkCkRQnmPWSiaUdZQzfJCeWXl96cHtRLP221gY.jpg",
//           "created_at": "2020-12-04T20:11:58.000000Z",
//           "updated_at": "2020-12-04T20:11:58.000000Z"
//         }
//       ],
//       "category": {
//         "id": 2,
//         "parent_id": 0,
//         "title": "clothes",
//         "description": "Iste dicta ipsa exercitationem sunt ea tempore.",
//         "products_images_count": 6,
//         "image": "https://api.alaalimshop.com/public/storage/categories/2M1HVkEBFfTgUw0NeymFK1PI8LXpHH6pqE44OAlz.png",
//         "products_count": 2,
//         "deleted_at": null,
//         "created_at": "2020-12-02T09:19:14.000000Z",
//         "updated_at": "2020-12-05T15:18:15.000000Z",
//         "parent_name": "",
//         "parent": null
//       },
//       "seller": {
//         "id": 17,
//         "seller_id": true,
//         "full_name": "mazin",
//         "shop_name": "mezo_store",
//         "shop_image": "https://api.alaalimshop.com/storage/app/public/https://api.alaalimshop.com/storage/app/public/shops/XJAJBcoS6nt7goyOXTuF4465hOauQkw8KvJ2m668.png",
//         "phone_number": "1234567890",
//         "username": "jone",
//         "contact_phone_number": "1234567890",
//         "email": null,
//         "identity": null,
//         "country_id": 887,
//         "state_id": 4,
//         "city": "dgsdgfgfg",
//         "area": null,
//         "lat": 0,
//         "lng": 0,
//         "status": "approved",
//         "allow_chatting": "on",
//         "display_contact_phone": "on",
//         "display_banking_info": "on",
//         "display_delivery_option": "on",
//         "display_products": "on",
//         "display_products_views": "on",
//         "display_other_branches": "on",
//         "display_products_prices": "on",
//         "display_news": 1,
//         "display_sliders": 1,
//         "activation_code": null,
//         "deleted_at": null,
//         "created_at": "2020-12-04T20:05:27.000000Z",
//         "updated_at": "2020-12-04T20:05:27.000000Z",
//         "additional_phone_numbers": [
//
//         ]
//       },
//       "fields": [
//         {
//           "id": 16,
//           "product_id": 5,
//           "field_id": 6,
//           "value": "2",
//           "deleted_at": null,
//           "created_at": "2020-12-04T20:11:58.000000Z",
//           "updated_at": "2020-12-04T20:11:58.000000Z",
//           "field": {
//             "id": 6,
//             "category_id": 2,
//             "title": "Reba Bradtke",
//             "deleted_at": null,
//             "created_at": "2020-12-02T09:19:14.000000Z",
//             "updated_at": "2020-12-02T09:19:14.000000Z"
//           }
//         },
//         {
//           "id": 17,
//           "product_id": 5,
//           "field_id": 7,
//           "value": "3",
//           "deleted_at": null,
//           "created_at": "2020-12-04T20:11:58.000000Z",
//           "updated_at": "2020-12-04T20:11:58.000000Z",
//           "field": {
//             "id": 7,
//             "category_id": 2,
//             "title": "Johnnie VonRueden",
//             "deleted_at": null,
//             "created_at": "2020-12-02T09:19:14.000000Z",
//             "updated_at": "2020-12-02T09:19:14.000000Z"
//           }
//         },
//         {
//           "id": 18,
//           "product_id": 5,
//           "field_id": 8,
//           "value": "r",
//           "deleted_at": null,
//           "created_at": "2020-12-04T20:11:58.000000Z",
//           "updated_at": "2020-12-04T20:11:58.000000Z",
//           "field": {
//             "id": 8,
//             "category_id": 2,
//             "title": "Kolby Oberbrunner DVM",
//             "deleted_at": null,
//             "created_at": "2020-12-02T09:19:14.000000Z",
//             "updated_at": "2020-12-02T09:19:14.000000Z"
//           }
//         },
//         {
//           "id": 19,
//           "product_id": 5,
//           "field_id": 9,
//           "value": "f",
//           "deleted_at": null,
//           "created_at": "2020-12-04T20:11:58.000000Z",
//           "updated_at": "2020-12-04T20:11:58.000000Z",
//           "field": {
//             "id": 9,
//             "category_id": 2,
//             "title": "Waylon Donnelly",
//             "deleted_at": null,
//             "created_at": "2020-12-02T09:19:14.000000Z",
//             "updated_at": "2020-12-02T09:19:14.000000Z"
//           }
//         },
//         {
//           "id": 20,
//           "product_id": 5,
//           "field_id": 10,
//           "value": "e",
//           "deleted_at": null,
//           "created_at": "2020-12-04T20:11:58.000000Z",
//           "updated_at": "2020-12-04T20:11:58.000000Z",
//           "field": {
//             "id": 10,
//             "category_id": 2,
//             "title": "Tyrell Leffler V",
//             "deleted_at": null,
//             "created_at": "2020-12-02T09:19:14.000000Z",
//             "updated_at": "2020-12-02T09:19:14.000000Z"
//           }
//         }
//       ],
//       "attributes": [
//         {
//           "id": 39,
//           "attribute_id": 24,
//           "attribute_value_id": 116,
//           "product_id": 5,
//           "deleted_at": null,
//           "created_at": "2020-12-04T20:11:58.000000Z",
//           "updated_at": "2020-12-04T20:11:58.000000Z",
//           "attribute": {
//             "id": 24,
//             "title": "Savanna Sawayn",
//             "deleted_at": null,
//             "created_at": "2020-12-02T09:19:06.000000Z",
//             "updated_at": "2020-12-02T09:19:06.000000Z"
//           },
//           "value": {
//             "id": 116,
//             "title": "Prof. Rod Mueller",
//             "attribute_id": 24,
//             "deleted_at": null,
//             "created_at": "2020-12-02T09:19:07.000000Z",
//             "updated_at": "2020-12-02T09:19:07.000000Z"
//           }
//         },
//         {
//           "id": 40,
//           "attribute_id": 24,
//           "attribute_value_id": 118,
//           "product_id": 5,
//           "deleted_at": null,
//           "created_at": "2020-12-04T20:11:58.000000Z",
//           "updated_at": "2020-12-04T20:11:58.000000Z",
//           "attribute": {
//             "id": 24,
//             "title": "Savanna Sawayn",
//             "deleted_at": null,
//             "created_at": "2020-12-02T09:19:06.000000Z",
//             "updated_at": "2020-12-02T09:19:06.000000Z"
//           },
//           "value": {
//             "id": 118,
//             "title": "Prof. Deondre Mayert",
//             "attribute_id": 24,
//             "deleted_at": null,
//             "created_at": "2020-12-02T09:19:07.000000Z",
//             "updated_at": "2020-12-02T09:19:07.000000Z"
//           }
//         },
//         {
//           "id": 41,
//           "attribute_id": 24,
//           "attribute_value_id": 119,
//           "product_id": 5,
//           "deleted_at": null,
//           "created_at": "2020-12-04T20:11:58.000000Z",
//           "updated_at": "2020-12-04T20:11:58.000000Z",
//           "attribute": {
//             "id": 24,
//             "title": "Savanna Sawayn",
//             "deleted_at": null,
//             "created_at": "2020-12-02T09:19:06.000000Z",
//             "updated_at": "2020-12-02T09:19:06.000000Z"
//           },
//           "value": {
//             "id": 119,
//             "title": "Vivien Williamson",
//             "attribute_id": 24,
//             "deleted_at": null,
//             "created_at": "2020-12-02T09:19:07.000000Z",
//             "updated_at": "2020-12-02T09:19:07.000000Z"
//           }
//         },
//         {
//           "id": 42,
//           "attribute_id": 24,
//           "attribute_value_id": 120,
//           "product_id": 5,
//           "deleted_at": null,
//           "created_at": "2020-12-04T20:11:58.000000Z",
//           "updated_at": "2020-12-04T20:11:58.000000Z",
//           "attribute": {
//             "id": 24,
//             "title": "Savanna Sawayn",
//             "deleted_at": null,
//             "created_at": "2020-12-02T09:19:06.000000Z",
//             "updated_at": "2020-12-02T09:19:06.000000Z"
//           },
//           "value": {
//             "id": 120,
//             "title": "Ms. Daniela Kris I",
//             "attribute_id": 24,
//             "deleted_at": null,
//             "created_at": "2020-12-02T09:19:07.000000Z",
//             "updated_at": "2020-12-02T09:19:07.000000Z"
//           }
//         },
//         {
//           "id": 43,
//           "attribute_id": 11,
//           "attribute_value_id": 55,
//           "product_id": 5,
//           "deleted_at": null,
//           "created_at": "2020-12-04T20:11:58.000000Z",
//           "updated_at": "2020-12-04T20:11:58.000000Z",
//           "attribute": {
//             "id": 11,
//             "title": "Jackson Berge",
//             "deleted_at": null,
//             "created_at": "2020-12-02T09:19:06.000000Z",
//             "updated_at": "2020-12-02T09:19:06.000000Z"
//           },
//           "value": {
//             "id": 55,
//             "title": "Dr. Broderick Prosacco DVM",
//             "attribute_id": 11,
//             "deleted_at": null,
//             "created_at": "2020-12-02T09:19:07.000000Z",
//             "updated_at": "2020-12-02T09:19:07.000000Z"
//           }
//         },
//         {
//           "id": 44,
//           "attribute_id": 15,
//           "attribute_value_id": 72,
//           "product_id": 5,
//           "deleted_at": null,
//           "created_at": "2020-12-04T20:11:58.000000Z",
//           "updated_at": "2020-12-04T20:11:58.000000Z",
//           "attribute": {
//             "id": 15,
//             "title": "Christy Carroll",
//             "deleted_at": null,
//             "created_at": "2020-12-02T09:19:06.000000Z",
//             "updated_at": "2020-12-02T09:19:06.000000Z"
//           },
//           "value": {
//             "id": 72,
//             "title": "Elmira Mayert",
//             "attribute_id": 15,
//             "deleted_at": null,
//             "created_at": "2020-12-02T09:19:07.000000Z",
//             "updated_at": "2020-12-02T09:19:07.000000Z"
//           }
//         },
//         {
//           "id": 45,
//           "attribute_id": 15,
//           "attribute_value_id": 73,
//           "product_id": 5,
//           "deleted_at": null,
//           "created_at": "2020-12-04T20:11:58.000000Z",
//           "updated_at": "2020-12-04T20:11:58.000000Z",
//           "attribute": {
//             "id": 15,
//             "title": "Christy Carroll",
//             "deleted_at": null,
//             "created_at": "2020-12-02T09:19:06.000000Z",
//             "updated_at": "2020-12-02T09:19:06.000000Z"
//           },
//           "value": {
//             "id": 73,
//             "title": "Hoyt White",
//             "attribute_id": 15,
//             "deleted_at": null,
//             "created_at": "2020-12-02T09:19:07.000000Z",
//             "updated_at": "2020-12-02T09:19:07.000000Z"
//           }
//         },
//         {
//           "id": 46,
//           "attribute_id": 15,
//           "attribute_value_id": 75,
//           "product_id": 5,
//           "deleted_at": null,
//           "created_at": "2020-12-04T20:11:58.000000Z",
//           "updated_at": "2020-12-04T20:11:58.000000Z",
//           "attribute": {
//             "id": 15,
//             "title": "Christy Carroll",
//             "deleted_at": null,
//             "created_at": "2020-12-02T09:19:06.000000Z",
//             "updated_at": "2020-12-02T09:19:06.000000Z"
//           },
//           "value": {
//             "id": 75,
//             "title": "Kristopher Adams",
//             "attribute_id": 15,
//             "deleted_at": null,
//             "created_at": "2020-12-02T09:19:07.000000Z",
//             "updated_at": "2020-12-02T09:19:07.000000Z"
//           }
//         },
//         {
//           "id": 47,
//           "attribute_id": 12,
//           "attribute_value_id": 56,
//           "product_id": 5,
//           "deleted_at": null,
//           "created_at": "2020-12-04T20:11:58.000000Z",
//           "updated_at": "2020-12-04T20:11:58.000000Z",
//           "attribute": {
//             "id": 12,
//             "title": "Wilma Feest",
//             "deleted_at": null,
//             "created_at": "2020-12-02T09:19:06.000000Z",
//             "updated_at": "2020-12-02T09:19:06.000000Z"
//           },
//           "value": {
//             "id": 56,
//             "title": "Shaina Graham",
//             "attribute_id": 12,
//             "deleted_at": null,
//             "created_at": "2020-12-02T09:19:07.000000Z",
//             "updated_at": "2020-12-02T09:19:07.000000Z"
//           }
//         },
//         {
//           "id": 48,
//           "attribute_id": 12,
//           "attribute_value_id": 58,
//           "product_id": 5,
//           "deleted_at": null,
//           "created_at": "2020-12-04T20:11:58.000000Z",
//           "updated_at": "2020-12-04T20:11:58.000000Z",
//           "attribute": {
//             "id": 12,
//             "title": "Wilma Feest",
//             "deleted_at": null,
//             "created_at": "2020-12-02T09:19:06.000000Z",
//             "updated_at": "2020-12-02T09:19:06.000000Z"
//           },
//           "value": {
//             "id": 58,
//             "title": "Orlando Hackett",
//             "attribute_id": 12,
//             "deleted_at": null,
//             "created_at": "2020-12-02T09:19:07.000000Z",
//             "updated_at": "2020-12-02T09:19:07.000000Z"
//           }
//         },
//         {
//           "id": 49,
//           "attribute_id": 12,
//           "attribute_value_id": 59,
//           "product_id": 5,
//           "deleted_at": null,
//           "created_at": "2020-12-04T20:11:58.000000Z",
//           "updated_at": "2020-12-04T20:11:58.000000Z",
//           "attribute": {
//             "id": 12,
//             "title": "Wilma Feest",
//             "deleted_at": null,
//             "created_at": "2020-12-02T09:19:06.000000Z",
//             "updated_at": "2020-12-02T09:19:06.000000Z"
//           },
//           "value": {
//             "id": 59,
//             "title": "Jaleel Spinka",
//             "attribute_id": 12,
//             "deleted_at": null,
//             "created_at": "2020-12-02T09:19:07.000000Z",
//             "updated_at": "2020-12-02T09:19:07.000000Z"
//           }
//         },
//         {
//           "id": 50,
//           "attribute_id": 12,
//           "attribute_value_id": 60,
//           "product_id": 5,
//           "deleted_at": null,
//           "created_at": "2020-12-04T20:11:58.000000Z",
//           "updated_at": "2020-12-04T20:11:58.000000Z",
//           "attribute": {
//             "id": 12,
//             "title": "Wilma Feest",
//             "deleted_at": null,
//             "created_at": "2020-12-02T09:19:06.000000Z",
//             "updated_at": "2020-12-02T09:19:06.000000Z"
//           },
//           "value": {
//             "id": 60,
//             "title": "Westley Denesik",
//             "attribute_id": 12,
//             "deleted_at": null,
//             "created_at": "2020-12-02T09:19:07.000000Z",
//             "updated_at": "2020-12-02T09:19:07.000000Z"
//           }
//         },
//         {
//           "id": 51,
//           "attribute_id": 17,
//           "attribute_value_id": 83,
//           "product_id": 5,
//           "deleted_at": null,
//           "created_at": "2020-12-04T20:11:58.000000Z",
//           "updated_at": "2020-12-04T20:11:58.000000Z",
//           "attribute": {
//             "id": 17,
//             "title": "Jerod Schmidt III",
//             "deleted_at": null,
//             "created_at": "2020-12-02T09:19:06.000000Z",
//             "updated_at": "2020-12-02T09:19:06.000000Z"
//           },
//           "value": {
//             "id": 83,
//             "title": "Catalina Sauer",
//             "attribute_id": 17,
//             "deleted_at": null,
//             "created_at": "2020-12-02T09:19:07.000000Z",
//             "updated_at": "2020-12-02T09:19:07.000000Z"
//           }
//         },
//         {
//           "id": 52,
//           "attribute_id": 19,
//           "attribute_value_id": 93,
//           "product_id": 5,
//           "deleted_at": null,
//           "created_at": "2020-12-04T20:11:58.000000Z",
//           "updated_at": "2020-12-04T20:11:58.000000Z",
//           "attribute": {
//             "id": 19,
//             "title": "Mrs. Wanda Jones I",
//             "deleted_at": null,
//             "created_at": "2020-12-02T09:19:06.000000Z",
//             "updated_at": "2020-12-02T09:19:06.000000Z"
//           },
//           "value": {
//             "id": 93,
//             "title": "Erica Muller V",
//             "attribute_id": 19,
//             "deleted_at": null,
//             "created_at": "2020-12-02T09:19:07.000000Z",
//             "updated_at": "2020-12-02T09:19:07.000000Z"
//           }
//         },
//         {
//           "id": 53,
//           "attribute_id": 17,
//           "attribute_value_id": 82,
//           "product_id": 5,
//           "deleted_at": null,
//           "created_at": "2020-12-04T20:11:58.000000Z",
//           "updated_at": "2020-12-04T20:11:58.000000Z",
//           "attribute": {
//             "id": 17,
//             "title": "Jerod Schmidt III",
//             "deleted_at": null,
//             "created_at": "2020-12-02T09:19:06.000000Z",
//             "updated_at": "2020-12-02T09:19:06.000000Z"
//           },
//           "value": {
//             "id": 82,
//             "title": "Miss Joanny Hamill IV",
//             "attribute_id": 17,
//             "deleted_at": null,
//             "created_at": "2020-12-02T09:19:07.000000Z",
//             "updated_at": "2020-12-02T09:19:07.000000Z"
//           }
//         },
//         {
//           "id": 54,
//           "attribute_id": 22,
//           "attribute_value_id": 107,
//           "product_id": 5,
//           "deleted_at": null,
//           "created_at": "2020-12-04T20:11:58.000000Z",
//           "updated_at": "2020-12-04T20:11:58.000000Z",
//           "attribute": {
//             "id": 22,
//             "title": "Dr. Alicia Walter III",
//             "deleted_at": null,
//             "created_at": "2020-12-02T09:19:06.000000Z",
//             "updated_at": "2020-12-02T09:19:06.000000Z"
//           },
//           "value": {
//             "id": 107,
//             "title": "Amber Hill",
//             "attribute_id": 22,
//             "deleted_at": null,
//             "created_at": "2020-12-02T09:19:07.000000Z",
//             "updated_at": "2020-12-02T09:19:07.000000Z"
//           }
//         },
//         {
//           "id": 55,
//           "attribute_id": 22,
//           "attribute_value_id": 110,
//           "product_id": 5,
//           "deleted_at": null,
//           "created_at": "2020-12-04T20:11:58.000000Z",
//           "updated_at": "2020-12-04T20:11:58.000000Z",
//           "attribute": {
//             "id": 22,
//             "title": "Dr. Alicia Walter III",
//             "deleted_at": null,
//             "created_at": "2020-12-02T09:19:06.000000Z",
//             "updated_at": "2020-12-02T09:19:06.000000Z"
//           },
//           "value": {
//             "id": 110,
//             "title": "Susana D'Amore",
//             "attribute_id": 22,
//             "deleted_at": null,
//             "created_at": "2020-12-02T09:19:07.000000Z",
//             "updated_at": "2020-12-02T09:19:07.000000Z"
//           }
//         },
//         {
//           "id": 56,
//           "attribute_id": 21,
//           "attribute_value_id": 101,
//           "product_id": 5,
//           "deleted_at": null,
//           "created_at": "2020-12-04T20:11:58.000000Z",
//           "updated_at": "2020-12-04T20:11:58.000000Z",
//           "attribute": {
//             "id": 21,
//             "title": "Mr. Deondre Kuhn Sr.",
//             "deleted_at": null,
//             "created_at": "2020-12-02T09:19:06.000000Z",
//             "updated_at": "2020-12-02T09:19:06.000000Z"
//           },
//           "value": {
//             "id": 101,
//             "title": "Ellis Gibson",
//             "attribute_id": 21,
//             "deleted_at": null,
//             "created_at": "2020-12-02T09:19:07.000000Z",
//             "updated_at": "2020-12-02T09:19:07.000000Z"
//           }
//         },
//         {
//           "id": 57,
//           "attribute_id": 21,
//           "attribute_value_id": 102,
//           "product_id": 5,
//           "deleted_at": null,
//           "created_at": "2020-12-04T20:11:58.000000Z",
//           "updated_at": "2020-12-04T20:11:58.000000Z",
//           "attribute": {
//             "id": 21,
//             "title": "Mr. Deondre Kuhn Sr.",
//             "deleted_at": null,
//             "created_at": "2020-12-02T09:19:06.000000Z",
//             "updated_at": "2020-12-02T09:19:06.000000Z"
//           },
//           "value": {
//             "id": 102,
//             "title": "Prof. Herminia Denesik MD",
//             "attribute_id": 21,
//             "deleted_at": null,
//             "created_at": "2020-12-02T09:19:07.000000Z",
//             "updated_at": "2020-12-02T09:19:07.000000Z"
//           }
//         },
//         {
//           "id": 58,
//           "attribute_id": 21,
//           "attribute_value_id": 105,
//           "product_id": 5,
//           "deleted_at": null,
//           "created_at": "2020-12-04T20:11:58.000000Z",
//           "updated_at": "2020-12-04T20:11:58.000000Z",
//           "attribute": {
//             "id": 21,
//             "title": "Mr. Deondre Kuhn Sr.",
//             "deleted_at": null,
//             "created_at": "2020-12-02T09:19:06.000000Z",
//             "updated_at": "2020-12-02T09:19:06.000000Z"
//           },
//           "value": {
//             "id": 105,
//             "title": "Donavon Heathcote",
//             "attribute_id": 21,
//             "deleted_at": null,
//             "created_at": "2020-12-02T09:19:07.000000Z",
//             "updated_at": "2020-12-02T09:19:07.000000Z"
//           }
//         },
//         {
//           "id": 59,
//           "attribute_id": 23,
//           "attribute_value_id": 114,
//           "product_id": 5,
//           "deleted_at": null,
//           "created_at": "2020-12-04T20:11:58.000000Z",
//           "updated_at": "2020-12-04T20:11:58.000000Z",
//           "attribute": {
//             "id": 23,
//             "title": "Mr. Dominic Gutkowski",
//             "deleted_at": null,
//             "created_at": "2020-12-02T09:19:06.000000Z",
//             "updated_at": "2020-12-02T09:19:06.000000Z"
//           },
//           "value": {
//             "id": 114,
//             "title": "Jan Batz",
//             "attribute_id": 23,
//             "deleted_at": null,
//             "created_at": "2020-12-02T09:19:07.000000Z",
//             "updated_at": "2020-12-02T09:19:07.000000Z"
//           }
//         },
//         {
//           "id": 60,
//           "attribute_id": 8,
//           "attribute_value_id": 36,
//           "product_id": 5,
//           "deleted_at": null,
//           "created_at": "2020-12-04T20:11:58.000000Z",
//           "updated_at": "2020-12-04T20:11:58.000000Z",
//           "attribute": {
//             "id": 8,
//             "title": "Ross Gulgowski",
//             "deleted_at": null,
//             "created_at": "2020-12-02T09:19:06.000000Z",
//             "updated_at": "2020-12-02T09:19:06.000000Z"
//           },
//           "value": {
//             "id": 36,
//             "title": "Garett Goyette",
//             "attribute_id": 8,
//             "deleted_at": null,
//             "created_at": "2020-12-02T09:19:06.000000Z",
//             "updated_at": "2020-12-02T09:19:06.000000Z"
//           }
//         },
//         {
//           "id": 61,
//           "attribute_id": 8,
//           "attribute_value_id": 38,
//           "product_id": 5,
//           "deleted_at": null,
//           "created_at": "2020-12-04T20:11:58.000000Z",
//           "updated_at": "2020-12-04T20:11:58.000000Z",
//           "attribute": {
//             "id": 8,
//             "title": "Ross Gulgowski",
//             "deleted_at": null,
//             "created_at": "2020-12-02T09:19:06.000000Z",
//             "updated_at": "2020-12-02T09:19:06.000000Z"
//           },
//           "value": {
//             "id": 38,
//             "title": "Karl Steuber DVM",
//             "attribute_id": 8,
//             "deleted_at": null,
//             "created_at": "2020-12-02T09:19:06.000000Z",
//             "updated_at": "2020-12-02T09:19:06.000000Z"
//           }
//         },
//         {
//           "id": 62,
//           "attribute_id": 8,
//           "attribute_value_id": 40,
//           "product_id": 5,
//           "deleted_at": null,
//           "created_at": "2020-12-04T20:11:58.000000Z",
//           "updated_at": "2020-12-04T20:11:58.000000Z",
//           "attribute": {
//             "id": 8,
//             "title": "Ross Gulgowski",
//             "deleted_at": null,
//             "created_at": "2020-12-02T09:19:06.000000Z",
//             "updated_at": "2020-12-02T09:19:06.000000Z"
//           },
//           "value": {
//             "id": 40,
//             "title": "Matilde O'Keefe",
//             "attribute_id": 8,
//             "deleted_at": null,
//             "created_at": "2020-12-02T09:19:07.000000Z",
//             "updated_at": "2020-12-02T09:19:07.000000Z"
//           }
//         },
//         {
//           "id": 63,
//           "attribute_id": 25,
//           "attribute_value_id": 121,
//           "product_id": 5,
//           "deleted_at": null,
//           "created_at": "2020-12-04T20:11:58.000000Z",
//           "updated_at": "2020-12-04T20:11:58.000000Z",
//           "attribute": {
//             "id": 25,
//             "title": "Precious Treutel",
//             "deleted_at": null,
//             "created_at": "2020-12-02T09:19:06.000000Z",
//             "updated_at": "2020-12-02T09:19:06.000000Z"
//           },
//           "value": {
//             "id": 121,
//             "title": "Miss Marquise Okuneva",
//             "attribute_id": 25,
//             "deleted_at": null,
//             "created_at": "2020-12-02T09:19:07.000000Z",
//             "updated_at": "2020-12-02T09:19:07.000000Z"
//           }
//         },
//         {
//           "id": 64,
//           "attribute_id": 25,
//           "attribute_value_id": 122,
//           "product_id": 5,
//           "deleted_at": null,
//           "created_at": "2020-12-04T20:11:58.000000Z",
//           "updated_at": "2020-12-04T20:11:58.000000Z",
//           "attribute": {
//             "id": 25,
//             "title": "Precious Treutel",
//             "deleted_at": null,
//             "created_at": "2020-12-02T09:19:06.000000Z",
//             "updated_at": "2020-12-02T09:19:06.000000Z"
//           },
//           "value": {
//             "id": 122,
//             "title": "Kariane Larson",
//             "attribute_id": 25,
//             "deleted_at": null,
//             "created_at": "2020-12-02T09:19:08.000000Z",
//             "updated_at": "2020-12-02T09:19:08.000000Z"
//           }
//         },
//         {
//           "id": 65,
//           "attribute_id": 25,
//           "attribute_value_id": 123,
//           "product_id": 5,
//           "deleted_at": null,
//           "created_at": "2020-12-04T20:11:58.000000Z",
//           "updated_at": "2020-12-04T20:11:58.000000Z",
//           "attribute": {
//             "id": 25,
//             "title": "Precious Treutel",
//             "deleted_at": null,
//             "created_at": "2020-12-02T09:19:06.000000Z",
//             "updated_at": "2020-12-02T09:19:06.000000Z"
//           },
//           "value": {
//             "id": 123,
//             "title": "Koby Funk",
//             "attribute_id": 25,
//             "deleted_at": null,
//             "created_at": "2020-12-02T09:19:08.000000Z",
//             "updated_at": "2020-12-02T09:19:08.000000Z"
//           }
//         },
//         {
//           "id": 66,
//           "attribute_id": 25,
//           "attribute_value_id": 125,
//           "product_id": 5,
//           "deleted_at": null,
//           "created_at": "2020-12-04T20:11:58.000000Z",
//           "updated_at": "2020-12-04T20:11:58.000000Z",
//           "attribute": {
//             "id": 25,
//             "title": "Precious Treutel",
//             "deleted_at": null,
//             "created_at": "2020-12-02T09:19:06.000000Z",
//             "updated_at": "2020-12-02T09:19:06.000000Z"
//           },
//           "value": {
//             "id": 125,
//             "title": "Flavie Rodriguez",
//             "attribute_id": 25,
//             "deleted_at": null,
//             "created_at": "2020-12-02T09:19:08.000000Z",
//             "updated_at": "2020-12-02T09:19:08.000000Z"
//           }
//         },
//         {
//           "id": 67,
//           "attribute_id": 23,
//           "attribute_value_id": 112,
//           "product_id": 5,
//           "deleted_at": null,
//           "created_at": "2020-12-04T20:11:58.000000Z",
//           "updated_at": "2020-12-04T20:11:58.000000Z",
//           "attribute": {
//             "id": 23,
//             "title": "Mr. Dominic Gutkowski",
//             "deleted_at": null,
//             "created_at": "2020-12-02T09:19:06.000000Z",
//             "updated_at": "2020-12-02T09:19:06.000000Z"
//           },
//           "value": {
//             "id": 112,
//             "title": "Harmony Hodkiewicz",
//             "attribute_id": 23,
//             "deleted_at": null,
//             "created_at": "2020-12-02T09:19:07.000000Z",
//             "updated_at": "2020-12-02T09:19:07.000000Z"
//           }
//         },
//         {
//           "id": 68,
//           "attribute_id": 23,
//           "attribute_value_id": 113,
//           "product_id": 5,
//           "deleted_at": null,
//           "created_at": "2020-12-04T20:11:58.000000Z",
//           "updated_at": "2020-12-04T20:11:58.000000Z",
//           "attribute": {
//             "id": 23,
//             "title": "Mr. Dominic Gutkowski",
//             "deleted_at": null,
//             "created_at": "2020-12-02T09:19:06.000000Z",
//             "updated_at": "2020-12-02T09:19:06.000000Z"
//           },
//           "value": {
//             "id": 113,
//             "title": "Prof. Vincenza Olson",
//             "attribute_id": 23,
//             "deleted_at": null,
//             "created_at": "2020-12-02T09:19:07.000000Z",
//             "updated_at": "2020-12-02T09:19:07.000000Z"
//           }
//         },
//         {
//           "id": 69,
//           "attribute_id": 23,
//           "attribute_value_id": 115,
//           "product_id": 5,
//           "deleted_at": null,
//           "created_at": "2020-12-04T20:11:58.000000Z",
//           "updated_at": "2020-12-04T20:11:58.000000Z",
//           "attribute": {
//             "id": 23,
//             "title": "Mr. Dominic Gutkowski",
//             "deleted_at": null,
//             "created_at": "2020-12-02T09:19:06.000000Z",
//             "updated_at": "2020-12-02T09:19:06.000000Z"
//           },
//           "value": {
//             "id": 115,
//             "title": "Shakira Reinger",
//             "attribute_id": 23,
//             "deleted_at": null,
//             "created_at": "2020-12-02T09:19:07.000000Z",
//             "updated_at": "2020-12-02T09:19:07.000000Z"
//           }
//         },
//         {
//           "id": 70,
//           "attribute_id": 1,
//           "attribute_value_id": 1,
//           "product_id": 5,
//           "deleted_at": null,
//           "created_at": "2020-12-04T20:11:58.000000Z",
//           "updated_at": "2020-12-04T20:11:58.000000Z",
//           "attribute": {
//             "id": 1,
//             "title": "Colleen Langworth MD",
//             "deleted_at": null,
//             "created_at": "2020-12-02T09:19:06.000000Z",
//             "updated_at": "2020-12-02T09:19:06.000000Z"
//           },
//           "value": {
//             "id": 1,
//             "title": "Mohamed Donnelly",
//             "attribute_id": 1,
//             "deleted_at": null,
//             "created_at": "2020-12-02T09:19:06.000000Z",
//             "updated_at": "2020-12-02T09:19:06.000000Z"
//           }
//         },
//         {
//           "id": 71,
//           "attribute_id": 1,
//           "attribute_value_id": 2,
//           "product_id": 5,
//           "deleted_at": null,
//           "created_at": "2020-12-04T20:11:58.000000Z",
//           "updated_at": "2020-12-04T20:11:58.000000Z",
//           "attribute": {
//             "id": 1,
//             "title": "Colleen Langworth MD",
//             "deleted_at": null,
//             "created_at": "2020-12-02T09:19:06.000000Z",
//             "updated_at": "2020-12-02T09:19:06.000000Z"
//           },
//           "value": {
//             "id": 2,
//             "title": "Zola Aufderhar",
//             "attribute_id": 1,
//             "deleted_at": null,
//             "created_at": "2020-12-02T09:19:06.000000Z",
//             "updated_at": "2020-12-02T09:19:06.000000Z"
//           }
//         },
//         {
//           "id": 72,
//           "attribute_id": 1,
//           "attribute_value_id": 3,
//           "product_id": 5,
//           "deleted_at": null,
//           "created_at": "2020-12-04T20:11:58.000000Z",
//           "updated_at": "2020-12-04T20:11:58.000000Z",
//           "attribute": {
//             "id": 1,
//             "title": "Colleen Langworth MD",
//             "deleted_at": null,
//             "created_at": "2020-12-02T09:19:06.000000Z",
//             "updated_at": "2020-12-02T09:19:06.000000Z"
//           },
//           "value": {
//             "id": 3,
//             "title": "Flossie Glover",
//             "attribute_id": 1,
//             "deleted_at": null,
//             "created_at": "2020-12-02T09:19:06.000000Z",
//             "updated_at": "2020-12-02T09:19:06.000000Z"
//           }
//         },
//         {
//           "id": 73,
//           "attribute_id": 1,
//           "attribute_value_id": 5,
//           "product_id": 5,
//           "deleted_at": null,
//           "created_at": "2020-12-04T20:11:58.000000Z",
//           "updated_at": "2020-12-04T20:11:58.000000Z",
//           "attribute": {
//             "id": 1,
//             "title": "Colleen Langworth MD",
//             "deleted_at": null,
//             "created_at": "2020-12-02T09:19:06.000000Z",
//             "updated_at": "2020-12-02T09:19:06.000000Z"
//           },
//           "value": {
//             "id": 5,
//             "title": "Prof. Raven Goodwin",
//             "attribute_id": 1,
//             "deleted_at": null,
//             "created_at": "2020-12-02T09:19:06.000000Z",
//             "updated_at": "2020-12-02T09:19:06.000000Z"
//           }
//         },
//         {
//           "id": 74,
//           "attribute_id": 10,
//           "attribute_value_id": 46,
//           "product_id": 5,
//           "deleted_at": null,
//           "created_at": "2020-12-04T20:11:58.000000Z",
//           "updated_at": "2020-12-04T20:11:58.000000Z",
//           "attribute": {
//             "id": 10,
//             "title": "Idella Mosciski",
//             "deleted_at": null,
//             "created_at": "2020-12-02T09:19:06.000000Z",
//             "updated_at": "2020-12-02T09:19:06.000000Z"
//           },
//           "value": {
//             "id": 46,
//             "title": "Dr. Eldora Schuppe",
//             "attribute_id": 10,
//             "deleted_at": null,
//             "created_at": "2020-12-02T09:19:07.000000Z",
//             "updated_at": "2020-12-02T09:19:07.000000Z"
//           }
//         },
//         {
//           "id": 75,
//           "attribute_id": 10,
//           "attribute_value_id": 47,
//           "product_id": 5,
//           "deleted_at": null,
//           "created_at": "2020-12-04T20:11:58.000000Z",
//           "updated_at": "2020-12-04T20:11:58.000000Z",
//           "attribute": {
//             "id": 10,
//             "title": "Idella Mosciski",
//             "deleted_at": null,
//             "created_at": "2020-12-02T09:19:06.000000Z",
//             "updated_at": "2020-12-02T09:19:06.000000Z"
//           },
//           "value": {
//             "id": 47,
//             "title": "Kali Krajcik",
//             "attribute_id": 10,
//             "deleted_at": null,
//             "created_at": "2020-12-02T09:19:07.000000Z",
//             "updated_at": "2020-12-02T09:19:07.000000Z"
//           }
//         },
//         {
//           "id": 76,
//           "attribute_id": 10,
//           "attribute_value_id": 48,
//           "product_id": 5,
//           "deleted_at": null,
//           "created_at": "2020-12-04T20:11:58.000000Z",
//           "updated_at": "2020-12-04T20:11:58.000000Z",
//           "attribute": {
//             "id": 10,
//             "title": "Idella Mosciski",
//             "deleted_at": null,
//             "created_at": "2020-12-02T09:19:06.000000Z",
//             "updated_at": "2020-12-02T09:19:06.000000Z"
//           },
//           "value": {
//             "id": 48,
//             "title": "Leslie Jacobi",
//             "attribute_id": 10,
//             "deleted_at": null,
//             "created_at": "2020-12-02T09:19:07.000000Z",
//             "updated_at": "2020-12-02T09:19:07.000000Z"
//           }
//         },
//         {
//           "id": 77,
//           "attribute_id": 3,
//           "attribute_value_id": 12,
//           "product_id": 5,
//           "deleted_at": null,
//           "created_at": "2020-12-04T20:11:58.000000Z",
//           "updated_at": "2020-12-04T20:11:58.000000Z",
//           "attribute": {
//             "id": 3,
//             "title": "Dr. Kellen Erdman PhD",
//             "deleted_at": null,
//             "created_at": "2020-12-02T09:19:06.000000Z",
//             "updated_at": "2020-12-02T09:19:06.000000Z"
//           },
//           "value": {
//             "id": 12,
//             "title": "Dr. Arden Batz",
//             "attribute_id": 3,
//             "deleted_at": null,
//             "created_at": "2020-12-02T09:19:06.000000Z",
//             "updated_at": "2020-12-02T09:19:06.000000Z"
//           }
//         },
//         {
//           "id": 78,
//           "attribute_id": 5,
//           "attribute_value_id": 21,
//           "product_id": 5,
//           "deleted_at": null,
//           "created_at": "2020-12-04T20:11:58.000000Z",
//           "updated_at": "2020-12-04T20:11:58.000000Z",
//           "attribute": {
//             "id": 5,
//             "title": "Miss Ebba Murphy",
//             "deleted_at": null,
//             "created_at": "2020-12-02T09:19:06.000000Z",
//             "updated_at": "2020-12-02T09:19:06.000000Z"
//           },
//           "value": {
//             "id": 21,
//             "title": "Caroline Dach",
//             "attribute_id": 5,
//             "deleted_at": null,
//             "created_at": "2020-12-02T09:19:06.000000Z",
//             "updated_at": "2020-12-02T09:19:06.000000Z"
//           }
//         },
//         {
//           "id": 79,
//           "attribute_id": 5,
//           "attribute_value_id": 22,
//           "product_id": 5,
//           "deleted_at": null,
//           "created_at": "2020-12-04T20:11:58.000000Z",
//           "updated_at": "2020-12-04T20:11:58.000000Z",
//           "attribute": {
//             "id": 5,
//             "title": "Miss Ebba Murphy",
//             "deleted_at": null,
//             "created_at": "2020-12-02T09:19:06.000000Z",
//             "updated_at": "2020-12-02T09:19:06.000000Z"
//           },
//           "value": {
//             "id": 22,
//             "title": "Freddie Ward",
//             "attribute_id": 5,
//             "deleted_at": null,
//             "created_at": "2020-12-02T09:19:06.000000Z",
//             "updated_at": "2020-12-02T09:19:06.000000Z"
//           }
//         },
//         {
//           "id": 80,
//           "attribute_id": 5,
//           "attribute_value_id": 23,
//           "product_id": 5,
//           "deleted_at": null,
//           "created_at": "2020-12-04T20:11:58.000000Z",
//           "updated_at": "2020-12-04T20:11:58.000000Z",
//           "attribute": {
//             "id": 5,
//             "title": "Miss Ebba Murphy",
//             "deleted_at": null,
//             "created_at": "2020-12-02T09:19:06.000000Z",
//             "updated_at": "2020-12-02T09:19:06.000000Z"
//           },
//           "value": {
//             "id": 23,
//             "title": "Marquise Rippin",
//             "attribute_id": 5,
//             "deleted_at": null,
//             "created_at": "2020-12-02T09:19:06.000000Z",
//             "updated_at": "2020-12-02T09:19:06.000000Z"
//           }
//         },
//         {
//           "id": 81,
//           "attribute_id": 5,
//           "attribute_value_id": 25,
//           "product_id": 5,
//           "deleted_at": null,
//           "created_at": "2020-12-04T20:11:58.000000Z",
//           "updated_at": "2020-12-04T20:11:58.000000Z",
//           "attribute": {
//             "id": 5,
//             "title": "Miss Ebba Murphy",
//             "deleted_at": null,
//             "created_at": "2020-12-02T09:19:06.000000Z",
//             "updated_at": "2020-12-02T09:19:06.000000Z"
//           },
//           "value": {
//             "id": 25,
//             "title": "Nina Dicki DDS",
//             "attribute_id": 5,
//             "deleted_at": null,
//             "created_at": "2020-12-02T09:19:06.000000Z",
//             "updated_at": "2020-12-02T09:19:06.000000Z"
//           }
//         },
//         {
//           "id": 82,
//           "attribute_id": 7,
//           "attribute_value_id": 31,
//           "product_id": 5,
//           "deleted_at": null,
//           "created_at": "2020-12-04T20:11:58.000000Z",
//           "updated_at": "2020-12-04T20:11:58.000000Z",
//           "attribute": {
//             "id": 7,
//             "title": "Emerald Kihn",
//             "deleted_at": null,
//             "created_at": "2020-12-02T09:19:06.000000Z",
//             "updated_at": "2020-12-02T09:19:06.000000Z"
//           },
//           "value": {
//             "id": 31,
//             "title": "Dr. Tomasa Veum",
//             "attribute_id": 7,
//             "deleted_at": null,
//             "created_at": "2020-12-02T09:19:06.000000Z",
//             "updated_at": "2020-12-02T09:19:06.000000Z"
//           }
//         },
//         {
//           "id": 83,
//           "attribute_id": 7,
//           "attribute_value_id": 33,
//           "product_id": 5,
//           "deleted_at": null,
//           "created_at": "2020-12-04T20:11:58.000000Z",
//           "updated_at": "2020-12-04T20:11:58.000000Z",
//           "attribute": {
//             "id": 7,
//             "title": "Emerald Kihn",
//             "deleted_at": null,
//             "created_at": "2020-12-02T09:19:06.000000Z",
//             "updated_at": "2020-12-02T09:19:06.000000Z"
//           },
//           "value": {
//             "id": 33,
//             "title": "Ruby Grady",
//             "attribute_id": 7,
//             "deleted_at": null,
//             "created_at": "2020-12-02T09:19:06.000000Z",
//             "updated_at": "2020-12-02T09:19:06.000000Z"
//           }
//         },
//         {
//           "id": 84,
//           "attribute_id": 7,
//           "attribute_value_id": 34,
//           "product_id": 5,
//           "deleted_at": null,
//           "created_at": "2020-12-04T20:11:58.000000Z",
//           "updated_at": "2020-12-04T20:11:58.000000Z",
//           "attribute": {
//             "id": 7,
//             "title": "Emerald Kihn",
//             "deleted_at": null,
//             "created_at": "2020-12-02T09:19:06.000000Z",
//             "updated_at": "2020-12-02T09:19:06.000000Z"
//           },
//           "value": {
//             "id": 34,
//             "title": "Janice Daugherty",
//             "attribute_id": 7,
//             "deleted_at": null,
//             "created_at": "2020-12-02T09:19:06.000000Z",
//             "updated_at": "2020-12-02T09:19:06.000000Z"
//           }
//         },
//         {
//           "id": 85,
//           "attribute_id": 7,
//           "attribute_value_id": 35,
//           "product_id": 5,
//           "deleted_at": null,
//           "created_at": "2020-12-04T20:11:58.000000Z",
//           "updated_at": "2020-12-04T20:11:58.000000Z",
//           "attribute": {
//             "id": 7,
//             "title": "Emerald Kihn",
//             "deleted_at": null,
//             "created_at": "2020-12-02T09:19:06.000000Z",
//             "updated_at": "2020-12-02T09:19:06.000000Z"
//           },
//           "value": {
//             "id": 35,
//             "title": "Eileen Cremin PhD",
//             "attribute_id": 7,
//             "deleted_at": null,
//             "created_at": "2020-12-02T09:19:06.000000Z",
//             "updated_at": "2020-12-02T09:19:06.000000Z"
//           }
//         },
//         {
//           "id": 86,
//           "attribute_id": 9,
//           "attribute_value_id": 43,
//           "product_id": 5,
//           "deleted_at": null,
//           "created_at": "2020-12-04T20:11:58.000000Z",
//           "updated_at": "2020-12-04T20:11:58.000000Z",
//           "attribute": {
//             "id": 9,
//             "title": "Prof. Kiana Schoen",
//             "deleted_at": null,
//             "created_at": "2020-12-02T09:19:06.000000Z",
//             "updated_at": "2020-12-02T09:19:06.000000Z"
//           },
//           "value": {
//             "id": 43,
//             "title": "Prof. Alda Feil",
//             "attribute_id": 9,
//             "deleted_at": null,
//             "created_at": "2020-12-02T09:19:07.000000Z",
//             "updated_at": "2020-12-02T09:19:07.000000Z"
//           }
//         },
//         {
//           "id": 87,
//           "attribute_id": 14,
//           "attribute_value_id": 66,
//           "product_id": 5,
//           "deleted_at": null,
//           "created_at": "2020-12-04T20:11:58.000000Z",
//           "updated_at": "2020-12-04T20:11:58.000000Z",
//           "attribute": {
//             "id": 14,
//             "title": "Ms. Kimberly Klocko PhD",
//             "deleted_at": null,
//             "created_at": "2020-12-02T09:19:06.000000Z",
//             "updated_at": "2020-12-02T09:19:06.000000Z"
//           },
//           "value": {
//             "id": 66,
//             "title": "Jessy Rodriguez II",
//             "attribute_id": 14,
//             "deleted_at": null,
//             "created_at": "2020-12-02T09:19:07.000000Z",
//             "updated_at": "2020-12-02T09:19:07.000000Z"
//           }
//         },
//         {
//           "id": 88,
//           "attribute_id": 31,
//           "attribute_value_id": 151,
//           "product_id": 5,
//           "deleted_at": null,
//           "created_at": "2020-12-04T20:11:58.000000Z",
//           "updated_at": "2020-12-04T20:11:58.000000Z",
//           "attribute": {
//             "id": 31,
//             "title": "Ida Howell",
//             "deleted_at": null,
//             "created_at": "2020-12-02T09:19:06.000000Z",
//             "updated_at": "2020-12-02T09:19:06.000000Z"
//           },
//           "value": {
//             "id": 151,
//             "title": "Mrs. Bria Braun",
//             "attribute_id": 31,
//             "deleted_at": null,
//             "created_at": "2020-12-02T09:19:08.000000Z",
//             "updated_at": "2020-12-02T09:19:08.000000Z"
//           }
//         },
//         {
//           "id": 89,
//           "attribute_id": 31,
//           "attribute_value_id": 152,
//           "product_id": 5,
//           "deleted_at": null,
//           "created_at": "2020-12-04T20:11:58.000000Z",
//           "updated_at": "2020-12-04T20:11:58.000000Z",
//           "attribute": {
//             "id": 31,
//             "title": "Ida Howell",
//             "deleted_at": null,
//             "created_at": "2020-12-02T09:19:06.000000Z",
//             "updated_at": "2020-12-02T09:19:06.000000Z"
//           },
//           "value": {
//             "id": 152,
//             "title": "Josiane Rath",
//             "attribute_id": 31,
//             "deleted_at": null,
//             "created_at": "2020-12-02T09:19:08.000000Z",
//             "updated_at": "2020-12-02T09:19:08.000000Z"
//           }
//         },
//         {
//           "id": 90,
//           "attribute_id": 31,
//           "attribute_value_id": 154,
//           "product_id": 5,
//           "deleted_at": null,
//           "created_at": "2020-12-04T20:11:58.000000Z",
//           "updated_at": "2020-12-04T20:11:58.000000Z",
//           "attribute": {
//             "id": 31,
//             "title": "Ida Howell",
//             "deleted_at": null,
//             "created_at": "2020-12-02T09:19:06.000000Z",
//             "updated_at": "2020-12-02T09:19:06.000000Z"
//           },
//           "value": {
//             "id": 154,
//             "title": "Ms. Karolann Hammes IV",
//             "attribute_id": 31,
//             "deleted_at": null,
//             "created_at": "2020-12-02T09:19:08.000000Z",
//             "updated_at": "2020-12-02T09:19:08.000000Z"
//           }
//         },
//         {
//           "id": 91,
//           "attribute_id": 31,
//           "attribute_value_id": 155,
//           "product_id": 5,
//           "deleted_at": null,
//           "created_at": "2020-12-04T20:11:58.000000Z",
//           "updated_at": "2020-12-04T20:11:58.000000Z",
//           "attribute": {
//             "id": 31,
//             "title": "Ida Howell",
//             "deleted_at": null,
//             "created_at": "2020-12-02T09:19:06.000000Z",
//             "updated_at": "2020-12-02T09:19:06.000000Z"
//           },
//           "value": {
//             "id": 155,
//             "title": "Jaren Schiller",
//             "attribute_id": 31,
//             "deleted_at": null,
//             "created_at": "2020-12-02T09:19:08.000000Z",
//             "updated_at": "2020-12-02T09:19:08.000000Z"
//           }
//         },
//         {
//           "id": 92,
//           "attribute_id": 13,
//           "attribute_value_id": 62,
//           "product_id": 5,
//           "deleted_at": null,
//           "created_at": "2020-12-04T20:11:58.000000Z",
//           "updated_at": "2020-12-04T20:11:58.000000Z",
//           "attribute": {
//             "id": 13,
//             "title": "Florida Kemmer",
//             "deleted_at": null,
//             "created_at": "2020-12-02T09:19:06.000000Z",
//             "updated_at": "2020-12-02T09:19:06.000000Z"
//           },
//           "value": {
//             "id": 62,
//             "title": "Dr. Janessa Ziemann II",
//             "attribute_id": 13,
//             "deleted_at": null,
//             "created_at": "2020-12-02T09:19:07.000000Z",
//             "updated_at": "2020-12-02T09:19:07.000000Z"
//           }
//         }
//       ]
//     },
//     {
//       "id": 4,
//       "category_id": 2,
//       "seller_id": 16,
//       "title": "Dignissimos dolor di",
//       "description": "Magnam possimus ita",
//       "views": 0,
//       "orders_count": 2,
//       "available_qty": 0,
//       "price": 51,
//       "tax": 86,
//       "status": "active",
//       "deleted_at": null,
//       "created_at": "2020-12-04T20:04:00.000000Z",
//       "updated_at": "2020-12-05T21:23:25.000000Z",
//       "shipping_offers": [
//         {
//           "id": 4,
//           "shipper_id": 16,
//           "shipper_type": "App\\\\Orm\\\\Seller",
//           "title": "Velit et reprehender",
//           "price": 434,
//           "status": "active",
//           "deleted_at": null,
//           "created_at": "2020-12-05T09:00:02.000000Z",
//           "updated_at": "2020-12-05T09:00:02.000000Z"
//         }
//       ],
//       "images": [
//         {
//           "id": 15,
//           "product_id": 4,
//           "image": "https://api.alaalimshop.com/public/storage/products/7V2uzKAcaBibAcGqu3CHCnAz1V045dqyDWQzFk7p.jpg",
//           "created_at": "2020-12-04T20:04:00.000000Z",
//           "updated_at": "2020-12-04T20:04:00.000000Z"
//         },
//         {
//           "id": 16,
//           "product_id": 4,
//           "image": "https://api.alaalimshop.com/public/storage/products/cdyA2sSAhUFXGaxv8bcmuY73QWEZ8yNE6lXwPTC7.jpg",
//           "created_at": "2020-12-04T20:04:00.000000Z",
//           "updated_at": "2020-12-04T20:04:00.000000Z"
//         },
//         {
//           "id": 17,
//           "product_id": 4,
//           "image": "https://api.alaalimshop.com/public/storage/products/5tLhJAuaMV6ohDCAqCAJZQNZSPkQw7f5raMZ1t6g.jpg",
//           "created_at": "2020-12-04T20:04:00.000000Z",
//           "updated_at": "2020-12-04T20:04:00.000000Z"
//         },
//         {
//           "id": 18,
//           "product_id": 4,
//           "image": "https://api.alaalimshop.com/public/storage/products/QhLfoEkawXHELbWRa4uUwODYlHBLvFMjWN2R58cS.jpg",
//           "created_at": "2020-12-04T20:04:00.000000Z",
//           "updated_at": "2020-12-04T20:04:00.000000Z"
//         },
//         {
//           "id": 19,
//           "product_id": 4,
//           "image": "https://api.alaalimshop.com/public/storage/products/8wo09PYtD1kf5xswERdd67vRL2muuzII4W5xHEsP.jpg",
//           "created_at": "2020-12-04T20:04:00.000000Z",
//           "updated_at": "2020-12-04T20:04:00.000000Z"
//         },
//         {
//           "id": 20,
//           "product_id": 4,
//           "image": "https://api.alaalimshop.com/public/storage/products/oftcEKZm63D6Smf91iI5cClUPJ4QvKvtBVd7KGWj.jpg",
//           "created_at": "2020-12-04T20:04:00.000000Z",
//           "updated_at": "2020-12-04T20:04:00.000000Z"
//         }
//       ],
//       "category": {
//         "id": 2,
//         "parent_id": 0,
//         "title": "clothes",
//         "description": "Iste dicta ipsa exercitationem sunt ea tempore.",
//         "products_images_count": 6,
//         "image": "https://api.alaalimshop.com/public/storage/categories/2M1HVkEBFfTgUw0NeymFK1PI8LXpHH6pqE44OAlz.png",
//         "products_count": 2,
//         "deleted_at": null,
//         "created_at": "2020-12-02T09:19:14.000000Z",
//         "updated_at": "2020-12-05T15:18:15.000000Z",
//         "parent_name": "",
//         "parent": null
//       },
//       "seller": {
//         "id": 16,
//         "seller_id": 16,
//         "full_name": "mazin",
//         "shop_name": "mezo_store",
//         "shop_image": "https://api.alaalimshop.com/storage/app/public/shops/XJAJBcoS6nt7goyOXTuF4465hOauQkw8KvJ2m668.png",
//         "phone_number": "0987644322",
//         "username": "mezo",
//         "contact_phone_number": "0987644322",
//         "email": "mazin@app.com",
//         "identity": "fgfgfhfghh",
//         "country_id": 887,
//         "state_id": 4,
//         "city": "dgsdgfgfg",
//         "area": null,
//         "lat": 465657,
//         "lng": 343434,
//         "status": "approved",
//         "allow_chatting": "on",
//         "display_contact_phone": "on",
//         "display_banking_info": "on",
//         "display_delivery_option": "on",
//         "display_products": "on",
//         "display_products_views": "on",
//         "display_other_branches": "on",
//         "display_products_prices": "on",
//         "display_news": 1,
//         "display_sliders": 1,
//         "activation_code": null,
//         "deleted_at": null,
//         "created_at": "2020-12-04T20:01:06.000000Z",
//         "updated_at": "2020-12-04T20:01:06.000000Z",
//         "additional_phone_numbers": [
//
//         ]
//       },
//       "fields": [
//         {
//           "id": 11,
//           "product_id": 4,
//           "field_id": 6,
//           "value": "Ex expedita qui numq",
//           "deleted_at": null,
//           "created_at": "2020-12-04T20:04:00.000000Z",
//           "updated_at": "2020-12-04T20:04:00.000000Z",
//           "field": {
//             "id": 6,
//             "category_id": 2,
//             "title": "Reba Bradtke",
//             "deleted_at": null,
//             "created_at": "2020-12-02T09:19:14.000000Z",
//             "updated_at": "2020-12-02T09:19:14.000000Z"
//           }
//         },
//         {
//           "id": 12,
//           "product_id": 4,
//           "field_id": 7,
//           "value": "Esse quis esse dol",
//           "deleted_at": null,
//           "created_at": "2020-12-04T20:04:00.000000Z",
//           "updated_at": "2020-12-04T20:04:00.000000Z",
//           "field": {
//             "id": 7,
//             "category_id": 2,
//             "title": "Johnnie VonRueden",
//             "deleted_at": null,
//             "created_at": "2020-12-02T09:19:14.000000Z",
//             "updated_at": "2020-12-02T09:19:14.000000Z"
//           }
//         },
//         {
//           "id": 13,
//           "product_id": 4,
//           "field_id": 8,
//           "value": "Qui dolore vitae nem",
//           "deleted_at": null,
//           "created_at": "2020-12-04T20:04:00.000000Z",
//           "updated_at": "2020-12-04T20:04:00.000000Z",
//           "field": {
//             "id": 8,
//             "category_id": 2,
//             "title": "Kolby Oberbrunner DVM",
//             "deleted_at": null,
//             "created_at": "2020-12-02T09:19:14.000000Z",
//             "updated_at": "2020-12-02T09:19:14.000000Z"
//           }
//         },
//         {
//           "id": 14,
//           "product_id": 4,
//           "field_id": 9,
//           "value": "Aspernatur impedit",
//           "deleted_at": null,
//           "created_at": "2020-12-04T20:04:00.000000Z",
//           "updated_at": "2020-12-04T20:04:00.000000Z",
//           "field": {
//             "id": 9,
//             "category_id": 2,
//             "title": "Waylon Donnelly",
//             "deleted_at": null,
//             "created_at": "2020-12-02T09:19:14.000000Z",
//             "updated_at": "2020-12-02T09:19:14.000000Z"
//           }
//         },
//         {
//           "id": 15,
//           "product_id": 4,
//           "field_id": 10,
//           "value": "Ea quae voluptas qui",
//           "deleted_at": null,
//           "created_at": "2020-12-04T20:04:00.000000Z",
//           "updated_at": "2020-12-04T20:04:00.000000Z",
//           "field": {
//             "id": 10,
//             "category_id": 2,
//             "title": "Tyrell Leffler V",
//             "deleted_at": null,
//             "created_at": "2020-12-02T09:19:14.000000Z",
//             "updated_at": "2020-12-02T09:19:14.000000Z"
//           }
//         },
//         {
//           "id": 21,
//           "product_id": 4,
//           "field_id": 6,
//           "value": "efdffdf",
//           "deleted_at": null,
//           "created_at": "2020-12-04T22:34:35.000000Z",
//           "updated_at": "2020-12-04T22:34:35.000000Z",
//           "field": {
//             "id": 6,
//             "category_id": 2,
//             "title": "Reba Bradtke",
//             "deleted_at": null,
//             "created_at": "2020-12-02T09:19:14.000000Z",
//             "updated_at": "2020-12-02T09:19:14.000000Z"
//           }
//         },
//         {
//           "id": 22,
//           "product_id": 4,
//           "field_id": 6,
//           "value": "mazin",
//           "deleted_at": null,
//           "created_at": "2020-12-04T22:34:48.000000Z",
//           "updated_at": "2020-12-04T22:34:48.000000Z",
//           "field": {
//             "id": 6,
//             "category_id": 2,
//             "title": "Reba Bradtke",
//             "deleted_at": null,
//             "created_at": "2020-12-02T09:19:14.000000Z",
//             "updated_at": "2020-12-02T09:19:14.000000Z"
//           }
//         }
//       ],
//       "attributes": [
//         {
//           "id": 21,
//           "attribute_id": 9,
//           "attribute_value_id": 44,
//           "product_id": 4,
//           "deleted_at": null,
//           "created_at": "2020-12-04T20:04:00.000000Z",
//           "updated_at": "2020-12-04T20:04:00.000000Z",
//           "attribute": {
//             "id": 9,
//             "title": "Prof. Kiana Schoen",
//             "deleted_at": null,
//             "created_at": "2020-12-02T09:19:06.000000Z",
//             "updated_at": "2020-12-02T09:19:06.000000Z"
//           },
//           "value": {
//             "id": 44,
//             "title": "Dr. Adella Runolfsdottir V",
//             "attribute_id": 9,
//             "deleted_at": null,
//             "created_at": "2020-12-02T09:19:07.000000Z",
//             "updated_at": "2020-12-02T09:19:07.000000Z"
//           }
//         },
//         {
//           "id": 22,
//           "attribute_id": 11,
//           "attribute_value_id": 53,
//           "product_id": 4,
//           "deleted_at": null,
//           "created_at": "2020-12-04T20:04:00.000000Z",
//           "updated_at": "2020-12-04T20:04:00.000000Z",
//           "attribute": {
//             "id": 11,
//             "title": "Jackson Berge",
//             "deleted_at": null,
//             "created_at": "2020-12-02T09:19:06.000000Z",
//             "updated_at": "2020-12-02T09:19:06.000000Z"
//           },
//           "value": {
//             "id": 53,
//             "title": "Prof. Reba Satterfield II",
//             "attribute_id": 11,
//             "deleted_at": null,
//             "created_at": "2020-12-02T09:19:07.000000Z",
//             "updated_at": "2020-12-02T09:19:07.000000Z"
//           }
//         },
//         {
//           "id": 23,
//           "attribute_id": 11,
//           "attribute_value_id": 54,
//           "product_id": 4,
//           "deleted_at": null,
//           "created_at": "2020-12-04T20:04:00.000000Z",
//           "updated_at": "2020-12-04T20:04:00.000000Z",
//           "attribute": {
//             "id": 11,
//             "title": "Jackson Berge",
//             "deleted_at": null,
//             "created_at": "2020-12-02T09:19:06.000000Z",
//             "updated_at": "2020-12-02T09:19:06.000000Z"
//           },
//           "value": {
//             "id": 54,
//             "title": "Clarissa Rogahn Jr.",
//             "attribute_id": 11,
//             "deleted_at": null,
//             "created_at": "2020-12-02T09:19:07.000000Z",
//             "updated_at": "2020-12-02T09:19:07.000000Z"
//           }
//         },
//         {
//           "id": 24,
//           "attribute_id": 11,
//           "attribute_value_id": 55,
//           "product_id": 4,
//           "deleted_at": null,
//           "created_at": "2020-12-04T20:04:00.000000Z",
//           "updated_at": "2020-12-04T20:04:00.000000Z",
//           "attribute": {
//             "id": 11,
//             "title": "Jackson Berge",
//             "deleted_at": null,
//             "created_at": "2020-12-02T09:19:06.000000Z",
//             "updated_at": "2020-12-02T09:19:06.000000Z"
//           },
//           "value": {
//             "id": 55,
//             "title": "Dr. Broderick Prosacco DVM",
//             "attribute_id": 11,
//             "deleted_at": null,
//             "created_at": "2020-12-02T09:19:07.000000Z",
//             "updated_at": "2020-12-02T09:19:07.000000Z"
//           }
//         },
//         {
//           "id": 25,
//           "attribute_id": 13,
//           "attribute_value_id": 61,
//           "product_id": 4,
//           "deleted_at": null,
//           "created_at": "2020-12-04T20:04:00.000000Z",
//           "updated_at": "2020-12-04T20:04:00.000000Z",
//           "attribute": {
//             "id": 13,
//             "title": "Florida Kemmer",
//             "deleted_at": null,
//             "created_at": "2020-12-02T09:19:06.000000Z",
//             "updated_at": "2020-12-02T09:19:06.000000Z"
//           },
//           "value": {
//             "id": 61,
//             "title": "Antonetta Ritchie",
//             "attribute_id": 13,
//             "deleted_at": null,
//             "created_at": "2020-12-02T09:19:07.000000Z",
//             "updated_at": "2020-12-02T09:19:07.000000Z"
//           }
//         },
//         {
//           "id": 26,
//           "attribute_id": 13,
//           "attribute_value_id": 63,
//           "product_id": 4,
//           "deleted_at": null,
//           "created_at": "2020-12-04T20:04:00.000000Z",
//           "updated_at": "2020-12-04T20:04:00.000000Z",
//           "attribute": {
//             "id": 13,
//             "title": "Florida Kemmer",
//             "deleted_at": null,
//             "created_at": "2020-12-02T09:19:06.000000Z",
//             "updated_at": "2020-12-02T09:19:06.000000Z"
//           },
//           "value": {
//             "id": 63,
//             "title": "Ike Gibson",
//             "attribute_id": 13,
//             "deleted_at": null,
//             "created_at": "2020-12-02T09:19:07.000000Z",
//             "updated_at": "2020-12-02T09:19:07.000000Z"
//           }
//         },
//         {
//           "id": 27,
//           "attribute_id": 13,
//           "attribute_value_id": 64,
//           "product_id": 4,
//           "deleted_at": null,
//           "created_at": "2020-12-04T20:04:00.000000Z",
//           "updated_at": "2020-12-04T20:04:00.000000Z",
//           "attribute": {
//             "id": 13,
//             "title": "Florida Kemmer",
//             "deleted_at": null,
//             "created_at": "2020-12-02T09:19:06.000000Z",
//             "updated_at": "2020-12-02T09:19:06.000000Z"
//           },
//           "value": {
//             "id": 64,
//             "title": "Dr. Antwon Hettinger",
//             "attribute_id": 13,
//             "deleted_at": null,
//             "created_at": "2020-12-02T09:19:07.000000Z",
//             "updated_at": "2020-12-02T09:19:07.000000Z"
//           }
//         },
//         {
//           "id": 28,
//           "attribute_id": 15,
//           "attribute_value_id": 71,
//           "product_id": 4,
//           "deleted_at": null,
//           "created_at": "2020-12-04T20:04:00.000000Z",
//           "updated_at": "2020-12-04T20:04:00.000000Z",
//           "attribute": {
//             "id": 15,
//             "title": "Christy Carroll",
//             "deleted_at": null,
//             "created_at": "2020-12-02T09:19:06.000000Z",
//             "updated_at": "2020-12-02T09:19:06.000000Z"
//           },
//           "value": {
//             "id": 71,
//             "title": "Al Blick",
//             "attribute_id": 15,
//             "deleted_at": null,
//             "created_at": "2020-12-02T09:19:07.000000Z",
//             "updated_at": "2020-12-02T09:19:07.000000Z"
//           }
//         },
//         {
//           "id": 29,
//           "attribute_id": 15,
//           "attribute_value_id": 72,
//           "product_id": 4,
//           "deleted_at": null,
//           "created_at": "2020-12-04T20:04:00.000000Z",
//           "updated_at": "2020-12-04T20:04:00.000000Z",
//           "attribute": {
//             "id": 15,
//             "title": "Christy Carroll",
//             "deleted_at": null,
//             "created_at": "2020-12-02T09:19:06.000000Z",
//             "updated_at": "2020-12-02T09:19:06.000000Z"
//           },
//           "value": {
//             "id": 72,
//             "title": "Elmira Mayert",
//             "attribute_id": 15,
//             "deleted_at": null,
//             "created_at": "2020-12-02T09:19:07.000000Z",
//             "updated_at": "2020-12-02T09:19:07.000000Z"
//           }
//         },
//         {
//           "id": 30,
//           "attribute_id": 12,
//           "attribute_value_id": 57,
//           "product_id": 4,
//           "deleted_at": null,
//           "created_at": "2020-12-04T20:04:00.000000Z",
//           "updated_at": "2020-12-04T20:04:00.000000Z",
//           "attribute": {
//             "id": 12,
//             "title": "Wilma Feest",
//             "deleted_at": null,
//             "created_at": "2020-12-02T09:19:06.000000Z",
//             "updated_at": "2020-12-02T09:19:06.000000Z"
//           },
//           "value": {
//             "id": 57,
//             "title": "Brendan Barton",
//             "attribute_id": 12,
//             "deleted_at": null,
//             "created_at": "2020-12-02T09:19:07.000000Z",
//             "updated_at": "2020-12-02T09:19:07.000000Z"
//           }
//         },
//         {
//           "id": 31,
//           "attribute_id": 12,
//           "attribute_value_id": 59,
//           "product_id": 4,
//           "deleted_at": null,
//           "created_at": "2020-12-04T20:04:00.000000Z",
//           "updated_at": "2020-12-04T20:04:00.000000Z",
//           "attribute": {
//             "id": 12,
//             "title": "Wilma Feest",
//             "deleted_at": null,
//             "created_at": "2020-12-02T09:19:06.000000Z",
//             "updated_at": "2020-12-02T09:19:06.000000Z"
//           },
//           "value": {
//             "id": 59,
//             "title": "Jaleel Spinka",
//             "attribute_id": 12,
//             "deleted_at": null,
//             "created_at": "2020-12-02T09:19:07.000000Z",
//             "updated_at": "2020-12-02T09:19:07.000000Z"
//           }
//         },
//         {
//           "id": 32,
//           "attribute_id": 12,
//           "attribute_value_id": 60,
//           "product_id": 4,
//           "deleted_at": null,
//           "created_at": "2020-12-04T20:04:00.000000Z",
//           "updated_at": "2020-12-04T20:04:00.000000Z",
//           "attribute": {
//             "id": 12,
//             "title": "Wilma Feest",
//             "deleted_at": null,
//             "created_at": "2020-12-02T09:19:06.000000Z",
//             "updated_at": "2020-12-02T09:19:06.000000Z"
//           },
//           "value": {
//             "id": 60,
//             "title": "Westley Denesik",
//             "attribute_id": 12,
//             "deleted_at": null,
//             "created_at": "2020-12-02T09:19:07.000000Z",
//             "updated_at": "2020-12-02T09:19:07.000000Z"
//           }
//         },
//         {
//           "id": 33,
//           "attribute_id": 17,
//           "attribute_value_id": 81,
//           "product_id": 4,
//           "deleted_at": null,
//           "created_at": "2020-12-04T20:04:00.000000Z",
//           "updated_at": "2020-12-04T20:04:00.000000Z",
//           "attribute": {
//             "id": 17,
//             "title": "Jerod Schmidt III",
//             "deleted_at": null,
//             "created_at": "2020-12-02T09:19:06.000000Z",
//             "updated_at": "2020-12-02T09:19:06.000000Z"
//           },
//           "value": {
//             "id": 81,
//             "title": "Bessie Windler",
//             "attribute_id": 17,
//             "deleted_at": null,
//             "created_at": "2020-12-02T09:19:07.000000Z",
//             "updated_at": "2020-12-02T09:19:07.000000Z"
//           }
//         },
//         {
//           "id": 34,
//           "attribute_id": 17,
//           "attribute_value_id": 83,
//           "product_id": 4,
//           "deleted_at": null,
//           "created_at": "2020-12-04T20:04:00.000000Z",
//           "updated_at": "2020-12-04T20:04:00.000000Z",
//           "attribute": {
//             "id": 17,
//             "title": "Jerod Schmidt III",
//             "deleted_at": null,
//             "created_at": "2020-12-02T09:19:06.000000Z",
//             "updated_at": "2020-12-02T09:19:06.000000Z"
//           },
//           "value": {
//             "id": 83,
//             "title": "Catalina Sauer",
//             "attribute_id": 17,
//             "deleted_at": null,
//             "created_at": "2020-12-02T09:19:07.000000Z",
//             "updated_at": "2020-12-02T09:19:07.000000Z"
//           }
//         },
//         {
//           "id": 35,
//           "attribute_id": 17,
//           "attribute_value_id": 84,
//           "product_id": 4,
//           "deleted_at": null,
//           "created_at": "2020-12-04T20:04:00.000000Z",
//           "updated_at": "2020-12-04T20:04:00.000000Z",
//           "attribute": {
//             "id": 17,
//             "title": "Jerod Schmidt III",
//             "deleted_at": null,
//             "created_at": "2020-12-02T09:19:06.000000Z",
//             "updated_at": "2020-12-02T09:19:06.000000Z"
//           },
//           "value": {
//             "id": 84,
//             "title": "Amiya Mueller",
//             "attribute_id": 17,
//             "deleted_at": null,
//             "created_at": "2020-12-02T09:19:07.000000Z",
//             "updated_at": "2020-12-02T09:19:07.000000Z"
//           }
//         },
//         {
//           "id": 36,
//           "attribute_id": 17,
//           "attribute_value_id": 85,
//           "product_id": 4,
//           "deleted_at": null,
//           "created_at": "2020-12-04T20:04:00.000000Z",
//           "updated_at": "2020-12-04T20:04:00.000000Z",
//           "attribute": {
//             "id": 17,
//             "title": "Jerod Schmidt III",
//             "deleted_at": null,
//             "created_at": "2020-12-02T09:19:06.000000Z",
//             "updated_at": "2020-12-02T09:19:06.000000Z"
//           },
//           "value": {
//             "id": 85,
//             "title": "Adrian Fadel",
//             "attribute_id": 17,
//             "deleted_at": null,
//             "created_at": "2020-12-02T09:19:07.000000Z",
//             "updated_at": "2020-12-02T09:19:07.000000Z"
//           }
//         },
//         {
//           "id": 37,
//           "attribute_id": 19,
//           "attribute_value_id": 91,
//           "product_id": 4,
//           "deleted_at": null,
//           "created_at": "2020-12-04T20:04:00.000000Z",
//           "updated_at": "2020-12-04T20:04:00.000000Z",
//           "attribute": {
//             "id": 19,
//             "title": "Mrs. Wanda Jones I",
//             "deleted_at": null,
//             "created_at": "2020-12-02T09:19:06.000000Z",
//             "updated_at": "2020-12-02T09:19:06.000000Z"
//           },
//           "value": {
//             "id": 91,
//             "title": "Mr. Dane Weber",
//             "attribute_id": 19,
//             "deleted_at": null,
//             "created_at": "2020-12-02T09:19:07.000000Z",
//             "updated_at": "2020-12-02T09:19:07.000000Z"
//           }
//         },
//         {
//           "id": 38,
//           "attribute_id": 19,
//           "attribute_value_id": 92,
//           "product_id": 4,
//           "deleted_at": null,
//           "created_at": "2020-12-04T20:04:00.000000Z",
//           "updated_at": "2020-12-04T20:04:00.000000Z",
//           "attribute": {
//             "id": 19,
//             "title": "Mrs. Wanda Jones I",
//             "deleted_at": null,
//             "created_at": "2020-12-02T09:19:06.000000Z",
//             "updated_at": "2020-12-02T09:19:06.000000Z"
//           },
//           "value": {
//             "id": 92,
//             "title": "Gerardo Daniel",
//             "attribute_id": 19,
//             "deleted_at": null,
//             "created_at": "2020-12-02T09:19:07.000000Z",
//             "updated_at": "2020-12-02T09:19:07.000000Z"
//           }
//         },
//         {
//           "id": 93,
//           "attribute_id": 17,
//           "attribute_value_id": 81,
//           "product_id": 4,
//           "deleted_at": null,
//           "created_at": "2020-12-04T21:26:03.000000Z",
//           "updated_at": "2020-12-04T21:26:03.000000Z",
//           "attribute": {
//             "id": 17,
//             "title": "Jerod Schmidt III",
//             "deleted_at": null,
//             "created_at": "2020-12-02T09:19:06.000000Z",
//             "updated_at": "2020-12-02T09:19:06.000000Z"
//           },
//           "value": {
//             "id": 81,
//             "title": "Bessie Windler",
//             "attribute_id": 17,
//             "deleted_at": null,
//             "created_at": "2020-12-02T09:19:07.000000Z",
//             "updated_at": "2020-12-02T09:19:07.000000Z"
//           }
//         }
//       ]
//     },
//     {
//       "id": 3,
//       "category_id": 4,
//       "seller_id": 8,
//       "title": "ساعات ‏روكلس ‏فاخرة",
//       "description": "ساعات عالية الجودة و مقاومة للماء",
//       "views": 0,
//       "orders_count": 3,
//       "available_qty": 0,
//       "price": 1500,
//       "tax": 200,
//       "status": "active",
//       "deleted_at": null,
//       "created_at": "2020-12-04T12:46:51.000000Z",
//       "updated_at": "2020-12-05T21:23:25.000000Z",
//       "shipping_offers": [
//         {
//           "id": 2,
//           "shipper_id": 8,
//           "shipper_type": "App\\\\Orm\\\\Seller",
//           "title": "shipping offer",
//           "price": 2900,
//           "status": "active",
//           "deleted_at": null,
//           "created_at": "2020-12-02T19:25:30.000000Z",
//           "updated_at": "2020-12-02T19:25:30.000000Z"
//         },
//         {
//           "id": 10,
//           "shipper_id": 8,
//           "shipper_type": "App\\\\Orm\\\\Seller",
//           "title": "عرض متوسط",
//           "price": 600,
//           "status": "active",
//           "deleted_at": null,
//           "created_at": "2020-12-05T22:52:17.000000Z",
//           "updated_at": "2020-12-05T22:52:17.000000Z"
//         },
//         {
//           "id": 11,
//           "shipper_id": 8,
//           "shipper_type": "App\\\\Orm\\\\Seller",
//           "title": "عرض سريع",
//           "price": 1200,
//           "status": "active",
//           "deleted_at": null,
//           "created_at": "2020-12-05T22:52:37.000000Z",
//           "updated_at": "2020-12-05T22:52:37.000000Z"
//         },
//         {
//           "id": 12,
//           "shipper_id": 8,
//           "shipper_type": "App\\\\Orm\\\\Seller",
//           "title": "حنب بيتك",
//           "price": 2000,
//           "status": "active",
//           "deleted_at": null,
//           "created_at": "2020-12-05T22:53:00.000000Z",
//           "updated_at": "2020-12-05T22:53:00.000000Z"
//         },
//         [
//
//         ]
//       ],
//       "images": [
//         {
//           "id": 11,
//           "product_id": 3,
//           "image": "https://api.alaalimshop.com/public/storage/products/oCPi09O14BDV7Lh8ovBMl4eUFZaWqL9xmUyfBrfN.jpg",
//           "created_at": "2020-12-04T12:46:51.000000Z",
//           "updated_at": "2020-12-04T12:46:51.000000Z"
//         },
//         {
//           "id": 12,
//           "product_id": 3,
//           "image": "https://api.alaalimshop.com/public/storage/products/6sUDUjahF9CQwFisQPF0lw0uclsbPwpePppP6rJG.jpg",
//           "created_at": "2020-12-04T12:46:51.000000Z",
//           "updated_at": "2020-12-04T12:46:51.000000Z"
//         },
//         {
//           "id": 13,
//           "product_id": 3,
//           "image": "https://api.alaalimshop.com/public/storage/products/BzNgkJQrSjVDnOzlwGShYERWQWqDL5a8lVghpHgD.jpg",
//           "created_at": "2020-12-04T12:46:51.000000Z",
//           "updated_at": "2020-12-04T12:46:51.000000Z"
//         }
//       ],
//       "category": {
//         "id": 4,
//         "parent_id": 2,
//         "title": "test",
//         "description": "test",
//         "products_images_count": 4,
//         "image": "https://api.alaalimshop.com/public/storage/categories/AzjPL2yu4pT26NbaAOcqZnb024OkV2aYnpO7O2tB.png",
//         "products_count": 2,
//         "deleted_at": null,
//         "created_at": "2020-12-03T09:18:54.000000Z",
//         "updated_at": "2020-12-05T08:01:20.000000Z",
//         "parent_name": "clothes",
//         "parent": {
//           "id": 2,
//           "parent_id": 0,
//           "title": "clothes",
//           "description": "Iste dicta ipsa exercitationem sunt ea tempore.",
//           "products_images_count": 6,
//           "image": "https://api.alaalimshop.com/public/storage/categories/2M1HVkEBFfTgUw0NeymFK1PI8LXpHH6pqE44OAlz.png",
//           "products_count": 2,
//           "deleted_at": null,
//           "created_at": "2020-12-02T09:19:14.000000Z",
//           "updated_at": "2020-12-05T15:18:15.000000Z",
//           "parent_name": "",
//           "parent": null
//         }
//       },
//       "seller": {
//         "id": 8,
//         "seller_id": 8,
//         "full_name": "almunzir99",
//         "shop_name": "DJ",
//         "shop_image": "https://api.alaalimshop.com/storage/app/public/shops/c1A2N8faeqJo1FEtNSeSefWhkRdZRnBJJ9ienwTS.jpg",
//         "phone_number": "0124647018",
//         "username": "almunzir99",
//         "contact_phone_number": "0124647018",
//         "email": null,
//         "identity": null,
//         "country_id": 887,
//         "state_id": 10,
//         "city": "none",
//         "area": null,
//         "lat": 15,
//         "lng": 45,
//         "status": "approved",
//         "allow_chatting": "on",
//         "display_contact_phone": "on",
//         "display_banking_info": "on",
//         "display_delivery_option": "on",
//         "display_products": "on",
//         "display_products_views": "on",
//         "display_other_branches": "on",
//         "display_products_prices": "on",
//         "display_news": 1,
//         "display_sliders": 1,
//         "activation_code": null,
//         "deleted_at": null,
//         "created_at": "2020-12-02T19:09:52.000000Z",
//         "updated_at": "2020-12-04T01:43:21.000000Z",
//         "additional_phone_numbers": [
//
//         ]
//       },
//       "fields": [
//
//       ],
//       "attributes": [
//         {
//           "id": 113,
//           "attribute_id": 4,
//           "attribute_value_id": 17,
//           "product_id": 3,
//           "deleted_at": null,
//           "created_at": "2020-12-05T07:51:06.000000Z",
//           "updated_at": "2020-12-05T07:51:06.000000Z",
//           "attribute": {
//             "id": 4,
//             "title": "Neil Mayert",
//             "deleted_at": null,
//             "created_at": "2020-12-02T09:19:06.000000Z",
//             "updated_at": "2020-12-02T09:19:06.000000Z"
//           },
//           "value": {
//             "id": 17,
//             "title": "Prof. Macey Huels MD",
//             "attribute_id": 4,
//             "deleted_at": null,
//             "created_at": "2020-12-02T09:19:06.000000Z",
//             "updated_at": "2020-12-02T09:19:06.000000Z"
//           }
//         },
//         {
//           "id": 131,
//           "attribute_id": 3,
//           "attribute_value_id": 14,
//           "product_id": 3,
//           "deleted_at": null,
//           "created_at": "2020-12-05T12:07:59.000000Z",
//           "updated_at": "2020-12-05T12:07:59.000000Z",
//           "attribute": {
//             "id": 3,
//             "title": "Dr. Kellen Erdman PhD",
//             "deleted_at": null,
//             "created_at": "2020-12-02T09:19:06.000000Z",
//             "updated_at": "2020-12-02T09:19:06.000000Z"
//           },
//           "value": {
//             "id": 14,
//             "title": "Noble Skiles Sr.",
//             "attribute_id": 3,
//             "deleted_at": null,
//             "created_at": "2020-12-02T09:19:06.000000Z",
//             "updated_at": "2020-12-02T09:19:06.000000Z"
//           }
//         },
//         {
//           "id": 133,
//           "attribute_id": 3,
//           "attribute_value_id": 11,
//           "product_id": 3,
//           "deleted_at": null,
//           "created_at": "2020-12-05T12:10:12.000000Z",
//           "updated_at": "2020-12-05T12:10:12.000000Z",
//           "attribute": {
//             "id": 3,
//             "title": "Dr. Kellen Erdman PhD",
//             "deleted_at": null,
//             "created_at": "2020-12-02T09:19:06.000000Z",
//             "updated_at": "2020-12-02T09:19:06.000000Z"
//           },
//           "value": {
//             "id": 11,
//             "title": "Kevon Mitchell",
//             "attribute_id": 3,
//             "deleted_at": null,
//             "created_at": "2020-12-02T09:19:06.000000Z",
//             "updated_at": "2020-12-02T09:19:06.000000Z"
//           }
//         },
//         {
//           "id": 134,
//           "attribute_id": 4,
//           "attribute_value_id": 19,
//           "product_id": 3,
//           "deleted_at": null,
//           "created_at": "2020-12-05T12:10:38.000000Z",
//           "updated_at": "2020-12-05T12:10:38.000000Z",
//           "attribute": {
//             "id": 4,
//             "title": "Neil Mayert",
//             "deleted_at": null,
//             "created_at": "2020-12-02T09:19:06.000000Z",
//             "updated_at": "2020-12-02T09:19:06.000000Z"
//           },
//           "value": {
//             "id": 19,
//             "title": "Prof. Virginia Hudson",
//             "attribute_id": 4,
//             "deleted_at": null,
//             "created_at": "2020-12-02T09:19:06.000000Z",
//             "updated_at": "2020-12-02T09:19:06.000000Z"
//           }
//         }
//       ]
//     },
//     {
//       "id": 2,
//       "category_id": 1,
//       "seller_id": 7,
//       "title": "Velit doloribus ea n",
//       "description": "Natus anim nesciunt",
//       "views": 0,
//       "orders_count": 0,
//       "available_qty": 0,
//       "price": 13,
//       "tax": 18,
//       "status": "active",
//       "deleted_at": null,
//       "created_at": "2020-12-03T21:32:21.000000Z",
//       "updated_at": "2020-12-03T21:32:21.000000Z",
//       "shipping_offers": [
//         [
//
//         ]
//       ],
//       "images": [
//         {
//           "id": 6,
//           "product_id": 2,
//           "image": "https://api.alaalimshop.com/public/storage/products/OCz84Gs5hvckSDVxEwB4MMOquszk25AUwqcTjDgH.jpg",
//           "created_at": "2020-12-03T21:32:21.000000Z",
//           "updated_at": "2020-12-03T21:32:21.000000Z"
//         },
//         {
//           "id": 7,
//           "product_id": 2,
//           "image": "https://api.alaalimshop.com/public/storage/products/66bxOtNnMyOyM7igUtAolenNiBRPmWNFPRerTUul.jpg",
//           "created_at": "2020-12-03T21:32:21.000000Z",
//           "updated_at": "2020-12-03T21:32:21.000000Z"
//         },
//         {
//           "id": 8,
//           "product_id": 2,
//           "image": "https://api.alaalimshop.com/public/storage/products/taaq14EYMBNCfA6IpdBN2iJGcBplXvWz7GsQrKQw.jpg",
//           "created_at": "2020-12-03T21:32:21.000000Z",
//           "updated_at": "2020-12-03T21:32:21.000000Z"
//         },
//         {
//           "id": 9,
//           "product_id": 2,
//           "image": "https://api.alaalimshop.com/public/storage/products/ngjg7jE35xOSGWkuW9QFZhXO3X2kbFgp03f6rYzk.jpg",
//           "created_at": "2020-12-03T21:32:21.000000Z",
//           "updated_at": "2020-12-03T21:32:21.000000Z"
//         },
//         {
//           "id": 10,
//           "product_id": 2,
//           "image": "https://api.alaalimshop.com/public/storage/products/MmAmKeF7skgqPrA3NcT7zA5XdJFrtbjnj9pP5c1U.jpg",
//           "created_at": "2020-12-03T21:32:21.000000Z",
//           "updated_at": "2020-12-03T21:32:21.000000Z"
//         }
//       ],
//       "category": {
//         "id": 1,
//         "parent_id": 0,
//         "title": "shoes",
//         "description": "Cumque quasi itaque ea illo error blanditiis.",
//         "products_images_count": 5,
//         "image": "https://api.alaalimshop.com/public/storage/categories/18x7m2cf2LyjSh169gscmihSusHMIU4SbKK9SYQx.jpg",
//         "products_count": 5,
//         "deleted_at": null,
//         "created_at": "2020-12-02T09:19:14.000000Z",
//         "updated_at": "2020-12-05T21:55:52.000000Z",
//         "parent_name": "",
//         "parent": null
//       },
//       "seller": {
//         "id": 7,
//         "seller_id": 7,
//         "full_name": "mazin",
//         "shop_name": "mezo_store",
//         "shop_image": "https://api.alaalimshop.com/storage/app/public/shops/Pi9pUw1erWWsqM6hmJxXsredbFGzQqNpoHuh29QC.png",
//         "phone_number": "0987654322",
//         "username": "ali",
//         "contact_phone_number": "0987654322",
//         "email": "mazin@app.com",
//         "identity": "fgfgfhfghh",
//         "country_id": 887,
//         "state_id": 4,
//         "city": "dgsdgfgfg",
//         "area": null,
//         "lat": 465657,
//         "lng": 343434,
//         "status": "approved",
//         "allow_chatting": "on",
//         "display_contact_phone": "on",
//         "display_banking_info": "on",
//         "display_delivery_option": "on",
//         "display_products": "on",
//         "display_products_views": "on",
//         "display_other_branches": "on",
//         "display_products_prices": "on",
//         "display_news": 1,
//         "display_sliders": 1,
//         "activation_code": null,
//         "deleted_at": null,
//         "created_at": "2020-12-02T09:57:32.000000Z",
//         "updated_at": "2020-12-04T09:57:41.000000Z",
//         "additional_phone_numbers": [
//
//         ]
//       },
//       "fields": [
//         {
//           "id": 6,
//           "product_id": 2,
//           "field_id": 1,
//           "value": "a",
//           "deleted_at": null,
//           "created_at": "2020-12-03T21:32:21.000000Z",
//           "updated_at": "2020-12-03T21:32:21.000000Z",
//           "field": {
//             "id": 1,
//             "category_id": 1,
//             "title": "Prof. Danika Hauck II",
//             "deleted_at": null,
//             "created_at": "2020-12-02T09:19:14.000000Z",
//             "updated_at": "2020-12-02T09:19:14.000000Z"
//           }
//         },
//         {
//           "id": 7,
//           "product_id": 2,
//           "field_id": 2,
//           "value": "b",
//           "deleted_at": null,
//           "created_at": "2020-12-03T21:32:21.000000Z",
//           "updated_at": "2020-12-03T21:32:21.000000Z",
//           "field": {
//             "id": 2,
//             "category_id": 1,
//             "title": "Estevan Daugherty",
//             "deleted_at": null,
//             "created_at": "2020-12-02T09:19:14.000000Z",
//             "updated_at": "2020-12-02T09:19:14.000000Z"
//           }
//         },
//         {
//           "id": 8,
//           "product_id": 2,
//           "field_id": 3,
//           "value": "c",
//           "deleted_at": null,
//           "created_at": "2020-12-03T21:32:21.000000Z",
//           "updated_at": "2020-12-03T21:32:21.000000Z",
//           "field": {
//             "id": 3,
//             "category_id": 1,
//             "title": "Ayana Gaylord",
//             "deleted_at": null,
//             "created_at": "2020-12-02T09:19:14.000000Z",
//             "updated_at": "2020-12-02T09:19:14.000000Z"
//           }
//         },
//         {
//           "id": 9,
//           "product_id": 2,
//           "field_id": 4,
//           "value": "d",
//           "deleted_at": null,
//           "created_at": "2020-12-03T21:32:21.000000Z",
//           "updated_at": "2020-12-03T21:32:21.000000Z",
//           "field": {
//             "id": 4,
//             "category_id": 1,
//             "title": "Rylee Howe",
//             "deleted_at": null,
//             "created_at": "2020-12-02T09:19:14.000000Z",
//             "updated_at": "2020-12-02T09:19:14.000000Z"
//           }
//         },
//         {
//           "id": 10,
//           "product_id": 2,
//           "field_id": 5,
//           "value": "f",
//           "deleted_at": null,
//           "created_at": "2020-12-03T21:32:21.000000Z",
//           "updated_at": "2020-12-03T21:32:21.000000Z",
//           "field": {
//             "id": 5,
//             "category_id": 1,
//             "title": "Prof. Cristian Aufderhar",
//             "deleted_at": null,
//             "created_at": "2020-12-02T09:19:14.000000Z",
//             "updated_at": "2020-12-02T09:19:14.000000Z"
//           }
//         }
//       ],
//       "attributes": [
//         {
//           "id": 3,
//           "attribute_id": 23,
//           "attribute_value_id": 111,
//           "product_id": 2,
//           "deleted_at": null,
//           "created_at": "2020-12-03T21:32:21.000000Z",
//           "updated_at": "2020-12-03T21:32:21.000000Z",
//           "attribute": {
//             "id": 23,
//             "title": "Mr. Dominic Gutkowski",
//             "deleted_at": null,
//             "created_at": "2020-12-02T09:19:06.000000Z",
//             "updated_at": "2020-12-02T09:19:06.000000Z"
//           },
//           "value": {
//             "id": 111,
//             "title": "Dr. Sister Bosco PhD",
//             "attribute_id": 23,
//             "deleted_at": null,
//             "created_at": "2020-12-02T09:19:07.000000Z",
//             "updated_at": "2020-12-02T09:19:07.000000Z"
//           }
//         },
//         {
//           "id": 4,
//           "attribute_id": 23,
//           "attribute_value_id": 112,
//           "product_id": 2,
//           "deleted_at": null,
//           "created_at": "2020-12-03T21:32:21.000000Z",
//           "updated_at": "2020-12-03T21:32:21.000000Z",
//           "attribute": {
//             "id": 23,
//             "title": "Mr. Dominic Gutkowski",
//             "deleted_at": null,
//             "created_at": "2020-12-02T09:19:06.000000Z",
//             "updated_at": "2020-12-02T09:19:06.000000Z"
//           },
//           "value": {
//             "id": 112,
//             "title": "Harmony Hodkiewicz",
//             "attribute_id": 23,
//             "deleted_at": null,
//             "created_at": "2020-12-02T09:19:07.000000Z",
//             "updated_at": "2020-12-02T09:19:07.000000Z"
//           }
//         },
//         {
//           "id": 5,
//           "attribute_id": 23,
//           "attribute_value_id": 113,
//           "product_id": 2,
//           "deleted_at": null,
//           "created_at": "2020-12-03T21:32:21.000000Z",
//           "updated_at": "2020-12-03T21:32:21.000000Z",
//           "attribute": {
//             "id": 23,
//             "title": "Mr. Dominic Gutkowski",
//             "deleted_at": null,
//             "created_at": "2020-12-02T09:19:06.000000Z",
//             "updated_at": "2020-12-02T09:19:06.000000Z"
//           },
//           "value": {
//             "id": 113,
//             "title": "Prof. Vincenza Olson",
//             "attribute_id": 23,
//             "deleted_at": null,
//             "created_at": "2020-12-02T09:19:07.000000Z",
//             "updated_at": "2020-12-02T09:19:07.000000Z"
//           }
//         },
//         {
//           "id": 6,
//           "attribute_id": 23,
//           "attribute_value_id": 114,
//           "product_id": 2,
//           "deleted_at": null,
//           "created_at": "2020-12-03T21:32:21.000000Z",
//           "updated_at": "2020-12-03T21:32:21.000000Z",
//           "attribute": {
//             "id": 23,
//             "title": "Mr. Dominic Gutkowski",
//             "deleted_at": null,
//             "created_at": "2020-12-02T09:19:06.000000Z",
//             "updated_at": "2020-12-02T09:19:06.000000Z"
//           },
//           "value": {
//             "id": 114,
//             "title": "Jan Batz",
//             "attribute_id": 23,
//             "deleted_at": null,
//             "created_at": "2020-12-02T09:19:07.000000Z",
//             "updated_at": "2020-12-02T09:19:07.000000Z"
//           }
//         },
//         {
//           "id": 7,
//           "attribute_id": 23,
//           "attribute_value_id": 115,
//           "product_id": 2,
//           "deleted_at": null,
//           "created_at": "2020-12-04T10:36:37.000000Z",
//           "updated_at": "2020-12-04T10:36:37.000000Z",
//           "attribute": {
//             "id": 23,
//             "title": "Mr. Dominic Gutkowski",
//             "deleted_at": null,
//             "created_at": "2020-12-02T09:19:06.000000Z",
//             "updated_at": "2020-12-02T09:19:06.000000Z"
//           },
//           "value": {
//             "id": 115,
//             "title": "Shakira Reinger",
//             "attribute_id": 23,
//             "deleted_at": null,
//             "created_at": "2020-12-02T09:19:07.000000Z",
//             "updated_at": "2020-12-02T09:19:07.000000Z"
//           }
//         },
//         {
//           "id": 8,
//           "attribute_id": 1,
//           "attribute_value_id": 1,
//           "product_id": 2,
//           "deleted_at": null,
//           "created_at": "2020-12-04T10:36:58.000000Z",
//           "updated_at": "2020-12-04T10:36:58.000000Z",
//           "attribute": {
//             "id": 1,
//             "title": "Colleen Langworth MD",
//             "deleted_at": null,
//             "created_at": "2020-12-02T09:19:06.000000Z",
//             "updated_at": "2020-12-02T09:19:06.000000Z"
//           },
//           "value": {
//             "id": 1,
//             "title": "Mohamed Donnelly",
//             "attribute_id": 1,
//             "deleted_at": null,
//             "created_at": "2020-12-02T09:19:06.000000Z",
//             "updated_at": "2020-12-02T09:19:06.000000Z"
//           }
//         },
//         {
//           "id": 9,
//           "attribute_id": 1,
//           "attribute_value_id": 2,
//           "product_id": 2,
//           "deleted_at": null,
//           "created_at": "2020-12-04T11:00:02.000000Z",
//           "updated_at": "2020-12-04T11:00:02.000000Z",
//           "attribute": {
//             "id": 1,
//             "title": "Colleen Langworth MD",
//             "deleted_at": null,
//             "created_at": "2020-12-02T09:19:06.000000Z",
//             "updated_at": "2020-12-02T09:19:06.000000Z"
//           },
//           "value": {
//             "id": 2,
//             "title": "Zola Aufderhar",
//             "attribute_id": 1,
//             "deleted_at": null,
//             "created_at": "2020-12-02T09:19:06.000000Z",
//             "updated_at": "2020-12-02T09:19:06.000000Z"
//           }
//         }
//       ]
//     },
//     {
//       "id": 1,
//       "category_id": 1,
//       "seller_id": 7,
//       "title": "wdesd",
//       "description": "ok",
//       "views": 0,
//       "orders_count": 4,
//       "available_qty": 0,
//       "price": 432,
//       "tax": 3,
//       "status": "active",
//       "deleted_at": null,
//       "created_at": "2020-12-02T12:51:05.000000Z",
//       "updated_at": "2020-12-04T22:44:18.000000Z",
//       "shipping_offers": [
//         [
//
//         ]
//       ],
//       "images": [
//         {
//           "id": 1,
//           "product_id": 1,
//           "image": "https://api.alaalimshop.com/public/storage/products/PAbyXjb4kWgMRMsbVbgPnERkGegTM8MQug0ssl4r.jpg",
//           "created_at": "2020-12-02T12:51:05.000000Z",
//           "updated_at": "2020-12-02T12:51:05.000000Z"
//         },
//         {
//           "id": 2,
//           "product_id": 1,
//           "image": "https://api.alaalimshop.com/public/storage/products/osh1MmlatDg9JR4eZtHNRaqvbDUo5IeBdRRSyK3b.jpg",
//           "created_at": "2020-12-02T12:51:05.000000Z",
//           "updated_at": "2020-12-02T12:51:05.000000Z"
//         },
//         {
//           "id": 3,
//           "product_id": 1,
//           "image": "https://api.alaalimshop.com/public/storage/products/0WdyAuKLuNtUAXflbai0djK2MKGKZP9f6I7VXoHR.jpg",
//           "created_at": "2020-12-02T12:51:05.000000Z",
//           "updated_at": "2020-12-02T12:51:05.000000Z"
//         },
//         {
//           "id": 4,
//           "product_id": 1,
//           "image": "https://api.alaalimshop.com/public/storage/products/nCLOs6yamH6aSugTVcNQanNX0kSZyralP6l9tOA0.jpg",
//           "created_at": "2020-12-02T12:51:05.000000Z",
//           "updated_at": "2020-12-02T12:51:05.000000Z"
//         },
//         {
//           "id": 5,
//           "product_id": 1,
//           "image": "https://api.alaalimshop.com/public/storage/products/M0O60WFvGL5mvkH2RqvxToMtS1AXS5j0zdefYR5X.jpg",
//           "created_at": "2020-12-02T12:51:05.000000Z",
//           "updated_at": "2020-12-02T12:51:05.000000Z"
//         }
//       ],
//       "category": {
//         "id": 1,
//         "parent_id": 0,
//         "title": "shoes",
//         "description": "Cumque quasi itaque ea illo error blanditiis.",
//         "products_images_count": 5,
//         "image": "https://api.alaalimshop.com/public/storage/categories/18x7m2cf2LyjSh169gscmihSusHMIU4SbKK9SYQx.jpg",
//         "products_count": 5,
//         "deleted_at": null,
//         "created_at": "2020-12-02T09:19:14.000000Z",
//         "updated_at": "2020-12-05T21:55:52.000000Z",
//         "parent_name": "",
//         "parent": null
//       },
//       "seller": {
//         "id": 7,
//         "seller_id": 7,
//         "full_name": "mazin",
//         "shop_name": "mezo_store",
//         "shop_image": "https://api.alaalimshop.com/storage/app/public/shops/Pi9pUw1erWWsqM6hmJxXsredbFGzQqNpoHuh29QC.png",
//         "phone_number": "0987654322",
//         "username": "ali",
//         "contact_phone_number": "0987654322",
//         "email": "mazin@app.com",
//         "identity": "fgfgfhfghh",
//         "country_id": 887,
//         "state_id": 4,
//         "city": "dgsdgfgfg",
//         "area": null,
//         "lat": 465657,
//         "lng": 343434,
//         "status": "approved",
//         "allow_chatting": "on",
//         "display_contact_phone": "on",
//         "display_banking_info": "on",
//         "display_delivery_option": "on",
//         "display_products": "on",
//         "display_products_views": "on",
//         "display_other_branches": "on",
//         "display_products_prices": "on",
//         "display_news": 1,
//         "display_sliders": 1,
//         "activation_code": null,
//         "deleted_at": null,
//         "created_at": "2020-12-02T09:57:32.000000Z",
//         "updated_at": "2020-12-04T09:57:41.000000Z",
//         "additional_phone_numbers": [
//
//         ]
//       },
//       "fields": [
//         {
//           "id": 1,
//           "product_id": 1,
//           "field_id": 1,
//           "value": "\`",
//           "deleted_at": null,
//           "created_at": "2020-12-02T12:51:05.000000Z",
//           "updated_at": "2020-12-02T12:51:05.000000Z",
//           "field": {
//             "id": 1,
//             "category_id": 1,
//             "title": "Prof. Danika Hauck II",
//             "deleted_at": null,
//             "created_at": "2020-12-02T09:19:14.000000Z",
//             "updated_at": "2020-12-02T09:19:14.000000Z"
//           }
//         },
//         {
//           "id": 2,
//           "product_id": 1,
//           "field_id": 1,
//           "value": "2",
//           "deleted_at": null,
//           "created_at": "2020-12-02T12:51:05.000000Z",
//           "updated_at": "2020-12-02T12:51:05.000000Z",
//           "field": {
//             "id": 1,
//             "category_id": 1,
//             "title": "Prof. Danika Hauck II",
//             "deleted_at": null,
//             "created_at": "2020-12-02T09:19:14.000000Z",
//             "updated_at": "2020-12-02T09:19:14.000000Z"
//           }
//         },
//         {
//           "id": 3,
//           "product_id": 1,
//           "field_id": 2,
//           "value": "3",
//           "deleted_at": null,
//           "created_at": "2020-12-02T12:51:05.000000Z",
//           "updated_at": "2020-12-02T12:51:05.000000Z",
//           "field": {
//             "id": 2,
//             "category_id": 1,
//             "title": "Estevan Daugherty",
//             "deleted_at": null,
//             "created_at": "2020-12-02T09:19:14.000000Z",
//             "updated_at": "2020-12-02T09:19:14.000000Z"
//           }
//         },
//         {
//           "id": 4,
//           "product_id": 1,
//           "field_id": 3,
//           "value": "3",
//           "deleted_at": null,
//           "created_at": "2020-12-02T12:51:05.000000Z",
//           "updated_at": "2020-12-02T12:51:05.000000Z",
//           "field": {
//             "id": 3,
//             "category_id": 1,
//             "title": "Ayana Gaylord",
//             "deleted_at": null,
//             "created_at": "2020-12-02T09:19:14.000000Z",
//             "updated_at": "2020-12-02T09:19:14.000000Z"
//           }
//         },
//         {
//           "id": 5,
//           "product_id": 1,
//           "field_id": 4,
//           "value": "4",
//           "deleted_at": null,
//           "created_at": "2020-12-02T12:51:05.000000Z",
//           "updated_at": "2020-12-02T12:51:05.000000Z",
//           "field": {
//             "id": 4,
//             "category_id": 1,
//             "title": "Rylee Howe",
//             "deleted_at": null,
//             "created_at": "2020-12-02T09:19:14.000000Z",
//             "updated_at": "2020-12-02T09:19:14.000000Z"
//           }
//         }
//       ],
//       "attributes": [
//         {
//           "id": 1,
//           "attribute_id": 1,
//           "attribute_value_id": 1,
//           "product_id": 1,
//           "deleted_at": null,
//           "created_at": "2020-12-02T12:51:05.000000Z",
//           "updated_at": "2020-12-02T12:51:05.000000Z",
//           "attribute": {
//             "id": 1,
//             "title": "Colleen Langworth MD",
//             "deleted_at": null,
//             "created_at": "2020-12-02T09:19:06.000000Z",
//             "updated_at": "2020-12-02T09:19:06.000000Z"
//           },
//           "value": {
//             "id": 1,
//             "title": "Mohamed Donnelly",
//             "attribute_id": 1,
//             "deleted_at": null,
//             "created_at": "2020-12-02T09:19:06.000000Z",
//             "updated_at": "2020-12-02T09:19:06.000000Z"
//           }
//         },
//         {
//           "id": 2,
//           "attribute_id": 2,
//           "attribute_value_id": 1,
//           "product_id": 1,
//           "deleted_at": null,
//           "created_at": "2020-12-02T12:51:05.000000Z",
//           "updated_at": "2020-12-02T12:51:05.000000Z",
//           "attribute": {
//             "id": 2,
//             "title": "Austin Swaniawski",
//             "deleted_at": null,
//             "created_at": "2020-12-02T09:19:06.000000Z",
//             "updated_at": "2020-12-02T09:19:06.000000Z"
//           },
//           "value": {
//             "id": 1,
//             "title": "Mohamed Donnelly",
//             "attribute_id": 1,
//             "deleted_at": null,
//             "created_at": "2020-12-02T09:19:06.000000Z",
//             "updated_at": "2020-12-02T09:19:06.000000Z"
//           }
//         }
//       ]
//     }
//   ],
//   "first_page_url": "https://api.alaalimshop.com/user/product?page=1",
//   "from": 1,
//   "last_page": 1,
//   "last_page_url": "https://api.alaalimshop.com/user/product?page=1",
//   "next_page_url": null,
//   "path": "https://api.alaalimshop.com/user/product",
//   "per_page": 25,
//   "prev_page_url": null,
//   "to": 9,
//   "total": 9
// }`);
//       let index = 0;
//     let products = [];
//     axios.get("/user/product").then(res => {
//
//
//           response.data.'data.forEach((product) => {
//
//           console.log(product)
//           let attributesMap = new Map;
//           product.attributes.forEach((attributeResponseData, index) => {
//             // if(attributes.length > 0){
//             let values = Array.from(attributesMap.get(attributeResponseData.attributes) ? attributesMap.get(attributeResponseData.attributes).values : []);
//             values.push(attributeResponseData.value);
//             let attributeObjectData = {
//               attribute: attributeResponseData.attribute,
//               values: values
//             }
//             // console.log(values)
//             attributesMap.set(attributeResponseData.attribute_id, attributeObjectData);
//           });
//
//           product.attributes = attributesMap;
//
//           products.push(product);
//
//       })
//
//     console.log(products);
//     // })
//   },
  methods: {
    updateShippingId(e) {
      console.log(e.id);
      this.shippingAddressId = e.id;
    },
    updateOrderItems(e) {
      this.orderItems = e.items;
    },
    changeActivePage(e) {
      this.activePage = e.page;
    },
    sendOrder() {
      let items = this.orderItems;

      this.$confirm('confirm', '', 'success').then(() => {
        this.$loading.show({delay: 0})
        this.$inertia.post('/api/web/orders', {
          'shipping_address_id': this.shippingAddressId,
          'shipping_method_id': 2,
          'items': items
        }, {
          replace: false,
          preserveState: true,
          preserveScroll: true,
          onFinish: () => {
            this.$loading.hide();
            this.$alert(`You will receive payment instructions via whatsapp  to ${this.$page.client.international_phone_number}`, 'Thank You for your order', 'success');
          }
        });
      })
    }
  },
};
</script>

<style scoped>
</style>