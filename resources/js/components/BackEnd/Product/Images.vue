<template>
  <div class="images-uploader">
    <loading
        :active.sync="isLoading"
        :can-cancel="true"
        :is-full-page="true"
    ></loading>

    <div
        v-if="item.id"
        class="images-uploader__main-image"
    >
      <div
          class="images-uploader__main-image-viewer"
      >

        <div
            class="images-uploader__main-image-container"
        >
          <img :src="activeImage" class="images-uploader__main-image-preview"/>
        </div>
        <div
            class="images-uploader__sub-images"
            data-v-52728e8a=""
            data-v-a15fc63e=""

        <div v-for="(attachment, index) in attachmentsList" :key="index">
          <img
              :src="$processedImageUrl(attachment.url, 300, 400)"
              class="images-uploader__sub-image-preview"
              @click="changeActiveImage(attachment)"
          />
          <div class="images-uploader__mt-3">
            <button
                class="images-uploader__delete-image"
                @click="deleteImage(attachment.id)"
            >
              حذف
            </button>
          </div>
        </div>
      </div>
    </div>
    <div v-if="attachmentsList.length < 4" class="images-uploader__flex-1">
      <label class="images-uploader__upload-area">
        <div class="images-uploader__upload-area-container">
          <div class="images-uploader__text-gray">
            <div class="images-uploader__upload">
              <img src="/accounting/images/cloud_upload.png" style="width: 500px;"/>
              <p class="lead">رفع المرفقات</p>
            </div>
          </div>
          <input
              ref="file"
              accept="image/*"
              multiple
              style="display: none"
              type="file"
              @change="handleFiles"
          />
        </div>
      </label>
      <div v-if="$page.errors" class="images-uploader__error">
        {{ $page.errors.images }}
      </div>
      <div v-if="$page.errors[`images.0`]" class="images-uploader__error">
        {{ $page.errors[`images.0`] }}
      </div>
      <div v-if="$page.errors[`images.1`]" class="images-uploader__error">
        {{ $page.errors[`images.1`] }}
      </div>
      <div v-if="$page.errors[`images.2`]" class="images-uploader__error">
        {{ $page.errors[`images.2`] }}
      </div>
      <div v-if="$page.errors[`images.3`]" class="images-uploader__error">
        {{ $page.errors[`images.3`] }}
      </div>
    </div>
  </div>

  <div class=" images-uploader__mt-10">
    <div class="images-uploader__content images-uploader__w-full images-uploader__gap-3">
      <div class="images-uploader__flex-1 images-uploader__w-full">
          <textarea
              v-model="description.ar_description"
              class="form-control images-uploader__text-right images-uploader__w-full  images-uploader__p-3"
              cols="100"
              placeholder="وصف عربي"
              rows="5"
              @change="publishDescription"
          ></textarea>
        <div
            v-if="$page.errors.ar_description"
            class="images-uploader__error"
        >
          {{ $page.errors.ar_description }}
        </div>
      </div>
      <div class="images-uploader__flex-1  images-uploader__w-full">
          <textarea
              v-model="description.description"
              class="form-control images-uploader__text-left  images-uploader__w-full images-uploader__p-3"
              cols="100"
              placeholder="وصف انجليزي"
              rows="5"
              style="direction:ltr"
              @change="publishDescription"
          ></textarea>
        <div
            v-if="$page.errors.description"
            class="images-uploader__error"
        >
          {{ $page.errors.description }}
        </div>

      </div>
    </div>

    <div v-if="!noUpdateButton" class="images-uploader__mt-3">
      <button class="images-uploader__update-button" @click="saveDescription">
        تعديل الوصف
      </button>
    </div>
  </div>
</template>

<script>
import 'vue-loading-overlay/dist/vue-loading.css'
import Loading from 'vue-loading-overlay'

export default {
  props: {
    item: {
      type: Object,
      required: true
    },
    attachments: {
      type: Array,
      required: true
    },
    noUpdateButton: {
      type: Boolean,
      default: false
    }
  },
  data () {
    return {
      attachmentsList: [],
      description: {
        ar_description: '',
        description: ''
      },
      activeImage: '',
      isLoading: false
    }
  },
  created () {
    this.attachmentsList = this.attachments
    const masterImage = this.attachments.find(p => p.is_main)
    this.activeImage = this.$processedImageUrl(masterImage ? masterImage.url : this.attachments[0] ? this.attachments[0].url : '', 300, 400)

    this.description.description = this.item.description
    this.description.ar_description = this.item.ar_description
  },
  components: {
    Loading
  },

  methods: {
    publishDescription () {
      this.$emit('descriptionUpdated', this.description)
    },
    saveDescription () {
      this.isLoading = true
      this.$inertia.post(
          `/api/upload_images/${this.item.id}/update_description`,
          this.description,
          {
            onSuccess: (page) => {
              if (Object.keys(page.props.errors).length === 0) {
                this.$alert('تم الحفظ بنجاح')
              }
            },
            onFinish: () => {
              this.isLoading = false
            }
          }
      )
    },
    changeActiveImage (image) {
      this.activeImage = this.$processedImageUrl(image.url, 300, 400)
      axios
          .get(`/api/upload_images/${this.item.id}/${image.id}/set_master`)
    },
    deleteImage (id) {
      const appVm = this
      const item = appVm.item
      const deleteUrl = `/api/upload_images/${item.id}/${id}`
      this.$confirm('Are you sure?').then(() => {
        appVm.sendDeleteRequest(deleteUrl)
      })
    },

    sendDeleteRequest (url) {
      axios.get(url).then((response) => {
        // console.log(response.data)
        location.reload()
      })
    },
    attachmentsUploaded (e) {
      this.attachments_list.push(e.attachment)
    },
    handleFiles (event) {
      this.isLoading = true
      const selectedFiles = this.$refs.file.files
      const serverData = new FormData()

      for (let i = 0; i <= selectedFiles.length; i++) {
        const imageFile = selectedFiles[i]
        serverData.append('images[]', imageFile)
      }

      axios
          .post(`/api/upload_images/${this.item.id}`, serverData)
          .then((res) => {
            this.$alert('تم الحفظ بنجاح')
            res.data.forEach(element => {
              this.attachmentsList.push(element)
            })
          })
          .catch((errr) => {
            this.$alert('لم يتم اضافة الصورة')
          })
          .finally(() => {
            this.isLoading = false
          })
      // this.$inertia.post(`/api/upload_images/${this.item.id}`, serverData, {
      //   onSuccess: page => {
      //     if (Object.keys(page.props.errors).length == 0) { this.$alert('تم الحفظ بنجاح') }
      //   },
      //   onFinish: () => {
      //     this.isLoading = false
      //   }
      // })
    }
  }
}
</script>

