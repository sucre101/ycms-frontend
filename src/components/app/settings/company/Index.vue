<template>
  <div class="table-list-items">
    <h4>List companies</h4>

    <div class="main-content">
      <div class="list-companies">
        <vue-custom-scrollbar class="content-scroll">
          <template v-for="(company, index) in companies">
            <div
              class="company"
              :class="{ 'active': company.id === currentCompany.id }"
              @click="changeCompany(company)"
            >
              {{ company.company_name }}
            </div>
          </template>
        </vue-custom-scrollbar>
      </div>
      <div class="description-block">
        <div class="tabs">
          <InnerTab
            :items="tabs"
            :selected="selectedTab"
            @change="selectTab"
            v-if="!update"
          />
          <div class="tab general-data" v-if="selectedTab === 0">
            General data
          </div>
          <div class="tab employee-list" v-if="selectedTab === 1">
            <template v-for="employee in currentCompany.employees">
              <div class="user-item">
                <template v-for="extended in employee.extended">
                  <div class="profile-avatar" v-if="extended.type_id === 2">
                    <img :src="getImage(extended.value)" alt="">
                  </div>
                  <div class="profile-name" @click="openProfileModal(employee)">
                    {{ getEmployeeFullname(employee) }}
                  </div>
                </template>

              </div>
            </template>
          </div>
        </div>

        <profile-modal
          :profile="currentProfile"
          v-if="showProfile"
          @close="closeProfileModal"
        />

      </div>
    </div>
  </div>
</template>

<script>
import InnerTab from "@/components/base/ui/InnerTab"
import vueCustomScrollbar from 'vue-custom-scrollbar'
import "vue-custom-scrollbar/dist/vueScrollbar.css"
import {imageUrl} from "@/helpers/general";
import ProfileModal from "@/components/app/settings/modals/ProfileModal";

export default {
  name: "index",

  components: {
    ProfileModal,
    InnerTab, vueCustomScrollbar
  },

  data() {
    return {
      slug: this.$root.$children[0].app.slug,
      companies: [],
      currentCompany: {},
      tabs: [
        { name: 'General' },
        { name: 'Employees' },
      ],
      selectedTab: 0,
      update: false,
      showProfile: false,
      currentProfile: null
    }
  },

  created() {
    this._loadData()
  },

  methods: {

    openProfileModal($employee) {
      this.showProfile = true
      this.currentProfile = this._.cloneDeep($employee)
      // this.$nextTick(() => {
      //   this.$refs.profile.open()
      // })
    },

    closeProfileModal() {
      this.showProfile = false
      this.currentProfile = null
    },

    selectTab(index) {
      this.selectedTab = index
    },

    getEmployeeFullname($employee) {
      return `${$employee.name} ${$employee.lastname ? $employee.lastname : ''}`
    },

    changeCompany($company) {
      this.update = true
      this.selectedTab = 0
      this.currentCompany = $company
      this.$nextTick(() => {
        this.update = false
      })
    },

    getImage(path) {
      return imageUrl(path)
    },

    _loadData() {
      axios.get(`${this.slug}/settings/company`)
        .then((res) => {
          this.companies = [...res.data.companies]

          // for (let i = 0; i <= 10; i++) {
          //   this._.forEach(res.data.companies, item => {
          //     this.companies.push(item)
          //   })
          // }

        })
        .then(res => this.currentCompany = this.companies[0])
    }

  }
}
</script>

<style lang="sass" scoped>
.content-scroll
  max-height: 600px
.table-list-items
  height: 650px
  .main-content
    display: flex
    flex-direction: row
    height: 100%

    .list-companies,.description-block
      width: 50%
      height: 90%
    .description-block
      .tabs
        margin: 10px 15px
        .tab
          margin-top: 20px
        .employee-list
          display: flex
          flex-direction: column
          .user-item
            border: 1px solid white
            display: flex
            flex-direction: row
            padding: 5px 10px
            border-radius: 4px
            align-items: center
            .profile-avatar
              width: 25px
              display: flex
              cursor: pointer
              img
                width: 100%
            .profile-name
              font-size: 16px
              margin-left: 10px
              letter-spacing: 1.2px
              cursor: pointer

    .list-companies
      overflow: hidden
      border-bottom: 1px solid #f0f3f6
      border-left: 1px solid #f0f3f6
      border-top: 1px solid #f0f3f6
      .company
        padding: 10px 20px
        cursor: pointer
        border-bottom: 1px solid #f0f3f6
        position: relative
        display: flex
        justify-content: center
      .active
        &:before
          content: ""
          width: 32px
          height: 32px
          background-color: #f0f3f6
          display: block
          position: absolute
          left: -16px
          top: 7px
          transform: rotate(45deg)
        &:after
          content: ""
          width: 32px
          height: 32px
          background-color: #f0f3f6
          display: block
          position: absolute
          right: -16px
          top: 7px
          transform: rotate(45deg)


</style>
