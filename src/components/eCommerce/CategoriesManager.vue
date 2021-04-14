<template>
<div>
  <div class="categories-list">
    <shop-category
      v-for="category in categories" :key="category.id"
      :category="category"
    />
  </div>

  <ycms-action-buttons
    :buttons-list="[
      {
        title: 'CREATE',
        handler: 'eval:this.$parent.newCategory()',
        class: 'bg-green-gradient'
      }
    ]"
  />


  <sweet-modal
    class="modal"
    ref="categoryModal"
    width="423"
    overlay-theme="dark"
  >
    <form class="flex-column">
      <h6>Category edit</h6>

      <input
        type="hidden"
        name="id"
        :value="editable ? editable.id : ''"
      >

      <ycms-image-uploader
        name="catImg"
        type="square-preview"
        :ratio="1"
        :crop-in-modal="false"
        full-width
        :preview-img="editableImg"
      />

      <input
        class="rounded-input required mt-20"
        placeholder="Name of category …"
        name="name"
        v-gref:catName
        :value="editable ? editable.name : ''"
      >

      <input type="hidden" name="shopId" :value="shop.id">

      <ycms-tree-drop
        v-gref:catTree
        placeholder="Select parent categories"
        :options="categories"
      />

      <ycms-cke v-gref:catDescription />

      <div class="bottom-buttons">
        <a
          class="small-rounded-btn arrow-left"
          @click="$refs.categoryModal.close()"
        >
          <img src="@/assets/img/dropleft-icon.svg">
          Cancel
        </a>
        <a
          class="small-rounded-btn active arrow-right"
          @click="postCategory"
          :style="{paddingLeft: '10px'}"
        >
          <img src="@/assets/img/dropleft-icon.svg">
          {{ editable ? 'Apply' : 'Create' }}
        </a>
      </div>
    </form>
  </sweet-modal>
</div>
</template>

<script>
import validation from '../../v-alidation'
import dragula from 'dragula'

export default {
  name: 'categories-manager',

  mixins: [validation],

  data() {
    return {
      categories: this.$parent.categories,
      shop: this.$parent.shop,
      markedForDeletion: null,
      awaitingIcon: {},
      editable: false,
    }
  },

  computed: {
  },

  props: {
  },

  methods: {
    newCategory() {
      this.editable = false
      this.$refs.categoryModal.open()
    },
    postCategory() {
      if (this.verify([
        {
          subj: 'grefs.catName.value',
          ref: 'grefs.catName',
          message: 'Please fill a name of category'
        },
        {
          subj: 'grefs.catTree.checked.length',
          ref: 'grefs.catTree',
          message: 'Please select subcategory'
        }
      ])) {
        let fd = new FormData(document.querySelector('form'))
        post('/shops/category', fd)
        .then(res => {
          this.$parent.categories = res.categories
          this.categories = this.$parent.categories
        })
        this.$refs.categoryModal.close()
      }
    },
    empty
  },

  computed: {
    editableImg() {
      if (this.editable && this.editable.media.length)
        return '/storage/media-lib/' + this.editable.media[0].id +
          '/conversions/cropped-thumb.jpg'
      else
        return '/img/no-image.png'
    }
  },

  mounted() {
    grefs.categoriesManager = this

    this.$root.$on('newCategories', cats => this.categories = cats)
  },
}
</script>

<style lang="scss" scoped>


.categories-list {
  display: flex;
  flex-direction: column;
  width: 100%;
  margin: 0 39px 0 0;
}

.rounded-input {
  width: 346px;
  height: 43px;
  border-radius: 22px;
  border: solid 1px #868686;
  outline: none;
  font-size: 14px;
  font-weight: 300;
  color: #0997b1;
  padding: 0 22px;
  margin-bottom: 14px;

  &.error {
    border-color: #b40000;
  }
}

.required {
  background: no-repeat url(~@/assets/img/required-star.svg) 325px 18px;
}

.tip {
  font-size: 14px;
  color: #b40000;
  padding-left: 15px;
}

.modal {
  .bottom-buttons {
    margin-top: 15px;

  }
}
</style>
