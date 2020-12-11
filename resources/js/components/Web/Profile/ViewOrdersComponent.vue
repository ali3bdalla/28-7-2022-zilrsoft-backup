<template>
  <el-table
      :data="tableData"
      style="width: 100%">
    <el-table-column
        fixed
        prop="date"
        label="Date">
    </el-table-column>
    <el-table-column
        prop="order"
        label="Order">
    </el-table-column>
    <el-table-column
        prop="status"
        label="Status">
    </el-table-column>
    <el-table-column
        prop="amount"
        label="Amount">
    </el-table-column>
  <!--    <el-table-column-->
  <!--        prop="address"-->
  <!--        label="Address"-->
  <!--        width="300">-->
  <!--    </el-table-column>-->
  <!--    <el-table-column-->
  <!--        prop="zip"-->
  <!--        label="Zip"-->
  <!--        width="120">-->
  <!--    </el-table-column>-->
    <el-table-column
        fixed="right"
        label="Operations"
        width="165">
      <template slot-scope="scope">
        <el-button @click="handleClick" type="text" size="small">Detail</el-button>

<!--        To Do => this should appear after the product status in shipping-->
        <el-button type="text" size="small">Track</el-button>
        <el-button type="text" size="small">ReOrder</el-button>
      </template>
    </el-table-column>
  </el-table>
</template>

<script>
export default {
  order: "ViewOrderComponent",
  methods: {
    handleClick() {
      console.log('click');
    }
  },
  data() {
    return {
      tableData: [{
        date: '2016-05-03',
        order: 'Tom',
        status: 'California',
        amount: 'Los Angeles',
        address: 'No. 189, Grove St, Los Angeles',
        zip: 'CA 90036',
        tag: 'Home'
      }, {
        date: '2016-05-02',
        order: 'Tom',
        status: 'California',
        amount: 'Los Angeles',
        address: 'No. 189, Grove St, Los Angeles',
        zip: 'CA 90036',
        tag: 'Office'
      }, {
        date: '2016-05-04',
        order: 'Tom',
        status: 'California',
        amount: 'Los Angeles',
        address: 'No. 189, Grove St, Los Angeles',
        zip: 'CA 90036',
        tag: 'Home'
      }, {
        date: '2016-05-01',
        order: 'Tom',
        status: 'California',
        amount: 'Los Angeles',
        address: 'No. 189, Grove St, Los Angeles',
        zip: 'CA 90036',
        tag: 'Office'
      }]
    }
  }
}
</script>

<style scoped>

</style>