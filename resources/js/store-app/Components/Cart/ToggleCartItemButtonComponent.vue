<template>
  <div>
    <button
      v-if="!existsCartItem"
      class="product__add-to-cart-button"
      @click="addItem"
    >
      <svg
        class="product__add-to-cart-icon"
        fill="none"
        stroke="currentColor"
        viewBox="0 0 24 24"
        xmlns="http://www.w3.org/2000/svg"
      >
        <path
          d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"
          stroke-linecap="round"
          stroke-linejoin="round"
          stroke-width="2"
        />
      </svg>
      <span v-if="item.available_qty">{{
        $page.props.$t.products.add_to_cart
      }}</span>
      <span v-else>{{ $page.props.$t.products.add_to_favourite }}</span>
    </button>
    <button v-else class="product__remove-from-cart-button" @click="removeItem">
      <svg
        class="product__add-to-cart-icon"
        fill="none"
        stroke="currentColor"
        viewBox="0 0 24 24"
        xmlns="http://www.w3.org/2000/svg"
      >
        <path
          d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"
          stroke-linecap="round"
          stroke-linejoin="round"
          stroke-width="2"
        />
      </svg>
      <span v-if="item.available_qty">{{
        $page.props.$t.products.remove_to_cart
      }}</span>
      <span v-else>{{ $page.props.$t.products.remove_from_favourite }}</span>
    </button>
  </div>
</template>

<script>
export default {
  props: ["item"],
  computed: {
    existsCartItem() {
      return this.$page.props.cart.items.find(
        (product) => product.item_id === this.item.id
      );
    },
  },
  methods: {
    addItem() {
      axios
        .post("/api/web/cart/add_item", {
          item_id: this.item.id,
        })
        .then((res) => {
          this.$page.props.cart.items.push(res.data);
          ++this.$page.props.cart.items_count;
        });
    },
    removeItem() {
      axios
        .delete("/api/web/cart/remove_item", {
          params: { cart_item_id: this.existsCartItem?.id },
        })
        .then((res) => {
          this.$page.props.cart.items.splice(
            this.$page.props.cart.items.indexOf(this.existsCartItem),
            1
          );
          --this.$page.props.cart.items_count;
        });
    },
  },
};
</script>

<style></style>>
