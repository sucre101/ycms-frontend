require('./util');
import Vue from 'vue'
import router from "./router";
import store from './store/index'
import {mapGetters} from "vuex";

import { YcmsLocalStorage } from './ycms-local-storage'
import SweetModal from 'sweet-modal-vue/src/plugin'
import { ToggleButton } from 'vue-js-toggle-button'
import vueDebounce from 'vue-debounce'
import InputTag from 'vue-input-tag'
import App from './components/App'
import * as VueGoogleMaps from 'vue2-google-maps'
import { initialize } from './helpers/general';
import _ from 'lodash'
import axios from 'axios'
import '@/assets/app.scss'

Vue.config.devtools = true;

window.axios = axios
axios.defaults.baseURL = 'http://api.ycms/ycms'

import AWN from "awesome-notifications"

Vue.prototype.notifier = new AWN

// window.io = require('socket.io-client')

Vue.prototype.locStor = new YcmsLocalStorage
Vue.use(VueGoogleMaps, {
  load: {
    key: 'API KEY',
    libraries: 'places',
  },
  installComponents: true
});
Vue.use(SweetModal)
Vue.use(vueDebounce, {listenTo: ['input', 'keyup']})
Vue.prototype._ = _

Vue.component('preloader', require('./components/base/ui/Preloader.vue').default)

Vue.component('vue-color', require('vue-color/src/components/Chrome.vue').default)

Vue.component('ToggleButton', ToggleButton)

Vue.component('input-tag', InputTag)

Vue.directive('active', (el, binding) => {

  if (binding.value === getPath() || (binding.value === getPath(1) && binding.value !== getPath())) {
    el.classList.add('active')
  } else {
    el.classList.remove('active')
  }

})

Vue.directive('scroll', {
  bind(el, binding) {
    el.classList.add(binding.value)
    console.log(el, document.body)
  }
})

Vue.directive('click-outside', {
  bind(el, binding) {
    el.addEventListener('click', e => e.stopPropagation());
    document.body.addEventListener('click', binding.value);
  },
  unbind(el, binding) {
    document.body.removeEventListener('click', binding.value);
  }
});

String.prototype.capitalize = () => {
  return this.charAt(0).toUpperCase() + this.slice(1);
}

initialize(store, router);

new Vue({
  components: {
    App
  },
  router,
  store,
  data: {
    drawerOpen: Vue.prototype.locStor.fetch('drawerOpen'),
    userId: false,
    pageTitle: null,
    openManager: false,
  },
  computed: {
    ...mapGetters(['reference'])
  },
  render: h => h(App),
}).$mount('#app')

