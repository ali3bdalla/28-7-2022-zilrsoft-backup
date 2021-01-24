<template>
    <div class="product__search-only-available">
        <toggle-button @change="onChangeEventHandler" :height="36" :width="150" v-model="myDataVariable" :labels="{checked: $page.$t.products.sorting_only_available, unchecked: $page.$t.products.sorting_only_available}"/>
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
