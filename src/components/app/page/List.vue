<template>
  <div class="page-list-table">

    <div class="actions">
      <div class="button save-btn" @click="_save()">Save</div>
      <div class="button new-btn" @click="createPageModal()">New</div>
    </div>

    <div class="top-bar-to-table">
      <span class="title">Page list</span>
      <div class="search">
        <div class="input-group">
          <input type="text" class="input-field" placeholder="Search ...">
        </div>
        <div class="count">
          <span>Items</span> {{ pages.length }}
        </div>
      </div>
    </div>
    <table>
      <thead>
        <tr>
          <td>№</td>
          <td>Feature</td>
          <td>Menu title</td>
          <td>Page title</td>
          <td>Available on Tab-menu</td>
          <td>Available on Left-menu</td>
          <td>Active</td>
          <td>Actions</td>
        </tr>
      </thead>
      <tbody>
        <tr v-for="(page, index) in pages">
          <td>{{ index + 1 }}</td>
          <td>{{ page.userModule || '-' }}</td>
          <td>{{ page.name }}</td>
          <td>{{ page.title }}</td>
          <td>
            <toggle-check :value="Boolean(page.tab_menu)" @complete="pageIndicatorsUpdate($event, page, 'tab_menu')"/>
          </td>
          <td>
            <toggle-check :value="Boolean(page.left_menu)" @complete="pageIndicatorsUpdate($event, page, 'left_menu')"/>
          </td>
          <td>
            <toggle-check :value="Boolean(page.active)" @complete="pageIndicatorsUpdate($event, page, 'active')"/>
          </td>
          <td>
            <div class="action-block">
              <span>
                <i class="far fa-edit"></i>
              </span>
              <span @click="_deletePage(page.id)">
                <i class="far fa-trash-alt"></i>
              </span>
            </div>
          </td>
        </tr>
      </tbody>
    </table>

    <sweet-modal
        class="modal"
        ref="createPage"
        width="550"
        overlay-theme="dark"
    >
      <div class="new-page-form">

        <div class="input-group">
          <input type="text" class="input-field" placeholder="Menu title" v-model.trim="newPage.name">
        </div>

        <div class="input-group">
          <input type="text" class="input-field" placeholder="Page title" v-model.trim="newPage.title">
        </div>

        <div class="button save" @click="_createPage()">Save</div>

      </div>
    </sweet-modal>

  </div>
</template>

<script>
import ToggleCheck from "../../base/ui/ToggleCheck";

export default {
  name: "page-list",

  components: {
    ToggleCheck
  },

  data() {
    return {
      newPage: {
        title: '',
        name: ''
      },
      pages: []
    }
  },

  created() {
    this._loadPages()
  },

  mounted() {
    window.setTitle(this.$route.meta.title)
  },

  methods: {

    pageIndicatorsUpdate($event, page, field) {
      page[field] = $event.value
    },

    createPageModal() {
      this.$refs.createPage.open()
    },

    _save() {
      let promise = axios.patch('pages', {pages: this.pages})
        .then((res) => {
          console.log(res)
        })

      this.notifier.async(promise, 'All pages updated')
    },

    _createPage() {
      let promise = axios.put('pages', this.newPage)
        .then((res) => {
          this.pages = [...res.data.pages]
        })
        .then(res => this.$refs.createPage.close())

      // this.notifier.async(promise, 'New page is created')
    },

    _loadPages() {
      axios.get('pages')
        .then((res) => {
          this.pages = [...res.data.pages]
        })
    },

    _deletePage($id) {
      let promise = axios.delete(`/pages/${$id}`)
        .then((res) => {
          this.pages = [...res.data.pages]
        })

      this.notifier.async(promise, 'Page deleted')
    }

  }
}
</script>

<style scoped lang="scss">
.page-list-table {
  diplay: flex;
  flex-direction: column;
  .actions {
    display: flex;
    flex-direction: row;
    justify-content: flex-end;
    margin-bottom: 20px;
    font-size: 15px;
    font-weight: 600;
    .button {
      width: 135px;
      height: 38px;
      display: flex;
      justify-content: center;
      align-items: center;
      color: #ffffff;
      border-radius: 8px;
      margin-right: 20px;
      cursor: pointer;
    }
    .save-btn {
      background-color: #161c2e;
    }
    .new-btn {
      background-color: #8674f4;
    }
  }
  .top-bar-to-table {
    display: flex;
    height: 70px;
    background-color: #ffffff;
    border: 1px solid #edf2f6;
    border-bottom: none;
    border-top-left-radius: 8px;
    border-top-right-radius: 8px;
    align-items: center;
    justify-content: space-between;
    .title {
      font-size: 20px;
      padding-left: 20px;
    }
    .search {
      display: flex;
      align-items: center;
      .count {
        margin-right: 40px;
        font-size: 14px;
        span {
          color: #687c97;
          font-size: 12px;
          padding-right: 10px;
        }
      }
      .input-group {
        margin: 0 20px 0 0;
        .input-field {
          border: solid 1px #f4f7f9;
          border-radius: 8px;
        }
      }
    }

  }
  table {
    width: 100%;
    background-color: #ffffff;
    border: 1px solid #edf2f6;
    border-radius: 8px;
    thead {
      background-color: #f9fbfd;
      height: 50px;
      tr {
        border-bottom: 1px solid #edf2f6;
        td {
          padding-left: 15px;
          color: #687c97;
          font-family: 'SFProText-Light', sans-serif;
          font-size: 14px;
          &:first-child {
            border-right: 1px solid #edf2f6;
            text-align: center;
            padding: 0;
            width: 60px;
          }
          &:nth-child(2) {
            width: 20%;
            text-align: left;
          }
          &:nth-child(3) {
            width: 20%;
            text-align: left;
          }
          &:nth-child(4) {
            width: 20%;
            text-align: left;
          }
          &:last-child {
            width: 10%;
          }
        }
      }
    }
    tbody {
      tr {
        border-bottom: 1px solid #edf2f6;
        font-family: 'SFProText-Light', sans-serif;
        font-size: 15px;
        td {
          padding: 25px 0 25px 15px;
          &:first-child {
            border-right: 1px solid #edf2f6;
            text-align: center;
            padding: 0;
          }
          .action-block {
            display: flex;
            justify-content: space-evenly;
            padding-right: 20px;
            font-size: 14px;
            color: #687c97;
            span {
              cursor: pointer;
            }
          }
        }
      }
    }
  }
}
</style>
