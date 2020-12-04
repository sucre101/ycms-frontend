<template>
  <div :style="style">
    <iframe
        height="100%"
        width="100%"
        :src="'https://www.youtube.com/embed/'+src"
    ></iframe>
  </div>
</template>

<script>
export default {
  name: "ycms-video-player",
  props: {
    src: String,
    style: String,
  },
}
</script>

<style lang="scss" scoped>
  .vjs-custom-skin {
    height: 60% !important;
  }

  .vjs-custom-skin .video-js {
    height: 60%;
  }
</style>
