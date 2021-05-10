<template>
    <div class="posts">
        <h4>Posts</h4>

        <a class="small-rounded-btn blue-bg "  @click="openPostModal(null)">Create Post</a>
        <div class="action" v-for="post in postList" :key="post.id">
            {{post.title}} <small v-if="post.published_at" >published at {{post.published_at}}</small>

            <i @click="openPostModal(post)" class="fa fa-edit"></i>
            <i @click="deletePostConfirm(post.id)" class="fa fa-trash"></i>
            <i @click="openBlocks(post.id)" class="fa fa-arrow-circle-right"></i>
            <i @click="publishPost(post.id)" class="fa fa-newspaper"></i>
        </div>

        <sweet-modal
            class="modal"
            ref="createPost"
            width="550"
            overlay-theme="dark"
        >
            <post-modal
                v-if="showModal"
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
        markedForDelete: 0,
        showModal: false
      }
    },

    created() {
      this.module.id = this.$parent.$parent.moduleId
      this.loadData()
    },

    methods: {
      loadData() {
        axios.get(`/${this.$route.params.folder.toLowerCase()}/${this.module.id}/posts`)
        .then((res) => {
          this.postList = this._.cloneDeep(res.data.posts)
        })
      },
      openBlocks(id){
        this.$router.replace({ query: { tab: 'posts', post: id } })
        this.$parent.showBlocks = true
      },
      openPostModal(post){
        if (post){
          this.post = post;
        }else {
          this.setNull()
        }
        this.showModal = true
        this.$refs.createPost.open()
      },
      storePost(post){
        axios.post(`/${this.$route.params.folder.toLowerCase()}/${this.module.id}/post/store`, {
          post: post,
        })
        .then(res => {
          if (res.data.message === "created"){
            this.notifier.success('Post created successfully')
          }else{
            this.notifier.success('Post updated successfully')
          }
          this.postList = this._.cloneDeep(res.data.posts)
          this.showModal = false
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
          this.postList = this._.cloneDeep(res.data.posts)
          this.notifier.success('Post deleted successfully')
        })
      },
      setNull(){
        this.post.title = ""
        this.post.rating_state = true
        this.post.rating_type = ""
        this.post.comment_state = true
        this.post.comment_rating_type = ""
        this.post.user_module_id = this.module.id
      }
    }
  }
</script>

<style scoped lang="scss">

    .posts {
        width: 100%;
        background-color: white;
        padding: 15px 50px;

        h4 {
            text-align: center;
            font-size: 10px;
            font-weight: 600;
            font-stretch: normal;
            font-style: normal;
            line-height: 1.4;
            letter-spacing: 2px;
            color: #aaaeb3;
            margin: 15px 0;
        }
        .action{
            padding-top: 15px;

            i{
                padding-left: 5px;
            }
        }
    }

</style>
