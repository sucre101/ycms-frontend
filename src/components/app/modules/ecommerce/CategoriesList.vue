<template>
  <div>
    <ul>
      <li v-for="cat in categories">
        {{ cat.title }} {{ cat.id }}
      </li>
    </ul>
  </div>
</template>

<script>
export default {
  name: "categories-list",

  data() {
    return {
      categories: [],
      module: {}
    }
  },

  created() {
    this.module.id = this.$parent.moduleId
    this.loadData()
  },

  mounted() {
    window.setTitle('Categories list')
  },

  methods: {

    loadData() {

      axios.get(`/${this.$route.params.folder.toLowerCase()}/${this.module.id}/categories`)
        .then((res) => {
          console.log(res)
        })

    }

  }

}
</script>

<style scoped>

</style>
