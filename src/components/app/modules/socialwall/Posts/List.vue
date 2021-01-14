<template>
  <div class="categories-list-table">
    <h4>Posts</h4>

    <a class="small-rounded-btn blue-bg "  @click="openPostModal">Create Post</a>
    <div class="action" v-for="post in postList" :key="post.id">
      {{post.title}} <small v-if="post.published_at" >published at {{post.published_at}}</small>

      <a @click="openPostModal(post)" >edit</a>
      <a @click="deletePostConfirm(post.id)" >delete</a>
      <a class="small-rounded-btn blue-bg "  :href="'/app/' + app.slug + '/feed/' + post.id + '/blocks/'">Blocks</a>
      <a class="small-rounded-btn blue-bg "  :href="'/app/' + app.slug + '/feed/' + post.id + '/tags/'">Tags</a>
      <a  class="small-rounded-btn blue-bg"  @click="publishPost(post.id)">Publish Post</a>
    </div>

    <sweet-modal
        class="modal"
        ref="createPost"
        width="550"
        overlay-theme="dark"
    >
      <post-modal
          :post="post"
          v-on:store-post="storePost"
      />
    </sweet-modal>
  </div>
</template>

<script>
  import Modal from "./Modal";

  export default {

    name: "post-list",

    components: {
      'post-modal': Modal
    },

    data() {
      return {
        postList: [],
        post: {},
        module: {},
        markedForDelete: 0
      }
    },

    created() {
      this.module.id = this.$parent.moduleId
      this.loadData()
    },

    methods: {
      loadData() {
        axios.get(`/${this.$route.params.folder.toLowerCase()}/${this.module.id}/posts`)
        .then((res) => {
          this.postList = this._.cloneDeep(res.data.posts)
        })
      },
      openPostModal(post){
        if (post){
          this.post = post;
        }else {
          this.setNull()
        }
        this.$refs.createPost.open()
      },
      storePost(){
        axios.get(`/${this.$route.params.folder.toLowerCase()}/${this.module.id}/post/store`, {
          post: this.post,
        })
        .then(res => {
          if (res.data.message === "created"){
            this.notifier.success('Post created successfully')
          }else{
            this.post.success('Post updated successfully')
          }
          this.posts = this._.cloneDeep(res.data.posts)
          this.setNull()

          this.$refs.createPost.close()
        })
      },
      publishPost(id){
        axios.post(`/${this.$route.params.folder.toLowerCase()}/${this.module.id}/post/publish`, {
          id: id,
        })
        .then(res => {
          if(res.data.success === true){
            this.notifier.success('Post published successfully')
          }else {
            this.notifier.warning('Post is not published')
          }
        })
      },
      deletePostConfirm(id){
        this.markedForDelete = id;
        this.notifier.confirm('Are you sure?', this.deletePost())
      },
      deletePost(){
        axios.post(`/${this.$route.params.folder.toLowerCase()}/${this.module.id}/post/delete`, {
          id: this.markedForDelete,
        })
        .then(res => {
          this.posts = this._.cloneDeep(res.data.posts)
          this.notifier.success('Post deleted successfully')
        })
      },
      setNull(){
        this.post.title = ""
        this.post.rating_state = true
        this.post.rating_type = ""
        this.post.comment_state = true
        this.post.comment_rating_type = ""
        this.post.module_id = this.module.id
      }
    }
  }
</script>

<style scoped lang="scss">


</style>
