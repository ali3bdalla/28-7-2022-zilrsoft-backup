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
  name: 'UploaderComponent',
  props: ['upload_link'],
  data: function () {
    return {
      primaryColor: 'red',
      isLoading: false
    }
  },
  methods: {
    handleFiles (event) {
      const imageFiles = this.$refs.file.files

      for (let i = 0; i <= imageFiles.length; i++) {
        const imageFile = imageFiles[i]
        if (imageFile != null) {
          if (imageFile.type.indexOf('image') === -1) {
            alert('This is not an image file')
          } else {
            this.startUploading(imageFile)
          }
        }
      }
    },
    startUploading: function (file) {
      this.isLoading = true
      const appVm = this
      const serverData = new FormData()
      serverData.append('attachment', file)
      axios.post(this.upload_link, serverData, {
        headers: {
          'Content-Type': 'multipart/form-data'
        }
      }).then(response => {
        appVm.$emit('uploaded', {
          attachment: response.data
        })
      }).catch(error => {
      }).finally(() => {
        appVm.isLoading = false
      })
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
