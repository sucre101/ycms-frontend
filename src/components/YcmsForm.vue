<template>
  <form class="settings-panel">
    <slot></slot>
    <div class="bottom-buttons">
      <div class="bottom-button bg-black" @click="saveSettings">
          SAVE
      </div>
      <slot name="bottom-right"></slot>
    </div>
  </form>
</template>

<script>
export default {
  name: 'ycms-form',

  data() {
    return {}
  },

  props: ['action'],

  methods: {
    saveSettings(){
      let formData = new FormData(this.$el)
      post(this.action, formData)
    }
  },
}
</script>

<style lang="scss" scoped>

.settings-panel {
  width: 362px;
  background-color: #ffffff;
  padding: 30px;
  margin-top: 32.5px;

  h6 {
    font-size: 10px;
    font-weight: 600;
    text-align: center;
    letter-spacing: 2px;
    color: #aaaeb3;
    margin-bottom: 39px;
  }

  &.full-width {
    width: calc(100% - 45px);
  }

  .settings-row {
    display: flex;
    align-items: center;
    margin-bottom: 23px;

    label {
      margin: 0;
      font-size: 12px;
      font-weight: 300;
      color: #393c40;
    }
  }

  .bottom-buttons {
    display: flex;
    margin-top: 42.5px;

    .bottom-button {

      font-size: 10px;
      font-weight: 600;
      color: #ffffff;
      width: 136px;
      height: 50px;
      border-radius: 5px;
      box-shadow: 0 10px 20px 0 rgba(0, 0, 0, 0.2);
      cursor: pointer;

      &.bg-black {
        background-image: linear-gradient(250deg, #2b2b2b, #212224);
      }

      &.bg-green-gradient {
        background-image: linear-gradient(250deg, #50b109, #0997b1);
      }
    }
  }
}
</style>
