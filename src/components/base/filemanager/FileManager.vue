<template>
  <div>

    <sweet-modal
      class="modal text-left"
      style="position: relative"
      ref="FileManager"
      width="1024"
      height="650"
      overlay-theme="dark"
      title="File Manager"
      id="file-manager"
      oncontextmenu="return false;"
    >

      <div class="folders-block">

        <div class="folder">

          <div class="title">
            <i class="fas fa-folder-open fa-2x"></i>
            <span @click="getRootFiles">Root</span>
          </div>

          <recursive-tree :archive="archive" v-if="!loading" :reload="true"/>

        </div>
      </div>

      <div class="files-block">
        <div v-for="file in files" v-if="!loading" class="item">
          <img :src="`/${file.name}`" @click.prevent="setFile(file)" />
        </div>

        <div class="btn-action blue" @click="browseFile">
          Upload
          <input type="file" id="file" ref="file" style="display: none" @change="handleFileUpload()"/>
        </div>

<!--        <progress max="100" :value.prop="progressUpload" v-if="isUpload"/>-->

      </div>

    </sweet-modal>

  </div>
</template>

<script>
import RecursiveTree from "@/components/base/filemanager/RecursiveTree";

export default {

  name: "file-manager",

  components: { RecursiveTree },

  data() {
    return {
      archive: [],
      loading: true,
      depth: 0,
      files: [],
      folders: [],
      uploadFile: null,
      progressUpload: 0,
      isUpload: false,
      currentFolder: null
    }
  },

  created() {
    axios.get(`/file-manager`)
      .then((res) => {
        this.archive = this._.cloneDeep(res.data.archive)
      })
    .then(res => this.loading = false)


    this.$root.$on('file::transfer', this.getFiles)
  },

  methods: {

    setFile(file) {
      this.$root.$emit('set::file', file)
      this.$nextTick(() => {
        this.$refs.FileManager.close()
        this.$root.$emit('fmanager::open', false)
      })
    },

    getFiles($array) {
      this.files = this._.cloneDeep($array)
    },

    getRootFiles() {
      this.$children[0].$children[0].changeFolder(this.archive, true)
    },

    browseFile() {
      this.$refs.file.click()
    },

    handleFileUpload() {

      this.uploadFile = this.$refs.file.files[0]

      if (this.uploadFile !== null) {

        let formData = new FormData()

        formData.append('file', this.uploadFile)

        this.$nextTick(() => {

          axios.post('/file-manager/upload',
              formData,
              {
                headers: {
                  'Content-Type': 'multipart/form-data'
                }
              }
          ).then((res) => {
            this.archive = this._.cloneDeep(res.data.archive)
            this.getRootFiles()
          })

        })

      }

    }

  }

}
</script>

<style lang="scss">
#file-manager {
  .sweet-modal {
    .sweet-title {
      display: flex !important;
      flex-direction: column !important;
      justify-content: center !important;
      align-items: center !important;
    }
    progress {
      height: 10px;
      position: absolute;
      top: 30px;
      left: 15px;
    }
    .btn-action {
      position: absolute;
      top: 20px;
      left: 30px;
    }
    .sweet-content {
      padding: 0;
      .sweet-content-content {
        display: flex;
        min-height: 600px;
        .folders-block {
          display: flex;
          flex-direction: column;
          width: 20%;
          border-right: 1px solid #eaeaea;
          padding-top: 15px;
          padding-left: 20px;
          .folder {
            font-size: 12px;
            display: flex;
            flex-direction: column;
            cursor: pointer;
            i {
              padding-right: 5px;
              color: #0997b1;
            }
            &.children {
              margin-left: 15px;
            }
          }
        }
        .files-block {
          display: flex;
          flex-direction: row;
          flex-wrap: wrap;
          justify-content: flex-start;
          max-height: 70vh;
          width: 80%;
          padding-top: 15px;
          padding-left: 15px;
          .item {
            width: 100px;
            height: 100px;
            display: flex;
            justify-content: center;
            border: 1px solid;
            cursor: pointer;
            margin-bottom: 10px;
            margin-right: 10px;
            img {
              width: 100%;
            }
          }
        }
      }

    }
  }
}


</style>
