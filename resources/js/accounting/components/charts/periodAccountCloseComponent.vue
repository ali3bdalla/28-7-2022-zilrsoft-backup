<template>
  <div>
    <div class="panel panel-primary">
      <div class="panel-body">
        <div class="row text-center">
          <div class="col-md-2">
            <div class="panel panel-default">
              <div class="panel-heading">رصيد افتتاحي</div>
              <div class="panel-body">
                <strong>{{ remainingAccountsBalance }}</strong>
              </div>
            </div>
          </div>

          <div class="col-md-2">
            <div class="panel panel-default">
              <div class="panel-heading">المبالغ الداخلة</div>
              <div class="panel-body">
                <strong>{{ parseFloat(inAmount).toFixed(2) }}</strong>
              </div>
            </div>
          </div>

          <div class="col-md-2">
            <div class="panel panel-default">
              <div class="panel-heading">المبالغ الخارجة</div>
              <div class="panel-body">
                <strong>{{ parseFloat(outAmount).toFixed(2) }}</strong>
              </div>
            </div>
          </div>

          <div class="col-md-3">
            <div class="panel panel-default">
              <div class="panel-heading">المبلغ المفترض</div>
              <div class="panel-body">
                <strong>{{
                    parseFloat(shouldBeAvailableAmount).toFixed(2)
                  }}</strong>
              </div>
            </div>
          </div>
          <div class="col-md-3">
            <div class="panel panel-default">
              <div class="panel-heading">المبلغ الغعلي</div>
              <div class="panel-body">
                <strong>{{ parseFloat(availableAmount).toFixed(2) }}</strong>
              </div>
            </div>
          </div>
          <div class="col-md-2">
            <div class="panel panel-default">
              <div class="panel-heading">الفرق</div>
              <div class="panel-body">
                <strong
                    :class="[
                    parseFloat(variationAmount).toFixed(2) < 0
                      ? 'redValue'
                      : 'greenValue',
                  ]"
                >{{ parseFloat(variationAmount).toFixed(2) }}</strong
                >
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="panel-footer">
        <div class="row">
          <div
              v-for="(gateway, index) in gatewaysList"
              :key="index"
              class="col-md-3"
          >
            <div class="panel panel-primary">
              <div class="panel-body">{{ gateway.locale_name }}</div>
              <div class="panel-footer">
                <input
                    v-model.number="gateway.amount"
                    class="form-control"
                    placeholder="المبلغ"
                />
              </div>
            </div>
          </div>
        </div>
      </div>

      <div class="panel-body">
        <button
            :disabled="!showButton"
            class="btn btn-custom-primary"
            @click="sendSubmitRequest"
        >
          اكمال عملية الاقفال
        </button>

        <a
            class="btn btn-custom-default pull-left"
            href="/accounting/dashboard"
        >
          الغاء
        </a>
      </div>
    </div>
  </div>
</template>
<script>
export default {
  props: ['remainingAccountsBalance', 'gateways', 'inAmount', 'outAmount'],
  data: function () {
    return {
      disabledRemainingAmount: true,
      showButton: true,
      gatewaysList: []
    }
  },
  computed: {
    variationAmount: {
      get (value) {
        return this.availableAmount - this.shouldBeAvailableAmount
      }
    },
    availableAmount: {
      get (value) {
        let amount = 0

        this.gatewaysList.forEach((element) => {
          amount += parseFloat(element.amount)
        })
        return amount
      },
      set () {
      }
    },
    shouldBeAvailableAmount: {
      get (value) {
        return (
          parseFloat(this.inAmount) -
            parseFloat(this.outAmount) +
            parseFloat(this.remainingAccountsBalance)
        )
      },
      set () {
      }
    }
  },
  created () {
    for (let index = 0; index < this.gateways.length; index++) {
      const gateway = this.gateways[index]
      gateway.amount = 0
      this.gatewaysList.push(gateway)
    }
  },
  methods: {
    sendSubmitRequest () {
      this.showButton = false
      axios
        .post('/api/daily/reseller/closing_accounts', {
          gateways: this.gatewaysList
        })
        .then((response) => {
          console.log(response.data)
          // window.location = '/daily/reseller/closing_accounts'
        })
        .catch((error) => {
          console.log(error)
          console.log(error.response.data)
          console.log(error.data)
        })
    }
  }
}
</script>

<style>
.redValue {
  color: red !important;
}

.greenValue {
  color: green !important;
}
</style>
