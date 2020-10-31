<template>
  <images-upload-layout>
    <loading :active.sync="isLoading"
             :can-cancel="true"
             :is-full-page="true"></loading>

    <div class="">
      <div class="grid grid-cols-3 gap-5">
        <div v-for="(attachment,index) in $page.attachments" :key="index" class="relative">
          <button class="bg-red-500 px-10 py-2 absolute mt-2 shadow " @click="deleteAttachment(attachment.id)">حذف
          </button>
          <img :src="attachment.url" class="h-64 w-full object-cover shadow-lg rounded-lg"/>
        </div>
        <div class="">
          <label class="">
            <div class="h-full w-full object-cover shadow-lg rounded-lg">
              <div class="text-gray-200">
                <div class="flex flex-col justify-center items-center">
                  <img src="/public/accounting/images/cloud_upload.png"/>
                  <p class="lead">رفع المرفقات</p>
                </div>
              </div>
              <input ref="file" accept='image/*' multiple style="display: none" type="file" @change="handleFiles">

            </div>

          </label>
          <div v-if="$page.errors.images" class="text-red-600 text-xl mt-2">{{ $page.errors.images }}</div>

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
      isLoading: false
    };
  },
  components: {
    ImagesUploadLayout,
    Loading
  },

  methods: {
    deleteAttachment(id) {
      let appVm = this;
      let item = appVm.$page.item;
      this.$confirm("Are you sure?").then(() => {
        axios.delete(`/api/upload_images/${item.id}/${id}`).then(response => {
          location.reload();
        });
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
