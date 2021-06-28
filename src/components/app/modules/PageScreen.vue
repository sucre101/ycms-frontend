<template>
  <div>
    <div class="page-list">
      <h3>Application pages</h3>
      <draggable
        :list="pages"
        :disabled="false"
        class="list-group"
        ghost-class="ghost"
        @start="dragging = true"
        @end="checkMove"
      >
        <div
          class="list-group-item"
          v-for="page in pages"
          :key="page.id"
        >
          <div class="item-title">
            <img src="@/assets/img/drag-drop.svg" alt="" class="handle">
            {{ page.name }}
            <span v-if="page.user_module">
              {{ page.user_module.alias || page.user_module.module.name }}
            </span>
            <span class="not-attached" v-else>Module not attached</span>
          </div>

          <div class="item-actions">
            <div class="btn-action-edit" @click="editPage(page)">Edit</div>
            <div class="btn-action-delete" @click="deletePage(page.id)">Delete</div>
          </div>

        </div>
        <div class="action-group">
          <div class="actions">
<!--            <ycms-action-buttons-->
<!--              :buttons-list="[-->
<!--            {-->
<!--              title: 'Add page',-->
<!--              handler: 'eval:this.$parent.$parent.openModal()',-->
<!--              class: 'bg-green-gradient',-->
<!--            },-->
<!--          ]"-->
<!--              align="right"-->
<!--            />-->
          </div>
        </div>

      </draggable>
    </div>


    <sweet-modal
      class="modal"
      width="915"
      overlay-theme="dark"
      ref="addPage"
    >
      <div v-if="step === 0" class="module-form">

        <h3>Link module to page</h3>

        <div
          v-for="module in modules"
          class="module-line"
          :key="module.id"
        >
          <div class="module" @click="chooseModule(module.id)">

            <img :src="getImage(module.module.image)" >
            <label>{{ module.alias || module.module.name }}</label>

          </div>
        </div>

      </div>
      <div v-if="step === 1">
        <div class="col">
          <div class="form-group">
            <div class="input-field">
              <label for="form-name">Name</label>
              <input id="form-name" v-model.trim="page.name" placeholder="Entry page name"/>
            </div>
            <div class="input-field">
              <label for="form-title">Title</label>
              <input id="form-title" v-model.trim="page.title" placeholder="Entry page title"/>
            </div>
            <div class="input-field">
              <label for="form-short-title">Short title</label>
              <input id="form-short-title" v-model.trim="page.short_title" placeholder="Entry page Short title"/>
            </div>
            <div class="input-field">
              <label for="form-image">Image</label>
              <input id="form-image" v-model.trim="page.image" placeholder="Image"/>
            </div>
            <div class="input-field">
              <label for="form-icon">Icon</label>
              <input id="form-icon" v-model.trim="page.icon" placeholder="Icon"/>
            </div>
            <div class="input-field">
              <label for="">Active</label>
              <toggle-check :value="page.active" @complete="switchActive"/>
            </div>
          </div>
        </div>
        <div class="action-group" style="display: flex; flex-direction: row; justify-content: flex-end">
          <div class="btn-action green" @click="save">Save</div>
        </div>
      </div>

    </sweet-modal>

  </div>
</template>

<script>
import ToggleCheck from "../../base/ui/ToggleCheck";
import draggable from 'vuedraggable'
import {imageUrl} from "@/helpers/general"

export default {
  name: "page-screen",

  components: {
    ToggleCheck, draggable
  },

  data() {
    return {
      pages: [],
      page: {},
      modules: [],
      step: 0
    }
  },

  mounted() {
    this.loadPages();
  },

  methods: {

    save() {
      axios.put('/page/create', this.page)
        .then((res) => {
          if (res.data.pages.length) {
            this.pages = this._.cloneDeep(res.data.pages);
          }
        })
        .then( res => this.$refs.addPage.close())
        .then( res => this.page = {} )
    },

    getImage(path) {
      return imageUrl(path)
    },

    openModal() {
      this.$refs.addPage.open();
      this.modules = this._.cloneDeep(this.$parent.$refs.tab0[0].userModules);
    },

    switchActive(value) {
      this.page.active = value.value;
    },

    chooseModule(id) {
      this.page.user_module_id = id;
      this.step++;
    },

    editPage(page) {
      this.page = page;
      this.openModal();
    },

    checkMove() {

      if (this.pages.length <= 1) {
        return false;
      }

      let list = []

      this.pages.forEach( page => {
        list.push( page.id )
      } )

      axios.post('/page/update-orders', list)
        .then((res) => {
          console.log(res)
        })
    },

    deletePage(id) {
      axios.post('/page/delete', {id: id})
        .then((res) => {
          this.pages = this._.cloneDeep(res.data.pages);
        })
    },

    loadPages() {
      axios.post('/page/list')
        .then((res) => {
          if (res.data.pages.length) {
            this.pages = this._.cloneDeep(res.data.pages);

          }
        })
    }
  }
}
</script>

<style scoped lang="scss">

.page-list {
  width: 70%;
  background-color: white;
  padding: 15px 50px;

  h3 {
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

  .list-group-item:hover {
    background-color: #0997b121;
  }

  .list-group-item {
    display: flex;
    flex-direction: row;
    height: 50px;
    align-items: center;

    .item-title {
      width: 70%;
      display: flex;
      align-items: center;
      cursor: grabbing;

      img {
        padding-right: 10px;
      }

      span {
        margin-left: 30%;
        font-size: 18px;
        color: #0f97b1;
      }
      span.not-attached {
        color: #c94444;
      }
    }

    .item-actions {
      display: flex;
      flex-direction: row;
      justify-content: space-between;
      width: 30%;
    }

    .btn-action-edit {
      width: 100px;
      height: 30px;
      border-radius: 16px;
      background-color: #0997b1;
      display: flex;
      align-items: center;
      justify-content: center;
      color: white;
      cursor: pointer;
    }

    .btn-action-delete {
      width: 100px;
      height: 30px;
      border-radius: 16px;
      background-color: #f84c4c;
      display: flex;
      align-items: center;
      justify-content: center;
      color: white;
      cursor: pointer;
    }

  }
}

.module-form {
  display: flex;
  flex-direction: row;
  flex-wrap: wrap;

  h3 {
    width: 100%;
  }

  .module-line {
    display: flex;
    flex-direction: row;
    justify-content: flex-start;
    width: auto;
    margin-right: 25px;
    max-width: 13%;

    .module {
      display: flex;
      flex-direction: column;
      align-items: center;
      justify-content: center;
      color: #0f97b1;
      cursor: pointer;
      img {
        width: 100%;
      }
    }
  }
}

.col {
  display: flex;

  .form-group {
    display: flex;
    width: 100%;
    flex-wrap: wrap;

    .input-field {
      width: 50%;
      display: flex;
      flex-direction: column;
      align-items: flex-start;
      justify-content: center;

      input {
        width: 70%;
        border-radius: 22px;
        border: solid 1px #868686;
        color: #0997b1;
        font-size: 16px;
        padding: 5px 20px;
      }

      input::-webkit-input-placeholder {
        color: #0997b1;
      }

      input:focus {
        border-width: 1.5px;
        border-color: #0997b1;
        outline: none;
      }
    }
  }
}
</style>
