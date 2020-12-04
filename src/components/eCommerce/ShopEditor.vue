<template>
  <div>
    <div class="flex-row-top full-width">
      <input
        class="rounded-input"
        type="text"
        v-model="shop.name"
        placeholder="Title of shop"
      />

      <toggle-button
        v-model="shop.active"
        :color="{checked: '#0997B1', unchecked: '#868686'}"
        :width="51"
        :height="31"
        :margin="2"
      />
      Active
    </div>

    <div
      class="tab"
      v-for="(active, header, i) in tabs" :key="i"
      v-text="header"
      :style="{borderBottomColor: active}"
    />
    <br>
    <br>

    <slot></slot>

  </div>
</template>

<script>
export default {
  name: 'shop-editor',

  props: {
    _shop: Object,
  },

  data() {
    return {
      shop: this._shop,
      tabs: {
        Manage: "#0997b1",
        Design: "#393c40",
        Settings: "#393c40",
        Orders: 'green',
      }
    };
  },

  methods: {
    setIcon(icon) {
      this.page.icon = icon
    }
  }
}
</script>

<style lang="scss" scoped>

.flex-row-top {

  justify-content: flex-start;
  margin: 42px 0 37px 0;
}

.rounded-input {
  font-size: 14px;
  font-weight: 300;
  color: #0997b1;
  width: 264px;
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

.vue-js-switch {
  margin: 0;
  margin-right: 19px;
}

.tab {
  width: 114px;
  border-bottom: solid 1px #0997b1;
  position: relative;
  color: #0997b1;
  font-size: 14px;
  cursor: pointer;
  display: inline-block;
  margin-right: 34px;
  &:after {
    content: "";
    position: absolute;
    display: block;
    width: 73px;
    height: 1px;
    border-bottom: solid 1px #393c40;
    bottom: -4px;
  }
}
</style>
