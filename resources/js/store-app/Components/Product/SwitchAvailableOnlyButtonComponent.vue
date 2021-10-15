<template>
    <div class="product__search-only-available">
        <toggle-button @change="onChangeEventHandler" :height="25" :width="120" v-model="myDataVariable" :labels="{checked: $page.props.$t.products.sorting_only_available, unchecked: $page.props.$t.products.sorting_only_available}"/>
    </div>
</template>

<script>

export default {
  data () {
    return {
      myDataVariable: false
    }
  },
  methods: {
    onChangeEventHandler (e) {
      this.$emit('changed', this.myDataVariable)
    }
  }
}
</script>

<style>

</style>
