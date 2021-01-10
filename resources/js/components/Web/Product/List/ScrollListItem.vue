<template>
  <!-- <ProductListItemComponent
    :key="source.id"
    :index="index"
    :item="source"
  ></ProductListItemComponent> -->
    <div class="item">

  <div>{{ index }} - {{ source.name }}</div>

    </div>
</template>

<script>
import ProductListItemComponent from "./../ProductListItemComponent";

export default {
  props: {
    index: {
      // index of current item
      type: Number,
    },
    source: {
      // here is: {uid: 'unique_1', text: 'abc'}
      type: Object,
      default() {
        return {};
      },
    },
  },
};
</script>

<style scoped>
.item {
  display: flex;
  flex-direction: column;
  border-bottom: 1px solid lightgrey;
  padding: 1em;
}
</style>
