<template>
  <div>
    <ul class="list-group">
      <li v-for="(printer,index) in printers" class="list-group-item" :key="index">
        <a class="list-group-item list-group-item-action flex-column align-items-start"
           href="#">
          <div class="d-flex w-100 justify-content-between"
          >
            <h5 class="mb-1">{{ printer }}</h5>
          </div>
          <div class="mb-1">
            <p v-if="default_receipt_printer==printer">الطابعة الافتراضية للايصال</p>
            <p v-if="default_barcode_printer==printer">الطابعة الافتراضية للباركود</p>

            <div v-if="default_receipt_printer!=printer && default_barcode_printer!=printer">
              <button class="btn  btn-info btn-sm" @click="setAsDefaultBarcode(printer)">اجعلها
                الافتراضية
                للباركود
              </button>

              <button class="btn btn-primary btn-sm" @click="setAsDefaultReceipt(printer)">اجعلها
                الافتراضية
                للايصال
              </button>

            </div>
          </div>

        </a>
      </li>

    </ul>

  </div>
</template>

<script>

const { qzCertificate } = require('../../../mass/qz-io')
const qz = require('qz-tray')

export default {
  data: function () {
    return {
      printers: [],
      default_receipt_printer: '',
      default_barcode_printer: ''
    }
  },

  created: function () {
    this.default_receipt_printer = localStorage.getItem('default_receipt_printer')
    this.default_barcode_printer = localStorage.getItem('default_barcode_printer')

    const vm = this

    qz.security.setCertificatePromise(function (resolve, reject) {
      // Alternate method 2 - direct
      resolve(qzCertificate)
    })

    qz.security.setSignaturePromise(function (toSign) {
      return function (resolve, reject) {
        $.get('/accounting/printer/sign_receipt_printer', { request: toSign }).then(resolve, reject)
      }
    })

    qz.websocket.connect().then(function () {
      const sha256 = function (str) {
        // We transform the string into an arraybuffer.
        const buffer = new TextEncoder('utf-8').encode(str)
        return window.crypto.subtle.digest('SHA-256', buffer).then(function (hash) {
          return hex(hash)
        })
      }

      const hex = function (buffer) {
        const hexCodes = []
        const view = new DataView(buffer)
        for (let i = 0; i < view.byteLength; i += 4) {
          // Using getUint32 reduces the number of iterations needed (we process 4 bytes each time)
          const value = view.getUint32(i)
          // toString(16) will give the hex representation of the number without padding
          const stringValue = value.toString(16)
          // We use concatenation and slice for padding
          const padding = '00000000'
          const paddedValue = (padding + stringValue).slice(-padding.length)
          hexCodes.push(paddedValue)
        }

        // Join all the hex strings into one
        return hexCodes.join('')
      }

      //  //   let createHash = require('sha.js');
      // qz.api.setSha256Type(function (data) {
      //     return createHash('sha256').update(data).digest('hex');
      // });
      //     //

      qz.api.setPromiseType(function promise (resolver) {
        return new Promise(resolver)
      })

      qz.api.setSha256Type(function (data) {
        return sha256(data)
      })

      qz.printers.find().then((printers) => {
        vm.printers = printers
      })
    })
  },

  methods: {
    setAsDefaultReceipt (printer) {
      localStorage.setItem('default_receipt_printer', printer)
      this.default_receipt_printer = printer
    },

    setAsDefaultBarcode (printer) {
      localStorage.setItem('default_barcode_printer', printer)
      this.default_barcode_printer = printer
    }

  }
}
</script>
