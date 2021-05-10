<template>
  <div class="content">
    <div  v-for="data in blocks" class="block">
      <div v-if="data.type === 'html'">
        <span v-html="data.content"></span>
      </div>
      <div v-else-if="data.type === 'image'">
        <img v-if="data.content"  :src="data.content" width="400px" />
      </div>
      <div v-else-if="data.type === 'video'">
        <video-player
            v-if="data.content"
            :src="data.content">
        </video-player>
      </div>
    </div>
    <div  v-for="tag in tags" class="tags">
      <span><i class="fas fa-tags"></i> {{tag.name}}</span>
    </div>
  </div>
</template>

<script>
  import VideoPlayer from "../../pagebuilder/Element/VideoPlayer";

  export default {

    name: "preview",

    components: {
      'video-player': VideoPlayer,
    },

    data() {
      return {
        blocks: [],
        tags: [],
        post: {}
      }
    },

    created() {
      this.post.id = this.$route.query.post
      this.loadData()
    },

    methods: {
      loadData() {
        axios.get(`/${this.$route.params.folder.toLowerCase()}/post/${this.post.id}`)
        .then((res) => {
          this.blocks = this._.cloneDeep(res.data.post.blocks)
          this.tags = this._.cloneDeep(res.data.post.tags)
        })
      },
    },

  }
</script>

<style scoped lang="scss">
  .content {
    margin-top: 25px;
    width: 100%;
    background-color: white;
    padding: 25px 50px;

  }
  .content>a{
    margin-top: 25px;
  }
  .block{
    margin-top: 10px;

    a{
      margin-top:5px;
      display: inline-block;
      padding: 6px 12px;
    }
    div{
      margin-top: 5px;
    }
  }
  .tags{
    display: inline-block;

    span{
      margin-right: 15px;
      background-color: #e5e5e5;
      padding: 5px;
      border-radius: 5px;
    }
  }
</style>
