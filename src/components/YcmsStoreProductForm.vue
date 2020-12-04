<template>
  <div>
    <a class="small-rounded-btn blue-bg text-white" :href="'/app/'+this.app.slug+'/shop-categories/'+category.id+'/edit'">Go Back</a>
    <a v-if="product.id && !product.union" class="small-rounded-btn blue-bg text-white" @click="makeUnion" >Make Union</a>
    <a v-if="product.union" class="small-rounded-btn blue-bg text-white" @click="deleteUnion" >Delete Union</a>
    <div class="head-elements">
      <div v-if="product.union"  class="union-images" >
        Union's images
        <ycms-product-image-uploader
          :entity_id="product.union.id"
          :slug="this.app.slug"
          :url="'/app/' + this.app.slug + '/shop-product-union/' + product.union.id + '/save-image'"
          name="union"
          :p_images="product.union.images"
          v-on:upload-image-clicked="imageClicked"
          v-on:update-images="updateImages"
          v-on:upload-image-success="imageUploaded"
          v-on:upload-image-removed="uploadImageDelete"
        ></ycms-product-image-uploader>
      </div>

      <div class="product-images" >
        Product's images
        <ycms-product-image-uploader
          :entity_id="product.id"
          :slug="this.app.slug"
          :url="product.id?'/app/' + this.app.slug + '/shop-products/' + product.id + '/save-image':null"
          name="product"
          :p_images="product.images"
          v-on:update-images="updateImages"
          v-on:upload-image-clicked="imageClicked"
          v-on:upload-image-success="imageUploaded"
          v-on:upload-image-loaded="uploadImageLoaded"
          v-on:upload-image-removed="uploadImageDelete"
        ></ycms-product-image-uploader>
      </div>

      <div class="head-inputs">
        <div v-if="product.union"  >
          <input
            v-model="product.union.name"
            class="rounded-input"
            type="text"
            placeholder="Name of union"
          /><br>
          Union products
          <div class="union" v-for="pr in product.union.products">
            <div class="radiobtn" >
              <input type="radio" :id="'id-'+pr.id"
                     name="drone" :value="pr.id" :checked="pr.id === product.id" @change="changeProduct(pr.id)" />
              <label :for="'id-'+pr.id">{{pr.name}}</label>
            </div>
            <a v-if="pr.id === product.id" class="small-rounded-btn blue-bg text-white" style="margin-left:10px" @click="duplicateApp(product.id)" >Duplicate</a>
            <ion-icon v-else @click="removeFromUnion(pr.id)"  style="margin-left:10px" name="trash-outline"></ion-icon>
          </div>

          <div class="union" >
            <div class="radiobtn" >
              <a class="union_button" @click="addProductToUnionModal">Add product</a>
            </div>
          </div>
        </div>
        <input
          v-model="product.name"
          class="rounded-input"
          type="text"
          placeholder="Name of product"
        />
        <br>
        <select class="rounded-input" :disabled="product.union" v-model="product.category_id" @change="changeSpecGroups">
          <option
            v-for="cat in this.categoriesList"
            :value="cat.id"
          >
            {{cat.name}}
          </option>
        </select>
        <br>
        <input
          v-model="product.sku"
          class="rounded-input"
          type="text"
          placeholder="SKU of product"
        /><br>
      </div>
    </div>

    <div class="flex-row-top" >
      <Tree :data="specGroups" :indent="60" ref="groupTree" :draggable="true"  @drag="ondrag" @change="changeGroupTree" >
        <div slot-scope="{data, store, vm}">
          <template v-if="!data.isDragPlaceHolder">
            <div class="node-group">
              <span v-if="vm.level === 1">
                <b v-if="data.children.length" @click="store.toggleOpen(data)" class="icons-group">
                  <ion-icon v-if="data.open" name="chevron-down-outline"></ion-icon>
                  <ion-icon v-else name="chevron-forward-outline"></ion-icon>
                </b>
                <a :ref="'group_name_'+data.id">
                  {{ data.name }}
                  <ion-icon name="create-outline" @click="showGroupName(data.id)" ></ion-icon>
                </a>
                <span :ref="'group_input_'+data.id" hidden>
                    <input type="text" v-model="data.name">
                    <ion-icon name="checkmark-outline" @click="saveGroupName(data.id, data.name)"></ion-icon>
                </span>
                <ion-icon @click="showModalSpec(data)" name="add-outline"></ion-icon>
                <ion-icon @click="showDeleteGroupWarning(data.id)" name="trash-outline"></ion-icon>
              </span>
              <span  v-else class="specs">
                <span class="spec_name">
                  <a  :ref="'spec_name_'+data.id">
                    {{ data.name }}
                    <ion-icon name="create-outline" @click="showSpecName(data.id)"   ></ion-icon>
                  </a>
                  <span  :ref="'spec_input_'+data.id" hidden>
                      <input type="text" v-model="data.name">
                      <ion-icon name="checkmark-outline" @click="saveSpecName(data.id, data.name)"></ion-icon>
                  </span>
                </span>
                            <input-tag
                              v-model="data.data"
                              class="rounded-input-tag"
                              type="text"
                              :add-tag-on-blur="true"
                              :placeholder="'value of '+data.name "
                            />
                            <select class="rounded-select" v-model="data.unit_id">
                              <option v-for="unit in units" :value="unit.id">{{unit.name}}</option>
                            </select>
                            <ion-icon @click="showDeleteSpecWarning(data.id, data.group_id)" name="trash-outline"></ion-icon>
              </span>
            </div>
          </template>
        </div>
      </Tree>
      <a class="small-rounded-btn blue-bg text-white" @click="showModalSpecGroup">Add Group</a>
      <br>
      <textarea placeholder="Description of union" v-if="product.union" v-model="product.union.desc" rows="5" cols="70"></textarea>
      <textarea placeholder="Description of product" v-model="product.desc" rows="5" cols="70"></textarea>

      <div v-if="labels.length" class="label-block">

        <h5>Labels</h5>

        <div class="fix-block">
          <div v-for="label in labels">
            <div>
              {{ label.title }}
            </div>
            <div v-for="item in label.stores">
              <input
                type="checkbox"
                :id="'checkbox_' + item.id + '_' + label.id"
                ref="labelCheckBoxes"
                v-model="item.checked"
              >
              <label :for="'checkbox_' + item.id + '_' + label.id">{{ item.name }}</label>
            </div>
          </div>
        </div>

      </div>

      <div style="border: 2px solid red; margin: 20px 0;">

        <h5>Product price in stores</h5>

        <table :class="{ 'table-scroll': product.prices?product.prices.length > 10:false }">
          <thead>
          <tr>
            <td>Store</td>
            <td>Price</td>
            <td>Price Old</td>
            <td>Discount</td>
            <td>Quantity</td>
          </tr>
          </thead>
          <tbody>
          <tr v-for="item in product.prices">
            <td width="150">
              <p style="display: initial; vertical-align: middle ">
                {{ item.name }}
              </p>
            </td>
            <td>
              <input
                v-model="item.pivot.price"
                type="text"
              >
            </td>
            <td>
              <input
                v-model="item.pivot.old_price"
                type="text"
              >
            </td>
            <td>
              <input
                v-model="item.pivot.discount"
                type="text"
              >
            </td>
            <td>
              <input
                v-model="item.pivot.quantity"
                type="text"
              >
            </td>
          </tr>
          </tbody>
        </table>

      </div>

      <span v-if="product.id" style="float: left">
      Created: {{product.created_at}}
      Updated: {{product.last_updated}}
    </span>
      <br>
      <a class="small-rounded-btn blue-bg text-white" @click="saveProduct" style="float: left">SAVE</a>
      <sweet-modal
        class="modal"
        ref="newSpecGroupModal"
        width="550"
        overlay-theme="dark"
      >
        <h4>Add Specification Group</h4>

        <input
          v-model="newSpecGroupName"
          class="rounded-input"
          width="100%"
          type="text"
          placeholder="Name of Specification Group"
        />
        <a class="small-rounded-btn blue-bg text-white" @click="createSpecGroup">Create</a>
      </sweet-modal>
      <sweet-modal
        class="modal"
        ref="newSpecModal"
        width="550"
        overlay-theme="dark"
      >
        <h4>Add Specification</h4>

        <input
          v-model="newSpecName"
          class="rounded-input"
          width="100%"
          type="text"
          placeholder="Name of Specification"
        />
        <select class="rounded-input" v-model="newSpecUnit">
          <option v-for="unit in units" :value="unit.id">{{unit.name}}</option>
        </select>
        <a class="small-rounded-btn blue-bg text-white" @click="createSpec">Create</a>
      </sweet-modal>

      <sweet-modal
        class="modal"
        ref="productToUnion"
        width="550"
        overlay-theme="dark"
      >
        <h4>Add Product to Union</h4>

        <select class="rounded-input" v-model="productToAdd" @change="changeSpecGroups">
          <option
            v-for="pro in productsToAdd"
            :value="pro.id"
          >
            {{pro.name}}
          </option>
        </select>
        <a class="small-rounded-btn blue-bg text-white" @click="addProductToUnion">Add to union</a>
      </sweet-modal>
      <sweet-modal
        class="modal"
        ref="imagePreview"
        width="550"
        overlay-theme="dark"
      >
        <img :src="imgPreview" width="100%">
      </sweet-modal>
    </div>
  </div>
