<template>
<div class="pages-list">
  <div class="pages-list-item"
    v-for="page in filteredPages" :key="page.id"
    :class="{home: page.name == 'home'}"
    :data-id="page.id"
  >
    <img
      v-if="notHome(page) && showNotDeleted"
      class="handle"
      src="@/assets/img/drag-drop.svg"
    >
    <div v-else style="width: 21px"></div>
    <div class="icon-container" @click="chooseIcon(page)">
      <ion-icon :name="page.icon"></ion-icon>
    </div>
    <div class="title">
      <a :href="`/app/${appSlug}/pages/${page.id}/edit`">{{ page.title }}</a>
    </div>
    <template v-if="showNotDeleted">
      <div class="module">
        {{ page.mod_desc.title }}
      </div>
      <toggle-button
        class="ml-39"
        v-model="page.active"
        :color="{checked: '#0997B1', unchecked: '#868686'}"
        :width="51"
        :height="31"
        :margin="2"
      />
      <toggle-button
        v-if="!parentPage"
        class="ml-123"
        v-model="page.tabbar"
        :color="{checked: '#0997B1', unchecked: '#868686'}"
        :width="51"
        :height="31"
        :margin="2"
      />
    </template>
    <div class="restore-block" v-else>
      <div
        class="small-rounded-btn green-bg"
        @click="restore(page)"
      >
        Restore
      </div>
    </div>
    <img
      v-if="notHome(page)"
      class="ml-auto"
      src="@/assets/img/garbage.png"
      alt="garb"
      @click="showDeleteWarning(page)"
    >
  </div>

  <sweet-modal
    class="modal"
    ref="modulesModal"
    width="915"
    overlay-theme="dark"
  >
    <h6>Select module for a page</h6>

    <ycms-page-template
      v-for="module in modules" :key="module.id"
      name="newPageModule"
      :title="module.title"
      :description="module.description"
      :img="module.image"
      :value="module.name"
    />

    <ycms-action-buttons
      :buttons-list="[
        {
          title: 'CREATE',
          handler: 'eval:this.$parent.$parent.createPage()',
          class: 'bg-green-gradient',
          action: '/app/' + appSlug + '/'
        }
      ]"
      align="right"
    />
  </sweet-modal>
  <div class="action-buttons-w-switch" v-if="showNotDeleted">
    <div class="switch-block" v-if="parentPage">
      <toggle-button
          v-model="$parent.page.module.root_pages"
          :color="{checked: '#0997B1', unchecked: '#868686'}"
          :width="51"
          :height="31"
          :margin="2"
        />
        root pages
    </div>
    <ycms-action-buttons
      :buttons-list="[
        {
          title: 'ADD PAGE',
          handler: 'eval:this.$parent.showModulesModal()',
          class: 'bg-green-gradient'
        },
        {
          title: 'SAVE',
          handler: 'eval:this.$parent.savePages()',
          class: 'bg-black',
        },
      ]"
      align="right"
    />
  </div>
  <ycms-ion-icon-searcher ref="iconSearcher" @pick="setIcon"/>
</div>
</template>

<script>
// import { diff, addedDiff, deletedDiff, updatedDiff, detailedDiff } from 'deep-object-diff';
import dragula from 'dragula'

