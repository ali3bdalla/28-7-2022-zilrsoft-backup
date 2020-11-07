<template>
  <images-upload-layout>
    <loading :active.sync="isLoading"
             :can-cancel="true"
             :is-full-page="true"></loading>

    <div class="text-center flex flex-col md:flex-row justify-center gap-0 md:gap-5   items-center">

      <div class="flex-1 p-3 w-full md:w-1/3 flex flex-col justify-between items-center">
        <div class="mb-2">
          <h3 class="text-lg md:text-xl text-red-500">Max Image Dimension 475px * 475px</h3>
          <h3 class="text-lg md:text-xl text-red-500">Max Image size: 1MB</h3>
        </div>
        <div
            class="border-2 p-5 w-full bg-white flex justify-center items-center  w-64 h-72 md:w-100 md:h-100 overflow-hidden">
          <img :src="activeImage"
               class=" object-contain  h-64"/>
        </div>
        <div class="grid grid-cols-4 gap-2 mt-5 bg-gray-200 p-2" data-v-52728e8a="" data-v-a15fc63e="">
          <div v-for="(attachment,index) in $page.attachments" :key="index" class="">
            <img :src="attachment.url" class="w-16 h-16  object-cover"

                 @click="changeActiveImage(attachment.url)">
            <div class="mt-2">
              <button class="btn btn-danger" @click="deleteImage(attachment.id)">حذف</button>
            </div>
          </div>
        </div>


      </div>


      <!--        <div v-for="(attachment,index) in $page.attachments" :key="index" class="relative">-->
      <!--          <button class="bg-red-500 px-10 py-2 absolute mt-2 shadow " @click="deleteAttachment(attachment.id)">حذف-->
      <!--          </button>-->
      <!--          <img :src="attachment.url" class="h-64 w-full object-cover shadow-lg rounded-lg"/>-->
      <!--        </div>-->
      <div class="flex-1">
        <label class="md:p-3 flex justify-center items-start">
          <div class="w-1/2  object-cover shadow-lg rounded-lg">
            <div class="text-gray-200">
              <div class="flex px-3 flex-col justify-center items-center">
                <img src="/public/accounting/images/cloud_upload.png"/>
                <p class="lead">رفع المرفقات</p>
              </div>
            </div>
            <input ref="file" accept='image/*' multiple style="display: none" type="file" @change="handleFiles">
          </div>

        </label>
        <div v-if="$page.errors" class="text-red-600 text-xl mt-2">{{ $page.errors.images }}</div>
        <div v-if="$page.errors[`images.0`]" class="text-red-600 text-xl mt-2">{{ $page.errors[`images.0`] }}</div>
        <div v-if="$page.errors[`images.1`]" class="text-red-600 text-xl mt-2">{{ $page.errors[`images.1`] }}</div>
        <div v-if="$page.errors[`images.2`]" class="text-red-600 text-xl mt-2">{{ $page.errors[`images.2`] }}</div>
        <div v-if="$page.errors[`images.3`]" class="text-red-600 text-xl mt-2">{{ $page.errors[`images.3`] }}</div>


      </div>


    </div>


    <div>
      <div>
        <div class="p-3">
          <textarea v-model="description.ar_description" class="form-control text-right"
                    placeholder="وصف عربي"></textarea>
          <div v-if="$page.errors.ar_description" class="text-red-600 text-xl mt-2">{{
              $page.errors.ar_description
            }}
          </div>

        </div>
        <div class="p-2">
          <textarea v-model="description.description" class="form-control text-right"
                    placeholder="وصف انجليزي"></textarea>
          <div v-if="$page.errors.description" class="text-red-600 text-xl mt-2">{{
              $page.errors.description
            }}
          </div>

          <div class="mt-3">
            <button class="btn btn-success" @click="saveDescription">تعديل الوصف</button>

          </div>
        </div>
      </div>
    </div>
  </images-upload-layout>
</template>

<script>
import ImagesUploadLayout from "../../Layouts/ImagesUploadLayout";
import Loading from 'vue-loading-overlay';
// Import stylesheet
import 'vue-loading-overlay/dist/vue-loading.css';

export default {

  data() {
    return {
      description: {
        ar_description: "",
        description: "",
      },
      activeImage: "",
      isLoading: false
    };
  },
  created() {
    this.activeImage = this.$page.attachments[0] ? this.$page.attachments[0].url : "";

    this.description.description = this.$page.item.description;
    this.description.ar_description = this.$page.item.ar_description;
    // console.log(this.$page.item);
  },
  components: {
    ImagesUploadLayout,
    Loading
  },

  methods: {

    saveDescription() {
      this.isLoading = true;
      this.$inertia.post(`/api/upload_images/${this.$page.item.id}/update_description`, this.description, {
        onFinish: () => {
          this.isLoading = false;
        },
      });
    },
    changeActiveImage(url) {
      this.activeImage = url;
    },
    deleteImage(id) {
      let appVm = this;
      // console.log(id);
      let item = appVm.$page.item;
      var deleteUrl = `/api/upload_images/${item.id}/${id}`;
      this.$confirm("Are you sure?").then(() => {
        appVm.sendDeleteRequest(deleteUrl);
      });

    },

    sendDeleteRequest(url) {
      axios.get(url).then(response => {
        location.reload();
      });
    },
    attachmentsUploaded(e) {
      this.attachments_list.push(e.attachment);
    },
    handleFiles(event) {
      this.isLoading = true;
      let selectedFiles = this.$refs.file.files;
      let serverData = new FormData();

      for (let i = 0; i <= selectedFiles.length; i++) {
        let imageFile = selectedFiles[i];
        serverData.append("images[]", imageFile);
      }
      this.$inertia.post(`/api/upload_images/${this.$page.item.id}`, serverData, {
        onFinish: () => {
          this.isLoading = false;
          console.log('finish');
        },
      });
    },


  }
}
</script>
<style scoped>
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
