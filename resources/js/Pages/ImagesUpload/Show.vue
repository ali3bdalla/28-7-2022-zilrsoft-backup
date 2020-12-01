<template>
  <images-upload-layout>
    <Images :attachments="$page.attachments" :item="$page.item"></Images>
  </images-upload-layout>
</template>

<script>
import ImagesUploadLayout from "../../Layouts/ImagesUploadLayout";

import Images from "../../components/BackEnd/Product/Images";

export default {components: {ImagesUploadLayout, Images}}
</script>
