<template>
    <div :class="{'hide-dom':showButton==false,'inline-dom':showButton!=false}">
        <button @click="printAll" class="btn btn-primary" v-if="showButton!=false">طباعة الباركود</button>
        <div>
            <accounting-barcode-printer-layout-component
                    :inside-invoice="true"
                    :invoice-id="invoiceId"
                    :items="invoiceItems"
                    :print="print"
                    @bulkPrintComplete="bulkPrintComplete"></accounting-barcode-printer-layout-component>
        </div>

    </div>
</template>
<script>
    export default {
        props: [
            "items", 'invoiceId', "printerWatcher", 'showButton'
        ],
        data: function () {
            return {
                print: false,
                invoiceItems: []
            };
        },
        created: function () {

            this.updateItems();
        },
        methods: {
            updateItems() {
                this.invoiceItems = [];
                for (let i = 0; i < this.items.length; i++) {
                    let item = this.items[i];
                    item.price_with_tax = item.item.price_with_tax;
                    item.ar_name = item.item.ar_name;
                    item.barcode = item.item.barcode;
                    this.invoiceItems.push(item);

                }
            },
            bulkPrintComplete() {
                this.$emit('bulkPrintComplete', {});
            },
            printAll() {
                this.print = this.print == true ? false : true;
            }
        },

        watch: {
            printerWatcher: function (value) {

                this.print = true;

            },
            items: function (value) {
                this.invoiceItems = value;
            }
        }
    }
</script>

<style>
    .hide-dom {
        display: none;
    }

    .inline-dom {
        display: inline;
    }
</style>