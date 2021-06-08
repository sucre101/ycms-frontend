<template>
  <div v-if="modules.length">
    <div class="page-container">

      <div
          v-for="module in userModules"
          class="page"
      >

        <img
            :src="getImage(module.module.image)"
            @click="$router.push({
            name: 'module-edit',
              params: {
                slug: $root.$children[0].app.slug,
                moduleId: module.id,
                folder: module.module.front_folder.toLowerCase()
              }
          })"
        >

        {{ module.alias || module.module.name }}

        <span class="module-destroy" @click="deleteModule(module.id)">X</span>

      </div>

      <div class="page add-page" @click="$refs.addModule.open()">
        <span>+</span>
      </div>

    </div>

    <sweet-modal
        v-if="modules.length"
        class="modal modules-list"
        width="915"
        overlay-theme="dark"
        ref="addModule"
    >

      <h3>Modules list</h3>

      <div class="modules-box">
        <div
            v-for="module in modules"
            class="module"
            @click="selectModule(module)"
            :class="{ active: selectedModule === module.id }"
        >
          <img :src="getImage(module.image)" >
          <label>{{ module.name }}</label>
        </div>
      </div>

      <div class="modal-action">
        <a class="small-rounded-btn active arrow-right" @click="addModule">
          <img src="@/assets/img/dropright-icon.svg">
          Create
        </a>
      </div>

    </sweet-modal>
  </div>
</template>

<script>
import {imageUrl} from "@/helpers/general"

export default {
  name: "modules-screen",

  data() {
    return {
      modules: [],
      userModules: [],
      selectedModule: null,
    }
  },

  created() {
    this.loadModules();
  },

  methods: {

    loadModules() {
      axios.post('/module')
          .then((res) => {
            this.modules = this._.cloneDeep(res.data.modules)
            this.userModules = this._.cloneDeep(res.data.userModules)
          })
          .catch((err) => {
            console.log(err)
          })
    },

    selectModule(module) {
      this.selectedModule = module.id;
    },

    getImage(path) {
      return imageUrl(path)
    },

    addModule() {
      axios.post('/module/add', {moduleId: this.selectedModule})
          .then((res) => {
            if (res.data.success) {
              this.userModules = this._.cloneDeep(res.data.modules)
            }
          })
          .then(res => this.$refs.addModule.close())
    },

    deleteModule(moduleId) {

      axios.post('/module/delete', {id: moduleId})
          .then((res) => {
            if (res.data.success) {
              this.userModules = this._.cloneDeep(res.data.modules)
            }
          })

    }

  }
}
</script>

<style lang="scss" scoped>
.page-modules {
  .page-container {
    display: flex;
    flex-direction: row;
    flex-wrap: wrap;
    width: 70%;

    .page {
      position: relative;
      background-color: #e5e5e5;
      display: flex;
      flex-direction: column;
      justify-content: center;
      margin-right: 15px;
      margin-bottom: 15px;
      align-items: center;
      text-align: center;
      cursor: pointer;

      img {
        max-width: 100px;
      }

      .module-destroy {
        position: absolute;
        top: 0;
        right: 10px;
        color: red;
        font-size: 20px;
        cursor: pointer;
      }
    }

    .page.add-page {
      background-color: transparent;

      span {
        background: #00C121;
        width: 60px;
        height: 60px;
        display: flex;
        justify-content: center;
        align-items: center;
        border-radius: 50%;
        cursor: pointer;
        font-size: 25px;
        color: white;
      }
    }
  }

  .modules-list {
    .modules-box {
      display: flex;
      flex-wrap: wrap;
      flex-direction: row;
      margin-top: 15px;
      justify-content: space-between;

      .module {
        display: flex;
        justify-content: center;
        align-items: center;
        margin-right: 15px;
        margin-bottom: 15px;
        cursor: pointer;
        min-width: 18%;
        border: 1px solid #cecece1f;
        min-height: 200px;
        img {
          max-width: 100px;
        }
        label {
          position: absolute;
          font-weight: 600;
          color: #2195f1;
          font-size: 18px;
          opacity: 0;
        }
      }

      .module:hover {
        background: #76a1d90a;
        img {
          opacity: 0.1;
        }
        label {
          opacity: 1;
        }
      }

      .module.active {
        border: 1px solid #00C121;
      }
    }

    .modal-action {
      display: flex;
      justify-content: center;
    }
  }

}
</style>
