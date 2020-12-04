<template>
  <div>

    <TopButtons></TopButtons>

    <div class="field" v-if="app.settings.store_structure">
      <span @click="clear"></span>
      <input
        type="text"
        @focus="showDrop"
        @focusout="hideDrop"
        v-model="storeData.structure[0].name"
        ref="input"
      >
      <div class="simple-dropdown" ref="dropdown" v-if="filteredList.length">
        <ul>
          <li v-for="elem in filteredList" @click.prevent="chooseStructure(elem)">
            {{ elem.name }}
          </li>
        </ul>
      </div>
    </div>

    <div class="field">
      <label for="store-name">Store name</label>
      <input type="text" v-model="storeData.name" id="store-name">
    </div>
    <div class="field">
      <label for="store-email">Store email</label>
      <input type="text" v-model="storeData.email" id="store-email">
    </div>
    <div class="field">
      <label for="store-phone">Store phone</label>
      <input type="text" v-model="storeData.phone" id="store-phone">
    </div>
    <div class="field">
      <label>Store currency</label>
      <select v-model="storeData.currency_id">
        <template v-for="currency in currencies">
          <option
            :value="currency.id"
          >
            {{ currency.name }}
          </option>
        </template>
      </select>
    </div>
    <div class="field">
      <label for="tax-rate">Tax rate</label>
      <input type="text" v-model="storeData.tax_rate" id="tax-rate">
    </div>
    <div class="field">
      <label for="tax_name">Tax name</label>
      <input type="text" v-model="storeData.tax_name" id="tax_name">
    </div>
    <div class="field">
      <label for="order">Sort order</label>
      <input type="text" v-model="storeData.order" id="order">
    </div>
    <div class="field">

      <ycms-address-searcher :address="storeData.address" ref="addrSearcher" @pick="setAddrGetCoords"/>

      <div style="display: flex; flex-direction: row">
        <div class="input-field">
          <label for="input-lat">Latitude</label>
          <input type="text" id="input-lat" :value="storeData.lat" readonly>
        </div>
        <div class="input-field">
          <label for="input-lon">Longitude</label>
          <input type="text" id="input-lon" :value="storeData.lon" readonly>
        </div>
      </div>

    </div>
    <ycms-action-buttons
      :buttons-list="[
        {
          title: 'Save',
          handler: 'eval:this.$parent.saveStore()',
          class: 'bg-green-gradient',
        },
      ]"
      align="left"
    />
  </div>
</template>

<script>
  import YcmsTopButtons from "../YcmsTopButtons";

  export default {
    name: "shop-store-edit",

    components: {
      TopButtons: YcmsTopButtons
    },

    data() {
      return {
        storeData: {},
        structureLoop: []
      }
    },

    props: ['store', 'app', 'structure', 'currencies'],

    computed: {
      filteredList(){
        return this.structureLoop.filter(s => {
          return s.name.toLowerCase().includes(this.storeData.structure[0].name.toLowerCase().trim())
        })
      }
    },

    created() {
      this.storeData = _.cloneDeep(this.store)
      this.structureLoop = _.cloneDeep(this.structure)

      if (!this.storeData.structure.length) {
        this.storeData.structure.push({id: null})
      }
    },

    methods: {

      setAddrGetCoords(addr) {

        this.storeData.address = addr.description

        new gMaps.Geocoder().geocode(
          {'address': addr.description},
          res => {
            this.storeData.lat = res[0].geometry.location.lat()
            this.storeData.lon = res[0].geometry.location.lng()
          }
        )
      },

      saveStore(){
        axios.post('/app/'+this.app.slug+'/shops/save-store', this.storeData)
        .then((res) => {
          this.storeData = _.cloneDeep(res.data.store)
          this.notifier.success('save data')
        })
      },

      clear(){
        this.storeData.structure[0].name = ''
        this.$refs.input.focus()
      },

      showDrop(){
        this.$refs.dropdown.classList.add('show')
      },

      hideDrop(){
        setTimeout(() => {
          this.$refs.dropdown.classList.remove('show')
        }, 100)
      },

      chooseStructure(element){
        this.storeData.structure[0].name = element.name
        this.storeData.structure[0].id = element.id
      }

    }
  }
</script>

<style scoped lang="scss">

  @keyframes show {
    from {opacity: 0;}
    to {opacity: 1;}
  }

  .field {
    display: flex;
    flex-direction: column;
    width: 400px;
    margin-bottom: 50px;
    position: relative;
    span {
      position: absolute;
      right: 0;
      font-size: 20px;
      cursor: pointer;
    }
    span:after {
      display: inline-block;
      content: "\00d7";
    }
    input {
      background: none;
      border: none;
      border-bottom: 1px solid;
    }
    input:focus {
      border-bottom: 2px solid green;
      outline: none;
    }
  }
  .simple-dropdown {
    display: none;
    width: 50%;
    border: 1px solid;
    margin-top: 5px;
    max-height: 400px;
    overflow-y: scroll;
    ul {
      list-style-type: none;
      margin: 0;
      padding: 0;
      li {
        padding: 5px 15px;
      }
      li:hover {
        background: #8296ff;
        cursor: pointer;
      }
    }
  }
  .show {
    display: block;
    animation: show 250ms ease-in-out both;
  }
  .field {
    .container {
      width: auto;
      .dropdown {
        width: 100% !important
      }
    }
  }
</style>
