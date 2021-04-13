<template>
  <div class="table-list-items">

    <div class="input-group" v-if="mod">
      <Label>Source</Label>
      <input type="text" v-model="mod.src" class="input-field" placeholder="example https://google.com">
    </div>

<!--    <div v-if="mod">-->
<!--      <iframe width="300" height="500" :src="mod.src"/>-->
<!--    </div>-->

    <div class="input-group" v-if="activeForSave">
      <div class="btn-action green" @click="_save">Save</div>
    </div>

  </div>
</template>

<script>
import {moduleUrl} from "@/helpers/general"

export default {
  name: "index",

  data() {
    return {
      moduleId: this.$route.params.moduleId,
      mod: {},
      activeForSave: false,
    }
  },

  created() {
    this._loadModuleData()
  },

  mounted() {
    window.setTitle('WebView module')
  },

  watch: {

    mod: {
      handler(val) {
        this.activeForSave = true
      },
      deep: true
    }

  },

  methods: {

    _loadModuleData() {

      axios.get(`${moduleUrl(this.$route)}`)
        .then((res) => {
          if (res.data.success) {
            this.mod = this._.cloneDeep(res.data[0])
          }
        })
        .then(res => this.activeForSave = false)
    },

    _save() {
      axios.patch(`${moduleUrl(this.$route)}`, { src: this.mod.src} )
        .then((res) => {
          if (res.data.success) {
            this.notifier.success('Saving data')
          }
        })
        .then(res => this.activeForSave = false)
    }

  }

}
</script>

<style scoped lang="scss">
.input-field {
  width: 50%;
}
</style>