</template>

<script>
  import {DraggableTree} from 'vue-draggable-nested-tree'
  import * as th from 'tree-helper'

  export default {
    components: {
      Tree: DraggableTree
    },
    name: "ycms-store-product-form",

    props: {
      app: Object,
      category: Object,
      categoriesList: Array,
      unitsList: Array,
      product_: Object,
      labels: Array
    },

    data() {
      return {
        imgPreview: null,
        productToAdd: null,
        productsToAdd: null,
        product: this.product_,
        categories: this.categoriesList,
        units: this.unitsList,
        category_: this.category,
        specGroups: this.categoriesList.filter(p => p.id === this.category.id)[0].spec_groups,
        newSpecGroupName: '',
        newSpecName: '',
        newSpecUnit: '',
        groupToAdd: null,
        markedForDeletion: null,
        markedForDeletion_s: null,
        markedForDeletion_union: null,
        files: []
      };
    },

    created() {
      this.createProductLabelsBlock();
      this.createProductPricesBlock();
    },

    methods: {
      updateImages() {
        post('/app/'+ this.app.slug +'/shop-products/get-images', {
          id: this.product.id,
        })
        .then(res => {
          console.log(res.product_images)
          res.product_images.map(x => {
            delete x.children;
          })
          res.union_images.map(x => {
            delete x.children;
          })
          console.log(res.product_images)
          this.product.images = res.product_images;
          this.product.union.images = res.union_images
        })
      },
      imageClicked(res) {
        this.imgPreview = res
        this.$refs.imagePreview.open()
      },
      imageUploaded() {
        this.notifier.success('Image uploaded successfully')
      },
      addProductToUnionModal() {
        this.$refs.productToUnion.open()
        post('/app/'+ this.app.slug +'/shop-products/get-products-to-union', {
          category_id: this.product.category_id,
          union_id: this.product.union.id,
        })
        .then(res => {
          this.productsToAdd = res
        })
      },
      addProductToUnion() {
        post('/app/'+ this.app.slug +'/shop-product-union/add-product-to-union', {
          product_id: this.productToAdd,
          union_id: this.product.union.id,
        })
        .then(res => {
          this.product.union = _.cloneDeep(res.union)
          this.changeProduct(this.productToAdd)
          this.notifier.success('Product added from to successfully')
          this.$refs.productToUnion.close()
        })
      },
      removeFromUnion(id) {
        this.markedForDeletion_union = id;
        this.notifier.confirm('Are you sure?', this.removeProduct)
      },
      removeProduct() {
        let index = this.product.union.products.map(x => {
          return x.id;
        }).indexOf(this.markedForDeletion_union)

        post('/app/'+ this.app.slug +'/shop-products/remove-from-union', {
          id: this.markedForDeletion_union,
        })
        .then(res => {
          this.product.union.products.splice(index, 1)
          this.notifier.success('product removed from union successfully')
        })
      },
      duplicateApp(id) {
        post('/app/'+ this.app.slug +'/shop-products/duplicate', {
          id: id,
        })
        .then(res => {
          this.notifier.success('product duplicated successfully')
          this.changeProduct(res.product.id)
        })
      },
      changeProduct(id){
        axios.post('/app/' + this.app.slug + '/shop-products/change-product', {
          'id': id,
        })
        .then((res) => {
          this.product = _.cloneDeep(res.data.product);
          this.categories = _.cloneDeep(res.data.categories);
          this.specGroups = this.categories.filter(p => p.id === this.product.category_id)[0].spec_groups

          this.createProductLabelsBlock();
          this.createProductPricesBlock();
          this.notifier.success('Product changed successfully')
        });
      },
      makeUnion(){
        axios.post('/app/' + this.app.slug + '/shop-product-union/new-union', {
          'id': this.product.id,
        })
        .then((res) => {
          console.log(res.data.union)
          this.product.union = _.cloneDeep(res.data.union)
          this.notifier.success('Union created successfully')
        });
      },
      deleteUnion(){
        axios.post('/app/' + this.app.slug + '/shop-product-union/delete', {
          'id': this.product.union.id,
        })
        .then((res) => {
          console.log(res.data.union)
          this.product.union = _.cloneDeep(res.data.union)
          this.notifier.success('Union deleted successfully')
        });
      },
      showGroupName(id){
        this.$refs["group_input_"+id].hidden = false;
        this.$refs["group_name_"+id].hidden = true;
      },
      showSpecName(id){
        this.$refs["spec_input_"+id].hidden = false;
        this.$refs["spec_name_"+id].hidden = true;
      },
      ondrag(node) {

        this.$refs.groupTree.rootData.droppable = node._vm.level === 1;

        th.depthFirstSearch(this.specGroups, (childNode) => {
          if(node._vm.level === 1){
            childNode.droppable = false;
          }else if(node._vm.level === 2 && childNode._vm.level === 1){
            childNode.droppable = true;
          }
        })
      },
      changeGroupTree(node){
        if(node._vm.level === 1){

          let index_g = this.specGroups.map(x => {
            return x.id;
          }).indexOf(node.id)

          axios.post('/app/' + this.app.slug + '/shop-specs-group/change-position', {
            'id': node.id,
            'order': index_g+1,
          })
          .then((res) => {
            this.notifier.success('Group changed position success')
          });
        }else if(node._vm.level === 2){

          let index_g = this.specGroups.map(x => {
            return x.id;
          }).indexOf(node.parent.id)

          let index = this.specGroups[index_g].children.map(x => {
            return x.id;
          }).indexOf(node.id);

          axios.post('/app/' + this.app.slug + '/shop-specs/change-position', {
            'id': node.id,
            'group_id': node.parent.id,
            'order': index+1,
          })
          .then((res) => {
            this.notifier.success('Group changed position success')
          });
        }
      },
      uploadImageDelete(id) {
        axios.post('/app/' + this.app.slug + '/shop-products/delete-image', {
          id: id,
        })
        .then(res => {
              this.notifier.success('Image deleted successfully')
            }
        )
      },
      uploadImageLoaded(result) {
        this.files = result
      },

      changeSpecGroups() {
        this.specGroups = this.categories.filter(p => p.id === this.product.category_id)[0].spec_groups
      },

      createProductPricesBlock(){
        Vue.set(this.product, 'prices', [])

        _.forEach(this.app.stores, (item) => {

          let index = _.findIndex(this.product.to_product, {id: item.id})

          if (index !== -1) {
            let obj = this.product.to_product[index]

            this.product.prices.push({
              name: obj.name,
              pivot: {
                store_id: item.id,
                product_id: this.product.id,
                price: obj.pivot.price,
                old_price: obj.pivot.old_price,
                discount: obj.pivot.discount,
                quantity: obj.pivot.quantity
              }
            })
          } else {
            this.product.prices.push({
              name: item.name,
              pivot: {
                store_id: item.id,
                product_id: this.product.id,
                price: null,
                old_price: null,
                discount: null,
                quantity: null
              }
            })
          }
        })
      },

      createProductLabelsBlock() {
        _.forEach(this.labels, (val) => {
          if(val.stores){
            _.forEach(this.$refs.labelCheckBoxes, (item) => {
              item.checked = false
            })
          }
          Vue.set(val, 'stores', [])
          _.forEach(this.app.stores, (item) => {
            let checked = _.findIndex(this.product.label_to_stores,
                {
                  product_id: this.product.id,
                  store_id: item.id,
                  label_list_id: val.id
                }
            )
            val.stores.push({name: item.name, id: item.id, checked: checked !== -1})
          })

        })
      },

      saveProduct() {
        if(!this.product.name){
          this.notifier.alert('You missed product name')
          return false;
        }

        let imageData = new FormData();

        this.files.forEach( (res) => {
          imageData.append("image[]", res.file)
        })
        let prod = this.product;
        if (prod.id){
          delete prod.images;

          if(prod.union)
          delete prod.union.images;
        }

        const config = {
          headers: {'content-type': 'multipart/form-data'}
        };
        axios.post('/app/' + this.app.slug + '/shop-products/update-product', {
          'product': prod,
          'groups': this.$refs.groupTree.getPureData(),
          'labels': this.labels,
        })
        .then((res) => {
          if (res.data) {
            if (this.files.length){
              axios.post('/app/' + this.app.slug + '/shop-products/' + res.data.product.id + '/save-image', imageData, config).then(
                  (res) => {
                    window.location.href = '/app/'+this.app.slug+'/shop-products/'+res.data.id+'/edit'
                  }
              )
            }else{
              if(!this.product.id){
                window.location.href = '/app/'+this.app.slug+'/shop-products/'+res.data.product.id+'/edit'
              }
            }
          }
          this.notifier.success('product saved success')
        });
      },

      showModalSpecGroup() {
        this.$refs.newSpecGroupModal.open()
      },

      showDeleteSpecWarning(id, group_id) {
        this.markedForDeletion = group_id;
        this.markedForDeletion_s = id;
        this.notifier.confirm('Are you sure?', this.deleteSpec)
      },

      showDeleteGroupWarning(id) {
        this.markedForDeletion = id;
        this.notifier.confirm('Are you sure?', this.deleteGroup)
      },

      deleteSpec() {
        let index_g = this.specGroups.map(x => {
          return x.id;
        }).indexOf(this.markedForDeletion)

        let index = this.specGroups[index_g].children.map(x => {
          return x.id;
        }).indexOf(this.markedForDeletion_s);

        axios.post('/app/' + this.app.slug + '/shop-specs/delete', {
          id: this.markedForDeletion_s,
        })
        .then(res => {
              this.specGroups[index_g].children.splice(index, 1)
              this.notifier.success('Specification group deleted successfully')
            }
        )
      },
      deleteGroup() {
        let index = this.specGroups.map(x => {
          return x.id;
        }).indexOf(this.markedForDeletion);
        axios.post('/app/' + this.app.slug + '/shop-specs-group/delete', {
          id: this.markedForDeletion,
        })
        .then(res => {
              this.specGroups.splice(index, 1)
              this.notifier.success('Specification group deleted successfully')
            }
        )
      },
      createSpecGroup() {
        post('/app/' + this.app.slug + '/shop-specs-group/new-spec-group', {
          name: this.newSpecGroupName,
          category_id: this.product.category_id,
        })
        .then(res => {
              this.specGroups.push(res.group)
              this.$refs.newSpecGroupModal.close()
              this.notifier.success('Specification group added success')
            }
        )
      },
      showModalSpec(spec) {
        this.$refs.newSpecModal.open()
        this.groupToAdd = spec
      },
      createSpec() {
        post('/app/' + this.app.slug + '/shop-specs/new-spec', {
          name: this.newSpecName,
          group_id: this.groupToAdd.id,
          unit_id: this.newSpecUnit,
        })
        .then(res => {
              this.specGroups[this.groupToAdd.order - 1].children.push(res.spec)
              this.$refs.newSpecModal.close()
              this.notifier.success('Specification added success')
            }
        )
      },
      saveGroupName(id, name) {
        axios.post('/app/' + this.app.slug + '/shop-specs-group/save-name', {
          id: id,
          name: name,
        })
        .then(res => {
              this.$refs["group_input_"+id].hidden = true;
              this.$refs["group_name_"+id].hidden = false;
              this.notifier.success('Specification group name saved successfully')
            }
        )
      },
      saveSpecName(id, name) {
        axios.post('/app/' + this.app.slug + '/shop-specs/save-name', {
          id: id,
          name: name,
        })
        .then(res => {
              this.$refs["spec_input_"+id].hidden = true;
              this.$refs["spec_name_"+id].hidden = false;
              this.notifier.success('Specification name saved successfully')
            }
        )
      },
    },
    mounted() {
    }
  };
