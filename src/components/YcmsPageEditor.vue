<template>
  <div>
    <div class="flex-row-top full-width">
      <div class="round" @click="$refs.iconSearcher.showModal()">
        <ion-icon :name="page.icon"></ion-icon>
      </div>
      <ycms-ion-icon-searcher ref="iconSearcher" @pick="setIcon"/>

      <input
        class="rounded-input"
        type="text"
        v-model="page.title"
        placeholder="Title of page"
      />

      <toggle-button
        v-model="page.active"
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
    />

    <slot></slot>

  </div>
</template>

<script>
export default {
  name: 'ycms-page-editor',

  props: {
    pageProp: Object,
  },

  data() {
    return {
      page: this.pageProp,
      tabs: {
        Manage: "#0997b1",
        // design: "#393c40",
        // settings: "#393c40"
      }
    };
  },

  methods: {
    setIcon(icon) {
      this.page.icon = icon
    }
  }
};
</script>

<style lang="scss" scoped>

.flex-row-top {

  justify-content: flex-start;
  margin: 42px 0 37px 0;
}

.round {

  width: 58px;
  height: 58px;
  border-radius: 29px;
  box-shadow: 0 10px 20px 0 rgba(0, 0, 0, 0.2);
  background-image: linear-gradient(225deg, #0997b1, #2ccae6);
  color: white;
  font-size: 35px;
  margin-right: 15px;
}

.rounded-input {
  margin-left: 15px;
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
