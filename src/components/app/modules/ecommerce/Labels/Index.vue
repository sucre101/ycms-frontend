<template>
  <div class="table-list-items" @contextmenu.capture.prevent>
    <h4>Label list</h4>

    <div class="btn-action blue save" v-if="activeForSave" @click="_save">
      Save
    </div>

    <div class="label-list">

      <template v-for="(label, index) in labels">
        <div class="label"
             :class="{ 'active' : currentLabel.id === label.id }"
             @click="selectLabel(label)"
        >
          {{ label.title }}
          <div class="actions" v-if="currentLabel.id === label.id">
            <i class="far fa-file-image"
               v-img-preview="label.image ? label.image.url_original : null"
               title="Right click remove picture"
               @click="setPicture"
               @click.right="removePicture(index)"
               :ref="`preview${index}`"
            ></i>
            <i class="far fa-edit" @click="edit = !edit"></i>
            <i class="fas fa-times" @click="_removeLabel()"></i>
          </div>
        </div>
      </template>

      <div class="add" @click="createLabel = !createLabel">
        <i class="fas fa-plus"></i>
      </div>

    </div>

    <div class="input-group" v-if="createLabel">
      <label>
        <input type="text" class="input-field" v-model.trim="label" placeholder="Label name" @keypress.enter="_createLabel">
      </label>
    </div>

    <div class="input-group" v-if="edit">
      <label>
        <input type="text" class="input-field" v-model.trim="currentLabel.title" placeholder="Label name" @keypress.enter="_save">
      </label>
    </div>

  </div>
</template>

<script>
import {moduleUrl} from "@/helpers/general"
import {imgPreview} from '@/vue-directives'

export default {
  name: "index",
  title: 'Labels',
  directives: {
    imgPreview
  },

  data() {
    return {
      labels: [],
      currentLabel: {},
      createLabel: false,
      activeForSave: false,
      label: null,
      edit: false
    }
  },

  watch: {

    currentLabel: {
      handler(val) {
        this.activeForSave = true
      },
      deep: true
    }

  },

  created() {
    this._loadData()
    this.$root.$on('set::file', this.putImage)
  },

  methods: {

    putImage($object) {

      this.currentLabel.image = {
        entity: 'label',
        entity_id: this.currentLabel.id,
        order: null,
        image_url: `${process.env.VUE_APP_URI}/${$object.name}`,
        url_original: `${process.env.VUE_APP_URI}/${$object.name}`
      }

      this.activeForSave = true
    },

    setPicture() {
      this.$root.$emit('fmanager::open', true)
    },

    removePicture($index) {
      if (this.currentLabel.image === null) {
        this.notifier.warning('Picture is empty')
        return;
      }

      let ref = `preview${$index}`
      let $this = this.$refs[ref][0]
      let preview = $this.children[0]

      $this.removeChild(preview)

      this.$set(this.currentLabel, 'image', null)
    },

    _removeLabel() {

      axios.delete(`${moduleUrl(this.$route)}/label/${this.currentLabel.id}`)
        .then((res) => {
          this.labels = this.labels.filter((val) => {
            return val.id !== this.currentLabel.id
          })

          this.notifier.success('Label deleted')
        })
        .then(res => this.currentLabel = this.labels[0])
    },

    _createLabel() {

      axios.put(`${moduleUrl(this.$route)}/label`, { name: this.label })
          .then((res) => {
            if (res.data.success) {
              this.labels.push({ title: this.label, image: null })
            }
          })
          .then(res => this.createLabel = false)
    },

    selectLabel($label) {
      this.currentLabel = $label
    },

    _save() {

      if (this.currentLabel.image !== null) {
        this.currentLabel.image.url_medium = this.currentLabel.image.url_small = this.currentLabel.image.url_original
      }

      axios.patch(`${moduleUrl(this.$route)}/label`, this.currentLabel)
        .then((res) => {
          if (res.data.success) {
            this.notifier.success('Save label')
            this.activeForSave = this.edit = false
          }
        })

    },

    _loadData() {

      axios.get(`${moduleUrl(this.$route)}/label`)
        .then((res) => {
          this.labels = [...res.data.labels]
          if (this.labels.length) {
            this.currentLabel = this._.cloneDeep(this.labels[0])
          }
        })
          .then((res) => {
            this.activeForSave = false

          })


    }

  }
}
</script>

<style scoped lang="scss">
.label-list {
  display: flex;
  flex-direction: row;
  justify-content: flex-start;
  flex-wrap: wrap;
  margin-bottom: 15px;
  align-items: center;
  .label {
    display: flex;
    background: blueviolet;
    color: white;
    font-size: 14px;
    padding: 2px 15px;
    border-radius: 22px;
    cursor: pointer;
    margin-right: 5px;
    margin-bottom: 5px;
    .actions {
      margin: 0 5px;
    }
    &.active {
      background: #16aac5;
      div {
        display: flex;
        flex-direction: row;
        align-items: center;
        justify-content: space-around;
        i {
          padding: 0 3px;
          &:hover {
            color: blueviolet;
          }
        }
      }
    }
  }
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
}
</style>
