<template>
    <button @click="printFile" class="btn "><i class="fa fa-print"></i>
        طباعة
        الايصال
    </button>
</template>

<script>
    let qz = require("qz-tray");


    export default {
        props: ['invoiceId', 'print'],
        data: function () {
            return {
                id: 0,
                disable_button: false
            };
        },
        created: function () {
            this.id = this.invoice_id;
            this.connectQZ();

        },

        methods: {


            connectQZ() {

                var vm = this;
                qz.security.setCertificatePromise(function (resolve, reject) {
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
                    console.log(toSign);
                    return function (resolve, reject) {
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


                    qz.api.setPromiseType(function promise(resolver) {
                        return new Promise(resolver);
                    });

                    qz.api.setSha256Type(function (data) {
                        return sha256(data)
                    });


                    if (vm.print) {

                        //    console.log('print the invoice')
                        //  vm.printFile();
                    }
                    return qz.printers.find();
                });
                // });
            },


            printFile() {
                var config = qz.configs.create(localStorage.getItem('default_receipt_printer'));
                var data = [{
                    type: 'html',
                    format: 'file',
                    data: '/management/printer/sale_receipt/' + this.id
                }];

                qz.print(config, data).then(function () {
                });
            },


            printFileWithId(id) {
                var config = qz.configs.create(localStorage.getItem('default_receipt_printer'));
                var data = [{
                    type: 'html',
                    format: 'file',
                    data: '/management/printer/sale_receipt/' + id
                }];

                qz.print(config, data).then(function () {
                });
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

        },


        watch: {
            print_counter: function (value) {

                // alert(value);
                // console.log(this.id);
                this.printFileWithId(value);

            },

            invoice_id: function (value) {
                this.id = value;

            }
        }
    }


</script>


<style scoped>
    .dis {
        display: none;
    }
</style>