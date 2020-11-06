<template>
  <div class="data__table__dropdown">
    <button class="data__table__dropdown__button" @click="isOpen = !isOpen">
      {{ $translator.table['options'] }}
    </button>
    <div class="data__table__dropdown__list">
      <ul class="data__table__dropdown__list__items">
        <li v-for="(item,index) in items" :key="index"><a :href="item.link">{{ item.name }}</a></li>
      </ul>
    </div>
  </div>
</template>

<script>
export default {
  props: {
    items: {
      type: Array,
      required: true
    }
  },
  data() {
    return {
      isOpen: false
    };
  },
  name: "Dropdown"
}
</script>

<style scoped>

</style>