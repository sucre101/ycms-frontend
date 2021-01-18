<template>
  <div class="edit-product-form" v-if="product">

    <InnerTab :items="tabs"
      @change="selectTab"
    />

    <div class="product-tabs">
      <div class="tab-general" v-if="selectedTab === 0">

        <div v-if="itsUnion" class="union-group-name">
          Group: {{ product.union.name }}
        </div>
        <div v-else class="union-group-name">
          Make union
        </div>

        <div class="input-group">
          <label>
            <input type="text" class="input-field" v-model.trim="product.name" placeholder="Title">
          </label>
        </div>

        <div class="input-group">
          <label>
            <input type="text" class="input-field" v-model.trim="product.sku" placeholder="Sku">
          </label>
        </div>

        <div class="input-group">
          <label>
            <textarea type="text" class="text-field" v-model.trim="product.desc" placeholder="Description" />
          </label>
        </div>

      </div>

      <div class="tab-data" v-if="selectedTab === 1">

        <div class="product-to-store-table">

          <div class="thead-table">
            <div>
              Store name
            </div>
            <div>
              Price
            </div>
            <div>
              Old price
            </div>
            <div>
              Discount
            </div>
            <div>
              Quantity
            </div>
          </div>

          <div class="item__" v-for="store in product.to_product">
            <div class="store-name">
              {{ store.name }}
            </div>

            <div class="product-store-data">
              <div class="input-group">
                <label>
                  <input type="text" class="input-field" v-model.trim="store.pivot.price" placeholder="Price">
                </label>
              </div>
              <div class="input-group">
                <label>
                  <input type="text" class="input-field" v-model.trim="store.pivot.old_price" placeholder="Old price">
                </label>
              </div>
              <div class="input-group">
                <label>
                  <input type="text" class="input-field" v-model.trim="store.pivot.discount" placeholder="Discount">
                </label>
              </div>
              <div class="input-group">
                <label>
                  <input type="text" class="input-field" v-model.trim="store.pivot.quantity" placeholder="Quantity">
                </label>
              </div>
            </div>
          </div>
        </div>

        <div>
          <ycms-action-buttons
              :buttons-list="[
                {
                  title: 'Add price to store',
                  handler: 'eval:this.$parent.$refs.addPrice.open()',
                  class: 'bg-green-gradient'
                },
              ]"
              align="right"
          />
        </div>

        <sweet-modal
            class="modal"
            ref="addPrice"
            width="600"
            overlay-theme="dark"
        >
          <div class="select-box">

            <h4>Choose store</h4>

            <ul>
              <li v-for="store in stores" @click="tapStorePrice(store)">
                {{ store.name }}
              </li>
            </ul>

          </div>
        </sweet-modal>

      </div>

      <div class="tab-specs" v-if="selectedTab === 2">

        <div class="spec-group-list-table">

          <div v-for="(specGroup, sgIndex) in product.category.spec_groups" class="spec-group">
            {{ specGroup.name }}

            <div class="spec" v-for="(spec, sIndex) in specGroup.specs" @mouseenter="specId = spec.id" @mouseleave="specInputId = 0">

              <div class="spec-name">{{ spec.name }}</div>

              <div class="spec-data">
                <template v-for="(specData, spIndex) in product.specs" class="spec-data" v-if="specData.spec_id === spec.id">

                <span v-for="(sdata, sdIndex) in specData.data" v-if="sdata.id === spec.spec_id">
                  {{ sdata }}
                  <span class="rm" @click="deleteSpecData(spIndex, sdIndex)">x</span>
                </span>

                </template>
                <div class="add-spec" @click="showSpecInput(sgIndex, sIndex)" v-if="specId === spec.id">
                  +
                </div>
                <input
                    type="text"
                    v-if="specInput.sp === sIndex && specInput.gr === sgIndex && specInput.active"
                    v-model="specInputData"
                    @keyup.enter="addSpecData(spec)"
                    ref="specInput"
                />
              </div>



            </div>

          </div>

        </div>

      </div>

      <div class="tab-labels" v-if="selectedTab === 3">

        <ul>
          <li v-for="(label, index) in product.label_to_stores">
            {{ getStoreName(label.store_id) }} - {{ label.label.title }}
            <span @click="removeLabel(index)">
              X
            </span>
          </li>
        </ul>

        <button class="add-label-to-store" @click.prevent="addLabel">
          +
        </button>

        <sweet-modal
            class="modal"
            ref="addLabel"
            width="600"
            overlay-theme="dark"
        >

          <div class="select-box">

            <h4>{{ !selectLabel ? 'Choose label' : 'Choose store' }}</h4>

            <ul v-if="!selectLabel">
              <li
                  v-for="label in labels"
                  @click="tapLabel(label)"
              >
                {{ label.title }}
              </li>
            </ul>

            <ul v-else>
              <li v-for="store in stores" @click="tapStoreLabel(store)">
                {{ store.name }}
              </li>
            </ul>

          </div>

        </sweet-modal>

      </div>

      <div class="tab-images" v-if="selectedTab === 4">

        <YcmsProductImageUploader
            :url="`${$route.params.folder.toLowerCase()}/${$parent.$parent.moduleId}/product/${productId}/save-image`"
            :p_images="product.images"
            :entity_id="product.id"
            name="product"
        />

      </div>

      <div class="tab-unions" v-if="selectedTab === 5">

        <div v-if="itsUnion" class="unions-list">
          <div v-for="union in product.union.products">
            <a href="#" :class="{ active: productId === union.id }" @click.prevent="changeProductUnion(union.id)">
              {{ union.name }}
            </a>
          </div>
        </div>

        <div class="union-description">
          <label>
            <textarea v-model="product.union.desc" placeholder="Union description"></textarea>
          </label>
        </div>



      </div>



    </div>

    <div class="save-button" @click="saveAllData" v-if="activeForSave">
      <img src="/img/ycms/exit_icon.svg" title="save product">
    </div>

  </div>
