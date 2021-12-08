<template>
  <div class="panel-heading">
    <div class="row">
      <div class="col-md-6">
        <button
            class="btn btn-custom-primary"
        >
          <i class="fa fa-save"></i> Save
        </button>

      </div>
    </div>
    <div class="row">
      <div class="col-md-4  col-sm-12">
        <accounting-select-with-search-layout-component
            :default-index="userId"
            :no_all_option="true"
            :options="users"
            identity="001"
            index="001"
            label_text="locale_name"
            placeholder="الهوية"
            title="الهوية"
            @valueUpdated="userChanged"
        >
        </accounting-select-with-search-layout-component>

      </div>
      <div class="col-md-2  col-sm-12">
        <AliceNameFormPop @updated="aliceNameChanged"></AliceNameFormPop>
      </div>
      <div class="col-md-6  col-sm-12">
        <input
            :value="new Date().toDateString()"
            class="form-control"
            disabled
            type="text"
        />
      </div>
    </div>
    <div class="row">
      <div class="col-md-6 col-sm-12">
        <accounting-select-with-search-layout-component
            :default-index="managerId"
            :no_all_option="true"
            :options="managers"
            identity="002"
            index="002"
            label_text="locale_name"
            placeholder="المستخدم"
            title="المستخدم"
            @valueUpdated="managerChanged"
        >
        </accounting-select-with-search-layout-component>

      </div>

    </div>
  </div>
</template>

<script>
import AliceNameFormPop from './AliceNameFormPop'

export default {
  components: { AliceNameFormPop },
  data () {
    return {
      users: [],
      managers: [],
      userId: null,
      managerId: null,
      aliceName: null
    }
  },
  mounted () {
    this.fetchUsers()
    this.fetchManagers()
  },
  methods: {
    aliceNameChanged ({ alice_name }) {
      this.aliceName = alice_name
    },
    userChanged ({ value }) {
      this.$emit('userChanged', value.id)
    },
    managerChanged ({ value }) {
      this.$emit('managerChanged', value.id)
    },
    fetchUsers () {
      axios.get('/api/users').then(res => {
        this.users = res.data
      })
    },
    fetchManagers () {
      axios.get('/api/managers').then(res => {
        this.managers = res.data
      })
    }
  },
  name: 'FormHeader'
}
</script>

<style scoped>

</style>
