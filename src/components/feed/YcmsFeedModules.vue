<template>
  <div>
    {{app.name}} modules
    <a class="small-rounded-btn blue-bg "   @click="openCreateModal">Create Module</a>
    <div class="action" v-for="module in modules" :key="module.id">
      <b>{{module.name}}</b>

      <ion-icon @click="openUpdateModal(module)" name="create-outline"></ion-icon>
      <ion-icon @click="deleteModuleConfirm(module.id)" name="trash-outline"></ion-icon>

      <a class="small-rounded-btn blue-bg "   :href="'/app/' + app.slug + '/feed/' + module.id + '/posts/'">Posts</a>
    </div>
    <sweet-modal
      class="modal"
      ref="createModule"
      width="550"
      overlay-theme="dark"
    >
      <h4>Create New Module</h4>
      <input v-model="newModule.name" type="text" placeholder="Name" />
      <input v-model="newModule.url" type="text" placeholder="Url" />
      <input v-model="newModule.rating_type" type="text" placeholder="Rating type" />
      <input v-model="newModule.comment_state" type="checkbox" placeholder="Comment state" />
      <input v-model="newModule.comment_rating_type" type="text" placeholder="Comment rating type" />
      <input v-model="newModule.comment_editable_interval" type="time" step="1" placeholder="Comment editable interval" />
      <input v-model="newModule.comment_max_depth" type="number"  placeholder="Comment max depth" />
      <input v-model="newModule.tag" type="checkbox" placeholder="Tag" />
      <a class="small-rounded-btn blue-bg text-white" @click="store">Save</a>
    </sweet-modal>
  </div>
</template>

<script>
  export default {
    name: 'ycms-feed-modules',

    props: {
      app: Object
    },
    data() {
      return {
        modules: this.app.feed_modules,
        markedForDelete: null,
        newModule: {
          name: "",
          url: "",
          rating_type: "",
          comment_state: true,
          comment_max_depth: "",
          comment_rating_type: "",
          comment_editable_interval: "",
          tag: true,
          app_id: this.app.id,
        },
      }
    },
    methods: {
      openCreateModal(){
        this.$refs.createModule.open()
      },
      openUpdateModal(module){
        this.newModule = module;
        this.$refs.createModule.open()
      },
      store(){
        axios.post('/app/' + this.app.slug + '/feed/module/store', {
          module: this.newModule,
        })
        .then(res => {
          if (res.data.message === "created"){
            this.notifier.success('Module created successfully')
          }else{
            this.notifier.success('Module updated successfully')
          }

          this.newModule.name = "",
              this.newModule.url = "",
              this.newModule.rating_type = "",
              this.newModule.comment_state = true,
              this.newModule.comment_max_depth = "",
              this.newModule.comment_rating_type = "",
              this.newModule.comment_editable_interval = "",
              this.newModule.tag = true,

              this.$refs.createModule.close()

          this.modules = res.data.modules;
        })
      },
      deleteModuleConfirm(id){
        this.markedForDelete = id;
        this.notifier.confirm('Are you sure?', this.deleteModule())
      },
      deleteModule(){
        let index = this.modules.map(x => {
          return x.id;
        }).indexOf(this.markedForDelete)

        axios.post('/app/'+ this.app.slug +'/feed/module/delete', {
          id: this.markedForDelete,
        })
        .then(res => {
          this.modules.splice(index, 1)
          this.notifier.success('Module deleted successfully')
        })
      },
    },
    mounted() {
    }

  }
</script>

<style lang="scss" scoped>

</style>
