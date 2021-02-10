<template>
  <div class="flex items-center justify-center flex-col" >
    <loading
      :active.sync="isLoading"
      :can-cancel="true"
      :is-full-page="true"
    ></loading>

    <div
    v-if="item.id"
      class="text-center flex flex-col md:flex-row justify-center gap-0 md:gap-5 items-center"
    >
      <div
        class="flex-1 p-3 w-full md:w-1/3 flex flex-col justify-between items-center"
      >

        <div
          class="border-2 p-5 w-full bg-white flex justify-center items-center w-64 h-72 md:w-100 md:h-100 overflow-hidden"
        >
          <img :src="activeImage" class="object-contain h-64" />
        </div>
        <div
          class="grid grid-cols-4 gap-2 mt-5 bg-gray-200 p-2"
          data-v-52728e8a=""
          data-v-a15fc63e=""
        >
          <div v-for="(attachment, index) in attachmentsList" :key="index" class="">
            <img
              :src="attachment.url"
              class="w-16 h-16 object-cover"
              @click="changeActiveImage(attachment)"
            />
            <div class="mt-2">
              <button
                class="bg-red-500 px-3 py-2 rounded"
                @click="deleteImage(attachment.id)"
              >
                حذف
              </button>
            </div>
          </div>
        </div>
      </div>
      <div class="flex-1"  v-if="attachmentsList.length < 4">
        <label class="md:p-3 flex justify-center items-start">
          <div class="w-1/2 object-cover shadow-lg rounded-lg">
            <div class="text-gray-200">
              <div class="flex px-3 flex-col justify-center items-center">
                <img style="width: 500px;" src="/accounting/images/cloud_upload.png" />
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
        <div v-if="$page.errors" class="text-red-600 text-xl mt-2">
          {{ $page.errors.images }}
        </div>
        <div v-if="$page.errors[`images.0`]" class="text-red-600 text-xl mt-2">
          {{ $page.errors[`images.0`] }}
        </div>
        <div v-if="$page.errors[`images.1`]" class="text-red-600 text-xl mt-2">
          {{ $page.errors[`images.1`] }}
        </div>
        <div v-if="$page.errors[`images.2`]" class="text-red-600 text-xl mt-2">
          {{ $page.errors[`images.2`] }}
        </div>
        <div v-if="$page.errors[`images.3`]" class="text-red-600 text-xl mt-2">
          {{ $page.errors[`images.3`] }}
        </div>
      </div>
    </div>

    <div class=" mt-10">
      <div class="flex items-center justify-between w-full gap-3">
        <div class="flex-1  w-full">
          <textarea
            @change="publishDescription"
            v-model="description.ar_description"
            class="form-control text-right w-full  p-3"
            placeholder="وصف عربي"
             cols="100"
            rows="5"
          ></textarea>
          <div
            v-if="$page.errors.ar_description"
            class="text-red-600 text-xl mt-2"
          >
            {{ $page.errors.ar_description }}
          </div>
        </div>
        <div class="flex-1  w-full">
          <textarea
            @change="publishDescription"
            v-model="description.description"
            class="form-control text-left  w-full p-3"
            style="direction:ltr"
            cols="100"
            rows="5"
            placeholder="وصف انجليزي"
          ></textarea>
          <div
            v-if="$page.errors.description"
            class="text-red-600 text-xl mt-2"
          >
            {{ $page.errors.description }}
          </div>

        </div>
      </div>

      <div class="mt-3" v-if="!noUpdateButton">
            <button class="bg-green-500 text-white rounded p-2" @click="saveDescription">
              تعديل الوصف
            </button>
          </div>
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
    this.activeImage = masterImage ? masterImage.url : this.attachments[0] ? this.attachments[0].url : ''

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
      this.activeImage = image.url
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
@import url("/css/store.css");
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
