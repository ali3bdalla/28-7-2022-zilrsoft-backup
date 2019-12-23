<template>
    <ul class="list-group">
        <div class="alert alert-success " v-if="showSuccesMessage">
            <h4><i class="icon fa fa-check"></i> complete!</h4>
            {{ message }}
        </div>
        <li class="" item="category" v-for="(category, index)  in categoriesList">
            <span>
                <b>
                    {{ category.locale_name }}
                </b>

                 &nbsp;
                &nbsp;
                <button :title="translator.delete" class=" has-text-danger "
                        v-on:click="deleteCategory(category,index)">
                    <i class="fa fa-trash-alt"></i>
                </button>

                <a :href="baseLink + '/create/' + category.id" :title="translator.create_subcategory"
                   class="  has-text-info ">
                    <i class="fa fa-plus-circle"></i>
                </a>
                &nbsp;
                &nbsp;

                <a :href="baseLink + '/clone/' + category.id" :title="translator.copy" class=" has-text-dark ">
                    <i class="fa fa-clone"></i>
                </a>

                 &nbsp;
                &nbsp;
                <a :href="baseLink + '/' + category.id + '/edit'" :title="translator.edit" class=" has-text-primary ">
                    <i class="fa fa-edit"></i>
                </a>
                 &nbsp;
                &nbsp;
                <a :href="baseLink + '/' + category.id + '/filters'" :title="translator.filters"
                   class=" has-text-info ">
                    <i class="fa fa-asterisk"></i>
                </a> 
                 &nbsp;
                &nbsp;

            </span>
            <categories-list-component :baseUrl="baseUrl"
                                       :categories='category.children'></categories-list-component>


        </li>

    </ul>
</template>

<script>
    export default {
        props: ['categories', 'baseUrl'],
        data: function () {
            return {
                categoriesList: [],
                showSuccesMessage: false,
                message: "",
                baseLink: "",
                translator: null

            }
        },
        created: function () {
            this.translator = JSON.parse(window.translator);
            this.categoriesList = this.categories;
            this.baseLink = this.baseUrl;
            this.makeTreeView();
            var vm = this;
            window.Echo.channel('categories')
                .listen('CategoryCreatedEvent', ({category}) => {

                    window.location.reload();
                    // if(category.parent_id===0){
                    //     vm.categoriesList.push(category);
                    // }else{
                    //     vm.categoriesList[vm.findCategory(category.parent_id)].children.push(category);
                    //  }

                    // console.log(category);
                });


        },
        computed: {},
        methods: {

            findCategory(id) {
                for (var i = 0; i < this.categoriesList.length; i++) {
                    if (this.categoriesList[i].id === id) {
                        return i;
                    }
                }
            },

            makeTreeView() {

            },

            addCategory(category, index) {

            },


            updateCateroy() {
            },


            deleteCategory(category, index) {
                this.hideMessages();
                var vm = this;

                this.$dialog
                    .confirm("هل انت متاكد من هذه العملية؟ ", {

                        loader: true // default: false - when set to true, the proceed button shows a loader when clicked.
                        // And a dialog object will be passed to the then() callback
                    })
                    .then(dialog => {
                        setTimeout(() => {
                                // console.log();
                                var link = '/management/categories/' + category.id;

                                axios.delete(link).then(function (response) {
                                        // vm.categoriesList.splice(index, 1);
                                        // vm.showMessage('تم حذف الفئة', true);
                                        window.location.reload();
                                    })
                                    .catch(function (error) {
                                        // handle error
                                        window.location.reload();
                                        // console.log(error);
                                    })
                                    .finally(function () {
                                        // always executed
                                    });
                                dialog.close();
                            }
                            , 1500);


                    })
                    .catch(() => {

                        console.log('Delete aborted');
                    });


            },


            cloneCategory() {

            },

            showMessage(message) {
                this.showSuccesMessage = true;

                this.message = message;


            },
            hideMessages() {
                this.showSuccesMessage = false;
            }


        },
        watch: {
            categoriesList: function () {
                this.makeTreeView();
            }
        }
    }

</script>


<style scoped>


    span {
        padding: 6px;
        color: #2c3e50;
        transition: all 0.5s easy-in-out;
        -webkit-transition: all 0.5s easy-in-out;
        -o-transition: all 0.5s easy-in-out;
        font-size: 20px;
        margin-bottom: 30px;
        background: #ecf0f1;
        cursor: pointer;
        /*margin-bottom:*/
    }

    span:hover {
        /*background: #bdc3c7;*/
        box-shadow: 0px 3px 7px black;
    }

    ul {
        margin: 15px;
        margin-left: 60px;
    }


    a, button {
        color: black;
        font-size: 15px
    }


    span {

        /*border-raduis: 5px;*/
        box-shadow: 0px 1px 1px black
    }

    ul ul {
        margin-top: 2em;
        margin-right: 20px;
    }

</style>
