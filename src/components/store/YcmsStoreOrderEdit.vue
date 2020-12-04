<template>
  <div>
    <a class="small-rounded-btn blue-bg text-white" :href="'/app/'+this.appSlug+'/orders'">Go Back</a>
    <div style="display: flex; flex-direction: row; justify-content: space-evenly">
      <table class="customer-data-table">
        <tr>
          <td>Customer</td>
          <td>{{ orderData.name }}</td>
        </tr>
        <tr>
          <td>Email</td>
          <td>{{ orderData.email }}</td>
        </tr>
        <tr>
          <td>Phone</td>
          <td>{{ orderData.phone }}</td>
        </tr>
        <tr>
          <td>Date added</td>
          <td>{{ orderData.created_at }}</td>
        </tr>
        <tr>
          <td>Comment to order</td>
          <td class="comment">
            <textarea v-model="orderData.comment" disabled ref="comment"></textarea>
            <span @click="makeEditComment" v-if="!editableComment">
              <i class="fas fa-edit"></i>
            </span>
          </td>
        </tr>
        <tr>
          <td>Status</td>
          <td>
            <select v-model="orderData.order_status_id" @change="orderEdited = true">
              <option
                v-for="status in statuses"
                :value="status.id">
                {{ status.name }}
              </option>
            </select>
          </td>
        </tr>
      </table>
      <table class="order-products-table">
        <thead style="border-bottom: 1px solid">
          <tr>
            <td>Product</td>
            <td>Quantity</td>
            <td width="30%">Total price</td>
          </tr>
        </thead>
        <tbody>
          <tr v-for="(product, index) in orderData.cart_to_product">
            <td>{{ product.product.name }}</td>
            <td>
              <div>
                <span @click="changeQty(index, false)">-</span>
                <input class="qty-input" type="text" v-model="product.quantity">
                <span @click="changeQty(index, true)">+</span>
              </div>
            </td>
            <td>{{ Number(product.quantity) * Number(product.price) }} {{ orderData.store.currency.name }}</td>
          </tr>
        </tbody>
        <tfoot>
          <tr style="border-top: 1px solid">
            <td colspan="2">Total</td>
            <td>
              {{ total }} {{ orderData.store.currency.name }}
            </td>
          </tr>
        </tfoot>
      </table>
    </div>
    <ycms-action-buttons
      v-if="orderEdited"
      :buttons-list="[
        {
          title: 'SAVE',
          handler: 'eval:this.$parent.save()',
          class: 'bg-green-gradient',
        }
      ]"
      align="left"
    />

  </div>
</template>

<script>
  export default {
    name: "ycms-store-order-edit",

    data() {
      return {
        editableComment: false,
        orderEdited: false,
        orderData: Object,
      }
    },

    computed: {
      total(){
        let total = 0;

        _.forEach(this.orderData.cart_to_product, (val) => {
          total = total + val.price * val.quantity;
        })
        return total;
      }
    },

    props: ['appSlug', 'order', 'statuses'],

    created() {
      this.orderData = _.cloneDeep(this.order)
    },

    methods: {
      makeEditComment(){
        this.$refs.comment.removeAttribute('disabled')
        this.editableComment = this.orderEdited = true
      },

      save(){
        axios.post('/app/'+this.appSlug+'/order/'+this.order.id+'/save', this.orderData)
          .then((res) => {
            if (res.data.success) {
              this.notifier.success('order save')
            }
          })
      },

      removeProduct(id){
        axios.post('/app/'+this.appSlug+'/order/'+this.order.id+'/remove-product', {
          id: id,
          order_id: this.order.id
        })
          .then((res) => {
            console.log(res)
          })
      },

      changeQty(index, val){

        if (val === true) {
          this.orderData.cart_to_product[index].quantity++
        } else {
          this.orderData.cart_to_product[index].quantity--
        }

        if (this.orderData.cart_to_product[index].quantity < 1) {
          this.orderData.cart_to_product[index].quantity = 0
        }

        this.orderEdited = true

      }
    }
  }
</script>

<style lang="scss" scoped>
  .customer-data-table {
    border: 1px solid;
    width: 48%;
    tr {
      border-bottom: 1px solid;
      textarea {
        width: 100%;
        resize: none;
        height: 150px;
        overflow-y: scroll;
        margin-bottom: 20px;
      }
      .comment {
        position: relative;
        span {
          position: absolute;
          top: 5px;
          right: 15px;
          cursor: pointer;
          color: grey;
          :hover {
            color: #0b2e13;
          }
        }
      }
    }
  }
  .order-products-table {
    width: 48%;
    border: 1px solid;
    .qty-input {
      width: 50px;
      text-align: center;
    }
  }
</style>
