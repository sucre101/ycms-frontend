<template>
  <div>
    <div v-for="arch in archive" class="folder children" v-if="arch.type === 'folder'">
      <div class="title">
        <i class="fas fa-folder fa-2x"></i>
        <span @click="changeFolder(arch)">{{ arch.name }}</span>
      </div>
      <recursive-tree :reload="false" :archive="convert(arch)" v-if="arch.type === 'folder'"/>
    </div>
  </div>
</template>

<script>
export default {
  name: "recursive-tree",

  props: ['archive', 'reload'],

  data() {
    return {
      files: []
    }
  },

  created() {
    if (this.reload) {
      this.changeFolder()
    }
  },

  methods: {

    convert($object, myArr = []) {

      if ($object.type === 'folder') {
        this._.forEach($object[0], (val) => {
          if (val.type === 'folder') {
            myArr.push(val)
          }
        })
      }
      return myArr
    },

    changeFolder($folder = false, root = false) {

      let files = []
      let folder

      if ($folder && root) {
        folder = $folder
      } else if ($folder && !root) {
        folder = $folder[0]
      } else {
        folder = false
      }

      this._.forEach(folder ? folder : this.archive, (val) => {
        if (val.type === 'file') {
          files.push(val)
        }
      })

      this.$root.$emit('file::transfer', files)
    }

  }
}
</script>

<style scoped>

</style>
