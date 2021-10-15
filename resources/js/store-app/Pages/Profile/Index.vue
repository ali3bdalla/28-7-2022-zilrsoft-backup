<template>
  <WebAppLayout>
    <div class="container my-5">
      <el-tabs type="border-card" >
        <el-tab-pane label="Config">
          <span slot="label"><i class="el-icon-date"></i> {{ $page.props.$t.profile.orders }}</span>

          <ViewOrdersComponent></ViewOrdersComponent>
        </el-tab-pane>
        <el-tab-pane>
          <span slot="label"><i class="el-icon-date"></i> {{ $page.props.$t.profile.my_info }}</span>
          <div>
           <UpdateInfoComponent></UpdateInfoComponent>
          </div>
        </el-tab-pane>
        <el-tab-pane label="Role">
          <span slot="label"><i class="el-icon-date"></i> {{ $page.props.$t.profile.phone_number }}</span>
          <ChangePhoneNumberComponent></ChangePhoneNumberComponent>
        </el-tab-pane>
        <el-tab-pane label="Role">
          <span slot="label"><i class="el-icon-date"></i> {{ $page.props.$t.profile.password }}</span>
          <ChangePasswordComponent></ChangePasswordComponent>
        </el-tab-pane>
        <el-tab-pane label="Task">
          <span slot="label"><i class="el-icon-date"></i> {{ $page.props.$t.profile.address }}</span>
          <ViewShippingAddressesComponent></ViewShippingAddressesComponent>
        </el-tab-pane>
      </el-tabs>
    </div>
  </WebAppLayout>
</template>

<script>
import WebAppLayout from '../../Layouts/WebAppLayout'
import UpdateInfoComponent from '../../Components/Profile/UpdateInfoComponent'
import ViewOrdersComponent from '../../Components/Profile/ViewOrdersComponent'
import ChangePhoneNumberComponent from '../../Components/Profile/ChangePhoneNumberComponent.vue'
import ChangePasswordComponent from '../../Components/Profile/ChangePasswordComponent.vue'
import ViewShippingAddressesComponent from '../../Components/Profile/ViewShippingAddressesComponent.vue'

export default {
  name: 'Index',
  components: {
    ViewShippingAddressesComponent,
    ChangePasswordComponent,
    ViewOrdersComponent,
    UpdateInfoComponent,
    ChangePhoneNumberComponent,
    WebAppLayout
  }
}
</script>
