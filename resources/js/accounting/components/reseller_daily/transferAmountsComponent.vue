<template>
  <div class="panel">
    <div class="panel-body">
      <div class="row">
        <div class="col-md-6">
          <label>من</label>
          <br/>
          <select
              v-model="activeGateway"
              class="form-control"
              @change="updateGateway"
          >
            <option
                v-for="gateway in gateways"
                :key="gateway.id"
                :value="gateway"
            >
              {{ gateway.locale_name }} {{ gateway.current_amount }}
            </option>
          </select>
        </div>

        <div class="col-md-6">
          <label>الى</label>
          <br/>
          <select
              v-model="receiveGateway"
              class="form-control"
              @change="toUpdateReceiverGateway"
          >
            <option
                v-for="(gateway, index) in toGateways"
                :key="index"
                :value="gateway"
            >
              {{ gateway.locale_name }}
            </option>
          </select>
        </div>
      </div>

      <div class="row">
        <div class="col-md-6">
          <label>المبلغ</label>
          <br/>
          <input
              v-model.number="amount"
              class="form-control"
              type="text"
              @keyup="toUpdateAmount"
          />
        </div>

        <div class="col-md-6">
          <label>المتبقي</label>
          <br/>
          <input
              v-model="remaining"
              class="form-control"
              disabled
              type="text"
          />
        </div>
      </div>
      <div class="row">
        <div class="col-md-6">
          <button class="btn btn-custom-primary" @click="toPushDataToServer">
            تحويل
          </button>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  props: ["gateways", "toGateways"],
  data: function () {
    return {
      removeExists: false,
      activeGateway: {
        id: 0,
        locale_name: "",
      },
      receiveGateway: {
        id: 0,
        locale_name: "",
      },
      amount: 0,
      remaining: 0,
      gateway_id: 0,
      receiver_gateway_id: 0,
      receiver_id: 0,
    };
  },
  methods: {
    updateGateway() {
      this.gateway_id = this.activeGateway.id;
    },

    toUpdateAmount() {
      if (this.activeGateway !== null) {
        this.remaining = parseFloat(parseFloat(this.activeGateway.current_amount) - parseFloat(this.amount)).toFixed(2);
      }
    },

    toUpdateReceiverGateway() {
      this.receiver_gateway_id = this.receiveGateway.id;
      this.receiver_id = this.receiveGateway.receiver_id;
    },
    toPushDataToServer() {
      axios
          .post("/api/daily/wallet/issue_transfer", {
            amount: this.amount,
            gateway_id: this.gateway_id,
            remove_existing_pending_transactions: this.removeExists,
            receiver_gateway_id: this.receiver_gateway_id,
            receiver_id: this.receiver_id,
          })
          .then((response) => {
            window.location = "/daily/reseller/accounts_transactions";
          })
          .catch((error) => {
            if (error.response.data.errors) {
              if (error.response.data.errors.exists_pending_transactions) {
                this.$confirm(
                    "يوجد عمليات تحويل سابقة لم يتم تاكيدها حتى الان ، يجب حذفها اولاً ، هل تريد حذفها ؟ ",
                    "حذف عمليات التحويل المعلقة ؟ ",
                    "warning",
                    {
                      confirmButtonText: "نعم",
                      cancelButtonText: "لا",
                    }
                )
                    .then(() => {
                      this.removeExists = true;
                      this.toPushDataToServer();
                    })
                    .catch((error) => {
                      location.reload();
                    });
              } else {
                alert(error.response.data.errors[0][0]);
              }
            } else {
              alert(error.response.data.message);
            }

            console.log(error.response.data.errors);
          });
    },
  },
};
</script>
