<template>
  <div class="categories-list-table">
    <h4>Tags</h4>
    <span v-for="tag in tagList">
          {{tag.tag.name}}
          <a @click="deleteTagConfirm(tag.id)"  >delete</a>
      </span>

    <div class="newTag">
      <vue-bootstrap-typeahead
          ref="tagName"
          v-model="tag.name"
          :data="tags"
          placeholder="Enter a tag"
      />
      <a class="small-rounded-btn blue-bg" @click="storeTag">Add tag</a>
    </div>
  </div>
</template>

<script>
  import VueBootstrapTypeahead from 'vue-bootstrap-typeahead'

  export default {
    name: "tag-list",
    components: {
      VueBootstrapTypeahead
    },

    data() {
      return {
        tagList: [],
        tags: ['a', 'b', 'c'],
        module: {},
        markedForDelete: 0,
        tag: {}
      }
    },

    created() {
      this.module.id = this.$parent.moduleId
      this.loadData()
    },

    methods: {
      loadData() {
        axios.get(`/${this.$route.params.folder.toLowerCase()}/${this.module.id}/tags`)
        .then((res) => {
          this.tagList = this._.cloneDeep(res.data.tags)
        })

          axios.get(`/${this.$route.params.folder.toLowerCase()}/${this.module.id}/getTags`)
          .then(res => {
            this.tags = this._.cloneDeep(res.data.tags)
          })
      },
      storeTag(){
        axios.post(`/${this.$route.params.folder.toLowerCase()}/${this.module.id}/tag/store`, {
          tag: this.newTag
        })
        .then(res => {
          this.tagList = this._.cloneDeep(res.data.tags);
          this.newTag.name = '';
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

  .categories-list-table {
    width: 70%;
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
    .list-group-item {
      display: flex;
    }
  }

</style>
