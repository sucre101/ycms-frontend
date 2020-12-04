<template>
<div :class="['bottom-buttons', alignment]">
  <div
    v-for="(button, index) in buttons" :key="'bb_' + index"
    :class="['bottom-button', button.enabled ? button.class : 'disabled']"
    @click="onClick(button)"
  >
    {{ button.title }}
  </div>
</div>
</template>

<script>
// FIXME: refactor this shit Serega genius AF
export default {
  name: 'ycms-action-buttons',

  data() {
    return {
      buttons: this.buttonsList
    }
  },
  props: {
    align: { type: String, default: 'right' },
    buttonsList: Array,
  },
  methods: {
    onClick(data) {
      if (!data.enabled) {
        this.notifier.warning('Choose template and module!')
        return
      }
      if (data.handler.match(/^eval:/)) {
        let toEval = data.handler.match(/^eval:(.+)/)[1]
        eval(toEval)
        return
      }
      if (data.handler === 'setHome')
        this.setHome(data.data, data.action, data.redir)
      else if (data.handler === 'reset')
        this.reset(data.data)
      else
        this[data.handler]()
    },
    async setHome(vars, action, redir) {
      let outData = new FormData()
      vars.forEach(v => outData.set(v, this.locStor.fetch(v)))

      post(action, outData);
    },
    createApp() {
      let fd = s('form').formData();
      ['menuTemplate', 'homepageModule'].forEach(v => fd.set(v, this.locStor.fetch(v)))
      post('/create-app', fd)
    },
    reset(vars) {
      vars.forEach(v => this.locStor.remove(v))
    },
    enable(button) {
      // && button.handler == 'createApp'
      let newStatus

      if (button.src === 'localStorage') {
        newStatus = button.data.reduce((res, cur) => {
          return !!(res && this.locStor.fetch(cur))
        }, true)
      }
      if (button.handler === 'createApp') {
        newStatus = ['menuTemplate', 'homepageModule'].reduce((res, cur) => {
          return !!(res && this.locStor.fetch(cur))
        }, true)
      }

      Vue.set(button, 'enabled', newStatus)
    }
  },
  created() {
    this.buttons.forEach(button => {
      if (button.check) {
        this.enable(button)
        this.$root.$on('ls-change', name => {
          this.enable(button)
        })
      } else {
        button.enabled = true
      }
    })
  },
  computed: {
    setClass(ind) {
      if (this.buttons[ind].enabled) {
        return this.buttons[ind].class
      } else {
        return 'disabled'
      }
    },
    alignment() {
      return 'align-' + this.align
    }
  }
}
</script>

<style lang="scss" scoped>
@import '../assets/mixins';

.bottom-buttons {
  display: flex;
  margin-top: 42.5px;

  &.align-left {
    flex-direction: row;
  }

  &.align-right {
    justify-content: flex-end;
  }

  .bottom-button {
    @include flex-center-all();
    font-size: 10px;
    font-weight: 600;
    color: #ffffff;
    width: 136px;
    height: 50px;
    border-radius: 5px;
    box-shadow: 0 10px 20px 0 rgba(0, 0, 0, 0.2);
    cursor: pointer;

    &:not(:last-child) {
      margin-right: 30px;
    }

    &.disabled {
      background-image: linear-gradient(250deg, #777777, #888888);
    }

    &.bg-black {
      background-image: linear-gradient(250deg, #2b2b2b, #212224);
    }

    &.bg-green-gradient {
      background-image: linear-gradient(250deg, #50b109, #0997b1);
    }
  }
}
</style>
