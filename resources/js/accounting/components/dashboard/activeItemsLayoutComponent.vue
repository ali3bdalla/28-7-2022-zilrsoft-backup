<template>
    <div class="panel panel-primary">
        <div class="panel-heading">
            اكثر المنتجات مبيعاً
        </div>
        <div class="panel-body">
            <ul class="list-group">
                <li class="list-group-item" v-for="item in items">
                    <span class="badge badge-primary badge-pill">{{item.history_count}}</span>
                    <a :href="'/accounting/items/' + item.id + '/transactions'">
                        {{ item.locale_name}}
                    </a>
                    {{ item.updated_at}} -  <span class="text-success">({{ item.cost}})</span>

                </li>

            </ul>
        </div>
    </div>
</template>

<script>
    import {query as ItemQuery} from '../../item';

    export default {
        data: function () {
            return {
                items: []
            };
        },
        created: function () {
            this.loadActiveItems();
        },
        methods: {
            loadActiveItems() {
                if (this.barcodeNameAndSerialField == "") {
                    if (this.invoiceData.items.length >= 1) {
                        this.$refs.saveAndPrintReceiptBarcode.focus();
                        return;
                    }
                }
                let appVm = this;
                ItemQuery.sendQueryRequestToFindItems(this.barcodeNameAndSerialField, 'dashbaord').then(response => {
                    appVm.items = response.data;
                }).catch(error => {
                    console.log(error);
                })
            },
        }
    }
</script>