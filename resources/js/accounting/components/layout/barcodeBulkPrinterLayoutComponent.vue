<template>
  <div style="display: inline">

    <div class="panel">
      <div class="panel-heading" v-for="bar in barcodesLoaders">{{ bar }}</div>
    </div>

    <button @click="printBulkBarcode" class="btn btn-primary" v-if="hideBtn!=true">طباعة الباركود
      <i class="fa fa-print"></i></button>

    <div class="row text-center align-content-center">
      <div class="col-md-6 text-center">

        <div :id="'barcode_area_' + index" :key="index" style="width: 255px;" v-for="(item,index) in
                itemsList">
          <barcode :value="item.barcode" font-size="18" height="100">
          </barcode>

          <div class="row">
            <div class="col-md-12 text-right div-col" style="margin-right: 5px;
                        margin-left: -3px;margin-top: -15px;font-family: 'Cairo', sans-serif !important;
                         font-size: 18px !important;"
                 v-text="item.ar_name.substr(0,24)">

            </div>

          </div>
          <div class="row">
            <div align="right" class="col-md-4 " style="margin-top: -15px;
                        font-weight: bold;margin-right: 3px !important;
                        margin-left: -3px;" v-text="invoiceTitle">

            </div>
            <div align="left" class="col-md-8  div-col" style="margin-left: -10px;margin-right: 10px;
                        margin-top: -28px;font-weight: bolder;">

                           <span style="font-size: 27px !important;">
                               {{ convertEnToArabicNumber(item.price_with_tax.toString()) }}
                           </span>
              <span> ر.س</span>
            </div>

          </div>
        </div>

      </div>

      <div class="col-md-6 text-center">
        <div :id="'showGeneratedBarcodeImageId_' + index" :key="index" style="width: 255px;"
             v-for="(item,index) in
                itemsList">

        </div>
      </div>
    </div>

  </div>
</template>

<script>
import DomToImage from 'dom-to-image'
import VueBarcode from 'vue-barcode'

const { qzCertificate } = require('../../../mass/qz-io')

const qz = require('qz-tray')

export default {
  components: { barcode: VueBarcode },
  props: ['items', 'invoiceId', 'hideBtn'],
  data: function () {
    return {
      barcodesLoaders: [],
      shouldPrint: false,
      invoiceTitle: '',
      itemsList: [],
      image: null,
      cropper: null,
      number_of_barcode: 1,
      itemsGeneratedImage: [],
      BaseApiUrl: metaHelper.getContent('BaseApiUrl')
    }
  },
  created: function () {
    this.invoiceTitle = this.invoiceId
    this.initItems()
    this.connectQZ()
  },
  mounted: function () {
    this.generatedData()
  },
  methods: {
    initItems () {
      for (let i = 0; i < this.items.length; i++) {
        const item = this.items[i]
        item.price_with_tax = item.item.price_with_tax
        item.ar_name = item.item.ar_name
        item.barcode = item.item.barcode
        this.itemsList.push(item)
      }
    },
    async generatedData (items = null) {
      const LocaleItems = items == null ? this.itemsList : items
      const appVm = this
      this.itemsGeneratedImage = []
      const len = LocaleItems.length
      for (let i = 0; i < len; i++) {
        await DomToImage.toPng(document.getElementById('barcode_area_' + i), {
          quality: 1,
          style: {
            width: '100%',
            height: '100%',
            padding: '0px',
            margin: '0px'
          }
        }).then(function (dataUrl) {
          appVm.src = dataUrl
          appVm.image = dataUrl
          appVm.itemsGeneratedImage.push(dataUrl)
        })
          .catch(function (error) {
            console.log(error)
          })
      }
    },
    connectQZ () {
      const appVm = this
      qz.security.setCertificatePromise(function (resolve, reject) {
        resolve(qzCertificate)
      })
      qz.security.setSignaturePromise(function (toSign) {
        return function (resolve, reject) {
          axios.get(appVm.BaseApiUrl + 'printer/sign_receipt_printer', { request: toSign }).then(resolve, reject)
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
    chr (n) {
      return String.fromCharCode(n)
    },
    printBulkBarcode () {
      const config = qz.configs.create(localStorage.getItem('default_barcode_printer'))
      const data = []
      for (let i = 0; i < this.itemsList.length; i++) {
        const generatedItem = this.itemsGeneratedImage[i]
        const actItem = this.itemsList[i]

        if (i > 0) {
          data.push(
            '\nN\n' +
              'A180,20,0,2,1,1,N, \n' +
              'A200,50,0,4,1,1,N, \n' +
              'B200,100,0,1A,1,2,30,B, \n' +
              '\nP1\n'
          )
        }

        for (let y = 0; y < actItem.qty; y++) {
          data.push(
            '\nN\n',
            {
              type: 'raw',
              format: 'image',
              data: generatedItem,
              options: {
                language: 'EPL',
                y: 0,
                x: 170
              }
            },
            '\nP1,1\n'
          )
        }
      }
      qz.print(config, data)
    }
  },
  watch: {
    items: function (value) {
      this.itemsList = []
      for (let i = 0; i < value.length; i++) {
        const item = value[i]
        this.itemsList.push(item)
      }
      const appVm = this
      const interval = setInterval(function () {
        appVm.generatedData()
        clearInterval(interval)
      }, 100)
      //
    },
    invoiceId: function (value) {
      this.invoiceTitle = value
      const appVm = this
      appVm.generatedData()
      const interval = setInterval(function () {
        appVm.shouldPrint = true
        clearInterval(interval)
      }, 1000)
    },
    shouldPrint: function (val) {
      this.printBulkBarcode()
      const appVm = this
      const interval = setInterval(function () {
        appVm.$emit('CompletePrintProcess', {})
        clearInterval(interval)
      }, 1000)
    }
  }
}
</script>

<style scoped>
@import "https://fonts.googleapis.com/css?family=Cairo&display=swap";

#barcode_area svg {
  margin-bottom: -16px;
}

#barcode_area .div-col {
  font-size: 20px;
  overflow: hidden;
}

#barcode_area .row {
  padding-top: 0px !important;
  margin-top: 0px !important;
  margin-bottom: 0px !important;
  padding-bottom: 0px !important;
}
</style>
