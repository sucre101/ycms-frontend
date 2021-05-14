<template>
  <div class="table-list-items">
    <h4>Publications</h4>

    <table class="table table-list-items">
      <tr>
        <td>ID</td>
        <td>Created</td>
        <td>Version</td>
        <td>Status</td>
        <td>Actions</td>
      </tr>
      <tr v-for="item in list">
        <td>{{ item.id }}</td>
        <td>{{ item.created_at }}</td>
        <td>{{ item.version }}</td>
        <td>{{ item.status_id }}</td>
        <td>
          <a class="btn-action blue" v-if="item.status_id === 2" @click="_downloadApp(item.version)" >Download</a>
        </td>
      </tr>
    </table>


    <div class="actions">
      <div class="btn-action blue" @click="_exportApp">Export</div>
    </div>
  </div>
</template>

<script>
  export default {
    name: "list",

    data() {
      return {
        list: []
      }
    },

    created() {
      this._getExportBuilds()
    },

    methods: {

      _downloadApp(version) {

        axios.get(`download?file=${encodeURIComponent("v"+version+"/app.apk")}`)
        .then((res) => {
          if (res.data.success) {
            const link = document.createElement('a');
            link.href = res.data.path;
            link.setAttribute('download',  `app-v${version}.apk`);
            document.body.appendChild(link);
            link.click()
          } else {
            this.notifier.warning(res.data.message)
          }

        })

      },

      _exportApp() {

        let slug = this.$root.$children[0].app.slug

        axios.get(`${slug}/publication/export`)
        .then((res) => {
          console.log(res)
        })

      },

      _getExportBuilds() {
        let slug = this.$root.$children[0].app.slug

        axios.get(`${slug}/publication`)
        .then((res) => {
          if (res.data.success) {
            this.list = res.data.builds
          }
        })
      }

    }

  }
</script>

<style scoped lang="scss">
  .actions {
    position: absolute;
    top: 15px;
    right: 15px;
  }
</style>
