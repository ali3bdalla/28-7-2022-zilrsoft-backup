<template>
  <a v-if="direct!==true" class="btn btn-default" @click="pushPrintRequest(targetId)">
    <i class="fa fa-print"></i> {{ app.trans.print_receipt }}
  </a>
</template>

<script>
const qzIo = require('../../../qz-io');
let qz = require("qz-tray");
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
        BaseApiUrl: metaHelper.getContent("BaseApiUrl"),
      },
    };
  },
  created: function () {

    this.initPrinterRequest();
    this.targetId = this.invoiceId;

  },

  methods: {
    initPrinterRequest() {
      let appVm = this;
      console.log(qzIo.certificate);
      qz.security.setCertificatePromise(function (resolve, reject) {
        resolve(qzIo.certificate);
      });
      qz.security.setSignaturePromise(function (toSign) {
        return function (resolve, reject) {
          $.get(appVm.app.BaseApiUrl + 'printer/sign_receipt_printer', {request: toSign}).then(resolve, reject);
        };
      });
      qz.websocket.connect().then(function () {
        let sha256 = function (str) {
          var buffer = new TextEncoder('utf-8').encode(str);
          return crypto.subtle.digest('SHA-256', buffer).then(function (hash) {
            return hex(hash)
          })
        };
        let hex = function (buffer) {
          var hexCodes = [];
          var view = new DataView(buffer);
          for (var i = 0; i < view.byteLength; i += 4) {
            var value = view.getUint32(i);
            var stringValue = value.toString(16);
            var padding = '00000000';
            var paddedValue = (padding + stringValue).slice(-padding.length);
            hexCodes.push(paddedValue)
          }
          return hexCodes.join('')
        };
        qz.api.setPromiseType(function promise(resolver) {
          return new Promise(resolver);
        });
        qz.api.setSha256Type(function (data) {
          return sha256(data)
        });
        return qz.printers.find();
      });
    },

    pushPrintRequest(id) {
      let config = qz.configs.create(localStorage.getItem('default_receipt_printer'));
      let data = [{
        type: 'html',
        format: 'file',
        data: this.app.BaseApiUrl + 'printer/print_receipt/' + id
      }];

      qz.print(config, data).then(function () {
      }).catch(error => console.log(error));
    },

    sha256(str) {
      // We transform the string into an arraybuffer.
      var buffer = new TextEncoder('utf-8').encode(str);
      return crypto.subtle.digest('SHA-256', buffer).then(function (hash) {
        return this.hex(hash)
      })
    },

    hex(buffer) {
      var hexCodes = [];
      var view = new DataView(buffer);
      for (var i = 0; i < view.byteLength; i += 4) {
        var value = view.getUint32(i);
        var stringValue = value.toString(16);
        var padding = '00000000';
        var paddedValue = (padding + stringValue).slice(-padding.length);
        hexCodes.push(paddedValue)
      }

      return hexCodes.join('')
    }

  },


  watch: {
    invoiceId: function (value) {
      this.pushPrintRequest(value);
    },

  }
}


</script>


<style scoped>
.dis {
  display: none;
}
</style>