<template>
  <div class="page-template" ref="pageTemplate" @click="onClick">
    <div class="template-image">
      <img :src="img">
    </div>
    {{ title }}
  </div>
</template>

<script>
export default {
  data() {
    return {}
  },
  props: {
    name: String,
    title: String,
    description: String,
    img: String,
    value: String,
  },
  methods: {
    onClick() {
      this.$parent.$parent.homePageModule = this.value
    },
  },
  mounted() {
    // this.$root.$on('ls-change', (name, val) => {
    //   if (name == this.name) {
    //     if (val == this.value) {
    //       this.$refs.pageTemplate.classList.add('active')
    //     } else {
    //       this.$refs.pageTemplate.classList.remove('active')
    //     }
    //   }
    // })
    // this.locStor.watch(this.name)
  },
}
</script>

<style lang="scss" scoped>
.page-template {
  display: inline-block;
  position: relative;
  width: 99px;
  margin: 0 15px 30px 15px;
  font-size: 12px;
  text-align: center;
  color: #2ccae6;
  cursor: pointer;

  .template-image {
    border-radius: 10px;
    background-color: #1dc4e9;
    margin-bottom: 20px;

    img:hover {
      opacity: 0.85;
    }
  }

  &.active {
    font-weight: bold;

    img {
      opacity: 0.75;
    }
  }
}
</style>
