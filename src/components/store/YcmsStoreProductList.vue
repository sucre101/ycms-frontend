<template>
  <div>

    <TopButton
      v-if="productList.length"
      :enable-sort="true"
    />

    <div id="product-list" ref="list">
      <ul v-if="productList.length">
        <li v-for="product in productList">
          <div class="img-thumb">
            <img :src="product.thumb.url_original" alt="">
          </div>
          <div class="info-block">
            <div class="name">
              <a :href="'/app/' + app.slug + '/shop-products/' + product.id + '/edit'">
                {{ product.name }}
              </a>
            </div>
            <div class="category">
              Category
              <a :href="'/app/' + app.slug + '/shop-categories/' + product.category.id + '/edit'">
                {{ product.category.name }}
              </a>
            </div>
          </div>
          <div class="action-group">
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
        </li>
      </ul>
    </div>
  </div>

</template>

<script>
  import YcmsTopButtons from "../YcmsTopButtons";
  export default {
    name: "ycms-store-product-list",

    components: {
      TopButton: YcmsTopButtons
    },

    props: ['app', 'products'],

    data() {
      return {
        productList: [],
        take: 15,
        skip: 0,
        markedForDeletion: null
      }
    },

    mounted() {
      this.getProducts();

      let scrollBlock = this.$refs.list;

      scrollBlock.addEventListener('scroll', e => {
        if (scrollBlock.scrollTop + scrollBlock.clientHeight >= scrollBlock.scrollHeight) {
          this.skip = this.skip + this.take
          this.getProducts();
        }
      })
    },

    methods: {
      getProducts() {
        axios.post('/app/' + this.app.slug + '/shop-products', {take: this.take, skip: this.skip})
          .then((res) => {
            _.forEach(res.data.products, (el) => {
              if (el.thumb == null) {
                el.thumb = { url_original: ''}
              }
              this.productList.push(el)
            })
          })
      },

      goToEdit(id) {
        window.location.href = "/app/" + this.app.slug + "/shop-products/" + id + "/edit"
      },

      duplicateApp(id) {
        axios.post('/app/' + this.app.slug + '/shop-products/duplicate', {id: id})
          .then((res) => {
            if (res.product.thumb == null) {
              res.product.thumb = { url_original: ''}
            }
            this.productList.push(res.product)
            this.notifier.success('product duplicated success')
          })
      },

      showDeleteWarning(id) {
        this.markedForDeletion = id
        this.notifier.confirm('Are you sure?', this.deleteCategory)
      },

      deleteCategory() {
        let index = this.productList.map(x => {
          return x.id;
        }).indexOf(this.markedForDeletion);
        post('/app/' + this.app.slug + '/shop-products/delete', {
          id: this.markedForDeletion,
        })
          .then(res => {
            this.productList.splice(index, 1)
            this.notifier.success('product deleted success')
          })
      },
    }
  }
</script>

<style scoped lang="scss">
  /* width */
  ::-webkit-scrollbar {
    width: 5px;
    border-radius: 50px;
  }

  /* Track */
  ::-webkit-scrollbar-track {
    background: #f1f1f1;
    border-radius: 50px;
  }

  /* Handle */
  ::-webkit-scrollbar-thumb {
    background: #0997b1;
    border-radius: 50px;
  }

  /* Handle on hover */
  ::-webkit-scrollbar-thumb:hover {
    background: #555;
    border-radius: 50px;
  }
  #product-list {
    height: 750px;
    overflow-y: scroll;
    width: 95%;
    border: 1px solid;
    padding: 20px 0;

    ul {
      margin: 0;
      padding: 0;

      li {
        display: flex;
        flex-direction: row;
        justify-content: flex-start;
        align-items: center;
        padding: 15px;

        .img-thumb {
          width: 50px;

          img {
            width: 100%;
          }
        }

        .info-block {
          width: 50%;
          display: flex;
          flex-direction: row;
          justify-content: space-between;

          .name {
            a {
              padding: 0 25px;
            }
          }
        }
      }
    }
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
  }
</style>
