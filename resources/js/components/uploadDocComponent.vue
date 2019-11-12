<template>
<div>
    <label class="custom-file-upload" :class="{'green':is_uploaded}">
        <input type="file" @change="initUploading"/>
        <i class="fa fa-cloud-upload-alt" v-show="!is_uploaded"></i>
        <i class="fa fa-check-circle has-text-green" v-show="is_uploaded"></i>
<!--        <i class="fa fa-checked"></i>-->
    </label>
</div>
</template>

<script>
    export  default  {
        data:function(){
            return {
                doc:'',
                is_uploaded:false
            };
        },
        methods:{

            initUploading(e)
            {

                let files = e.target.files || e.dataTransfer.files;
                if (!files.length)
                    return;

                this.makeDoc(files[0]);



            },

            makeDoc(file) {
                let reader = new FileReader();
                let vm = this;
                reader.onload = (e) => {
                    vm.doc = e.target.result;
                    vm.is_uploaded = !this.is_uploaded;
                    vm.$emit('uploaded',{
                        'file':this.doc
                    })
                };
                reader.readAsDataURL(file);

            },

        }
    }
</script>


<style scoped>
    .custom-file-upload {
        background-color:
            black;
        padding: 5px;
        /*padding-right: 10px;*/
        border-radius: 21%;
        width: 49px;
        height: 37px;
        color:
            white;
        font-size: 22px;
        text-align: center;
        vertical-align: middle;
        line-height: -0.0em;
    }
    input[type="file"] {
        display: none;
    }
    /*.custom-file-upload {*/
    /*    border: 1px solid #ccc;*/
    /*    display: inline-block;*/
    /*    padding: 6px 12px;*/
    /*    cursor: pointer;*/
    /*}*/
</style>
