<template>
  <div class="table-list-items">

    <h4>Unions list</h4>

    <div class="btn-action blue save" v-if="activeForSave" @click="_saveUnion">
      Save
    </div>

    <div class="budge-list">
      <div
          v-for="union in unions"
          class="budge"
          :class="{ 'active' : currentUnion.id === union.id }"
          @click="selectUnion(union)"
      >
        {{ union.name }}
        <div v-if="union.id === currentUnion.id">
          <i class="far fa-edit" @click="editUnion(union)"></i>
          <i class="fas fa-times" @click="removeUnion(union.id)"></i>
        </div>
      </div>
      <div class="add" @click="createUnion = !createUnion">
        <i class="fas fa-plus"></i>
      </div>
    </div>

    <div class="input-group" v-if="createUnion">
      <label>
        <input type="text" class="input-field" v-model.trim="union" placeholder="Group name" @keypress.enter="_createUnion">
      </label>
    </div>

    <div class="input-group" v-if="changeUnion">
      <label>
        <input type="text" class="input-field" v-model.trim="currentUnion.name" placeholder="Group name" @keypress.enter="_saveUnion">
      </label>
    </div>

    <div class="budge-description">
      <ckeditor :editor="editor" @focus="changeDescription" v-model="currentUnion.desc"></ckeditor>
    </div>

  </div>
</template>

<script>
import {moduleUrl} from "@/helpers/general";
import CKEditor from '@ckeditor/ckeditor5-vue';
import ClassicEditor from '@ckeditor/ckeditor5-build-classic';

export default {
  name: "index",
  title: 'Unions',
  components: {
    ckeditor: CKEditor.component,
  },

  data() {
    return {
      editor: ClassicEditor,
      unions: [],
      union: null,
      currentUnion: {
        desc: ''
      },
      createUnion: false,
      activeForSave: false,
      changeUnion: false,
    }
  },

  watch: {

    currentUnion: {
      handler(val) {
        if (this.changeUnion) {
          this.activeForSave = true
        }
      },
      deep: true
    }

  },

  created() {
    this._loadData()
  },

  methods: {

    _createUnion() {

      axios.put(`${moduleUrl(this.$route)}/union`, { name: this.union })
        .then((res) => {
          if (res.data.success) {
            this.unions.push({ name: this.union, desc: '' })
          }
        })
        .then(res => this.createUnion = false)
    },

    _loadData() {

      axios.get(`${moduleUrl(this.$route)}/union`)
        .then((res) => {
          this.unions = this._.cloneDeep(res.data.unions)
        })
        .then((res) => {
          if (this.unions.length) {
            this.currentUnion = this._.cloneDeep(this.unions[0])
          }
          this.activeForSave = false
        })

    },

    selectUnion($union) {
      this.currentUnion = $union
    },

    removeUnion($id) {

      axios.delete(`${moduleUrl(this.$route)}/union/${$id}`)
        .then((res) => {
          if (res.data.success) {

            this.unions = this.unions.filter((val) => {
              return val.id !== $id
            })

            this.notifier.success('Union deleted')
          }
        })

    },

    editUnion($union) {
      this.changeUnion = true
      this.activeForSave = true
      this.currentUnion = $union
    },

    changeDescription() {
      this.activeForSave = true
    },

    _saveUnion() {

      axios.patch(`${moduleUrl(this.$route)}/union`, this.currentUnion)
        .then((res) => {
          if (res.data.success) {
            this.changeUnion = false
            this.notifier.success('Union changed')
          }
        })
        .then(res => this.activeForSave = false)
    }

  }

}
</script>

<style scoped lang="scss">
.add {
  width: 35px;
  height: 35px;
  background-image: linear-gradient(250deg, #50b109, #0997b1);
  color: white;
  font-size: 12px;
  display: flex;
  justify-content: center;
  align-items: center;
  border-radius: 50%;
  line-height: 0;
  cursor: pointer;
}
.table-list-items {
  .budge-list {
    display: flex;
    flex-direction: row;
    justify-content: flex-start;
    flex-wrap: wrap;
    margin-bottom: 15px;
    align-items: center;
    .budge {
      margin-right: 5px;
      margin-bottom: 5px;
      &.active {
        background: #16aac5;
        display: flex;
        div {
          margin-left: 10px;
          width: 40px;
          display: flex;
          flex-direction: row;
          align-items: center;
          justify-content: space-around;
          i {
            &:hover {
              color: blueviolet;
            }
          }
        }
      }
    }
  }
}
</style>
