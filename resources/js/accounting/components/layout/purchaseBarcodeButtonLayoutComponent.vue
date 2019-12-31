<template>
    <div>
        <button @click="printAll" class="btn btn-custom-primary">
            طباعة الباركود للمنتجات
        </button>


        <accounting-barcode-printer-layout-component
                :inside-invoice="true"
                :invoice-id="invoiceId"
                :items="invoiceItems"
                :print="print"></accounting-barcode-printer-layout-component>

    </div>
</template>
<script>
    export default {
        props: [
            "items", 'invoiceId'
        ],
        data: function () {
            return {
                print: false,
                invoiceItems: []
            };
        },
        created: function () {
            for (let i = 0; i < this.items.length; i++) {
                let item = this.items[i];
                item.price_with_tax = item.item.price_with_tax;
                item.ar_name = item.item.ar_name;
                item.barcode = item.item.barcode;
                this.invoiceItems.push(item);

            }

        },
        methods: {
            printAll() {
                this.print = true;
            }
        }
    }
</script>