<template>
  <div>
    <sweet-modal
        class="modal"
        ref="profile"
        width="350"
        overlay-theme="dark"
        blocking
        hide-close-button
    >

      <div id="profile-card">

        <div class="avatar">
          <img :src="getProfileAvatar">
        </div>

        <div class="profile-name">
          {{ getFullName }}
        </div>

        <div class="profile-email">
          {{ profile.email }}
        </div>

      </div>

<!--      <div @click="closeModal()">-->
<!--        close-->
<!--      </div>-->

    </sweet-modal>

  </div>
</template>

<script>
import {imageUrl} from "@/helpers/general";

export default {
  name: "profile-modal",

  props: {

    profile: {
      type: Object,
      required: true,
      default: () => {
        return {}
      }
    }

  },

  computed: {

    getProfileAvatar() {
      let url = ''

      this.profile.extended.forEach(val => {

        if (val.type_id === 2) {
          url = this.getImage(val.value)
        }
      })
      return url
    },

    getFullName() {
      return `${this.profile.name} ${this.profile.lastname ? this.profile.lastname : ''}`
    }

  },

  created() {
    this.$nextTick(() => {
      this.$refs.profile.open()
    })
  },

  methods: {

    closeModal() {
      this.$refs.profile.close()
      this.$emit('close')
    },

    getImage(path) {
      return imageUrl(path)
    },


  }

}
</script>

<style lang="scss">
#profile-card {
  position: relative;
  width: 100%;
  display: flex;
  flex-direction: column;
  .avatar {
    width: 150px;
    height: 150px;
    display: flex;
    position: absolute;
    left: 30%;
    top: -75px;
    background-color: white;
    border-radius: 50%;
    justify-content: center;
    align-content: center;
    align-items: center;
    border: 10px solid rgba(24, 32, 40, 0.94);;
    img {
      width: 50%;
    }
  }
  .profile-name {
    margin-top: 100px;
    font-size: 20px;
    letter-spacing: 2px;
    font-weight: 600;
    text-transform: capitalize;
  }
}
.modal {
  .sweet-modal {
    border-radius: 15px;
    min-height: 450px;
    overflow: visible;
  }
  .sweet-content {
    padding: 0 !important
  }
}

</style>
