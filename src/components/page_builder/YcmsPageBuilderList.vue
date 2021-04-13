<template>
  <div>
    <a class="ycms-button bg-green-gradient" @click="openPageModal">New Page</a>
    <ul>
      <li v-for="page in pages" :key="page.id">
        {{page.title}}
        <a  :href="'/app/'+app.slug+'/page-builder/'+page.id+'/edit'">Edit</a>
        <a  @click="deletePageConfirm(page.id)">Delete</a>
      </li>
    </ul>

    <sweet-modal
      class="modal"
      ref="newPageModal"
      width="550"
      overlay-theme="dark"
    >
      <h4>Create new page</h4>
      <input v-model="newPage" class="rounded-input" placeholder="Page title">
      <a class="small-rounded-btn blue-bg " @click="storePage">Save</a>
    </sweet-modal>
  </div>
</template>
<script>
  export default {
    name: 'ycms-page-builder-list',
    props: {
      app: Object,
      pages_: Array,
    },
    components:{
    },
    data() {
      return {
        pages: this.pages_,
        newPage: '',
        markedForDelete: null,
        step: 1
      }
    },
    methods: {
      goToStep(st){
        this.step = st;
      },
      openPageModal(){
        this.$refs.newPageModal.open()
      },
      deletePageConfirm(id){
        this.markedForDelete = id;
        this.notifier.confirm('Are you sure?', this.deletePage())
      },
      storePage(){
        axios.post('/app/' + this.app.slug + '/page-builder/store', {
          title: this.newPage,
        })
        .then(res => {
          if(res.data.success === true){
            this.notifier.success('Page created successfully')
            this.pages = res.data.pages
            this.newPage = '';
          }else{
            this.notifier.success('Error')
          }
          this.$refs.newPageModal.close()
        })
      },
      deletePage(){
        axios.post('/app/'+ this.app.slug +'/page-builder/delete', {
          id: this.markedForDelete,
        })
        .then(res => {
          if(res.data.success === true){
            this.notifier.success('Page deleted successfully')
            this.pages = res.data.pages
            this.newPage = '';
          }else{
            this.notifier.success('Error')
          }
        })
      },
    },
    mounted() {

    }
  }
</script>

<style lang="scss" scoped>

</style>
