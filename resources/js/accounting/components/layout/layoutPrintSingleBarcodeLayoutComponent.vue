<template>
    <div>
        <div class="row">
            <div class="col-xs-4">
                <input class="form-control" min="0" type="number" v-model.number="number_of_barcode">
            </div>

            <div class="col-xs-4" id="barcodeId3">
                <button @click="printFile" class="btn btn-primary">طباعة الباركود
                    <i class="fa fa-print"></i></button>
            </div>
        </div>

        <div class="row text-center align-content-center">
            <div class="col-md-6 text-center">

                <div id="barcode_area" style="    width: 266px;">
                    <barcode :value="item.barcode" height="100">
                    </barcode>

                    <div class="row">
                        <div class="col-md-12 text-right div-col" style="margin-right: 3px;
                        margin-left: -3px;margin-top: -3px;" v-text="item.locale_name.substr(0,24)">

                        </div>

                    </div>
                    <div class="row">
                        <div align="right" class="col-md-6 " style="margin-top: -18px;
                        font-weight: bold;margin-right: 3px !important;
                        margin-left: -3px;" v-text="PurchaseId">

                        </div>
                        <div align="left" class="col-md-6  div-col" style="margin-top: -18px; font-weight: bold;
                        margin-left: -3px;" v-text="convertEnToArabicNumber(item.price_with_tax.toString() ) +
                        ' ر.س'">

                        </div>

                    </div>
                </div>

            </div>

            <div class="col-md-6 text-center">
                <div id="showGeneratedBarcodeImageId">

                </div>
            </div>
        </div>

    </div>
</template>

<script>

import domtoimage from 'dom-to-image'
import VueBarcode from 'vue-barcode'
const qz = require('qz-tray')

const { qzCertificate } = require('../../../mass/qz-io')

export default {
  components: { barcode: VueBarcode, domtoimage, VueBarcode },
  props: ['invoice_id', 'item'],
  data: function () {
    return {
      PurchaseId: '',
      image: null,
      cropper: null,
      number_of_barcode: 1,
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
    this.connectQZ()
  },

  mounted () {
    const vm = this
    domtoimage.toPng(document.getElementById('barcode_area'), {
      quality: 1,
      style: {
        width: '100%',
        height: '100%',
        padding: '0px',
        margin: '0px'
      }
    })
      .then(function (dataUrl) {
        const img = new Image()
        img.src = dataUrl
        vm.image = dataUrl
        document.getElementById('showGeneratedBarcodeImageId').appendChild(img)
      })
      .catch(function (error) {
      })
  },

  methods: {
    convertEnToArabicNumber (en) {
      const response = []
      const en_arr = en.split('')
      for (let i = 0; i < en_arr.length; i++) {
        const num = en_arr[i]

        if (num == 0) {
          response.push('٠')
        } else if (num == 1) {
          response.push('١')
        } else if (num == 2) {
          response.push('٢')
        } else if (num == 3) {
          response.push('٣')
        } else if (num == 4) {
          response.push('٤')
        } else if (num == 5) {
          response.push('٥')
        } else if (num == 6) {
          response.push('٦')
        } else if (num == 7) {
          response.push('٧')
        } else if (num == 8) {
          response.push('٨')
        } else if (num == 9) {
          response.push('٩')
        } else {
          response.push(num)
        }
      }

      return response.join('')
    },

    connectQZ () {
      const appVm = this
      qz.security.setCertificatePromise(function (resolve, reject) {
        // Alternate method 2 - direct
        resolve(qzCertificate)
      })
      qz.security.setSignaturePromise(function (toSign) {
        return function (resolve, reject) {
          $.get(appVm.app.BaseApiUrl + 'printer/sign_receipt_printer', { request: toSign }).then(resolve, reject)
        }
      })
      qz.websocket.connect().then(function () {
        const sha256 = function (str) {
          // We transform the string into an arraybuffer.
          const buffer = new TextEncoder('utf-8').encode(str)
          return crypto.subtle.digest('SHA-256', buffer).then(function (hash) {
            return hex(hash)
          })
        }

        var hex = function (buffer) {
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

        //  //   var createHash = require('sha.js');
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

        return qz.printers.find()
      })
    },

    chr (n) {
      return String.fromCharCode(n)
    },

    printFile () {
      const config = qz.configs.create(localStorage.getItem('default_barcode_printer'))
      const data = []
      data.push(
        '\nN\n' +
                    'A180,20,0,2,1,1,N, \n' +
                    'A200,50,0,4,1,1,N, \n' +
                    'B200,100,0,1A,1,2,30,B, \n' +
                    '\nP1\n'
      )
      for (let i = 0; i < this.number_of_barcode; i++) {
        data.push(
          '\nN\n',
          {
            type: 'raw',
            format: 'image',
            data: this.image,
            options: { language: 'EPL', y: 0, x: 170 }
          },
          '\nP1,1\n'
        )
      }
      qz.print(config, data)
    }

  }
}

</script>

<style>
    #barcode_area svg {
        /*height: 100px !important;*/
        margin-bottom: -16px;
    }

    #barcode_area .div-col {
        /*padding: 4px !important;*/
        font-size: 20px;
        overflow: hidden;
        /*margin: 0px;*/
    }

    #barcode_area .row {
        padding-top: 0px !important;
        margin-top: 0px !important;
        margin-bottom: 0px !important;
        padding-bottom: 0px !important;
    }

</style>
