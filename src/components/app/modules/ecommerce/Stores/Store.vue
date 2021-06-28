<template>
  <div class="edit-form">
    <InnerTab :items="tabs"
              @change="selectTab"
    />

    <div class="container">

      <div class="main-tab" v-if="selectedTab === 0">

        <div class="input-group">
          <label>
            <input type="text" class="input-field" v-model.trim="store.email">
          </label>
        </div>

      </div>

      <div class="address-tab" v-if="selectedTab === 1">
        <div class="input-group">
          <label>
            <AddressSearcher
                ref="addrSearcher"
                @pick="setAddrGetCoords"
                :address="store.address"
                :inject-script="false"
            />
          </label>
        </div>
        <div class="at-map">
          <GmapMap
              :center="{ lat: Number(store.lat), lng: Number(store.lon) }"
              :zoom="7"
              map-type-id="terrain"
              style="width: 100%; height: 400px"
          >
            <GmapMarker
                :position="{ lat: Number(store.lat), lng: Number(store.lon) }"
                :clickable="true"
                :draggable="true"
            />
          </GmapMap>
        </div>
      </div>

      <div class="phone-tab" v-if="selectedTab === 2">

        <div class="input-group">
          <label>
            <the-mask :mask="['+# (###) ###-##-##']" class="required input-field" ref="phone" placeholder="+* (***) ***-**-**" v-model.trim="store.phone"/>
          </label>
        </div>

      </div>

    </div>

  </div>
</template>

<script>
import {moduleUrl} from "@/helpers/general"
import InnerTab from "@/components/base/ui/InnerTab"
import {TheMask} from 'vue-the-mask'
import AddressSearcher from "@/components/base/AddressSearcher";

export default {
  name: "store",

  components: {
    InnerTab, TheMask, AddressSearcher
  },

  data() {
    return {
      store: {},
      tabs: [
        { name: 'Main' },
        { name: 'Address' },
        { name: 'Phones' },
      ],
      selectedTab: 0,

    }
  },

  created() {
    this.$root.$emit('navigator::setBack', true)

    setTimeout(() => {
      this._loadData()
    }, 1000)

  },

  computed: {

    // store() {
    //   return this.$route.query.store
    // }

  },
  mounted() {
    this.$parent.loading = true;
  },

  methods: {

    selectTab(index) {
      this.selectedTab = index
    },

    setAddrGetCoords(addr) {
      this.store.address = addr.description
      new gMaps.Geocoder().geocode(
          {'address': addr.description},
          res => {
            this.store.country = res[0].address_components
                .find(c => c.types.includes('country')).long_name
            this.store.city = res[0].address_components
                .find(c => c.types.includes('locality')).long_name
            this.store.lat = res[0].geometry.location.lat()
            this.store.lon = res[0].geometry.location.lng()
          }
      )
    },

    _loadData() {

      axios.get(`${moduleUrl(this.$route)}/store/${this.$route.query.store}`)
        .then((res) => {
          this.store = res.data.store
        })

      this.$nextTick(() => {
        this.$parent.loading = false;
      })
    }

  }

}
</script>

<style scoped lang="scss">

.edit-form {
  >.container {
    margin-top: 40px
  }
}


</style>
