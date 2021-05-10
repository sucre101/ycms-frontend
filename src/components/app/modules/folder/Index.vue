<template>
  <div class="table-list-items">

    <h4>Folder module</h4>

    <div class="btn-action blue save" @click="_save" v-if="activeForSave">
      Save
    </div>

    <div class="module-list">

      <div>
        <label>Modules in folder</label>
        <draggable class="list-group" :list="modules" group="people">
          <div
              class="list-group-item"
              v-for="(element, index) in modules"
              :key="index"
          >
            {{ element.alias || element.module.name }}
          </div>
        </draggable>
      </div>

      <div>
        <label>Your modules</label>
        <draggable class="list-group" :list="userModules" group="people">
          <div
              class="list-group-item"
              v-for="(element, index) in userModules"
              :key="index"
          >
            {{ element.alias || element.module.name }}
          </div>
        </draggable>
      </div>
    </div>

  </div>
</template>

<script>
import {moduleUrl} from "@/helpers/general"
import draggable from 'vuedraggable'

export default {
  name: "index",

  components: {
    draggable
  },

  data() {
    return {
      modules: [],
      userModules: [],
      activeForSave: false
    }
  },

  watch: {

    modules: {
      handler(val) {
        this.activeForSave = true
      },
      deep: true
    },

  },

  created() {
    this._loadData()
  },

  mounted() {
    window.setTitle('Folder module')
  },

  methods: {


    _loadData() {

      axios.get(`${moduleUrl(this.$route)}`)
        .then((res) => {
          this.userModules = this._.filter(res.data.userModules, (item) => {
            return item.id !== Number(this.$route.params.moduleId)
          })
          this.modules = this._.cloneDeep(res.data.folder)
        })
        .then( res => this.activeForSave = false )

    },

    _save() {

      axios.post(`${moduleUrl(this.$route)}`, this.modules)
        .then((res) => {
          if (res.data.success) {
            this.notifier.success('Save data')
          }
        })
        .then(res => this.activeForSave = false)

    }

  }
}
</script>

<style scoped lang="scss">

.module-list {
  display: flex;
  flex-direction: row;
  justify-content: space-between;
  div {
    width: 47%;
    text-align: center;
    .list-group {
      width: 100%;
      display: flex;
      flex-direction: column;
      border: 1px solid #c6c9cf;
      padding: 5px 10px;
      background: #f6f9fd47;
      min-height: 400px;
      font-size: 20px;
      padding-top: 20px;
      justify-content: flex-start;
    }
    .list-group-item {
      width: 100%;
      display: flex;
      padding: 10px 20px;
      border-radius: 25px;
      border: 1px solid #c6c9cf;
      margin-bottom: 15px;
      background: #fff;
      cursor: pointer;
    }
  }
}

</style>
