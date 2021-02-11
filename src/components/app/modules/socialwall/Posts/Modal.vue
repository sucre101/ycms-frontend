<template>
    <div class="post-modal">
        <h4>Create New post</h4>
        <div class="element">
            <label for="title">Title</label>
            <input v-model="post_.title" class="rounded-input" type="text" placeholder="Title" id="title" />
        </div>

        <div class="element">
            <label for="rat_st">Rating state</label>
            <input v-model="post_.rating_state"  type="checkbox" placeholder="Rating state" id="rat_st" />
        </div>

        <div class="element">
            <label for="rat_type">Rating type</label>
            <input v-model="post_.rating_type" class="rounded-input" type="text" placeholder="Rating type" id="rat_type" />
        </div>

        <div class="element">
            <label for="com_st">Comment state</label>
            <input v-model="post_.comment_state" type="checkbox" placeholder="Comment state" id="com_st" />
        </div>

        <div class="element">
            <label for="com_type">Comment type</label>
            <input v-model="post_.comment_rating_type" class="rounded-input"  type="text" placeholder="Comment rating type" id="com_type" />
        </div>

        <div class="element">
            <a class="small-rounded-btn blue-bg text-white" @click="storePost">Save</a>
        </div>
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
    .element{
        text-align: left;

        label{
            padding-right: 10px;
        }
    }
</style>
