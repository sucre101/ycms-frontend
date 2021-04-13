<template>
  <div class="table-list-items">
    <h4>Publications</h4>

    <div class="table-list">

      <div v-for="item in list">
        {{ item.id }}, {{ item.created_at }}, {{ item.status_id }}
      </div>

    </div>

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
