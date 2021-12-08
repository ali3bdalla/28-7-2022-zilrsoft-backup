<template>
  <div class="panel panel-default">
    <FormHeader @managerChanged="managerChanged" @userChanged="userChanged"/>
    <FormItemPicker></FormItemPicker>
    <div class="panel-body">
      <ItemsForm></ItemsForm>
    </div>
  </div>
</template>

<script>
import FormHeader from './FormHeader'
import FormItemPicker from './FormItemPicker'
import ItemsForm from './FormItems'

export default {
  props: {
    loggedManager: {
      type: Object,
      required: true
    }
  },
  name: 'Form',
  components: {
    ItemsForm,
    FormItemPicker,
    FormHeader
  },
  data () {
    return {
      totals: Object,
      userId: Number,
      managerId: Number
    }
  },
  methods: {
    userChanged (userId) {
      this.userId = userId
    },
    managerChanged (managerId) {
      this.managerId = managerId
    },
  }
}
</script>

<style scoped>

</style>
