<template>
  <div class="category-table">
    <InnerTab :items="[
        { name: 'General' },
        { name: 'Specs' }
    ]"
        @change="selectTab"
    />

    <div class="category-tabs">

      <div class="tab-general" v-if="currentTab === 0">
        tab general
      </div>

      <div class="tab-specs" v-if="currentTab === 1">
        <div class="spec-group-list">
          <div
              v-for="(specGroup, index) in category.spec_groups"
              class="spec-group"
              :class="{ active:  index === currentActive }"
              @mouseenter="currentActive = index"
          >
            <div class="btn-action">
              <span @click="addNewSpec(index)">Add</span>
              <span>Remove</span>
            </div>
            {{ specGroup.name }}
            <span class="spec" v-for="spec in specGroup.specs">
              {{ spec.name }}
            </span>
          </div>
        </div>
        <div @click="newSpecGroup = true" v-if="!newSpecGroup">
          Add Group
        </div>
        <input type="text" v-model.trim="newSpecGroupName" v-if="newSpecGroup"  @keyup.enter="setSpecDataName">
      </div>
    </div>

  </div>
</template>

<script>
import InnerTab from "@/components/base/ui/InnerTab";

export default {
  name: "category",

  components: {
    InnerTab
  },

  data() {
    return {
      currentTab: 0,
      category: {},
      categoryId: null,
      currentActive: 0,
      newSpecGroupName: '',
      newSpecGroup: false
    }
  },

  computed: {

    // category() {
    //   return this.$route.query.category
    // }

  },

  watch: {
    $route(to, from) {

      if (!to.query.hasOwnProperty('category')) {
        this.$parent.editCategory = false
      }
    },
  },

  created() {
    this.categoryId = this.$route.query.category

    this.loadData()
  },

  methods: {

    selectTab(tab) {
      this.currentTab = tab
    },

    setSpecDataName() {
      this.category.spec_groups.push({ name: this.newSpecGroupName, specs: [] })
      this.newSpecGroupName = ''
      this.newSpecGroup = false;
    },

    addNewSpec(index) {
      console.log()
      this.category.spec_groups[index].specs.push({ name: 'example' })
    },

    loadData() {

      axios.get(`/${this.$route.params.folder.toLowerCase()}/${this.$parent.$parent.moduleId}/category/${this.categoryId}`)
        .then((res) => {
          this.category = this._.cloneDeep(res.data.category)
          console.log(this.category)
        })

    }

  }

}
</script>

<style scoped lang="scss">
.category-table {
  width: 70%;
  background-color: white;
  padding: 15px 50px;
  .category-tabs {
    margin-top: 50px;

    .spec-group-list {
      .spec-group.active {
        .spec {
          display: flex;
        }
      }
      .spec-group {
        display: flex;
        flex-direction: column;
        border: 1px solid red;
        width: 48%;
        padding: 15px;
        font-size: 18px;
        font-weight: bold;
        position: relative;
        .spec {
          font-weight: normal;
          font-size: 16px;
          padding-left: 15px;
          cursor: pointer;
          display: none;
        }
        .btn-action {
          position: absolute;
          right: 0;
          span {
            font-size: 12px;
            font-weight: normal;
            margin: 0 15px;
          }
        }
      }
    }
  }
}
</style>
