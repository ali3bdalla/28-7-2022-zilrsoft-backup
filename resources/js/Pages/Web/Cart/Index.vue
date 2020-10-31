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
                    <input type="checkbox"/>
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
                  <td v-if="$page.client_logged" class="w-20">
                    <input
                        v-if="
                          parseInt(item.available_qty) >=
                          parseInt(item.quantity)
                        "
                        :checked="orderProducts.includes(item.id)"
                        type="checkbox"
                        @change="toggleOrderProduct(item)"
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
                            @click="updateOrderProductQuantity(item, 'dec')"
                        >
                          -
                        </button>
                        <input
                            v-model="item.quantity"
                            type="text"
                            @change="itemQtyUpdated(item)"
                        />
                        <button
                            class="inc qtybtn"
                            @click="updateOrderProductQuantity(item, 'inc')"
                        >
                          +
                        </button>
                      </div>
                    </div>
                  </td>
                  <td class="total-price first-row">{{ getProductTotal(item) }}</td>
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


            <div v-if="$page.client_logged" class="bg-gray-100 shadow p-2 rounded-lg mb-5" >
              <h3 class="font-bold text-gray-500  text-2xl">Shipping Address</h3>
              <div class="my-4 grid grid-cols-2 gap-3">
                <div>
                  <input class="form-control" placholder="First Name" :value="$page.client.name"/>
                </div>
                <div>
                  <input class="form-control" placholder="Last Name" :value="$page.client.name"/>
                </div>
                <div>
                  <input class="form-control" value="saudi" disabled/>
                </div>

              </div>

            </div>
            <div v-if="$store.state.cartCount >= 1" class="row">
              <div class="col-lg-4 offset-lg-8">
                <div v-for="shipper in shippingCompanies" :key="shipper.title" class="bg-gray-100 flex justify-between px-2 py-1 mb-2 border border-gray-800 shadow-lg items-center rounded ">
                  <div>
                    <img class="h-12 w-48 object-contain object-left "
                         :src="shipper.image"/>
                  </div>
                  <div class="text-right">
                    <input type="radio" name="shipper" :checked="shipper.title === 'SMSA'" :disabled="shipper.title === 'DHL'"/>
                  </div>

                </div>

                <div class="proceed-checkout">
                  <ul>
                    <!--                    <li class="subtotal w-20 h-20 object-cover">-->
                    <!--                      <div class="h-12  overflow-hidden ">-->
                    <!--&lt;!&ndash;                        <img class="h-12" src="https://upload.wikimedia.org/wikipedia/commons/thumb/d/d4/SMSA_Express_logo_%28English_version%29.svg/816px-SMSA_Express_logo_%28English_version%29.svg.png"/>&ndash;&gt;-->


                    <!--                      </div>-->
                    <!--                      <span><input type="checkbox"/></span>-->

                    <!--                    </li>-->

                    {{cites.length}}
                    <li class="cart-total">
                      Total
                      <span>{{ parseFloat(orderTotal).toFixed(2) }}</span>
                    </li>
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
      orderTotal: 0,
      cart: [],
      orderProducts: [],
    };
  },
  computed:{

    cites()
    {
      return "Dammam:Dhahran:Jeddah:Jouf:Khamis Mushayat:Khayber:Madinah:Riyadh:Tabuk:Taif:Yanbu:Khubar:Makkah:Buraydah:Unayzah:Hail:Abha:Hufuf:Jubail:Qatif:Khafji:Ras Tannurah:Buqaiq:Sayhat:Safwa:Jazan:Sabya:Abu Arish:Hafar Al Baten:Rabigh:Lith:Ula:Duwadimi:Majmaah:Zulfi:Afif:Arar:Kharj:Muzahmiyah:Ranyah:Turbah:Taima:Dhuba:Qurayyat:Turayf:Wadi Dawasir:Quwayiyah:Muhayil:Salwa:Rafha:Baha:Baljurashi:Qunfudhah:Mukhwah:Mandaq:Qilwah:Atawlah:Aqiq:Mudhaylif:Nairiyah:Qarya Al Uliya:Tarut:Anak:Udhayliyah:Najran:Sharourah:Habounah:Samtah:Ahad Al Masarhah:Baysh:Darb:Dhamad:Bani Malek:Furasan:Tuwal:Shuqayq:Badr:Jamoum:Khulais:Bahrah:Masturah:Shaibah:Rass:Bukayriyah:Badaya:Riyadh Al Khabra:Uyun Al Jiwa:Nabhaniah:Sajir:Khabra:Uqlat As Suqur:Rafayaa Al Gimsh:Nabhaniah:Dukhnah:Nifi:Skakah:Dawmat Al Jandal:Namas:Sapt Al Ulaya:Bellasmar:Tanumah:Bashayer:Jadidah:Rafha:Qaysumah:Baqaa:Shinan:Muqiq:Shamli:Bishah:Dhahran Al Janoub:Taberjal:Baysh:Sabt Alalayah:Rijal Alma:Tathleeth:Bareq:Balsamar:Majardah:Ahad Rafidah:Al Hasa:Shaqra:Aflaj:Al Qouz:Ummlujj:Wajh:Midhnab:Artawiyah:Hawtat Sudayr :Ghat:Mubarraz:Thuwal:Dhalim:Khurmah:Qunfudhah:Tubarjal:Haql:Dhurma:Rumah:Huraymila:Dair:Batha:Hawtat Bani Tamim:Tareeb:Namerah:Alardhah:Sulayyil:Sarat Abida:Oyun:Adham:Muwayh:Tumair:Hanakiyah:Edabi:Marat:Aflaj Layla:Dilam:Hurayyiq:Haradh:Bijadiyah:Ras AlKhair:Tanajib:Sarrar:Saffaniyah:Al Hait:Umlej:".split(":");
    },
    shippingCompanies()
    {
      return [
        {
          title:"SMSA",
          image:"https://upload.wikimedia.org/wikipedia/commons/thumb/d/d4/SMSA_Express_logo_%28English_version%29.svg/816px-SMSA_Express_logo_%28English_version%29.svg.png",
        } ,
        {
          title:"DHL",
          image:"https://upload.wikimedia.org/wikipedia/commons/thumb/a/ac/DHL_Logo.svg/352px-DHL_Logo.svg.png",
        }
      ];
    }
  },
  components: {
    WebLayout,
  },

  created() {
    this.validateCart();
  },
  methods: {

    findProductById(id) {
      return this.$store.state.cart.find(
          p => p.id === id
      )
    },
    findProduct(product) {
      return this.findProductById(product.id);
    },

    updateProduct(payload) {
      this.$store.commit("addToCart", payload);
    },

    updateOrderProductQuantity(item, type) {
      let product = this.findProduct(item);
      let quantity = parseInt(product.quantity);
      if (type === "inc") {
        quantity += 1;
      } else {
        quantity -= 1;
      }
      this.updateProduct({item: item, quantity: quantity});
    },


    validateCart() {
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
              responseItems.forEach(function (item) {
                let product = appVm.findProduct(item);

                if (product) {
                  appVm.$store.commit("updateItemCartAvailableQty", {
                    item: product,
                    available_qty: item.available_qty,
                  });
                  if (
                      parseInt(item.available_qty) >= parseInt(product.quantity)
                  ) {
                    appVm.orderProducts.push(product.id);
                  }
                }
              });
              appVm.updateOrderTotal();
            })
            .catch((error) => {
              // callback(res);
            });
      }
    },

    itemQtyUpdated(item) {
      let quantity = parseInt(item.quantity);

      if (quantity >= 0) {
        this.$store.commit("addToCart", {item: item, quantity: quantity});
      }

      this.updateOrderTotal();
    },
    toggleOrderProduct(item) {
      let index = this.orderProducts.indexOf(item.id);
      console.log(index);
      if (index) {
        this.orderProducts.splice(index, 1);
      } else {
        this.orderProducts.push(item.id);
      }
      this.updateOrderTotal();
    },
    getProductTotal(item) {
      let total = parseFloat(item.price) * parseInt(item.quantity);
      return total.toFixed(2);
    },

    removeCartItem(item) {
      this.$store.commit("removeFromCart", item);
    },

    updateOrderTotal() {
      let appVm = this;
      let amount = 0;
      for (let index = 0; index < this.orderProducts.length; index++) {
        const element = this.orderProducts[index];
        let product = this.findProductById(element);
        if (product) {
          amount += parseFloat(appVm.getProductTotal(product));
        }
      }
      this.orderTotal = amount;
    },
  },
};
</script>

<style scoped>
</style>