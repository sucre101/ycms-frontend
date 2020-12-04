<template>
  <div>
    <table style="border: 1px solid; width: 98%" v-if="orders.length">
      <thead>
        <tr>
          <td>Order id</td>
          <td>Customer</td>
          <td>Status</td>
          <td>Date added</td>
          <td></td>
        </tr>
      </thead>
      <tbody>
        <tr v-for="order in orders">
          <td>{{ order.id }}</td>
          <td>{{ order.name }}</td>
          <td>{{ order.status.name }}</td>
          <td>{{ order.created_at }}</td>
          <td style="width: 100px">
            <div class="action-group">
              <div class="actions">
                <ion-icon
                  title="show order"
                  name="create-outline"
                  @click="showOrder(order.id)"
                />
              </div>
              <div class="actions">
                <ion-icon
                  title="delete order"
                  name="trash-outline" />
              </div>
            </div>
          </td>
        </tr>
      </tbody>
    </table>
    <div v-else>
      <h4>Order list is empty</h4>
    </div>
  </div>
</template>

<script>
  export default {
    name: "ycms-store-orders-list",

    props: ['orders', 'appSlug'],

    methods: {
      showOrder(id) {
        return window.location.href=`/app/${this.appSlug}/order/${id}`;
      }
    }
  }
</script>

<style lang="scss">


  .action-group {
    display: flex;
    flex-direction: row;
    margin-left: auto;
    width: 100px;
    font-size: 21px;
    padding: 5px;
    align-items: flex-end;
  }

  .actions {

    color: #0997b1;
    margin-left: 10px;
    width: 30px;
    font-size: 21px;
    cursor: pointer;
  }
</style>
