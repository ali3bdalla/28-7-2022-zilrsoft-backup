<template>
    <div class="row">
        <div clas="col-xs-4">
            <input class="form-control" min="0" type="number" v-model.number="number_of_barcode">
        </div>

        <div class="col-xs-4" id="barcodeId3">
            <button @click="printFile" class="button is-primary">طباعة الباركود <i class="fa fa-print"></i></button>
        </div>

        <!--        <img :src="image"/>-->

        <div id="barcode_area" style="font-size: 17px;
    font-weight: bold;
    width: 300px;
    font-style: normal;
    font-family: monospace !important;">
            <barcode v-bind:value="item.barcode">
            </barcode>
            <div class="columns">
                <div class="column is-three-quarters" style="font-size: 17px;
    font-weight: bold;
    font-style: normal;
    font-family: 'Arial Unicode MS' !important;">{{ item.locale_name }}</div>
                <div class="column" style="font-size: 17px;
    font-weight: bold;
    font-style: normal;
    font-family: monospace !important;"> {{ item.price }}</div>
            </div>
        </div>

    </div>
</template>

<script>


    var qz = require("qz-tray");
    import domtoimage from 'dom-to-image';
    import VueBarcode from 'vue-barcode';


    export default {
        components: {'barcode': VueBarcode, domtoimage},
        props: ['invoice_id', 'item'],
        data: function () {
            return {
                image: null,
                cropper: null,
                number_of_barcode: 1
            };

        },
        created: function () {
            this.connectQZ();
        },

        mounted() {

            var vm = this;
            domtoimage.toPng(document.getElementById('barcode_area'), {
                quality: 1, style: {}
            })
                .then(function (dataUrl) {
                    var img = new Image();
                    img.src = dataUrl;
                    vm.image = dataUrl;
                    console.log(dataUrl);
                    //  document.body.appendChild(img);
                })
                .catch(function (error) {
                    console.error('oops, something went wrong!', error);
                });
        },


        methods: {


            connectQZ() {

                var vm = this;

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

                qz.security.setSignaturePromise(function(toSign) {
                    console.log(toSign);
                    return function(resolve, reject) {
                        $.get("/management/printer/sign", {request: toSign}).then(resolve, reject);
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
                // });
            },


            chr(n) {
                return String.fromCharCode(n);
            },


            printFile() {


                var config = qz.configs.create(localStorage.getItem('default_barcode_printer'));
                // var name = this.item.name;
                // var barcode = this.item.barcode;
                // var price = this.item.price;
                var data = [];


                for (var i = 0; i < this.number_of_barcode; i++) {
                    //
                    //     data.push(
                    //         '\nN\n' +
                    //         'A180,20,0,2,1,1,N,"' + name + '"\n' +
                    //         'A200,50,0,4,1,1,N,"' + helpers.convertEnToArabicNumber(''+price+'') + '"\n' +
                    //         'B200,100,0,1A,1,2,30,B,"' + barcode + '"\n' +
                    //         '\nP1\n'
                    //     );
                    //
                    // }

                    data.push(
                        '\nN\n',
                        //'Q232,26\n',
                        {
                            type: 'raw', format: 'image', data: this.image,
                            options: {language: 'EPL', x: 155 , y: 30}
                        },
                        '\nP1,1\n'
                    );

                }
                // console.log(localStorage.getItem('default_barcode_printer'),);
                // //
                // var printData = [
                //     '\nN\n',
                //
                //     // 'q609\n',
                //     // 'Q203,26\n',
                //     // 'B180,40,0,1A,3,400,50,B,"123468"\n',
                //     //'A180,130,0,3,1,1,N,"اهلا وسهلا"\n',
                //     // 'A180,176,0,3,1,1,N,"QZ.IO"\n',
                //     {
                //         type: 'raw', format: 'image', data: this.image,
                //         options: {language: 'EPL', x: 180, y: 22}
                //     },
                //     '\nP1,1\n'
                // ];


                // console.log(printData);

                qz.print(config, data);
            },


            sha256(str) {
                var buffer = new TextEncoder('utf-8').encode(str);
                return crypto.subtle.digest('SHA-256', buffer).then(function (hash) {
                    return this.hex(hash)
                })
            },


            hex(buffer) {
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

                return hexCodes.join('')
            }

        }
    }


</script>


<style>
    #barcode_area svg {
        transform: translate(0, 0) !important;
        height: 120px !important;
        margin-bottom: 0px !important;
    }
</style>
