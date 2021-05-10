<template>
  <div class="content">
    <span v-for="tag in tagList" class="tag">
          {{tag.name}}
          <i @click="deleteTagConfirm(tag.id)"  class="fa fa-trash" ></i>
    </span>

    <div class="newTag">
      <input v-model="tag.name" />
      <a class="small-rounded-btn blue-bg" @click="storeTag">Add tag</a>
    </div>
  </div>
</template>

<script>
  export default {
    name: "tag-list",
    components: {
    },

    data() {
      return {
        tagList: [],
        module: {},
        markedForDelete: 0,
        tag: {
          name: '',
          user_module_id: null
        }
      }
    },

    created() {
      this.module.id = this.$parent.moduleId
      this.tag.user_module_id = this.$parent.moduleId
      this.loadData()
    },

    methods: {
      loadData() {
        axios.get(`/${this.$route.params.folder.toLowerCase()}/${this.module.id}/tags`)
        .then((res) => {
          this.tagList = this._.cloneDeep(res.data.tags)
        })
      },
      storeTag(){
        if (!this.tag.name){
          this.notifier.warning('Tag name is empty');
          return false;
        }
        axios.post(`/${this.$route.params.folder.toLowerCase()}/${this.module.id}/tag/store`, {
          tag: this.tag
        })
        .then(res => {
          this.tagList = this._.cloneDeep(res.data.tags);
          this.tag.name = '';
          this.notifier.success('Tag added successfully');
        })
      },
      deleteTagConfirm(id){
        this.markedForDelete = id;
        this.notifier.confirm('Are you sure?', this.deleteTag())
      },
      deleteTag(){
        axios.post(`/${this.$route.params.folder.toLowerCase()}/${this.module.id}/tag/delete`, {
          id: this.markedForDelete,
        })
        .then(res => {
          this.tagList = this._.cloneDeep(res.data.tags);
          this.notifier.success('Tag deleted successfully')
        })
      },
    }

  }
</script>

<style scoped lang="scss">

  .content {
    margin-top: 25px;
    width: 100%;
    background-color: white;
    padding: 25px 50px;
    display: flex;

    flex-flow: row wrap;
    justify-content: flex-start;
  }
  .tag{
    border: 1px solid;
    border-radius: 10px;
    box-shadow: 2px 2px 10px;
    padding: 5px;
    margin: 5px;
  }
  .newTag{
    width: 100%;
    margin-top: 25px;
    display: flex;
    flex-direction: row;

    a{
      margin-left: 20px;
    }
  }

</style>
