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
      categories: []
    }
  },

  created() {
    this.loadData()
  },

  mounted() {
    window.setTitle('Categories list')
  },

  methods: {

    loadData() {

      for (let i = 1; i <= 10; i++) {

        this.categories.push({ id: i, title: 'Category' })

      }

    }

  }

}
</script>

<style scoped>

</style>