</template>

<script>
import InnerTab from "@/components/base/ui/InnerTab";
import YcmsProductImageUploader from "@/components/YcmsProductImageUploader";
import YcmsActionButtons from "@/components/YcmsActionButtons";

export default {
  name: "product",

  components: {
    InnerTab, YcmsProductImageUploader, YcmsActionButtons
  },

  data() {
    return {
      productId: null,
      product: {},
      labels: [],
      stores: [],
      selectedTab: 0,
      selectLabel: false,
      newLabel: {},
      activeForSave: false,
      priceTemplate: {
        pivot: {
          price: 0,
          old_price: 0,
          quantity: 0,
          discount: 0,
        }
      },
      specId: 0,
      specInputData: '',
      specInput: {
        active: false,
        gr: 0,
        sp: 0,
      },
      itsUnion: false,
      tabs: [
        { name: 'General' },
        { name: 'Data' },
        { name: 'Specs' },
        { name: 'Labels' },
        { name: 'Images' },
      ]
    }
  },

  created() {
    this.productId = Number(this.$route.query.product)

    this.loadData()
  },

  mounted() {
    window.setTitle('Product edit')
  },

  watch: {

    $route(to, from) {

      if (!to.query.hasOwnProperty(this.$options.name)) {
        this.$parent.editProduct = false
      }
    },

    product: {
      handler(val) {
        this.activeForSave = true
      },
      deep: true
    }

  },

  methods: {

    loadData() {

      axios.get(`/${this.$route.params.folder.toLowerCase()}/${this.$parent.$parent.moduleId}/product/${this.productId}`)
        .then((res) => {
          if (res.data.success) {

            this.product = this._.cloneDeep(res.data.product)
            this.labels = this._.cloneDeep(res.data.labels)
            this.stores = this._.cloneDeep(res.data.stores)

            if (this.product.union !== null) {
              if (this.itsUnion === false) {
                this.itsUnion = true
                this.tabs.push({ name: 'Unions' })
              }
            }

          } else {
            this.notifier.warning(res.data.error)
            this.$router.replace({ query: { tab: 'products' } })
            this.$parent.editProduct = false
          }
        }).then( res => this.activeForSave = false)
    },

    getStoreName(id) {
      return this.stores[this._.findIndex(this.stores, { id: id })].name
    },

    addLabel() {

      this.$refs.addLabel.open();

    },

    removeLabel(index) {

      this.product.label_to_stores = this.product.label_to_stores.filter( (k, v) => {
        return v !== index
      })
    },

    tapLabel(label) {

      this.newLabel = {
        product_id: this.productId,
        label_list_id: label.id,
        name: label.name,
        label: { id: label.id, title: label.title }
      }

      this.selectLabel = true
    },

    tapStoreLabel(store) {
      this.newLabel.store_id = store.id
      let index = this._.findIndex( this.product.label_to_stores,
          {
            product_id: this.newLabel.product_id,
            store_id: store.id,
            label_list_id: this.newLabel.label_list_id
          })

      if (index !== -1) {
        this.notifier.warning('Error')
        return false
      }

      this.product.label_to_stores.push(this.newLabel)
      this.$refs.addLabel.close();
      this.selectLabel = false
    },

    tapStorePrice(store) {

      let index = this._.findIndex(this.product.to_product, { id: store.id });

      if (index !== -1) {
        this.notifier.warning('This store will be added')
        return false
      }

      this.priceTemplate.name = store.name
      this.priceTemplate.id = store.id

      this.product.to_product.push(this.priceTemplate)

      this.$refs.addPrice.close();
    },

    selectTab(index) {
      this.selectedTab = index
    },

    deleteSpecData(spIndex, sdIndex) {
      this.product.specs[spIndex].data.splice(sdIndex, 1);
    },

    addSpecData(spec) {

      let index = this._.findIndex(this.product.specs, { spec_id: spec.id })
      let newSpec = {}

      if (index !== -1) {

        this.product.specs[index].data.push(this.specInputData)

      } else {

        newSpec.spec_id = spec.id
        newSpec.data = [this.specInputData]
        newSpec.product_id = this.productId

        this.$nextTick(() => {
          this.product.specs.push(newSpec)
        })

      }

      this.specInput.gr = 0
      this.specInput.sp = 0
      this.specInput.active = false
      this.specInputData = ''
    },

    showSpecInput(gsIndex, sIndex) {

      this.specInput.gr = gsIndex
      this.specInput.sp = sIndex
      this.specInput.active = true


      this.$nextTick(() => {
        this.$refs.specInput[0].focus()
      })
    },

    changeProductUnion(id) {

      this.$router.replace( { query: { tab: 'products', product: id }} )
      this.productId = id
      this.loadData();

    },

    saveAllData() {

      axios.post(`/${this.$route.params.folder.toLowerCase()}/${this.$parent.$parent.moduleId}/product/${this.productId}/update-product`, this.product)
          .then((res) => {
            this.notifier.success('Save data')
            this.$nextTick(() => {
              this.activeForSave = false
            })
          })
          .catch((err) => {
            console.log(err)
          })

    }

  }

}
</script>