export default {
  data() {
    return {
      pages: this.pagesList.filter(p => p.deleted_at == null),
      deleted: this.pagesList.filter(p => p.deleted_at != null),
      modules: this.modulesList,
      newPageModule: null,
      homeWarningShown: false,
      filter: 'notDeleted',
      toDelete: [],
      toRestore: [],
      deletePermanently: [],
      markedForDeletion: null,
      awaitingIcon: {},
      debug: true,
    }
  },

  computed: {
    filteredPages() {
      return this.filter == 'notDeleted'
        ? this.pages.concat(this.toRestore)
        : this.deleted.concat(this.toDelete)
    },
    showNotDeleted() {
      return this.filter == 'notDeleted'
    },
    showDeleted() {
      return this.deleted || this.toDelete
    }
  },

  props: {
    pagesList: Array,
    modulesList: Array,
    parentPage: Object,
    appSlug: String,
  },

  methods: {
    showModulesModal() {
      this.$refs.modulesModal.open()
    },
    createPage() {
      this.$refs.modulesModal.close()
      post('/app/'+ this.appSlug +'/pages/new-page', {
        parent: this.parentPage,
        pageModule: this.newPageModule,
      })
      .then(res => this.pages.push(res.page))
    },
    notHome(page) {
      return page.name != 'home'
    },
    setOrder() {
      if (this.filter == 'notDeleted') {
        let pageEls = this.$el.getElementsByClassName('pages-list-item')
        Array.from(pageEls).forEach((el, i) => {
          this.pages.concat(this.toRestore)
          .find(p => p.id == el.dataset.id).order = i
        })
      }
    },
    savePages() {
      post('/app/'+ this.appSlug +'/pages/update-pages', {
        parent: this.$parent.page,
        pages: this.pages,
        toRestore: this.toRestore,
        toDelete: this.toDelete,
        deletePermanently: this.deletePermanently
      })
    },
    showDeleteWarning(page){
      this.markedForDeletion = page
      this.notifier.confirm('Are you sure?', this.deletePage)
    },
    deletePage() {
      //TODO: Refactor it!
      if (this.parentPage) {
        this.deletePermanently.push(this.pages.pull(this.markedForDeletion))
        return
      }
      if (this.pages.includes(this.markedForDeletion)) {
        this.toDelete.push(this.pages.pull(this.markedForDeletion))
        return
      }
      if (this.toRestore.includes(this.markedForDeletion)) {
        this.deleted.push(this.toRestore.pull(this.markedForDeletion))
        return
      }
      if (this.deleted.includes(this.markedForDeletion)) {
        this.deletePermanently.push(this.deleted.pull(this.markedForDeletion))
        return
      }
      if (this.toDelete.includes(this.markedForDeletion)) {
        this.deletePermanently.push(this.toDelete.pull(this.markedForDeletion))
        return
      }
    },
    restore(page) {
      if(this.deleted.includes(page))
        this.toRestore.push(this.deleted.pull(page))
      if (this.toDelete.includes(page)) {
        page.order = Math.max(...this.pages.map(p => p.order)) + 1
        this.pages.push(this.toDelete.pull(page))
      }
    },
    chooseIcon(page) {
      this.awaitingIcon = page
      this.$refs.iconSearcher.showModal()
    },
    setIcon(icon){
      this.awaitingIcon.icon = icon
    }
  },

  mounted() {
    this.setOrder()

    this.$root.$on('ls-change', (name, val) => {
      if (name == 'newPageModule') this.newPageModule = val
    })

    this.$root.$on('filter', val => {
      this.filter = val
      let filterButtons = document.getElementsByClassName('filter')
      for(let el of filterButtons) {
        el.classList.contains(val)
          ? el.classList.add('active')
          : el.classList.remove('active')
      }
    })

    dragula([this.$el], {
      moves: (el, src, handle) => handle.classList.contains('handle'),
      accepts: (el, target, source, sibling) => {
        if (sibling.classList.contains('home')) {
          if (!this.homeWarningShown)
            this.notifier.warning('You cannot change home position')
          this.homeWarningShown = true;
          return false
        } else {
          return true
        }
      },
    })
    .on('drop', (el, target, source, sibling) => {
      this.setOrder()
    })
  },

  updated() {
    this.setOrder()
  },
}
</script>

<style lang="scss" scoped>


.pages-list {
  display: flex;
  flex-direction: column;
  width: 100%;
  margin: 0 39px 0 0;
}

.pages-list-item {

  justify-content: flex-start;
  width: 100%;
  height: 56px;
  padding: 0 21px;
  background-color: rgba(#868686, .06);
  border-radius: 5px;
  margin-bottom: 5px;

  .icon-container {

    background-image: linear-gradient(45deg, #2ccae6, #0997b1);
    box-shadow: 0 10px 20px 0 rgba(0, 0, 0, 0.2);
    width: 31px;
    height: 31px;
    border-radius: 50%;
    color: white;
    font-size: 21px;
    margin-left: 32px;
  }

  .title {
    a {
      display: block;
      color: #0997b1;
      margin-left: 22px;
      min-width: 100px;
    }
  }

  .module {

    justify-content: flex-start;
    background-color: #868686;
    color: white;
    width: 158px;
    height: 31px;
    border-radius: 16px;
    font-size: 14px;
    font-weight: 300;
    margin-left: 57px;
    padding-left: 14px;
  }

  .vue-js-switch {
    margin-bottom: 0;
  }
  .v-switch-core {
    background-color: #0997b1 !important;
  }

  img {
    cursor: pointer;
  }

  .restore-block {

    flex: 1;
  }
}

.action-buttons-w-switch {
  display: flex;

  .switch-block {
    margin-top: 42.5px;

    justify-content: flex-start;

    .vue-js-switch {
      margin: 0 21px 0 0;
    }
  }

  .bottom-buttons {
    flex: 1;
  }
}
</style>
