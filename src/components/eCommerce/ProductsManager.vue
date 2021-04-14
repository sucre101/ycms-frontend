<template>
<div>
  <div class="products-list">
    <div class="product"
      v-for="prod in products" :key="prod.id"
      :data-id="prod.id"
    >
      <img
        class="handle"
        src="@/assets/img/drag-drop.svg"
      >

      <div class="icon-container" @click="edit(prod)">
        <img :src="src(prod)">
      </div>

      <div class="title" @click="edit(prod)">
        {{ prod.name }}
      </div>

      <div class="buttons-block">
        <div
          class="small-rounded-btn blue-bg"
          @click="edit(prod)"
        >
          Edit
        </div>
      </div>

      <img
        class="ml-auto"
        src="@/assets/img/garbage.png"
        alt="garb"
        @click="showDeleteWarning(prod)"
      >
    </div>
  </div>

  <ycms-action-buttons
    :buttons-list="[
      {
        title: 'CREATE',
        handler: 'eval:this.$parent.newProduct()',
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
      <h6>{{ editable ? 'Edit' : 'Add' }} product</h6>

      <input
        type="hidden"
        name="id"
        :value="editable ? editable.id : ''"
      >

      <ycms-image-uploader
        name="image"
        type="square-preview"
        :ratio="1"
        :crop-in-modal="false"
        full-width
        :preview-img="editableImg"
      />

      <input
        class="rounded-input required mt-20"
        placeholder="Name of product …"
        name="name"
        v-gref:prodName
        :value="editable ? editable.name : ''"
      >

      <input
        class="rounded-input required"
        placeholder="Price …"
        name="price"
        v-gref:prodPrice
        :value="editable ? editable.price / 100 : ''"
      >

      <input
        class="rounded-input required"
        placeholder="Old price …"
        name="old_price"
        v-gref:oldPrice
        :value="editable ? editable.old_price / 100 : ''"
      >

      <!-- <input type="hidden" name="categoryId" :value="shop.id"> -->

      <ycms-tree-drop
        v-gref:catTree
        name="category"
        placeholder="Category"
        :options="categories"
        single-value
        only-leaf
      />

      <ycms-cke v-gref:prodDescription />

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
          @click="postProduct"
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
  mixins: [validation],

  data() {
    return {
      categories: this.$parent.categories,
      shop: this.$parent.shop,
      products: [],
      markedForDeletion: null,
      editable: false,
      catId: location.pathname.match(/category\/(\d+)/)[1],
    }
  },

  computed: {
  },

  props: {
  },

  methods: {
    newProduct() {
      this.editable = false
      grefs.catTree.checked = [this.catId]
      grefs.prodDescription.content = ''
      this.$refs.categoryModal.open()
    },
    prodSrc(prod) {
      if (prod.media.length)
        return '/storage/media-lib/' + this.editable.media[0].id +
          '/conversions/cropped-thumb.jpg'
      else
        return '/img/no-image.png'
    },
    postProduct() {
      if (this.verify([
        {
          subj: 'grefs.prodName.value',
          ref: 'grefs.prodName',
          message: 'Please fill a name of product'
        },
        {
          subj: 'grefs.prodPrice.value',
          ref: 'grefs.prodPrice',
          message: 'Please fill a price'
        },
        {
          subj: 'grefs.oldPrice.value',
          ref: 'grefs.oldPrice',
          message: 'Please fill old price'
        },
        {
          subj: 'grefs.catTree.checked.length',
          ref: 'grefs.catTree',
          message: 'Please select category'
        },
      ])) {
        let fd = new FormData(document.querySelector('form'))
        post('/shops/product', fd).then(res => this.products = res.products)
        this.$refs.categoryModal.close()
      }
    },
    edit(prod) {
      this.editable = prod
      grefs.catTree.checked = [prod.category_id]
      grefs.prodDescription.content = prod.description || ''
      this.$refs.categoryModal.open()
    },
    src(prod) {
      if (prod.media.length)
        return '/storage/media-lib/' + prod.media[0].id +
          '/conversions/cropped-thumb.jpg'
      else
        return '/img/no-image.png'
    },
    saveOrder() {
      let products = this.$el.getElementsByClassName('product')
      let order = Array.from(products).map((el, i) => [el.dataset.id, i])
      post('/shops/save-products-order', {order})
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
    dragula([this.$el.getElementsByClassName('products-list')[0]], {
      moves: (el, src, handle) => handle.classList.contains('handle'),
    })
    .on('drop', (el, target, source, sibling) => {
      this.saveOrder()
    })

    get(`/api/category/${this.catId}/products`).then(res => this.products = res)
  },
}
</script>

<style lang="scss" scoped>


.products-list {
  display: flex;
  flex-direction: column;
  width: 100%;
  margin: 0 39px 0 0;
}

.product {

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
    display: block;
    color: #0997b1;
    margin-left: 22px;
    cursor: pointer;
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
  background: no-repeat url(/img/required-star.svg) 325px 18px;
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
