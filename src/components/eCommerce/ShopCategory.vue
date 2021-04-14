<template>
  <div class="container">
    <div class="category" :data-id="category.id">
      <div class="parent-cat">
        <div class="icon-container" @click="showProducts">
          <img v-bind="{src}">
        </div>
        <div class="title">
          <a @click="showProducts">{{ category.name }}</a>
        </div>
        <div class="buttons-block">
          <div
            class="small-rounded-btn blue-bg"
            @click="edit(category)"
          >
            Edit
          </div>
        </div>
        <img
          class="ml-auto"
          src="@/img/garbage.png"
          @click="showDeleteWarning(category)"
        >
      </div>
      <div class="children">
        <shop-category
          v-for="child in category.children" :key="child.id"
          :category="child"
        />
      </div>
    </div>
  </div>
</template>

<script>
import Cropper from 'cropperjs'

export default {
  name: 'shop-category',

  data() {
    return {
      markedForDeletion: {},
    }
  },

  props: {
    category: Object,
  },

  methods: {
    edit(category) {
      // grefs.catId.value = category.id
      grefs.categoriesManager.editable = category
      grefs.catTree.checked = [category.parent_id]
      // this.$root.$emit('setChecked', [category.parent_id])
      grefs.catDescription.content = category.description || ''
      grefs.categoriesManager.$refs.categoryModal.open()
    },
    showDeleteWarning(category) {
      this.markedForDeletion = category
      this.notifier.confirm('Are you sure?', this.deleteCategory)
    },
    deleteCategory() {
      post('/shops/delete-category', {cat: this.markedForDeletion})
      .then(res => this.$root.$emit('newCategories', res.categories))
    },
    showProducts() {
      if (this.category.children.length) {
        this.notifier.warning('You can have products only in leaf categories')
      } else {
        pushPath(`/shops/category/${this.category.id}/products`)
      }
    }
  },

  computed: {
    src(){
      if (this.category.media.length)
        return '/storage/media-lib/' + this.category.media[0].id +
          '/conversions/cropped-thumb.jpg'
      else
        return '@/img/no-image.png'
    }
  },
}
</script>

<style lang="scss" scoped>


.parent-cat {

  justify-content: flex-start;
  width: 100%;
  height: 56px;
  padding: 0 21px;
  background-color: rgba(#868686, .06);
  border-radius: 5px;
  margin-bottom: 5px;

  .icon-container {

    box-shadow: 0 10px 20px 0 rgba(0, 0, 0, 0.2);
    width: 31px;
    height: 31px;
    border-radius: 50%;
    color: white;
    font-size: 16px;
    margin-left: 32px;
    overflow: hidden;
    cursor: pointer;

    > img {
      width: 100%;
      height: 100%;
      object-fit: cover;
    }
  }

  .title {
    a {
      display: block;
      color: #0997b1;
      margin-left: 22px;
      cursor: pointer;
    }
  }

  .small-rounded-btn {

    width: 93px;
    height: 31px;
    font-size: 14px;
    font-weight: 300;
    padding: 0;
  }

  .vue-js-switch {
    margin-bottom: 0;
  }
  .v-switch-core {
    background-color: #0997b1 !important;
  }

  > img {
    cursor: pointer;
    margin-left: 20px;
  }

  .buttons-block {

    justify-content: flex-end;
    flex: 1;
  }
}

.children {
  margin-left: 30px;
}
</style>
