<template>
<div class="pages-list-item">
  <img src="/img/drag-drop.svg" alt="dragdrop">
  <div class="icon-container">
    <ion-icon :name="icon(page)"></ion-icon>
  </div>
  <div class="title">
    {{ title(page) }}
  </div>
  <div class="module">
    {{ page.mod_desc.title }}
  </div>
  <toggle-button
    class="ml-39"
    v-model="page.active"
    :color="{checked: '#0997B1', unchecked: '#868686'}"
    :width="51"
    :height="31"
    :margin="2"
  />
  <toggle-button
    class="ml-123"
    v-model="page.active"
    :color="{checked: '#0997B1', unchecked: '#868686'}"
    :width="51"
    :height="31"
    :margin="2"
  />
  <img class="ml-auto" src="/img/garbage.png" alt="garb">
</div>
</template>

<script>
export default {
  data() {
    return {
      page: this.pageProp
    }
  },
  props: {
    pageProp: Object
  },
  methods: {
    icon(page) {
      return 'home'
    },
    title(page) {
      return 'Home'
    }
  }
}
</script>

<style lang="scss" scoped>
@import '../../sass/mixins';

.pages-list-item {
  @include flex-center-all();
  justify-content: flex-start;
  width: 100%;
  height: 56px;
  padding: 0 21px;
  background-color: rgba(#868686, .06);
  border-radius: 5px;
  margin-bottom: 5px;

  .icon-container {
    @include flex-center-all();
    background-image: linear-gradient(45deg, #2ccae6, #0997b1);
    box-shadow: 0 10px 20px 0 rgba(0, 0, 0, 0.2);
    width: 31px;
    height: 31px;
    border-radius: 50%;
    color: white;
    font-size: 21px;
    margin-left: 32px;
  }

  .title {
    color: #0997b1;
    margin-left: 22px;
    margin-left: 57px;
  }

  .module {
    @include flex-center-all();
    justify-content: flex-start;
    background-color: #868686;
    color: white;
    width: 158px;
    height: 31px;
    border-radius: 16px;
    font-size: 14px;
    font-weight: 300;
    margin-left: 57px;
    padding-left: 14px;
  }

  .vue-js-switch {
    margin-bottom: 0;
  }
  .v-switch-core {
    background-color: #0997b1 !important;
  }

  img {
    cursor: pointer;
  }
}
</style>