<style scoped>
.images-uploader {
  display: flex;
  align-items: center;
  justify-content: center;
  flex-direction: column;
}

.images-uploader__main-image {
  text-align: center;
  display: flex;
  flex-direction: column;
  justify-content: center;
  grid-gap: 0;
  gap: 0;
  align-items: center;
}

@media (min-width: 768px) {
  .images-uploader__main-image {
    flex-direction: row;
    grid-gap: 1.25rem;
    gap: 1.25rem;
  }
}

.images-uploader__main-image-viewer {
  flex: 1 1 0%;
  padding: 0.75rem;
  width: 100%;
  flex-direction: column;
  justify-content: space-between;
  align-items: center;
}

@media (min-width: 768px) {
  .images-uploader__main-image-viewer {
    width: 33.333333%;
  }
}

.images-uploader__main-image-container {
  border-width: 2px;
  padding: 1.25rem;
  width: 100%;
  --bg-opacity: 1;
  background-color: #fff;
  background-color: rgba(255, 255, 255, var(--bg-opacity));
  display: flex;
  justify-content: center;
  align-items: center;
  width: 16rem;
  height: 16rem;
  overflow: hidden;
}

@media (min-width: 768px) {
  .images-uploader__main-image-container {
    padding: 0.75rem;
  }
}

.images-uploader__content {
  display: flex;
  align-items: center;
  justify-content: space-between;
}

.images-uploader__main-image-preview {
  -o-object-fit: contain;
  object-fit: contain;
  height: 16rem;
}

.images-uploader__sub-images {
  display: grid;
  grid-template-columns: repeat(4, minmax(0, 1fr));
  grid-gap: 0.5rem;
  gap: 0.5rem;
  margin-top: 1.25rem;
  --bg-opacity: 1;
  background-color: #edf2f7;
  background-color: rgba(237, 242, 247, var(--bg-opacity));
  padding: 0.5rem;
}

.images-uploader__sub-image-preview {
  width: 4rem;
  height: 4rem;
  -o-object-fit: cover;
  object-fit: cover;
}

.images-uploader__delete-image {
  --bg-opacity: 1;
  background-color: #f56565;
  background-color: rgba(245, 101, 101, var(--bg-opacity));
  padding-left: 0.75rem;
  padding-right: 0.75rem;
  padding-top: 0.5rem;
  padding-bottom: 0.5rem;
  border-radius: 0.25rem;
}

.images-uploader__flex-1 {
  flex: 1 1 0%;
}

.images-uploader__w-full {
  width: 100%;
}

.images-uploader__mt-3 {
  margin-top: 0.75rem;
}

.images-uploader__update-button {
  --bg-opacity: 1;
  background-color: #48bb78;
  background-color: rgba(72, 187, 120, var(--bg-opacity));
  --text-opacity: 1;
  color: #fff;
  color: rgba(255, 255, 255, var(--text-opacity));
  border-radius: 0.25rem;
  padding: 0.5rem;
}

.images-uploader__error {
  --text-opacity: 1;
  color: #e53e3e;
  color: rgba(229, 62, 62, var(--text-opacity));
  font-size: 1.25rem;
  margin-top: 0.5rem;
}

.images-uploader__upload-area {
  display: flex;
  justify-content: center;
  align-items: flex-start;
}

@media (min-width: 768px) {
  .images-uploader__upload-area {
    padding: 0.75rem;
  }
}

.images-uploader__upload-area-container {
  width: 50%;
  -o-object-fit: cover;
  object-fit: cover;
  box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
  border-radius: 0.5rem;
}

.images-uploader__text-gray {
  --text-opacity: 1;
  color: #edf2f7;
  color: rgba(237, 242, 247, var(--text-opacity));
}

.images-uploader__upload {
  display: flex;
  padding-left: 0.75rem;
  padding-right: 0.75rem;
  flex-direction: column;
  justify-content: center;
  align-items: center;
}

.images-uploader__text-right {
  text-align: right;
}

.images-uploader__text-left {
  text-align: left;
}

.images-uploader__gap-3 {
  grid-gap: 0.75rem;
  gap: 0.75rem;
}

.images-uploader__p-3 {
  padding: 0.75rem;
}

.images-uploader__mt-10 {
  margin-top: 2.5rem;
}

/*@import url("/css/store.css");*/
/*@import url("/css/rlt_store.css");*/
label {
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
