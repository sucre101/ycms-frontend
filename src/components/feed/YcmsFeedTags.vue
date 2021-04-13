<template>
  <div>
    <a class="small-rounded-btn blue-bg " :href="'/app/'+app.slug+'/feed/'+post.module_id+'/posts'">Go Back</a>
    <div>
      <span v-for="tag in tags">
          {{tag.tag.name}}
          <ion-icon @click="deleteTagConfirm(tag.id)" name="trash-outline"></ion-icon>
      </span>
    </div>
    <div class="newTag">
      <vue-bootstrap-typeahead
        ref="tagName"
        v-model="newTag.name"
        :data="tags_json"
        placeholder="Enter a tag"
      />
      <a class="small-rounded-btn blue-bg" @click="storeTag">Add tag</a>
    </div>
  </div>
</template>

<script>
  import VueBootstrapTypeahead from 'vue-bootstrap-typeahead'
  export default {
    name: 'ycms-feed-tags',
    props: {
      app: Object,
      post: Object
    },
    components:{
      VueBootstrapTypeahead
    },
    data() {
      return {
        tags: this.post.post_tags,
        markedForDelete: null,
        tags_json: [],
        newTag: {
          post_id: this.post.id,
          name: '',
        },
      }
    },
    methods: {
      storeTag(){
        axios.post('/app/' + this.app.slug + '/feed/tag/store', {
          tag: this.newTag
        })
        .then(res => {
          this.notifier.success('Tag added successfully');
          this.tags = res.data.tags;
          this.newTag.name = '';
        })
      },
      deleteTagConfirm(id){
        this.markedForDelete = id;
        this.notifier.confirm('Are you sure?', this.deleteTag())
      },
      deleteTag(){
        axios.post('/app/'+ this.app.slug +'/feed/tag/delete', {
          id: this.markedForDelete,
        })
        .then(res => {
          this.tags = res.data.tags
          this.notifier.success('Tag deleted successfully')
        })
      },
    },
    mounted() {
      axios.get('/app/' + this.app.slug + '/feed/' + this.post.id + '/tags_json')
      .then(res => {
        this.tags_json = res.data.tags
      })
    }

  }
</script>

<style>
  .newTag{
    display: flex;
    flex-direction: row;
    color: black;
  }
  .list-group{
    display: flex;
    flex-direction: column;
  }
  .list-group>a{
    width: 100%;
  }
  .list-group>a>span{
    padding: 3px;
    color: black;
    text-decoration: none;
    width: 100% !important;
  }
  .list-group>a:hover{
    background-color: lightgray;
  }
  #wrapper {
    padding-top: 50px;
    width: 400px;
    margin: 0 auto 0 auto;
    color: black;
  }
</style>