<style scoped lang="scss">
.edit-product-form {
  width: 70%;
  background-color: white;
  padding: 15px 50px;
  position: relative;
  .product-tabs {
    margin-top: 50px;

    .input-group {
      margin: 15px 0;
      .input-field {
        padding: 12px 16px 12px 22px;
        border-radius: 22px;
        border: solid 1px #868686 !important;
      }
      .text-field {
        padding: 12px 16px 12px 22px;
        border-radius: 22px;
        border: solid 1px #868686 !important;
        resize: none;
        width: 50%;
      }
      label {
        width: 100%;
        margin: 0;
      }
    }
    .product-to-store-table {
      display: flex;
      flex-direction: column;
      .thead-table {
        display: flex;
        justify-content: space-around;
        padding-bottom: 20px;
        border-bottom: 1px solid #868686;
      }
      .item__ {
        width: 100%;
        display: flex;
        align-items: center;
        .product-store-data {
          display: flex;
          align-content: center;
          justify-content: center;
          .input-group {
            min-width: 25%;
            text-align: center;
            input {
              width: 70%;
            }
          }

        }
        .store-name {
          width: 30%;
          padding-left: 15px;
          text-align: center;
        }
      }
    }
  }
  .save-button {
    position: absolute;
    right: 25px;
    top: 25px;
    cursor: pointer;
  }
  .select-box {
    ul {
      margin: 0;
      padding: 0;
      display: flex;
      flex-wrap: wrap;
      li {
        display: flex;
        border: 1px solid;
        padding: 10px 15px;
        border-radius: 22px;
        cursor: pointer;
        margin-right: 10px;
        background-image: linear-gradient(252deg, #0997b1, #2ccae6);
        color: #ffffff;
      }
    }
  }
  .save-button.active{
    background-color: red;
  }
  .tab-general {
    .union-group-name {
      display: inline-block;
      background: blueviolet;
      color: white;
      font-size: 14px;
      padding: 2px 15px;
      border-radius: 22px;
      cursor: pointer;
    }
  }
  .tab-labels {
    ul {
      display: flex;
      flex-direction: row;
      flex-wrap: wrap;
      padding: 0;
      li {
        display: flex;
        padding: 10px 15px;
        border-radius: 22px;
        margin-right: 10px;
        cursor: pointer;
        padding-right: 40px;
        position: relative;
        background-image: linear-gradient(252deg, #0997b1, #2ccae6);
        color: #ffffff;
        span {
          position: absolute;
          right: 15px;
        }
      }
    }
  }
  .tab-unions {
    display: flex;
    flex-direction: row;
    justify-content: space-between;
    .unions-list {
      display: flex;
      flex-direction: column;
      width: 40%;
    }
    .union-description {
      display: flex;
      width: 55%;
      textarea {
        resize: none;
        width: 90%;
        padding: 10px 15px;
        min-height: 200px;
      }
    }
    a.active {
      font-weight: 600;
    }
  }
  .add-label-to-store {
    background: #00C121;
    width: 60px;
    height: 60px;
    display: flex;
    justify-content: center;
    align-items: center;
    border-radius: 50%;
    cursor: pointer;
    font-size: 25px;
    color: white;
    border: none;
  }
  .spec-group-list-table {
    width: 100%;
    display: flex;
    justify-content: space-between;
    flex-direction: row;
    align-items: baseline;
    .spec-group {
      width: 48%;
      border: 1px solid;
      display: flex;
      flex-direction: column;
      align-items: center;
      .spec {
        display: flex;
        flex-direction: row;
        justify-content: space-between;
        width: 100%;
        border-top: 1px solid;
        align-items: center;
        padding-left: 5px;
        div:last-child {
          border-left: 1px solid;
        }
        .spec-data {
          display: flex;
          width: 50%;
          padding: 5px;
          flex-direction: row;
          justify-content: flex-start;
          align-items: center;
          flex-wrap: wrap;
          span {
            background: #e4e5e4;
            padding: 5px 20px;
            position: relative;
            margin-right: 5px;
            margin-bottom: 5px;
          }
          span.rm {
            padding: 0;
            position: absolute;
            top: -7px;
            right: 2px;
            background: none;
            cursor: pointer;
          }
          .add-spec {
            border: none;
            background-color: #e5e5;
            display: flex;
            align-items: center;
            padding: 5px;
            border-radius: 50%;
            width: 30px;
            text-align: center;
            align-content: center;
            justify-content: center;
            height: 30px;
            cursor: pointer;
          }
          input {
            padding: 2px 5px 2px 10px;
            border-radius: 22px;
            border: solid 1px #868686 !important;
          }
        }
      }
    }
  }
}
</style>
