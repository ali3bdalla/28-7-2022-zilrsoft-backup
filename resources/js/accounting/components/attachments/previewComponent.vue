<template>
  <div class="row">
    <div
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
      <!-- <attachment-uploader-component
        :upload_link="new_attachment_link"
        @uploaded="attachmentsUploaded"
      ></attachment-uploader-component> -->
    </div>
  </div>
</template>

<script>
// import UploaderComponent from '../attachments/uploaderComponent'

export default {
  props: {
    item: {
      type: Object,
      required: true
    }
  },
  name: 'PreviewComponent',
  data: function () {
    return {
      attachments_list: []
    }
  },
  components: {
    // 'attachment-uploader-component': UploaderComponent
  },
  created: function () {
    this.attachments_list = this.item.attachments
  },
  methods: {
    deleteAttachment (id, index) {

    },
    attachmentsUploaded (e) {

    }
  }
}
</script>

<style scoped>
.panel {
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
</style>
