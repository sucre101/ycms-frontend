<template>
  <div class="table-list-items">
    <h4>User extended fields</h4>

    <div v-if="activeForSave" class="btn-action blue save" @click="_saveProfileExtendedFields()">Save</div>

    <template v-for="field in extended">
      <div class="input-group">

        <template v-if="field.type_id === 2">
          <label class="custom-fields">
            {{ getterFields[field.type_id].title }}
            <div class="avatar-block" @click="setImage()">
              <img :src="getImage(field.value)" alt="">
            </div>
            <div class="remove fas fa-times" @click="removeField(field)"></div>
          </label>
        </template>

        <template v-if="field.type_id === 3">
          <label class="custom-fields">
            {{ getterFields[field.type_id].title }}
            <the-mask :mask="['+# (###) ###-##-##']" class="input-field" placeholder="+* (***) ***-**-**" :value.trim="field.value"/>
            <div class="remove fas fa-times" @click="removeField(field)"></div>
          </label>
        </template>

        <template v-if="field.type_id === 4">
          <label class="custom-fields">
            {{ getterFields[field.type_id].title }}
            <input type="text" :value="field.value" class="input-field">
            <div class="remove fas fa-times" @click="removeField(field)"></div>
          </label>
        </template>

        <template v-if="field.type_id === 6">
          <label class="custom-fields">
            {{ getterFields[field.type_id].title }}
            <input type="text" :value="field.value" class="input-field" readonly>
            <div class="remove fas fa-times" @click="removeField(field)"></div>
          </label>
        </template>

      </div>
    </template>
    <div v-if="activeButton">
      <div class="input-group">
        <label>
          <input type="text">
        </label>
      </div>
      <template v-for="input in getterFields">
        {{ input.title }}
      </template>
    </div>
    <div class="btn-action green" @click="showModal()">Add</div>

    <sweet-modal
        class="modal"
        ref="newFieldModal"
        width="550"
        overlay-theme="dark"
        blocking
        hide-close-button
    >
      <h4>Create new field</h4>

      <div class="list-input">
        <div ref="inputVariant" class="input-variant" v-for="input in getCustomFields" @click="select($event, input)">
          {{ input.title }}
        </div>
      </div>



      <!--      <div class="input-group" v-if="newFieldInput !== null" ref="newInputField">-->

<!--        <template v-if="newFieldInput.type_id === 3">-->
<!--          <the-mask :mask="['+# (###) ###-##-##']" class="input-field" placeholder="+* (***) ***-**-**"/>-->
<!--        </template>-->

<!--        <template v-if="newFieldInput.type_id === 4">-->
<!--          <input type="email" class="input-field" placeholder="email">-->
<!--        </template>-->

<!--        <template v-if="newFieldInput.type_id === 6">-->
<!--          <address-searcher ref="addrSearcher" @pick="setAddrGetCoords"/>-->
<!--        </template>-->

<!--      </div>-->

<!--      <div class="actions">-->
<!--        <div class="btn-action green" v-if="canSave" @click="_saveNewField()">Add</div>-->
<!--        <div class="btn-action blue" @click="$refs.newFieldModal.close()">Close</div>-->
<!--      </div>-->

    </sweet-modal>
  </div>
</template>

<script>
import {userFields} from "@/store/getters"
import {TheMask} from 'vue-the-mask'
import {imageUrl} from '@/helpers/general'
import AddressSearcher from "@/components/base/AddressSearcher"

