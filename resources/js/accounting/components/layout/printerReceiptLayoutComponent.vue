<template>
  <a v-if="direct!==true" class="btn btn-default" @click="pushPrintRequest(targetId)">
    <i class="fa fa-print"></i> {{ app.trans.print_receipt }}
  </a>
</template>

<script>
const { qzCertificate } = require('../../../mass/qz-io')
const qz = require('qz-tray')
export default {
  props: ['invoiceId', 'printId', 'direct'],
  data: function () {
    return {
      targetId: 0,
      app: {
        primaryColor: metaHelper.getContent('primary-color'),
        secondColor: metaHelper.getContent('second-color'),
        appLocate: metaHelper.getContent('app-locate'),
        trans: trans('invoices-page'),
        BaseApiUrl: metaHelper.getContent('BaseApiUrl')
      }
    }
  },
  created: function () {
    this.initPrinterRequest()
    this.targetId = this.invoiceId
  },

  methods: {
    initPrinterRequest () {
      const appVm = this

      qz.security.setCertificatePromise(function (resolve, reject) {
        resolve(qzCertificate)
      })
      qz.security.setSignaturePromise(function (toSign) {
        return function (resolve, reject) {
          $.get(appVm.app.BaseApiUrl + 'printer/sign_receipt_printer', { request: toSign }).then(resolve, reject)
        }
      })
      qz.websocket.connect().then(function () {
        const sha256 = function (str) {
          const buffer = new TextEncoder('utf-8').encode(str)
          return crypto.subtle.digest('SHA-256', buffer).then(function (hash) {
            return hex(hash)
          })
        }
        const hex = function (buffer) {
          const hexCodes = []
          const view = new DataView(buffer)
          for (let i = 0; i < view.byteLength; i += 4) {
            const value = view.getUint32(i)
            const stringValue = value.toString(16)
            const padding = '00000000'
            const paddedValue = (padding + stringValue).slice(-padding.length)
            hexCodes.push(paddedValue)
          }
          return hexCodes.join('')
        }
        qz.api.setPromiseType(function promise (resolver) {
          return new Promise(resolver)
        })
        qz.api.setSha256Type(function (data) {
          return sha256(data)
        })
        return qz.printers.find()
      })
    },

    pushPrintRequest (id) {
      const config = qz.configs.create(localStorage.getItem('default_receipt_printer'))
      const data = [{
        type: 'html',
        format: 'file',
        data: this.app.BaseApiUrl + 'printer/print_receipt/' + id
      }]

      qz.print(config, data).then(function () {
      }).catch(error => console.log(error))
    },

    sha256 (str) {
      // We transform the string into an arraybuffer.
      const buffer = new TextEncoder('utf-8').encode(str)
      return crypto.subtle.digest('SHA-256', buffer).then(function (hash) {
        return this.hex(hash)
      })
    },

    hex (buffer) {
      const hexCodes = []
      const view = new DataView(buffer)
      for (let i = 0; i < view.byteLength; i += 4) {
        const value = view.getUint32(i)
        const stringValue = value.toString(16)
        const padding = '00000000'
        const paddedValue = (padding + stringValue).slice(-padding.length)
        hexCodes.push(paddedValue)
      }

      return hexCodes.join('')
    }

  },

  watch: {
    invoiceId: function (value) {
      this.pushPrintRequest(value)
    }

  }
}

</script>

<style scoped>
.dis {
  display: none;
}
</style>