</script>

<style lang="scss" scoped>


  .flex-row-top {
    text-align: center;
    padding: 15px;
  }

  .rounded-input {
    margin-left: 15px;
    font-size: 14px;
    font-weight: 300;
    color: #0997b1;
    width: 424px;
    height: 43px;
    border-radius: 22px;
    border: solid 1px #868686;
    background-color: white;
    margin-right: 22px;
    outline: none;
    padding-left: 15px;
  }

  ::placeholder {
    color: #0997b1;
    opacity: 1; /* Firefox */
  }

  .spec-groups {
    text-align: center;
    margin-top: 30px;

    .spec_group_name {
      font-weight: bold;
      font-size: 18px;
    }
  }
  .specs {

    justify-content: flex-start;

    .spec_name {
      width: 30%;
    }

    .rounded-input-tag {
      width: 50%;
      margin-left: 15px;
      font-size: 14px;
      font-weight: 300;
      color: #0997b1;
      border: solid 1px #868686;
      background-color: white;
      margin-right: 22px;
      outline: none;
      padding: 0 0 0 15px;
    }

    .rounded-select {
      height: 30px;
      width: 10%;
      margin-left: 15px;
      font-size: 14px;
      font-weight: 300;
      color: #0997b1;
      border-radius: 22px;
      border: solid 1px #868686;
      background-color: white;
      margin-right: 22px;
      outline: none;
      padding-left: 15px;
    }
  }

  .table-scroll tbody {
    display:block;
    height: 250px;
    overflow-y: scroll;
    overflow-x: hidden;
  }
  .table-scroll thead, .table-scroll tbody tr {
    display: table;
    width: 100%;
    table-layout: fixed;
  }
  .table-scroll thead {
    width: calc( 100% - 1em )
  }
  .table-scroll {
    width: 100%;
    margin-bottom: 15px;
  }

  .label-block {
    border: 2px solid red;
    margin: 20px 0;
    .fix-block {
      max-height: 400px;
      overflow: scroll;
      > div {
        display: flex;
        border: 1px solid;
        padding: 15px 0;
        > div:first-child {
          width: 15%;
        }
        > div:not(:first-child) {
          padding: 0 20px;
        }
      }
    }
  }

  $bluecolor: #0997b1;
  $accentcolor: #fcae2c;
  $lightcolor: #fff;
  $darkcolor: #444;

  .head-elements {
    position: relative;
    width: 100%;
    .head-inputs {
      vertical-align:top;
      width: 62%;
      display: inline-block;
      text-align: center;
    }
    .union-images {
      width: 18%;
      vertical-align:top;
      display: inline-block;
      max-height: 500px;
      min-height: 100px;
      overflow-y: scroll;
      overflow-x: hidden;
    }
    .product-images {
      width: 18%;
      vertical-align:top;
      display: inline-block;
      max-height: 500px;
      min-height: 100px;
      overflow-y: scroll;
      overflow-x: hidden;
    }
  }
  .union {

    justify-content: flex-start;
    max-width: 500px;
    position: relative;
    margin-left: 15%;
    font-size: 15px;
  }
  .radiobtn {
    position: relative;
    display: block;
    width: 75%;

    label {
      display: block;
      background: lighten($accentcolor, 30%);
      color: $darkcolor;
      border-radius: 5px;
      padding: 10px 20px;
      border: 2px solid lighten($accentcolor, 20%);
      margin-bottom: 5px;
      cursor: pointer;

      &:after,
      &:before {
        content: "";
        position: absolute;
        right: 11px;
        top: 11px;
        width: 20px;
        height: 20px;
        border-radius: 3px;
        background: lighten($accentcolor, 15%);
      }
      &:before {
        background: transparent;
        transition: 0.1s width cubic-bezier(0.075, 0.82, 0.165, 1) 0s,
        0.3s height cubic-bezier(0.075, 0.82, 0.165, 2) 0.1s;
        z-index: 2;
        overflow: hidden;
        background-repeat: no-repeat;
        background-size: 13px;
        background-position: center;
        width: 0;
        height: 0;
        background-image: url(data:image/svg+xml;base64,PHN2ZyB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHZpZXdCb3g9IjAgMCAxNS4zIDEzLjIiPiAgPHBhdGggZmlsbD0iI2ZmZiIgZD0iTTE0LjcuOGwtLjQtLjRhMS43IDEuNyAwIDAgMC0yLjMuMUw1LjIgOC4yIDMgNi40YTEuNyAxLjcgMCAwIDAtMi4zLjFMLjQgN2ExLjcgMS43IDAgMCAwIC4xIDIuM2wzLjggMy41YTEuNyAxLjcgMCAwIDAgMi40LS4xTDE1IDMuMWExLjcgMS43IDAgMCAwLS4yLTIuM3oiIGRhdGEtbmFtZT0iUGZhZCA0Ii8+PC9zdmc+);
      }
    }

    input[type="radio"] {
      display: none;
      position: absolute;
      width: 100%;
      appearance: none;

      &:checked + label {
        background: lighten($accentcolor, 15%);
        animation-name: blink;
        animation-duration: 1s;
        border-color: $accentcolor;

        &:after {
          background: $accentcolor;
        }

        &:before {
          width: 20px;
          height: 20px;
        }
      }
    }
    .union_button {
      display: block;
      background: lighten($bluecolor, 15%);
      color: $darkcolor;
      border-radius: 5px;
      padding: 10px 20px;
      border: 2px solid lighten($bluecolor, 5%);
      margin-bottom: 5px;
      cursor: pointer;
    }
  }

</style>
