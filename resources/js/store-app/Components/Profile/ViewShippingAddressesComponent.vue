<template>
  <el-table :data="$page.props.shipping_addresses" style="width: 100%">
    <el-table-column
      prop="first_name"
      :label="$page.props.$t.profile.first_name"
      width="150"
    >
    </el-table-column>
    <el-table-column
      prop="last_name"
      :label="$page.props.$t.profile.last_name"
      width="150"
    >
    </el-table-column>
    <el-table-column
      prop="country"
      :label="$page.props.$t.profile.country"
      width="200"
    >
      <template slot-scope="scope" >
        <div>{{scope.row.city.country.locale_name}}</div>
      </template>
    </el-table-column>
    <el-table-column
      prop="city"
      :label="$page.props.$t.profile.city"
      width="150"
    >
      <template slot-scope="scope" >
        <div>{{scope.row.city.locale_name}}</div>
      </template>
    </el-table-column>

   <el-table-column
      prop="phone_number"
      :label="$page.props.$t.profile.phone_number"
      width="150"
    >
    </el-table-column>

     <el-table-column
      prop="street_name"
      :label="$page.props.$t.profile.street_name"
      width="150"
    >
    </el-table-column>
     <el-table-column
      prop="description"
      :label="$page.props.$t.products.description"
      width="300"
    >
    </el-table-column>

  </el-table>
</template>

<script>
export default {
  name: 'ViewShippingAddressesComponent',
  methods: {
    handleClick () {
      console.log('click')
    }
  },
  data () {
    return {}
  }
}
</script>

<style scoped>
</style>
