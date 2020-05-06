<template>
    <label v-if="upload_link!=null">
        <div class="panel">

            <div class="panel-body">

                <div class="uploader-content">
                    <bounce :color="primaryColor" :loading="!isLoading" v-show="isLoading"></bounce>
                    <img v-show="!isLoading" src="/public/accounting/images/cloud_upload.png"/>
                    <p v-show="!isLoading" class="lead">رفع المرفقات</p>
                </div>
            </div>
            <input type="file" style="display: none" accept='image/*' multiple @change="handleFiles" ref="file">

        </div>

    </label>
</template>

<script>
    export default {
        name: "UploaderComponent",
        props: ["upload_link"],
        data: function () {

            return {
                primaryColor: "red",
                isLoading: false
            };

        },
        methods: {
            handleFiles(event) {

                let imageFiles = this.$refs.file.files;
                for (let i = 0; i <= imageFiles.length; i++) {
                    let imageFile = imageFiles[i];
                    if (imageFile.type.indexOf("image") === -1) {
                        alert("This is not an image file");
                    } else {
                        this.startUploading(imageFile);
                    }
                }



            },
            startUploading: function (file) {
                this.isLoading = true;
                let appVm = this;
                let serverData = new FormData();
                serverData.append("attachment", file);
                axios.post(this.upload_link, serverData, {
                    headers: {
                        'Content-Type': 'multipart/form-data'
                    }
                }).then(response => {
                    appVm.$emit("uploaded", {
                        attachment: response.data
                    })
                }).catch(error => {
                    console.log(error.response);
                    console.log(error.response.data);
                }).finally(() => {
                    appVm.isLoading = false;
                });
            }
        }
    }
</script>

<style scoped>
    label {
        padding: 10px;
        width: 100%;
        height: 250px;
        cursor: pointer;
    }

    .uploader-content {
        vertical-align: middle;
        height: 100%;
        text-align: center;
    }


    .panel:hover {
        background: #eeeeee;
    }
</style>
