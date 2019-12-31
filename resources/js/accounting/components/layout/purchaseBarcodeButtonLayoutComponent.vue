<template>
    <div style="display: none">
        <accounting-barcode-printer-layout-component
                :inside-invoice="true"
                :invoice-id="invoiceId"
                :items="invoiceItems"
                :print="print"
                @bulkPrintComplete="bulkPrintComplete"></accounting-barcode-printer-layout-component>

    </div>
</template>
<script>
    export default {
        props: [
            "items", 'invoiceId', "printerWatcher"
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
                this.print = true;
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