<template>
  <div class="phone-block">
    <div class="phone" :style="'transform:scale('+size+')'">
      <img src="@/img/ycms/phone.svg" alt="phone">
      <svg class="docs-demo-device__md-bar" viewBox="0 0 1384.3 40.3">
        <path class="st0"
              d="M1343 5l18.8 32.3c.8 1.3 2.7 1.3 3.5 0L1384 5c.8-1.3-.2-3-1.7-3h-37.6c-1.5 0-2.5 1.7-1.7 3z"></path>
        <circle class="st0" cx="1299" cy="20.2" r="20"></circle>
        <path class="st0"
              d="M1213 1.2h30c2.2 0 4 1.8 4 4v30c0 2.2-1.8 4-4 4h-30c-2.2 0-4-1.8-4-4v-30c0-2.3 1.8-4 4-4zM16 4.2h64c8.8 0 16 7.2 16 16s-7.2 16-16 16H16c-8.8 0-16-7.2-16-16s7.2-16 16-16z"></path>
      </svg>
      <iframe
        class="app-preview"
        ref="ionic"
        frameborder="0"
      ></iframe>

      <div class="reload-circle" @click="reload">
        <i class="fas fa-fingerprint"></i>
      </div>
    </div>
    <div class="buttons-block">
      <template v-if="!showScale">
        <div
          class="ctrl-phoneblock-btn"
          :class="assemble"
          @click="emitAction('assemble')"
        >
          A
        </div>
        <div
          class="ctrl-phoneblock-btn"
          :class="build"
          @click="emitAction('build')"
        >
          B
        </div>
        <div
          class="ctrl-phoneblock-btn"
          :class="serve"
          @click="emitAction('serve')"
        >
          S
        </div>
        <div
          class="ctrl-phoneblock-btn"
          @click="reload()"
        >
          R
        </div>
        <div
          class="ctrl-phoneblock-btn"
          @click="setSize()"
        >
          Z
        </div>
      </template>

      <div v-if="showScale">
        <input
          type="range"
          orient="vertical"
          min="0.3"
          max="0.8"
          step="0.01"
          v-model="size"
          @mouseup="completeSize"
        >
      </div>

    </div>
  </div>
</template>

<script>
export default {
  name: 'ycms-phone-block',

  data() {
    return {
      newMode: this.mode,
      host: this._host,
      port: 0,
      assemble: '',
      build: '',
      serve: '',
      showScale: false
    }
  },

  props: {
    mode: {
      type: String,
      default: 'iOS'
    },
    _host: {
      type: String
    },
    appId: {
      type: Number
    },
    size: {
      type: String,
      default: '0.3'
    }
  },

  methods: {
    // setMode(mode){
    //   post('/set-app-mode', {mode})

    //   this.newMode = mode
    // },
    setSize() {
      this.showScale = !this.showScale
    },
    completeSize() {
      this.showScale = false
      this.$store.commit('setPhoneSize', this.size)
    },
    reload() {
      this.$refs.ionic.src = this.$refs.ionic.src
    },
    emitMessage(socketKey, msg) {
      this.socket.emit(socketKey, msg);
      console.log(socketKey, msg);
    },
    emitAction(socketKey) {
      this.emitMessage(socketKey, this.appId);
    },
    setPort(port) {
      this.port = port;
      console.log(`Set port ${port}`);
      this.$refs.ionic.src = this.getUrl(port);
      setTimeout(() => this.reload(), 200);
    },
    getUrl(port) {
      const protocol = window.location.protocol;
      const hostname = window.location.hostname;
      const url = `${protocol}//${hostname}:${port}`;
      return url;
    }
  },

  mounted() {
    // sIO.private('user.' + this.$root.userId)
    // .listen('.ionic.started', e => this.$refs.ionic.src = e.host)

    this.$refs.ionic.onerror = function (e, url, line) {
      l({e, url, line})
    }
  },

  created() {
    const url = this.getUrl(3010);
    // this.socket = io(url);
    // this.socket.on('disconnect', (data) => {
    //   console.log('disconnect', data);
    // });
    // this.socket.on('connect', (data) => {
    //   console.log('connect', data, this.socket);
    // });
    // this.socket.on('message', (msg) => {
    //   console.log(`message: ${msg}`);
    // });
    //
    // ['assemble', 'build', 'serve', 'port', 'state', 'full'].forEach(socketKey => {
    //   this.socket.on(socketKey, (data) => {
    //     data = {socketKey, ...data};
    //     console.log(data);
    //   });
    // });
    //
    // ['port'].forEach(socketKey => { // 'serve',
    //   this.socket.on(socketKey, (data) => {
    //     if (data.state === 'done' && data.port) {
    //       this.setPort(data.port);
    //     }
    //   });
    // });
    //
    // ['assemble', 'build', 'serve'].forEach(socketKey => {
    //   this.socket.on(socketKey, (data) => {
    //     let buttonClass = '';
    //     if (data.state === 'accepted') buttonClass = 'loading';
    //     if (data.state === 'done') buttonClass = 'done';
    //
    //     this[socketKey] = buttonClass;
    //   });
    // });
    //
    // this.emitMessage('message', 'from ycms-client-panel 12');
    //
    // this.emitAction('full');
  }
}
</script>

