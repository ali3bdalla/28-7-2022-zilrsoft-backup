<template>
  <div class="panel">
    <div class="panel-heading">
      <h4>{{ trans.permissions.title }}</h4>
    </div>
    <div class="panel-body">
      <div class="row">
        <div class="col-md-3" v-for="(role, index) in roles" :key="`role_${role.id}`">
          <div class="panel panel-primary" v-if="role.name != 'super admin'">
            <div class="panel-heading">
              <toggle-button
                :sync="true"
                @change="checkUnCheckRole(role, index)"
                type="checkbox"
                v-model="role.is_checked"
              ></toggle-button>
              {{ role.name }}
            </div>
            <div
              class="panel-footer"
              v-for="(permission, index2) in role.permissions"
              :key="`permission_${index2}_${index}_${role.id}_${permission.id}_${Math.random()}`"
            >
              <toggle-button
                :sync="true"
                @change="
                  checkUnCheckPermission(permission, index2, role, index, false)
                "
                type="checkbox"
                v-model="permission.is_checked"
              ></toggle-button>
              <label> {{ permission.name }}</label>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>
<script>
export default {
  props: ["initPermissions"],
  data: function () {
    return {
      trans: trans("users-page"),
      BaseApiUrl: metaHelper.getContent("BaseApiUrl"),
      roles: [],
      user_permissions: [],
    };
  },
  created() {
    this.sendRequestToLoadRolesAndPermissions();
    if (null != this.initPermissions) {
      this.user_permissions = this.initPermissions;
    }
  },
  methods: {
    sendRequestToLoadRolesAndPermissions() {
      var appVm = this;
      axios
        .get(this.BaseApiUrl + "roles_permissions")
        .then(function (response) {
          appVm.handleRoleAndPermissions(response.data);
        })
        .catch(function (error) {
          alert(error);
        })
        .finally(function () {});
    },
    handleRoleAndPermissions(data) {
      var roles = data;
    //   console.log(roles);
      var result = [];
      if (this.initPermissions != null) {
        for (let i = 0; i < roles.length; i++) {
          var role = roles[i];
          let permissions = [];
          for (let i = 0; i < role.permissions.length; i++) {
            var permission = role.permissions[i];
            permission.is_checked = db.model.in_array(
              this.user_permissions,
              role.permissions[i]["name"]
            );
            permissions.push(permission);
          }
          role.permissions = permissions;

          result.push(role);
        }

        this.roles = result;
      } else {
        this.roles = roles;
      }
    },
    checkUnCheckRole(role, index) {
      var permissionLen = role.permissions.length;
      for (var i = 0; i < permissionLen; i++) {
        var permission = role.permissions[i];
        if (role.is_checked) {
          permission.is_checked = true;
        } else {
          permission.is_checked = false;
        }
        this.checkUnCheckPermission(permission, i, role, index, true);
      }
      this.pushPermissionsUpdated();
    },
    checkUnCheckPermission(permission, index2, role, index, isBulk) {
      if (permission.is_checked) {
        this.user_permissions.push(permission.name);
      } else {
        this.user_permissions.splice(
          this.user_permissions.indexOf(permission.name),
          1
        );
      }
      if (!isBulk) {
        this.pushPermissionsUpdated();
      }
    },

    pushPermissionsUpdated() {
      this.$emit("pushPermissionsUpdated", {
        permissions: this.user_permissions,
      });
    },
  },
};
</script>

<style scoped>
.panel-footer {
  height: 43px;
}
</style>