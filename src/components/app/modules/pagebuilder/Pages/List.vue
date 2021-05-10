<template>
  <div class="pages-list-table">
    <h4>Your pages</h4>
    <div v-if="pages.length">
      <div v-for="page in pages" class="item">

        <div class="item-name">{{ page.title }}</div>

        <div class="item-actions">

          <div class="btn btn-edit" @click.prevent="editpage(page.id)">Edit</div>
          <div class="btn btn-copy" @click.prevent="editpage(page.id)">Duplicate</div>
          <div class="btn btn-remove" @click.prevent="editpage(page.id)">
            <img src="@/assets/img/garbage.png" alt="">
          </div>

        </div>

      </div>
    </div>

  </div>
</template>

<script>
export default {
  name: "pages-list",

  data() {
    return {
      pages: [],
      module: {}
    }
  },

  created() {
    this.module.id = this.$parent.$parent.moduleId
    this.loadData()
  },

  methods: {

    loadData() {

      axios.get(`/${this.$route.params.folder.toLowerCase()}/${this.module.id}/pages`)
          .then((res) => {
            this.pages = this._.cloneDeep(res.data.pages)
          })
    },

    editpage(id) {

      this.$router.replace({ query: { tab: 'pages', page: id } })
      this.$parent.editpage = true

    }

  }

}
</script>

<style scoped lang="scss">
.pages-list-table {
  width: 70%;
  background-color: white;
  padding: 15px 50px;
  .item {
    display: flex;
    flex-direction: row;
    justify-content: space-between;
    margin-bottom: 20px;
    align-items: center;
    .item-actions {
      display: flex;
      align-items: center;
      .btn {
        padding: 5px 34px 7px 35px;
        border-radius: 16px;
        color: white;
        margin: 0 10px;
        cursor: pointer;
      }
      .btn-edit {
        background-color: #0997b1;
      }
      .btn-copy {
        background-color: #50b109;
      }
      .btn-remove {
        padding: 0;
      }
    }
  }

  h4 {
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
}
</style>
