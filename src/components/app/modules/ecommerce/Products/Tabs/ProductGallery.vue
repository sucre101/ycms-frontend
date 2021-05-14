<template>
  <div class="product-gallery">
    <draggable
      :list="productImages"
      class="box"
      ghost-class="ghost"
      :move="checkMove"
    >
      <div
        class="product-image"
        v-for="(image, index) in productImages"
        :key="index"
      >
        <img :src="getImageUrl(image.url_original)">
        <span @click="remove(index)">
          <i class="fas fa-times"></i>
        </span>
      </div>
    </draggable>
    <div class="add" @click="addImage">
      <i class="fas fa-plus"></i>
    </div>
  </div>
</template>

<script>
import draggable from "vuedraggable";
import {imageUrl} from "@/helpers/general";

export default {
  name: "product-gallery",

  components: {
    draggable
  },

  props: {
    images: {
      type: Array,
      default: () => {
        return []
      },
      required: true
    }
  },

  data() {
    return {
      productImages: []
    }
  },

  created() {
    this.productImages = this._.cloneDeep(this.images)
    this.$root.$on('set::file', this.putImage)
  },

  destroyed() {
    this.$root.$off('set::file')
  },

  methods: {

    getImageUrl(path) {
      return imageUrl(path)
    },

    remove(index) {

      let $array = this.productImages

      this.productImages = this._.filter($array, (val, idx) => {
        return idx !== index
      })

      this.$emit('remove', this.productImages)
    },


    addImage() {
      this.$root.$emit('fmanager::open', true)
    },

    putImage($object) {
      this.productImages.push({
        entity: 'product',
        entity_id: this.$parent.$parent.product.id,
        order: null,
        image_url: `/${$object.name}`,
        url_original: `/${$object.name}`
      })

      this.$emit('put', this.productImages)
    },

    checkMove($event) {

      this.$nextTick(() => {
        this.$emit('put', $event.relatedContext.list)
      })


    }

  }
}
</script>

<style scoped lang="scss">
.product-gallery {
  display: flex;
  align-items: center;
  .box {
    display: flex;
    flex-direction: row;
    justify-content: flex-start;
    flex-wrap: wrap;
    align-items: center;
    .product-image {
      width: 180px;
      height: 180px;
      cursor: pointer;
      display: flex;
      justify-content: center;
      align-items: center;
      margin-right: 5px;
      position: relative;
      overflow: hidden;
      img {
        width: 95%;
      }
      &:hover {
        border: 1px solid;
      }
      span {
        position: absolute;
        top: 5px;
        right: 5px;
        &:hover {
          color: #0997b1;
        }
      }
    }
  }
  .add {
    width: 35px;
    height: 35px;
    background-image: linear-gradient(250deg, #50b109, #0997b1);
    color: white;
    font-size: 12px;
    display: flex;
    justify-content: center;
    align-items: center;
    border-radius: 50%;
    line-height: 0;
    cursor: pointer;
  }
}
</style>
