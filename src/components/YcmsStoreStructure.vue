<template>
  <div class="structure-tree">

    <TopButtons
      :links="[
        {name: 'Go back', href: '/app/'+app.slug+'/shops', class: 'small-rounded-btn content-top-button mr-15', cond: true},
      ]"
    />

    <Tree
      v-if="structure.length"
      :data="structure"
      draggable="draggable"
      cross-tree="cross-tree"
      @change="updateTree"
    >
      <div slot-scope="{data, store}" style="display: flex; align-items: center">
        <template v-if="!data.isDragPlaceHolder">
          <img src="@/assets/img/drag-drop.svg" alt="" class="handle">

          <span v-if="!edit.id || edit.id !== data.id" style="padding-left: 5px">
            {{ data.name }}
          </span>
          <b style="padding-left: 8px; margin-top: 8px"
             v-if="data.children &amp;&amp; data.children.length"
             @click="store.toggleOpen(data)"
          >
            <ion-icon :name="data.open ? 'chevron-down-outline' : 'chevron-up-outline'"/>
          </b>
          <span v-if="edit.id === data.id || !data.name">
            <input type="text" v-model.trim="edit.value" @keyup.enter="editOrCreateElement(data)">
            <a
              href=""
              @click.prevent="editOrCreateElement(data)"
            >
              <i class="fas fa-check" title="complete"></i>
            </a>
            <a
              href=""
              @click.prevent="cancelEdit(store, data)"
              style="color: red"
            >
              <i class="fas fa-window-close" title="complete"></i>
            </a>
          </span>
          <div class="action-group" v-if="!edit.id || edit.id !== data.id">

            <div class="actions">
              <ion-icon
                v-if="data.app_id"
                title="edit element"
                @click="showEditInput(data)"
                name="create-outline"/>
            </div>

            <div class="actions">
              <ion-icon
                v-if="data.app_id"
                title="add child"
                @click="addChild(data)"
                name="git-merge-outline"/>
            </div>

            <div class="actions">
              <ion-icon
                title="delete element"
                @click="deleteElement(store, data)"
                name="trash-outline"/>
            </div>

          </div>

        </template>
      </div>
    </Tree>
    <ycms-action-buttons
      :buttons-list="[
            {
              title: 'Add structure',
              handler: 'eval:this.$parent.$refs.structure.open()',
              class: 'bg-green-gradient',
            },
          ]"
      align="left"
    />
    <sweet-modal
      ref="structure"
      class="modal text-left"
      width="500"
      height="700"
      :hide-close-button="true"
      overlay-theme="dark"
    >
      <div style="text-align: left">
        <h6>Please add a structure</h6>

        <div class="content">
          <div class="cols">
            <div class="col">

              <select v-model="newStructure.parentId">
                <option value="">Choose Province</option>
                <template v-for="list in flatStructure">
                  <option :value="list.id">
                    {{ list.name }}
                  </option>
                </template>
              </select>

              <input
                ref="name"
                placeholder="Enter name"
                v-model.trim="newStructure.name"
              >
            </div>
          </div>
        </div>

        <div class="steps-controls">
          <a
            class="small-rounded-btn arrow-left"
            @click="$refs.structure.close(), newStructure.country = ''"
          >
            <img src="@/assets/img/dropleft-icon.svg">
            Cancel
          </a>
          <a class="small-rounded-btn active arrow-right" @click="createOrAddStructure">
            <img src="@/assets/img/dropleft-icon.svg">
            Create
          </a>
        </div>
      </div>
    </sweet-modal>
  </div>
</template>

<script>
  import {DraggableTree} from 'vue-draggable-nested-tree'
  import YcmsTopButtons from './YcmsTopButtons';

  export default {
    name: "ycms-store-structure",

    components: {
      Tree: DraggableTree,
      TopButtons: YcmsTopButtons
    },

    data() {
      return {
        newStructure: {
          name: '',
          parentId: ''
        },
        newStore: {},
        edit: {
          id: false,
          value: ''
        },
      }
    },

    props: ['app', 'structure', 'flatStructure'],

    methods: {

      updateTree(element, targetTree) {

        axios.post('/app/'+ this.app.slug +'/shops/update-structure', {
          id: element.id,
          parent_id: element.parent.id,
          target: targetTree.getPureData(),
        } )
          .then(res => {
            this.notifier.success('tree rebuild success')
          })

      },

      addChild(structure) {
        structure.children.unshift({open: true});
      },

      showEditInput(data) {
        this.edit.value = data.name
        this.edit.id = data.id
      },

      cancelEdit(store, element) {
        this.edit.id = false;
        this.edit.value = '';

        if (!element.app_id) {
          store.deleteNode(element)
        }
      },

      deleteElement(store, data) {
        if (data.app_id) {
          axios.post('/app/' + this.app.slug + '/shops/delete-element-structure', {id: data.id})
            .then((res) => {
              store.deleteNode(data);
              this.notifier.success('element was delete')
            })
        } else {
          store.deleteNode(data);
        }
      },

      createOrAddStructure() {
        axios.post('/app/' + this.app.slug + '/shops/new-structure', this.newStructure)
          .then((res) => {
            this.structure = _.cloneDeep(res.data.structure)
            this.notifier.success('add new element success')
            this.$refs.structure.close()
          })
      },

      editOrCreateElement(element) {
        if (!this.edit.value) {
          this.notifier.warning('name was wrong')
          return false
        }

        if (this.edit.value === element.name) {
          this.notifier.warning('you selected name like previous name')
          this.edit.id = false
          return false
        }

        if (element.id) {
          axios.post('/app/' + this.app.slug + '/shops/edit-element-structure', this.edit)
            .then((res) => {
              this.structure = _.cloneDeep(res.data.structure)
              this.notifier.success('element was rename')
              this.edit.id = false;
              this.edit.value = '';
            })
        } else {
          this.newStructure.parentId = element.parent.id;
          this.newStructure.name = this.edit.value;

          axios.post('/app/' + this.app.slug + '/shops/new-structure', this.newStructure)
            .then((res) => {
              this.structure = _.cloneDeep(res.data.structure)
              this.notifier.success('add new element success')
              this.edit.id = false;
              this.edit.value = '';
            })
        }
      },
    }
  }
</script>

<style scoped lang="scss">
  .structure-tree {
    clear: both;
    margin-right: 20px;
  }
</style>
