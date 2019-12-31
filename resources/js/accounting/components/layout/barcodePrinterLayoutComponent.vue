<template>
    <div>
        <div class="row" v-if="insideInvoice!==true">
            <div class="col-xs-4">
                <input class="form-control" min="0" type="number" v-model.number="number_of_barcode">
            </div>

            <div class="col-xs-4" id="barcodeId3">
                <button @click="printSingleFile" class="btn btn-primary">طباعة الباركود
                    <i class="fa fa-print"></i></button>
            </div>
        </div>

        <div class="row text-center align-content-center">
            <div class="col-md-6 text-center">

                <div id="barcode_area" style="    width: 266px;">
                    <barcode :value="barcode" height="100">
                    </barcode>

                    <div class="row">
                        <div class="col-md-12 text-right div-col" style="margin-right: 3px;
                        margin-left: -3px;margin-top: -3px;" v-text="name">

                        </div>

                    </div>
                    <div class="row">
                        <div align="right" class="col-md-6 " style="margin-top: -18px;
                        font-weight: bold;margin-right: 3px !important;
                        margin-left: -3px;" v-text="purchaseInvoiceId">

                        </div>
                        <div align="left" class="col-md-6  div-col" style="margin-top: -18px; font-weight: bold;
                        margin-left: -3px;" v-text="convertEnToArabicNumber(price.toString() ) + ' ر.س'">

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


    var qz = require("qz-tray");
    import domtoimage from 'dom-to-image';
    import VueBarcode from 'vue-barcode';


    export default {
        components: {'barcode': VueBarcode, domtoimage, VueBarcode},
        props: ['items', 'print', 'insideInvoice', 'item', 'invoice-id'],
        data: function () {
            return {
                blukData: [],
                purchaseInvoiceId: "",
                image: null,
                cropper: null,
                number_of_barcode: 1,
                itemData: {
                    ar_name: "",
                    barcode: "",
                    price_with_tax: "",
                },
                barcode:0,
                price:0,
                watcher: false,
                itemsData: [],
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
            this.itemsData = this.items;
            this.purchaseInvoiceId = this.invoiceId;
            this.connectQZ();
            if (this.item != null) {

                this.itemData = this.item;

            }

        },

        mounted: function () {
            if (this.item != null) {

                this.generatedData();
                console.log(this.image);
            }
        },

        methods: {


            generatedData(barcode_count = null) {

                let appVm = this;

                //
                domtoimage.toPng(document.getElementById('barcode_area'), {
                    quality: 1, style: {
                        width: '100%',
                        height: '100%',
                        padding: '0px',
                        margin: "0px"
                    }
                }).then(function (dataUrl) {
                    appVm.src = dataUrl;
                    appVm.image = dataUrl;

                    if (appVm.insideInvoice == true && appVm.print == true) {
                        appVm.printBulkFile(dataUrl, barcode_count);
                    }
                })
                    .catch(function (error) {
                        console.log(error)
                    });
            },

            generatedBulkData(barcode_count = null,item = null) {

                let appVm = this;

                this.itemData.barcode = item.barcode;
                this.itemData.price_with_tax = item.price_with_tax;
                this.itemData.ar_anme = item.ar_anme;
                //
                // domtoimage.toPng(document.getElementById('barcode_area'), {
                //     quality: 1, style: {
                //         width: '100%',
                //         height: '100%',
                //         padding: '0px',
                //         margin: "0px"
                //     }
                // }).then(function (dataUrl) {
                //     appVm.src = dataUrl;
                //     appVm.image = dataUrl;
                //
                //     if (appVm.insideInvoice == true && appVm.print == true) {
                //         appVm.printBulkFile(dataUrl, barcode_count);
                //     }
                // })
                //     .catch(function (error) {
                //         console.log(error)
                //     });
            },

            connectQZ() {
                var appVm = this;
                qz.security.setCertificatePromise(function (resolve, reject) {

                    //Alternate method 2 - direct
                    resolve("-----BEGIN CERTIFICATE-----\n" +
                        "MIIEvTCCAqegAwIBAgIENzI3OTALBgkqhkiG9w0BAQUwgZgxCzAJBgNVBAYTAlVT\n" +
                        "MQswCQYDVQQIDAJOWTEbMBkGA1UECgwSUVogSW5kdXN0cmllcywgTExDMRswGQYD\n" +
                        "VQQLDBJRWiBJbmR1c3RyaWVzLCBMTEMxGTAXBgNVBAMMEHF6aW5kdXN0cmllcy5j\n" +
                        "b20xJzAlBgkqhkiG9w0BCQEWGHN1cHBvcnRAcXppbmR1c3RyaWVzLmNvbTAeFw0x\n" +
                        "OTEwMjIwNDAwMDBaFw0yMDEwMjMwNDAwMDBaMIGIMQswCQYDVQQGDAJTQTEMMAoG\n" +
                        "A1UECAwDRUxRMQ8wDQYDVQQHDAZBclJhc3MxETAPBgNVBAoMCHppbHJzb2Z0MREw\n" +
                        "DwYDVQQLDAh6aWxyc29mdDERMA8GA1UEAwwIemlscnNvZnQxITAfBgkqhkiG9w0B\n" +
                        "CQEMEmllem85MDAyQGdtYWlsLmNvbTCCASAwCwYJKoZIhvcNAQEBA4IBDwAwggEK\n" +
                        "AoIBAQC5hEvbf8nAIPkFhYkeaBu1tiKPyFB5UNg9QqhsigaTe2kRXartwqMEf5wj\n" +
                        "WQ8R91bRUh7JYyruJ4t2KU4FMb+xq+tJM+jyyRwXYBk09ZZTsfUM6vYwGB93xLh4\n" +
                        "dzOyZwM/1VTPr54DSh0N8wYPSyjWGdnO7vrxLGHRMEx9gJgz9ROLK4eYPK/aeKxB\n" +
                        "fmeFSErb0RxIelgFTTOzzFm2cecaGi+vWg7ob18ZsO0kgfaHsE77qoAIhsVbX90d\n" +
                        "W6tpIeeprtFU8MG44MpPB08Vs2JGumqWVVpQvRYa/P4/Jj+tMOKVPTt0TSQiHJ9Z\n" +
                        "hO8tMLwpFHwEq/QxYZjFpaMDvdSPAgMBAAGjIzAhMB8GA1UdIwQYMBaAFJCmULeE\n" +
                        "1LnqX/IFhBN4ReipdVRcMAsGCSqGSIb3DQEBBQOCAgEAJ+LtyRVigi6P8tMaMeQQ\n" +
                        "VFqQV2NK8RsSE2xp6+2MlViHHRPOdAsYQjor0OU4XFAHx/W6s8R4LiNlcjqBZFwO\n" +
                        "rqpz3M+zKlhllu3xYmyKeK0M/5cgQf8omGfJezXszMmfDpvsUgvrQAat/KX3GIEr\n" +
                        "x4IBab0zPSdzjpDsGgmnEhObZLpkhoRJEvPQWEbmCLVbxY0DR8Ji8+GLcU+mc15j\n" +
                        "Ig7Ics1n9hnJPfNHjBIe5lKnUDZ216oiYkhEzD90swFqGMG6OW/KGAH6lm4hwr0P\n" +
                        "JyuMCvnqCavtc010I+VLekhE8RBKMmjR341MZmCXMbE0pQPB2jpo3yNZJUCo/rID\n" +
                        "WUNxEPJWJ7abVaVbU/NBbgNWfowC0ksTF2ARzhvnVrjgoUgIRvT0+m032f1UoRCy\n" +
                        "XuA3NVJjIIs3kY/y43K7wk99sAN/tyii9HPASBJPNCVtDpiIVRHxNhb5uIaBz4xO\n" +
                        "hZoxNoaZximOj8XtohLEpemD4dR5bSDmouo2ym4sDyoHAboGEnHCJ5yb2MaCMQmy\n" +
                        "PbmfvIkd+D8w0jh/X2PB4QTXDHmwupAKc2PMxa01PgD758dplVs1NF3CdFqfayYV\n" +
                        "1B1cF0c556uJHikzgHThY3pM/CmkBWZrlLjZYwmMt4vQ94YXiZHUUqx3OVQZ+8Wr\n" +
                        "dheR/+IpDG+X84DE6acB+mU=\n" +
                        "-----END CERTIFICATE-----\n" +
                        "--START INTERMEDIATE CERT--\n" +
                        "-----BEGIN CERTIFICATE-----\n" +
                        "MIIFEjCCA/qgAwIBAgICEAAwDQYJKoZIhvcNAQELBQAwgawxCzAJBgNVBAYTAlVT\n" +
                        "MQswCQYDVQQIDAJOWTESMBAGA1UEBwwJQ2FuYXN0b3RhMRswGQYDVQQKDBJRWiBJ\n" +
                        "bmR1c3RyaWVzLCBMTEMxGzAZBgNVBAsMElFaIEluZHVzdHJpZXMsIExMQzEZMBcG\n" +
                        "A1UEAwwQcXppbmR1c3RyaWVzLmNvbTEnMCUGCSqGSIb3DQEJARYYc3VwcG9ydEBx\n" +
                        "emluZHVzdHJpZXMuY29tMB4XDTE1MDMwMjAwNTAxOFoXDTM1MDMwMjAwNTAxOFow\n" +
                        "gZgxCzAJBgNVBAYTAlVTMQswCQYDVQQIDAJOWTEbMBkGA1UECgwSUVogSW5kdXN0\n" +
                        "cmllcywgTExDMRswGQYDVQQLDBJRWiBJbmR1c3RyaWVzLCBMTEMxGTAXBgNVBAMM\n" +
                        "EHF6aW5kdXN0cmllcy5jb20xJzAlBgkqhkiG9w0BCQEWGHN1cHBvcnRAcXppbmR1\n" +
                        "c3RyaWVzLmNvbTCCAiIwDQYJKoZIhvcNAQEBBQADggIPADCCAgoCggIBANTDgNLU\n" +
                        "iohl/rQoZ2bTMHVEk1mA020LYhgfWjO0+GsLlbg5SvWVFWkv4ZgffuVRXLHrwz1H\n" +
                        "YpMyo+Zh8ksJF9ssJWCwQGO5ciM6dmoryyB0VZHGY1blewdMuxieXP7Kr6XD3GRM\n" +
                        "GAhEwTxjUzI3ksuRunX4IcnRXKYkg5pjs4nLEhXtIZWDLiXPUsyUAEq1U1qdL1AH\n" +
                        "EtdK/L3zLATnhPB6ZiM+HzNG4aAPynSA38fpeeZ4R0tINMpFThwNgGUsxYKsP9kh\n" +
                        "0gxGl8YHL6ZzC7BC8FXIB/0Wteng0+XLAVto56Pyxt7BdxtNVuVNNXgkCi9tMqVX\n" +
                        "xOk3oIvODDt0UoQUZ/umUuoMuOLekYUpZVk4utCqXXlB4mVfS5/zWB6nVxFX8Io1\n" +
                        "9FOiDLTwZVtBmzmeikzb6o1QLp9F2TAvlf8+DIGDOo0DpPQUtOUyLPCh5hBaDGFE\n" +
                        "ZhE56qPCBiQIc4T2klWX/80C5NZnd/tJNxjyUyk7bjdDzhzT10CGRAsqxAnsjvMD\n" +
                        "2KcMf3oXN4PNgyfpbfq2ipxJ1u777Gpbzyf0xoKwH9FYigmqfRH2N2pEdiYawKrX\n" +
                        "6pyXzGM4cvQ5X1Yxf2x/+xdTLdVaLnZgwrdqwFYmDejGAldXlYDl3jbBHVM1v+uY\n" +
                        "5ItGTjk+3vLrxmvGy5XFVG+8fF/xaVfo5TW5AgMBAAGjUDBOMB0GA1UdDgQWBBSQ\n" +
                        "plC3hNS56l/yBYQTeEXoqXVUXDAfBgNVHSMEGDAWgBQDRcZNwPqOqQvagw9BpW0S\n" +
                        "BkOpXjAMBgNVHRMEBTADAQH/MA0GCSqGSIb3DQEBCwUAA4IBAQAJIO8SiNr9jpLQ\n" +
                        "eUsFUmbueoxyI5L+P5eV92ceVOJ2tAlBA13vzF1NWlpSlrMmQcVUE/K4D01qtr0k\n" +
                        "gDs6LUHvj2XXLpyEogitbBgipkQpwCTJVfC9bWYBwEotC7Y8mVjjEV7uXAT71GKT\n" +
                        "x8XlB9maf+BTZGgyoulA5pTYJ++7s/xX9gzSWCa+eXGcjguBtYYXaAjjAqFGRAvu\n" +
                        "pz1yrDWcA6H94HeErJKUXBakS0Jm/V33JDuVXY+aZ8EQi2kV82aZbNdXll/R6iGw\n" +
                        "2ur4rDErnHsiphBgZB71C5FD4cdfSONTsYxmPmyUb5T+KLUouxZ9B0Wh28ucc1Lp\n" +
                        "rbO7BnjW\n" +
                        "-----END CERTIFICATE-----");


                });
                qz.security.setSignaturePromise(function (toSign) {

                    return function (resolve, reject) {
                        $.get(appVm.app.BaseApiUrl + 'printer/sign_receipt_printer', {request: toSign}).then(resolve, reject);
                    };
                });
                qz.websocket.connect().then(function () {


                    var sha256 = function (str) {
                        // We transform the string into an arraybuffer.
                        var buffer = new TextEncoder('utf-8').encode(str);
                        return crypto.subtle.digest('SHA-256', buffer).then(function (hash) {
                            return hex(hash)
                        })
                    };

                    var hex = function (buffer) {
                        var hexCodes = [];
                        var view = new DataView(buffer);
                        for (var i = 0; i < view.byteLength; i += 4) {
                            // Using getUint32 reduces the number of iterations needed (we process 4 bytes each time)
                            var value = view.getUint32(i);
                            // toString(16) will give the hex representation of the number without padding
                            var stringValue = value.toString(16);
                            // We use concatenation and slice for padding
                            var padding = '00000000';
                            var paddedValue = (padding + stringValue).slice(-padding.length);
                            hexCodes.push(paddedValue)
                        }

                        // Join all the hex strings into one
                        return hexCodes.join('')
                    };


                    //  //   var createHash = require('sha.js');
                    // qz.api.setSha256Type(function (data) {
                    //     return createHash('sha256').update(data).digest('hex');
                    // });
                    //     //

                    qz.api.setPromiseType(function promise(resolver) {
                        return new Promise(resolver);
                    });

                    qz.api.setSha256Type(function (data) {
                        return sha256(data)
                    });


                    return qz.printers.find();
                });
            },

            convertEnToArabicNumber(en) {
                var response = [];
                var en_arr = en.split('');
                for (var i = 0; i < en_arr.length; i++) {
                    var num = en_arr[i];

                    if (num == 0) {
                        response.push('٠');
                    } else if (num == 1) {
                        response.push('١');
                    } else if (num == 2) {
                        response.push('٢');
                    } else if (num == 3) {
                        response.push('٣');
                    } else if (num == 4) {
                        response.push('٤');
                    } else if (num == 5) {
                        response.push('٥');
                    } else if (num == 6) {
                        response.push('٦');
                    } else if (num == 7) {
                        response.push('٧');
                    } else if (num == 8) {
                        response.push('٨');
                    } else if (num == 9) {
                        response.push('٩');
                    } else {
                        response.push(num);
                    }


                }


                return response.join('');
            }
            ,

            chr(n) {
                return String.fromCharCode(n);
            },


            printSingleFile() {

                let config = qz.configs.create(localStorage.getItem('default_barcode_printer'));

                let data = [];
                data.push(
                    '\nN\n' +
                    'A180,20,0,2,1,1,N, \n' +
                    'A200,50,0,4,1,1,N, \n' +
                    'B200,100,0,1A,1,2,30,B, \n' +
                    '\nP1\n'
                );


                for (let i = 0; i < this.number_of_barcode; i++) {
                    data.push(
                        '\nN\n',
                        {
                            type: 'raw', format: 'image', data: this.image,
                            options: {language: 'EPL', y: 0, x: 170}
                        },
                        '\nP1,1\n'
                    );
                }


                qz.print(config, data);
            },


            printBulkFile(embededImage = null, Qty = null) {

                // let config = qz.configs.create(localStorage.getItem('default_barcode_printer'));
                //
                // let barcode_count = Qty == null ? 0 : Qty;
                //
                // let data = [];
                // data.push(
                //     '\nN\n' +
                //     'A180,20,0,2,1,1,N, \n' +
                //     'A200,50,0,4,1,1,N, \n' +
                //     'B200,100,0,1A,1,2,30,B, \n' +
                //     '\nP1\n'
                // );
                //
                //
                // for (let i = 0; i < barcode_count; i++) {
                //     data.push(
                //         '\nN\n',
                //         {
                //             type: 'raw', format: 'image', data: embededImage,
                //             options: {language: 'EPL', y: 0, x: 170}
                //         },
                //         '\nP1,1\n'
                //     );
                // }
                //
                //
                // qz.print(config, data);
                this.watcher = false;
            },

            bulkPrintListener() {


                // var indexer = 0;
                // for (let i = 0; i < this.itemsData.length; i++) {
                //     // this.itemData = this.itemsData[indexer];
                //     // this.watcher = true;
                //     // this.generatedBulkData(this.itemsData[0].qty,this.itemsData[0]);
                //
                //
                //     let index = 0;
                //     while (index<1000000) {
                //         index++;
                //     }
                //     indexer++;
                //     //
                //     //
                //     // while (goNext != true) {
                //     //     if (this.watcher) {
                //     //         goNext = true;
                //     //     }
                //     // }
                //     // goNext = false;
                // }
                //
                // this.$emit('bulkPrintComplete', {});
            }

        },
        watch: {
            print: function (value) {
                this.bulkPrintListener();
            },
            items: function (value) {
                this.itemsData = value;
            },
            invoiceId: function (value) {
                this.purchaseInvoiceId = value;
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