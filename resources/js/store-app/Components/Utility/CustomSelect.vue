<template>
    <div class="bg-white">
        <v-select :options="items" label="locale_name" @input="picked"></v-select>
    </div>
</template>
<script> 
    import vSelect from 'vue-select'
    import 'vue-select/dist/vue-select.css';

    export default {
        name: "CustomSelect",
        components: {vSelect},
        props: {
            items: {
                required: true, 
                type: Array
            },
        },
        methods: {
            picked(item) {
                this.$emit("itemPicked",item);
            }
        }
    }
</script>