export default {
  name: "extended-data",
  title: 'Extended',

  components: {
    TheMask, AddressSearcher
  },

  data() {
    return {
      extended: [],
      getterFields: userFields,
      activeButton: false,
      newFieldInput: null,
      canSave: false,
      activeForSave: false,
      image:
          'https://images.unsplash.com/photo-1590291409749-452efbe0d76c?ixid=MXwxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHw%3D&ixlib=rb-1.2.1&auto=format&fit=crop&w=634&q=80',
      result: {
        coordinates: null,
        image: null,
      },
    }
  },

  computed: {

    getCustomFields() {
      let res = []
      for (let item in this.getterFields) {
        if (this.getterFields[item].type_id !== 1) {

          if (this.getterFields[item].type_id === 2) {
            let index = this._.findIndex(this.extended, { type_id: 2 })

            if (index === -1) {
              res.push(this.getterFields[item])
            }
          } else {
            res.push(this.getterFields[item])
          }
        }
      }
      return res
    }
  },

  watch: {
    extended: {
      handler(val) {
        this.activeForSave = true
      },
      deep: true
    },
  },

  created() {
    this._getExtendedData()
    this.$root.$on('set::file', this.putImage)
  },

  methods: {

    setImage() {
      this.$root.$emit('fmanager::open', true)
    },

    changePhone() {
      console.log(123)
    },

    putImage($object) {

      let index = this._.findIndex(this.extended, { type_id: 2 })

      if (index !== -1) {

        let path = $object.name

        if (path.slice(0, 1) !== '/') {
          path = `/${path}`
        }

        this.extended[index].value = path
      }
    },

    removeField($field) {

      let fields = this.extended.filter(val => {
        return val.id !== $field.id
      })
      this.extended = fields
    },

    showModal() {
      this.$refs.newFieldModal.open()
    },

    getImage(path) {
      return imageUrl(path)
    },

    closeModal() {
      this.$refs.inputVariant.forEach(value => {
        value.classList.remove('active')
      })

      this.$refs.newFieldModal.close()

      this.$nextTick(() => {
        this.newFieldInput = null
        this.canSave = false
      })
    },

    _saveNewField() {
      let input = this.$refs.newInputField.getElementsByTagName('input')

      if (input.length) {
        let _this = input[0]

        if (_this.value === '' || _this.value === null) {
          this.notifier.warning('fill input')
          return false
        }

        this.newFieldInput.value = _this.value
        this.extended.push(this.newFieldInput)
        this.$nextTick(() => {
          this.closeModal()
        })
      }
    },

    _saveProfileExtendedFields() {
      axios.post('owner/extended', this.extended)
        .then((res) => {
          console.log(this.res)
        })
    },

    setAddrGetCoords(addr) {
      this.newFieldInput.value = addr.description
    },

    select($event, input) {

      this.$refs.inputVariant.forEach(value => {
        value.classList.remove('active')
      })

      this.$nextTick(() => {
        $event.target.classList.add('active')
        this.newFieldInput = input
        this.$set(this.newFieldInput, 'value', null)
        this.canSave = true
      })
    },

    _getExtendedData() {
      axios.get('owner')
          .then((res) => {
            if (res.data.extended.length) {
              this.extended = [...res.data.extended]
            }
          })
        .then(res => this.activeForSave = false)
    }

  }
}
</script>

<style scoped lang="scss">
.custom-fields {
  display: flex;
  flex-direction: column;
  width: 250px;
  position: relative;
  .remove {
    position: absolute;
    top: 0;
    right: 0;
    cursor: pointer;
    &:hover {
      color: #0997b1;
    }
  }
}
.list-input {
  display: flex;
  flex-direction: row;
  flex-wrap: wrap;
  .input-variant {
    border: 1px solid grey;
    border-radius: 20px;
    padding: 5px 15px;
    display: flex;
    cursor: pointer;
    margin: 0 10px 10px 0;
    &:hover {
      background-color: #0997b1;
    }
    &.active {
      background-color: #0997b1;
    }
  }
}
.input-field {
  width: 250px;
  margin: auto;
  margin-top: 10px;
}
.avatar-block {
  width: 100px;
  display: flex;
  justify-content: center;
  align-items: center;
  border: 1px solid #868686;
  height: 100px;
  cursor: pointer;
  position: relative;
  &:hover {
    opacity: 0.4;
    border-color: #0997b1;
    &::before {
      content: 'change';
      width: 50px;
      height: 50px;
      position: absolute;
      top: 40px;
    }
  }
  img {
    width: 75%;
  }
}
.sweet-content-content {
  .container {
    width: auto !important;
  }
  .actions {
    width: 100%;
    display: flex;
    flex-direction: row;
    justify-content: space-evenly;
  }
}
.cropper {
  height: 500px;
  background: #DDD;
}
</style>
