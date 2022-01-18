<template>
  <div class="w-full my-8">
    <el-steps
      :active="active"
      align-center
      finish-status="success"
      class="w-full"
    >
      <el-step title="السلة"></el-step>
      <el-step title="وسيلة الشحن"></el-step>
      <el-step title="العنوان"></el-step>
      <el-step title="اكمال"></el-step>
    </el-steps>
  </div>
</template>

<script>
export default {
  props: {
    active: {
      type: Number,
      default: 1,
    },
  },
};
</script>
