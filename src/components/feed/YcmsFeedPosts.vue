<template>
  <div>
    <a class="small-rounded-btn blue-bg " :href="'/app/'+app.slug+'/feed/modules'">Go Back</a>
    <a class="small-rounded-btn blue-bg "  @click="openCreatePost">Create Post</a>
    <div class="action" v-for="post in posts" :key="post.id">
      {{post.title}} <small v-if="post.published_at" >published at {{post.published_at}}</small>

      <ion-icon @click="openUpdatePost(post)" name="create-outline"></ion-icon>
      <ion-icon @click="deletePostConfirm(post.id)" name="trash-outline"></ion-icon>
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
      <h4>Create New post</h4>
      <input v-model="newPost.title" type="text" placeholder="Title" />
      <input v-model="newPost.rating_state" type="checkbox" placeholder="Rating state" />
      <input v-model="newPost.rating_type" type="text" placeholder="Rating type" />
      <input v-model="newPost.comment_state" type="checkbox" placeholder="Comment state" />
      <input v-model="newPost.comment_rating_type" type="text" placeholder="Comment rating type" />
      <a class="small-rounded-btn blue-bg text-white" @click="storePost">Save</a>
    </sweet-modal>
  </div>
</template>

<script>
  export default {
    name: 'ycms-feed-posts',

    props: {
      app: Object,
      module: Object
    },
    data() {
      return {
        posts: this.module.posts,
        markedForDelete: null,
        newPost: {
          title: "",
          rating_state: true,
          rating_type: "",
          comment_state: true,
          comment_rating_type: "",
          module_id: this.module.id,
        },
      }
    },
    methods: {
      publishPost(id){
        axios.post('/app/' + this.app.slug + '/feed/post/publish', {
          id: id,
        })
        .then(res => {
          if(res.data.success === true){
            this.notifier.success('Post published successfully')
          }
        })
      },
      openCreatePost(){
        this.$refs.createPost.open()
      },
      openUpdatePost(post){
        this.newPost = post;
        this.$refs.createPost.open()
      },
      storePost(){
        axios.post('/app/' + this.app.slug + '/feed/post/store', {
          post: this.newPost,
        })
        .then(res => {
          if (res.data.message === "created"){
            this.notifier.success('Post created successfully')
          }else{
            this.notifier.success('Post updated successfully')
          }
          this.posts = res.data.posts

          this.newPost.title = "",
              this.newPost.rating_state = true,
              this.newPost.rating_type = "",
              this.newPost.comment_state = true,
              this.newPost.comment_rating_type = "",

              this.$refs.createPost.close()
        })
      },
      deletePostConfirm(id){
        this.markedForDelete = id;
        this.notifier.confirm('Are you sure?', this.deletePost())
      },
      deletePost(){
        let index = this.posts.map(x => {
          return x.id;
        }).indexOf(this.markedForDelete)

        axios.post('/app/'+ this.app.slug +'/feed/post/delete', {
          id: this.markedForDelete,
        })
        .then(res => {
          this.posts.splice(index, 1)
          this.notifier.success('Post deleted successfully')
        })
      },
    },

  }
</script>

<style lang="scss" scoped>

</style>
