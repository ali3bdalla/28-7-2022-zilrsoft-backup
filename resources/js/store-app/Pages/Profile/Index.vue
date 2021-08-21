<template>
  <WebAppLayout>
    <div class="container my-5">
      <el-tabs type="border-card" >
        <el-tab-pane label="Config">
          <span slot="label"><i class="el-icon-date"></i> {{ $page.$t.profile.orders }}</span>

          <ViewOrdersComponent></ViewOrdersComponent>
        </el-tab-pane>
        <el-tab-pane>
          <span slot="label"><i class="el-icon-date"></i> {{ $page.$t.profile.my_info }}</span>
          <div>
           <UpdateInfoComponent></UpdateInfoComponent>
          </div>
        </el-tab-pane>
        <el-tab-pane label="Role">
          <span slot="label"><i class="el-icon-date"></i> {{ $page.$t.profile.phone_number }}</span>
          <ChangePhoneNumberComponent></ChangePhoneNumberComponent>
        </el-tab-pane>
        <el-tab-pane label="Role">
          <span slot="label"><i class="el-icon-date"></i> {{ $page.$t.profile.password }}</span>
          <ChangePasswordComponent></ChangePasswordComponent>
        </el-tab-pane>
        <el-tab-pane label="Task">
          <span slot="label"><i class="el-icon-date"></i> {{ $page.$t.profile.address }}</span>
          <ViewShippingAddressesComponent></ViewShippingAddressesComponent>
        </el-tab-pane>

        <!-- <el-tab-pane label="Task">
          <span slot="label"><i class="el-icon-date"></i> {{ $page.$t.profile.payments }}</span>
          <ViewPaymentsComponent></ViewPaymentsComponent>
        </el-tab-pane> -->
      </el-tabs>
    </div>
  </WebAppLayout>
</template>

<script>
import WebAppLayout from '../../Layouts/WebAppLayout'
import UpdateInfoComponent from '../../../components/Web/Profile/UpdateInfoComponent'
import ViewOrdersComponent from '../../../components/Web/Profile/ViewOrdersComponent'
import ChangePhoneNumberComponent from '../../../components/Web/Profile/ChangePhoneNumberComponent'
import ChangePasswordComponent from '../../../components/Web/Profile/ChangePasswordComponent'
import ViewPaymentsComponent from '../../../components/Web/Profile/ViewPaymentsComponent'
import ViewShippingAddressesComponent from '../../../components/Web/Profile/ViewShippingAddressesComponent'

export default {
  name: 'Index',
  components: {
    ViewShippingAddressesComponent,
    ViewPaymentsComponent,
    ChangePasswordComponent,
    ViewOrdersComponent,
    UpdateInfoComponent,
    ChangePhoneNumberComponent,
    WebAppLayout
  }
}
</script>
