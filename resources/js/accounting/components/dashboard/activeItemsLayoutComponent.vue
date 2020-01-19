<template>
    <div class="panel panel-primary">
        <div class="panel-heading">
            اكثر المنتجات مبيعاً
        </div>
        <div class="panel-body">
            <tile :color="primaryColor" :loading="isLoading" v-show="isLoading"></tile>
            <ul class="list-group">
                <li :key="item.id" class="list-group-item" v-for="item in items" v-if="!isLoading">
                    <span class="badge badge-primary badge-pill">{{item.history_count}}</span>
                    <a :href="'/accounting/items/' + item.id + '/transactions'">
                        {{ item.locale_name}}
                    </a>
                    <p>
                        {{ item.updated_at}} - <span class="text-success">({{ parseFloat(item.cost).toFixed(2)}})</span>
                    </p>


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
                isLoading: true,
                items: [],
                primaryColor: metaHelper.getContent('primary-color'),
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
                    appVm.isLoading = false;
                }).catch(error => {
                    console.log(error);
                })
            },
        }
    }
</script>