<style lang="scss" scoped>


input[type=range][orient=vertical] {
  writing-mode: bt-lr; /* IE */
  -webkit-appearance: slider-vertical; /* WebKit */
  width: 8px;
  height: 175px;
  padding: 0 5px;
}

.phone-block {
  width: 480px;
  background: transparent;
  position: absolute;
  right: 0;
  height: 100vh;
  display: flex;
  align-items: center;
  justify-content: space-between;

  .buttons-block {
    position: relative;
    display: flex;
    justify-content: space-evenly;
    width: 100%;
    flex-direction: column;
    max-height: 480px;
    align-items: center;

    .ctrl-phoneblock-btn {
      border: 1px solid grey;
      border-radius: 50%;
      padding: 7px 15px;
      margin: 20px 10px;
      cursor: pointer;
    }

    //.small-rounded-btn.active {
    //  background-image: linear-gradient(252deg, #0997b1, #2ccae6);
    //  color: #ffffff;
    //}

    .small-rounded-btn {
      display: flex;

      ion-icon {
        display: none;
        margin: 0.1rem;
        margin-left: .2rem;
      }
    }

    .small-rounded-btn.loading {
      ion-icon.loading {
        display: inline-block;
        animation: spin 4s linear infinite;
      }
    }

    @-moz-keyframes spin {
      100% {
        -moz-transform: rotate(360deg);
      }
    }
    @-webkit-keyframes spin {
      100% {
        -webkit-transform: rotate(360deg);
      }
    }
    @keyframes spin {
      100% {
        -webkit-transform: rotate(360deg);
        transform: rotate(360deg);
      }
    }

    .small-rounded-btn.done {
      ion-icon.done {
        display: inline-block;
        color: #50b109;
      }
    }
  }

  .phone {
    position: relative;

    > img {
      display: block;
      width: 410px;
      background: white;
      border-radius: 45px;
    }

    > svg {
      position: absolute;
      top: 25px;
      width: 80%;
      right: 40px;
    }

    // .status-bar {
    // TODO: implement status bar
    // }

    .app-preview {
      position: absolute;
      left: 15px;
      top: 39px;
      width: 380px;
      height: 786px;
      z-index: 2;
      border-radius: 10px 10px 40px 40px;

      // overflow-y: scroll;
      // -webkit-overflow-scrolling: touch;
      // -ms-overflow-style: none;  // IE 10+
      // scrollbar-width: none;  // Firefox
      // scroll-behavior: smooth;

      // &::-webkit-scrollbar {
      //   display: none; // Safari and Chrome
      // }
    }
  }
}

.reload-circle {

  position: absolute;
  bottom: 75px;
  right: 232px;
  border-radius: 50%;
  opacity: 0;
  width: 60px;
  height: 60px;
  cursor: pointer;
  color: #ccc;
  font-size: 24px;
  transition: opacity 0.3s;

  &:hover {
    opacity: .85;
  }
}
</style>
