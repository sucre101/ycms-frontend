<template>
  <div class="searcher">

    <img src="@/assets/img/ycms/search-icon.svg" alt="" @click="search">

    <input type="text" :placeholder="placeholder" v-model.trim="query" @keyup.enter="search">

    <a href="#" @click.prevent="clearSearch">
      <img src="@/assets/img/ycms/clear-search.svg">
    </a>

  </div>
</template>

<script>
export default {
  name: "searcher",

  props: {
    placeholder: {
      type: String,
      default: 'Search Term'
    },
    url: {
      type: String,
      required: true
    },
    template: {
      type: String,
      default: ''
    }
  },

  data() {
    return {
      query: null,
      templateOne: ''
    }
  },

  methods: {

    search() {

      if (this.query === null) {
        this.notifier.warning('Enter your query')
        return false;
      }

      axios.post(this.url, {query: this.query})
        .then((res) => {
          this.returnResult(res.data)
        })
    },

    clearSearch() {
      return this.query = null
    },

    returnResult(data) {
      this.$emit('complete', data);
    },

    // makeTemplate() {
    //   return this.$parent.createTemplate
    // }

    // createTemplate(data) {
    //
    //   let temp = document.createElement('ul')
    //   temp.classList.add('response-list')
    //
    //   this._.forEach(data.apps, el => {
    //     let els = document.createElement('li')
    //     els.append(el.name)
    //     temp.appendChild(els)
    //   })
    //
    //   return temp
    //
    // }

  }
}
</script>

<style scoped lang="scss">
.searcher {
  width: 151px;
  position: relative;

  input {
    height: 20px;
    border-radius: 3px;
    box-shadow: 0 0.5px 1px 0 rgba(0, 0, 0, 0.28);
    border: solid 1px rgba(0, 0, 0, 0.1) !important;
    background-color: var(--white);
    width: 100%;
    font-size: 13px;
    color: rgba(0, 0, 0, 0.76);
    padding: 0 20px;
  }

  img {
    position: absolute;
    top: 7px;
    left: 6px;
    cursor: pointer;
  }

  a {
    position: absolute;
    right: 24px;
    top: 0;
  }
}
</style>
