<template>
  <div class="">
    <loading :active.sync="isLoading"
             :can-cancel="true"
             :is-full-page="true"></loading>
    <div class=" text-center my-10 shadow-xl">
      <div class="form-group text-center">
        <div :dir="'rtl'">
          <treeselect
              v-model="categoryIdMutation"
              :disable-branch-nodes="false"
              :loadOptions=" loadOptions "
              :options="categories"
              :show-count="true"
              @select="categoryListUpdated"
          ></treeselect>
        </div>

        <div class="my-5">
          <button class="btn btn-primary" @click="showAll">عرض الكل</button>
        </div>
        <br>
      </div>

    </div>

    <table class="table table-light text-center table-bordered">
      <thead>
      <tr>
        <td class="text-center" scope="col">#</td>
        <td class="text-center" scope="col">الباركود</td>
        <td class="text-center" scope="col">اسم المنتج</td>
        <td class="text-center btn cursor-pointer  " scope="col" @click="filterByModel">
           <button> رقم الموديل </button> ({{ getTrans(activeModel)}})
        </td>
        <td class="text-center" scope="col">عدد الصور ({{ completedProducts }})</td>
        <td class="text-center" scope="col">#</td>
      </tr>
      </thead>
      <tbody>

      <tr v-for="(product,index) in products" :key="product.id" :class="{'bg-success':product.attachments_count == 4}">
        <td>{{ parseInt(itemsCount) - index }}</td>
        <td>
          <a :href="`https://www.google.com/search?q=${product.barcode}&safe=strict&source=lnms&tbm=isch&sa=X&tbs=isz:m`"
             target="_blank">{{ product.barcode }}</a></td>
        <td>
          <a :href="`https://www.google.com/search?q=${product.barcode}&safe=strict&source=lnms&tbm=isch&sa=X&tbs=isz:m`"
             target="_blank">{{ product.name }}</a>
          <br>
          <a :href="`https://www.google.com/search?q=${product.ar_name}&safe=strict&source=lnms&tbm=isch&sa=X&tbs=isz:m`"
             target="_blank">{{ product.ar_name }}</a>

        </td>

        <td>
          <a :href="`https://www.google.com/search?q=${product.model_name}&safe=strict&source=lnms&tbm=isch&sa=X&tbs=isz:m`"
             target="_blank">{{ product.model_name }}</a>
          <br>
          <a :href="`https://www.google.com/search?q=${product.model_ar_name}&safe=strict&source=lnms&tbm=isch&sa=X&tbs=isz:m`"
             target="_blank">{{ product.model_ar_name }}</a>

        </td>

        <td>{{ product.attachments_count }}</td>
        <td><a :href="`/images_upload/${product.id}`" class="btn btn-primary" target="_blank"> المرفقات</a>
        </td>
      </tr>
      </tbody>

    </table>
  </div>

</template>

<script>
import Treeselect from '@riophae/vue-treeselect'
import '@riophae/vue-treeselect/dist/vue-treeselect.css'
import Loading from 'vue-loading-overlay'
// Import stylesheet
import 'vue-loading-overlay/dist/vue-loading.css'
export default {
  data () {
    return {
      isLoading: false,
      activeModel: 'all',
      categoryIdMutation: 0
    }
  },
  props: ['products', 'categories', 'categoryId', 'itemsCount', 'completedProducts', 'queryActiveModel'],
  components: { Treeselect, Loading },
  created () {
    this.categoryIdMutation = this.categoryId

    this.activeModel = this.queryActiveModel
  },
  methods: {

    getTrans (key) {
      if (key == 'all') { return 'الكل' }

      if (key == 'not_empty') { return 'يوجد موديل' }

      if (key == 'empty') { return 'لا يوجد موديل' }
    },
    filterByModel () {
      this.isLoading = true
      if (this.activeModel === 'all') {
        this.activeModel = 'not_empty'
      } else {
        if (this.activeModel === 'not_empty') {
          this.activeModel = 'empty'
        } else {
          this.activeModel = 'all'
        }
      }
      window.location.href = '?active_model=' + this.activeModel
    },
    categoryListUpdated (e) {
      location.href = '/images_upload?category_id=' + e.id
    },

    showAll () {
      location.href = '/images_upload'
    },

    loadOptions (e) {

    }
  }
}

</script>

<style>
.vue-treeselect {
  text-align: center !important;
}

</style>
