<template>
    <a :class="{'dis':disable_button}" :href="'/management/printer/a4/'+ id" class="button is-info" id="a4_id_button"
       target="_blank"><i
            class="fa fa-print"></i> &nbsp;{{
        title}}</a>
</template>

<script>
    export default {
        props: ['invoice_id', 'title', 'print', 'print_counter'],
        data: function () {
            return {
                body: '',
                id: 0,
                disable_button: false
            };
        },

        created: function () {


            //   console.log(this.invoice_id);
            // var vm =this;

            // axios.get('/management/printer/a4/' + this.invoice_id).then((response) => {
            //     // console.log(response.data);
            //     vm.body = response.data;
            // });


            // var vm = this;
            // axios.get('/management/printer/a4/' + this.invoice_id).then((response) => {
            //     // console.log(response.data);
            //     vm.body = response.data;
            //     vm.printPage();
            // });



            this.id = this.invoice_id;

            if (this.print_counter >= 1) {
                this.disable_button = true;
            }


            if (this.print) {
                this.printDirect();
            }

        },

        methods: {
            printDirect() {

                document.getElementById('a4_id_button').click();


            },
            printPage() {
                // invoice_id
                printJS({printable: this.body, type: 'raw-html'});
            }
        },

        watch: {

            invoice_id: function (value) {
             //   alert(value);

                this.id = value;
            }
            ,
            print_counter: function (value) {
                 this.printDirect();
              //  console.log(this.id);
            }
        }
    }
</script>


<style scoped>
    .dis {
        display: none;
    }
</style>