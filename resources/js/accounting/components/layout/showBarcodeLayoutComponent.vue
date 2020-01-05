<template>
    <barcode :value="barcode" font-size="18" height="100">
    </barcode>
</template>
<script>
    import VueBarcode from 'vue-barcode';

    export default {
        components: {
            'barcode': VueBarcode, VueBarcode
        },
        props: ['barcode']
    }
</script>