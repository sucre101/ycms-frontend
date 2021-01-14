<template>
  <div>
      <h4>Create New post</h4>
      <input v-model="post_.title" type="text" placeholder="Title" />
      <input v-model="post_.rating_state" type="checkbox" placeholder="Rating state" />
      <input v-model="post_.rating_type" type="text" placeholder="Rating type" />
      <input v-model="post_.comment_state" type="checkbox" placeholder="Comment state" />
      <input v-model="post_.comment_rating_type" type="text" placeholder="Comment rating type" />
      <a class="small-rounded-btn blue-bg text-white" @click="storePost">Save</a>
  </div>
</template>

<script>

export default {

  name: "post-modal",
  props:{
    post: Object
  },
  data() {
    return {
      post_: this._.cloneDeep(this.post)
    }
  },
  methods: {
    storePost(){
      this.$emit('store-post', this.post_);
    },
  }
}
</script>

<style scoped lang="scss">


</style>
