<template>
  <div>
    <div v-if="orders.length === 0">
      <div class="text-center flex items-center justify-center py-6">
          <h1 class="text-xl text-gray-500">
            {{ $page.props.$t.order.no_order }}
          </h1>
      </div>
    </div>
    <div v-else>
      <el-table :data="orders" style="width: 100%" class="text-center">
        <el-table-column
          prop="created_at"
          :label="$page.props.$t.order.created_at"
        >
        </el-table-column>
        <el-table-column prop="id" :label="$page.props.$t.order.id"> </el-table-column>
        <el-table-column prop="status" :label="$page.props.$t.order.status">
             <template slot-scope="scope">
              <el-tag class="w-full" :type="scope.row.status === 'delivered' ? 'success' : scope.row.status === 'canceled' ? 'danger': 'plain'" effect="dark">{{ $page.props.$t.order.statuses[scope.row.status] }}</el-tag>
          </template>
        </el-table-column>
        <el-table-column prop="net" :label="$page.props.$t.order.amount">
            <template slot-scope="scope">
              <RenderMoneyComponent :money="scope.row.net"></RenderMoneyComponent> {{ $page.props.$t.products.sar }}
           </template>
        </el-table-column>
        <el-table-column prop="payment_method" :label="$page.props.$t.order.payment_method">
           <template slot-scope="scope">
              {{ $page.props.$t.cart[scope.row.payment_method] }}
          </template>
        </el-table-column>
        <el-table-column prop="shipping_method" :label="$page.props.$t.order.shipping_method">
           <template slot-scope="scope">
              {{ scope.row.shipping_method.locale_name }}
           </template>
        </el-table-column>
        <el-table-column prop="tracking_number" :label="$page.props.$t.order.tracking_number">
           <template slot-scope="scope">
              <a v-if="scope.row.shipping_method_id  ===  2" target="_blank" :href="`https://www.smsaexpress.com/ar/trackingdetails?tracknumbers=${scope.row.tracking_number}`">{{ scope.row.tracking_number }}</a>
              <div v-else>{{ scope.row.tracking_number }}</div>
           </template>
        </el-table-column>
         <el-table-column prop="pdf_url" :label="$page.props.$t.order.pdf">
           <template slot-scope="scope">
              <a :href="scope.row.pdf_url" v-if="scope.row.pdf_path"><el-button size="small">{{$page.props.$t.common.download}}</el-button></a>
           </template>
        </el-table-column>

      </el-table>
    </div>
  </div>
</template>

<script>
import RenderMoneyComponent from '../Utility/RenderMoneyComponent.vue'
export default {
  components: { RenderMoneyComponent },
  order: 'ViewOrderComponent',
  created () {
    this.fetch()
  },
  data () {
    return {
      orders: [],
      first_page: 0,
      current_page: 0,
      next_page_url: '',
      last_page: 0,
      total: 0
    }
  },
  methods: {
    handleClick () {
      console.log('click')
    },
    fetch () {
      axios
        .get('/api/web/orders')
        .then((response) => {
          this.orders = response.data.data
          this.first_page = response.data.first_page
          this.current_page = response.data.current_page
          this.per_page = response.data.per_page
          this.next_page_url = response.data.next_page_url
          this.last_page = response.data.last_page
        })
        .catch((error) => {
          console.log(error.message)
        })
    }
  }
}
</script>

<style>
.el-table .cell {
  text-align: center !important;
}
</style>
