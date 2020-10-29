<template>
  <web-layout>
    <section class="shopping-cart spad">
      <div class="container">
        <div class="row">
          <div class="col-lg-12">
            <div v-if="$store.state.cartCount >= 1" class="cart-table">
              <table>
                <thead>
                  <tr>
                    <th v-if="$page.client_logged">
                      <input v-model="selectedAll" type="checkbox" />
                    </th>
                    <th>Image</th>
                    <th class="p-name">Product Name</th>
                    <th>Price</th>
                    <th>Quantity</th>
                    <th>Total</th>
                    <th>
                      <i
                        v-if="$store.state.cartCount >= 1"
                        class="ti-close"
                      ></i>
                    </th>
                  </tr>
                </thead>
                <tbody>
                  <tr
                    v-for="(item, index) in $store.state.cart"
                    :key="index"
                    :class="[
                      parseInt(item.available_qty) < parseInt(item.quantity)
                        ? 'bg-red-200'
                        : '',
                    ]"
                    class="border-b border-gray-500"
                  >
                    <td class="w-20" v-if="$page.client_logged">
                      <input
                      v-if="parseInt(item.available_qty) >= parseInt(item.quantity)"
                        type="checkbox"
                        :checked="selectedItems.includes(item.id)"
                        @change="toggleSelectedItem(item)"
                      />
                    </td>
                    <td class="cart-pic first-row text-center">
                      <img
                        alt=""
                        class="w-20 h-20 rounded-full object-center self-center mx-auto"
                        src="https://preview.colorlib.com/theme/fashi/img/cart-page/product-1.jpg"
                      />
                    </td>
                    <td class="cart-title first-row">
                      <h5>{{ item.name }}</h5>
                    </td>
                    <td class="p-price first-row">
                      {{ parseFloat(item.price).toFixed(2) }}
                    </td>
                    <td class="qua-col first-row">
                      <div class="quantity">
                        <div class="pro-qty">
                          <button
                            class="dec qtybtn"
                            @click="updateItemQuantity(item, 'dec')"
                          >
                            -
                          </button>
                          <input v-model="item.quantity" @change="itemQtyUpdated(item)" type="text" />
                          <button
                            class="inc qtybtn"
                            @click="updateItemQuantity(item, 'inc')"
                          >
                            +
                          </button>
                        </div>
                      </div>
                    </td>
                    <td class="total-price first-row">{{ getTotal(item) }}</td>
                    <td class="close-td first-row">
                      <i class="ti-close" @click="removeCartItem(item)"></i>
                    </td>
                  </tr>
                </tbody>
              </table>
            </div>
            <div v-else class="text-center">
              <h1 class="text-6xl text-gray-500 font-bold uppercase">
                Your Cart Is Empty
              </h1>
            </div>
            <div v-if="$store.state.cartCount >= 1" class="row">
              <!--              <div class="col-lg-4">-->
              <!--                <div class="cart-buttons">-->
              <!--                  <a class="primary-btn continue-shop" href="#">Continue shopping</a>-->
              <!--                  <a class="primary-btn up-cart" href="#">Update cart</a>-->
              <!--                </div>-->
              <!--                <div class="discount-coupon">-->
              <!--                  <h6>Discount Codes</h6>-->
              <!--                  <form action="#" class="coupon-form">-->
              <!--                    <input placeholder="Enter your codes" type="text">-->
              <!--                    <button class="site-btn coupon-btn" type="submit">Apply</button>-->
              <!--                  </form>-->
              <!--                </div>-->
              <!--              </div>-->
              <div class="col-lg-4 offset-lg-8">
                <div class="proceed-checkout">
                  <ul>
                    <!--<li class="subtotal">Subtotal <span>{{}}</span></li>-->
                    <li class="cart-total">Total <span>{{parseFloat(selectedItemsTotal).toFixed(2)}}</span></li>
                  </ul>
                  <a v-if="$page.client_logged" class="proceed-btn" href="#"
                    >Checkout</a
                  >
                  <a v-else class="proceed-btn" href="/web/sign_in"
                    >LOGIN TO CHECK OUT</a
                  >
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>
  </web-layout>
</template>

<script>
import WebLayout from "../../../Layouts/WebAppLayout";

export default {
  name: "Index",

  data() {
    return {
      selectedAll:true,
      cartItems: [],
      selectedItems: [],
      selectedItemsTotal:[]
    };
  },
  components: {
    WebLayout,
  },

  created() {
    // this.$alert("Hello Vue Simple Alert.");

    //     this.$fire({
    //   title: "Error",
    //   text: "Selected Product Out Of Stock",
    //   type: "error",
    //   timer: 000
    // }).then(r => {
    //   console.log(r.value);
    // });

    this.updateCartItems();
  },
  methods: {
    updateItemQuantity(item, type) {
      let product = this.$store.state.cart.find(
        (product) => product.id == item.id
      );

      let quantity = parseInt(product.quantity);
      if (type == "inc") {
        quantity += parseInt(1);
      } else {
        quantity -= parseInt(1);
      }

      this.$store.commit("addToCart", { item: item, quantity: quantity });
    },

    updateCartItems() {
      if (this.$store.state.cartCount > 0) {
        let items = [];

        for (const item of this.$store.state.cart) {
          if (item.id) {
            items.push(item.id);
          }
        }

        let appVm = this;
        axios
          .post("/api/web/cart/get_items_details", {
            items: items,
          })
          .then((res) => {
            let responseItems = res.data;
            responseItems.forEach(function(item){
              let productItem = appVm.$store.state.cart.find(
              product => product.id === item.id
              );


              if (productItem) {
                appVm.$store.commit("updateItemCartAvailableQty",{
                  item:productItem,
                  available_qty:item.available_qty
                });

                if (
                  parseInt(item.available_qty) >= parseInt(productItem.quantity)
                ) {
                  appVm.selectedItems.push(productItem.id);

                }
              }



            })
        
          })
          .catch((error) => {
            // callback(res);
          });
      }
    },


    itemQtyUpdated(item)
    {

    
      let quantity = parseInt(item.quantity);

      if(quantity>=0)
      {
        this.$store.commit("addToCart", { item: item, quantity: quantity });
      }
      // if (type == "inc") {
      //   quantity += parseInt(1);
      // } else {
      //   quantity -= parseInt(1);
      // }



      // console.log(item.quantity)
      // console.log(item);
    },
    toggleSelectedItem(item)
    {
      let product = this.selectedItems.find(p => p.id == item.id);
      if(product)
      {
        let index = this.selectedItems.indexOf(product);
        this.selectedItems.splice(index,1);
      }else
      {
        this.selectedItems.push(item);
      }
    },
    getTotal(item) {
      return parseFloat(parseInt(item.quantity) * item.price).toFixed(2);
    },
    removeCartItem(item) {
      this.$store.commit("removeFromCart", item);
    },
  },

  watch:{
    selectedAll(value)
    {
      if(value)
      {
        this.updateCartItems();
      }else
      {
        this.selectedItems = [];
      }
    },
    selectedItems(value)
    {
      let appVm=  this;
      for (let index = 0; index < value.length; index++) {
        const element = value[index];
        let amount = 0;
        let product = this.$store.state.cart.find(product => product.id === element);
        if(product)
        {
          amount+=appVm.getTotal(product);
        }

        appVm.selectedItemsTotal = amount;
      }

    }
  },
  computed: {},
};
</script>

<style scoped>
</style>