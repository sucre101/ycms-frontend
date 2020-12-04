<template>
  <div class=" full-width">
    <div v-for="product in products" class="node-group">
      {{product.name}}
      <div class="action-group">
        <div v-if="product.union" class="actions">
            <ion-icon name="pricetags-outline" ></ion-icon>
            <span class="tooltiptext">{{product.union.name || "No Title"}}</span>
        </div>
        <div class="actions ">
          <ion-icon @click="goToEdit(product.id)" name="create-outline"></ion-icon>
        </div>
        <div class="actions ">
          <ion-icon @click="duplicateApp(product.id)" name="copy-outline"></ion-icon>
        </div>
        <div class="actions ">
          <ion-icon @click="showDeleteWarning(product.id)" name="trash-outline"></ion-icon>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
  // import { diff, addedDiff, deletedDiff, updatedDiff, detailedDiff } from 'deep-object-diff';
  import {DraggableTree} from 'vue-draggable-nested-tree'

  export default {
    components: {
      Tree: DraggableTree
    },
    data() {
      return {
        products: this.productsList,
        markedForDeletion: null
      }
    },

    computed: {},

    props: {
      productsList: Array,
      app: Object,
    },

    methods: {
      goToEdit(id) {
        window.location.href = "/app/" + this.app.slug + "/shop-products/" + id + "/edit"
      },
      duplicateApp(id) {
        post('/app/' + this.app.slug + '/shop-products/duplicate', {
          id: id,
        })
          .then(res => {
            this.products.push(res.product)
            this.notifier.success('product duplicated success')
          })
      },
      showDeleteWarning(id) {
        this.markedForDeletion = id
        this.notifier.confirm('Are you sure?', this.deleteCategory)
      },
      deleteCategory() {
        let index = this.products.map(x => {
          return x.id;
        }).indexOf(this.markedForDeletion);
        post('/app/' + this.app.slug + '/shop-products/delete', {
          id: this.markedForDeletion,
        })
          .then(res => {
            this.products.splice(index, 1)
            this.notifier.success('product deleted success')
            this.categoryName = ''
          })
      },

    },

    mounted() {
    },
  }
</script>

<style lang="scss" scoped>

  .flex-row-top {
    margin: 42px 0 37px 0;
  }

  .node-group {
    width: 100%;
    display: flex;
    flex-direction: row;
    margin-left: auto;
    min-width: 100px;
    font-size: 21px;
    padding: 5px;
    cursor: pointer;
  }

  .action-group {
    display: flex;
    flex-direction: row;
    margin-left: auto;
    min-width: 100px;
    font-size: 21px;
    padding: 5px;
  }

  .actions {

    color: #0997b1;
    margin-left: 10px;
    width: 30px;
    font-size: 21px;
    vertical-align: bottom;
    position: relative;
    display: inline-block;
  }
  .tooltip {
  }

  .actions .tooltiptext {
    visibility: hidden;
    width: 120px;
    background-color: #555;
    color: #fff;
    text-align: center;
    border-radius: 6px;
    padding: 5px 0;
    position: absolute;
    z-index: 1;
    bottom: 125%;
    left: 50%;
    margin-left: -60px;
    opacity: 0;
    transition: opacity 0.3s;
  }

  .actions .tooltiptext::after {
    content: "";
    position: absolute;
    top: 100%;
    left: 50%;
    margin-left: -5px;
    border-width: 5px;
    border-style: solid;
    border-color: #555 transparent transparent transparent;
  }

  .actions:hover .tooltiptext {
    visibility: visible;
    opacity: 1;
  }
</style>
