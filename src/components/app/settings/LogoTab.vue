<template>
  <div class="table-list-items">
    <h4>Logo & SplashScreen</h4>

    <div class="main-block">

      <div class="change-block">

        <div class="background-picker">

          <span>Background</span>

          <ColorPicker :color="background" @update="updateBackground"/>

        </div>

        <div class="select-icon-block">
          <img :src="getImageUrl(logo)" @click="addImage">
        </div>

      </div>

      <div class="preview-block">

        <div class="phone" :style="{ 'background-color': background }" >
          <img :src="getImageUrl(logo)" alt="" width="50%">
        </div>

      </div>

      <div class="btn-action blue" v-if="activeForSave" @click="updateAppSettings">Save</div>

    </div>

  </div>
</template>

<script>
import ColorPicker from "@/components/base/ui/ColorPicker"
import {imageUrl} from "@/helpers/general"

export default {
  name: "logo-tab",

  components: {
    ColorPicker
  },

  data() {
    return {
      background: '#FFFFFF',
      logo: '/img/ycms/yappix-background.png',
      activeForSave: false,
    }
  },

  watch: {

    logo: {
      handler(val) {

        if (val.charAt(0) !== '/') {
          this.logo = '/' + val
        }
        this.activeForSave = true
      },
      deep: true
    },

    background: {
      handler(val) {
        this.activeForSave = true
      },
      deep: true
    }

  },

  created() {

    const settings = this.$root.$children[0].app.app_settings

    settings.forEach( item => {

      switch (item.name) {
        case 'background_splash':
          this.background = item.value
          break;
        case 'app_icon_src':
          this.logo = item.value
          break;
      }

    } )

    this.$root.$on('set::file', this.changeIcon)

    this.$nextTick(() => {
      this.activeForSave = false
    })
  },

  methods: {

    updateBackground(value) {
      this.background = value
    },

    getImageUrl(path) {
      return imageUrl(path)
    },

    addImage() {
      this.$root.$emit('fmanager::open', true)
    },

    changeIcon(data) {
      this.logo = `${data.name}`
    },

    updateAppSettings() {

      const data = {
        logo: this.logo,
        background: this.background
      }

      axios.patch(`${this.$root.$children[0].app.slug}/settings/update`, data)
        .then((res) => {

          if (res.data.success) {
            this.$store.commit('updateAppIconSettings', JSON.stringify(res.data.settings))
            this.notifier.success('Save data')
          }

        })
        .then( res => this.activeForSave = false )

    }

  }
}
</script>

<style scoped lang="scss">

.main-block {
  width: 100%;
  display: flex;
  height: 600px;
  position: relative;
  .change-block {
    width: 75%;
    .background-picker {
      display: flex;
      flex-direction: row;
      justify-content: space-between;
      align-items: center;
      width: 40%;
      margin-bottom: 40px;
    }
    .select-icon-block {
      display: flex;
      width: 40%;
      border: 1px dotted gray;
      img {
        width: 100%;
        cursor: pointer;
      }
    }
  }
  .preview-block {
    width: 25%;
    display: flex;
    flex-direction: row;
    justify-content: center;
    height: 100%;

    .phone {
      background-image: url("~@/assets/img/ycms/phone.svg");
      background-repeat: no-repeat;
      background-size: contain;
      height: 87%;
      width: 100%;
      border-radius: 27px;
      display: flex;
      flex-direction: column;
      justify-content: center;
      align-items: center;
    }
  }
  .btn-action {
    position: absolute;
    top: -45px;
    right: -35px;
  }
}

</style>
