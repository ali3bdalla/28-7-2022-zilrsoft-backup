<template>
  <div class="row mt-5" style="margin-top: 20px">
    <images :item="item" :attachments="item.attachments"></images>
    <!-- <div
      class="col-md-3"
      v-for="(attachment, index) in attachments_list"
      :key="attachment.id"
    >
      <div class="panel panel-primary">
        <div class="panel-body">
          <div class="content-preview">
            <button
              class="btn btn-danger delete"
              @click="deleteAttachment(attachment.id, index)"
            >
              حذف
            </button>
            <img
              class="img-fluid rounded float-left img-responsive imagePreview"
              :src="attachment.url"
            />
          </div>
        </div>
      </div>
    </div>
    <div class="col-md-3">
      <label>
        <div class="panel">
          <div class="panel-body">
            <div class="uploader-content">

              <img
                v-show="!isLoading"
                src="/accounting/images/cloud_upload.png"
              />
              <p v-show="!isLoading" class="lead">رفع المرفقات</p>
            </div>
          </div>
          <input
            type="file"
            style="display: none"
            accept="image/*"
            multiple
            @change="handleFiles"
            ref="file"
          />
        </div>
      </label>
    </div> -->
  </div>
</template>

<script>
import Images from '../../../components/BackEnd/Product/Images.vue'

export default {
  components: { Images },
  props: {
    item: {
      type: Object,
      required: true
    }
  }
  // name: 'PreviewComponent',
  // data: function () {
  //   return {
  //     isLoading: false,
  //     attachments_list: []
  //   }
  // },
  // created: function () {
  //   this.attachments_list = this.item.attachments
  // },
  // methods: {
  //   deleteAttachment (id, index) {
  //     //   const appVm = this
  //     //   axios.delete(this.new_attachment_link + '/' + id).then(response => {
  //     //     appVm.attachments_list = db.model.deleteByIndex(appVm.attachments_list, index)
  //     //   }).catch(error => {
  //     //     console.log(error.response)
  //     //     console.log(error.response.message)
  //     //   })
  //   },
  //   attachmentsUploaded (e) {
  //     //   this.attachments_list.push(e.attachment)
  //     // console.log(e.attachment);
  //   },
  //   handleFiles (event) {
  //     const imageFiles = this.$refs.file.files
  //     if ((imageFiles.length + this.attachments_list.length) >= 5) {
  //       this.$alert('مسموح رفع ٤ صور فقط للمنتج')
  //       return
  //     }
  //     for (let i = 0; i <= imageFiles.length; i++) {
  //       const imageFile = imageFiles[i]
  //       if (imageFile != null) {
  //         if (imageFile.type.indexOf('image') === -1) {
  //           this.$alert('الملف الرفوع')
  //         } else {
  //           this.startUploading(imageFile)
  //         }
  //       }
  //     }
  //   },
  //   startUploading: function (file) {
  //     this.isLoading = true
  //     const appVm = this
  //     const serverData = new FormData()
  //     serverData.append('attachment', file)
  //     axios.post(this.upload_link, serverData, {
  //       headers: {
  //         'Content-Type': 'multipart/form-data'
  //       }
  //     }).then(response => {
  //       appVm.$emit('uploaded', {
  //         attachment: response.data
  //       })
  //     }).catch(error => {
  //       console.log(error.response)
  //       console.log(error.response.data)
  //     }).finally(() => {
  //       appVm.isLoading = false
  //     })
  //   }
  // }
}
</script>

<style scoped>
/* .panel {
  width: 100%;
  height: 250px;
  cursor: pointer;
}

.content-preview {
  margin: -10px 0px 0px -180px;
  vertical-align: middle;
  height: 100%;
  text-align: center;
  width: 100%;
  height: 230px;
  object-fit: cover;
}

.delete {
  position: absolute;
  right: 29px;
  top: 21px;
}

.imagePreview {
  width: 100%;
  height: 100%;
  overflow: hidden;
  object-fit: cover;
  object-position: 20% 10px;
}
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
    } */
</style>
