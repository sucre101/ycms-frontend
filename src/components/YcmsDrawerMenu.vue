<template>
  <div
    class="drawer-menu"
    :class="{ active: isOpen }"
    :style="{ height: height }"
  >
    <div class="app-name">
      <div class="app-icon"></div>
      <span>{{ app.name }}</span>
      <div class="dropdown" @click="toggleLeftMenu">
        <img :src="isOpen ? '@/assets/img/dropleft-icon.svg' : '@/assets//img/dropright-icon.svg'">
      </div>
    </div>

    <router-link :to="{ name: 'module-list', params: { slug: app.slug } }" tag="a">
      <div class="menu-entry">
        <div class="icon-container">
          <img src="@/assets/img/document-icon.svg" alt="document">
        </div>
        <span>Modules & Pages</span>
      </div>
    </router-link>

    <a :href="'/app/' + app.slug + '/style'">
      <div class="menu-entry">
        <div class="icon-container">
          <img src="@/assets/img/style-icon.svg" alt="document">
        </div>
        <span>Styles & Appearance</span>
      </div>
    </a>

<!--    <a href="" style="cursor: initial">-->
<!--      <div-->
<!--        class="menu-entry"-->
<!--        :class="{ 'active': act }"-->
<!--      >-->
<!--        <div class="icon-container">-->
<!--          <img src="@/assets/img/email-icon.svg" alt="email">-->
<!--        </div>-->
<!--        <span>eCommerce</span>-->
<!--        <div class="dropdown" @click.prevent="act = !act">-->
<!--          <img :src="act ? '/img/dropdown-icon.svg' : '/img/dropleft-icon.svg'">-->
<!--        </div>-->
<!--      </div>-->
<!--      <div v-if="act" class="dropdown-box">-->
<!--        <ul>-->
<!--          <li>-->
<!--            <a :href="'/app/' + app.slug + '/shops'">-->
<!--              Stores-->
<!--            </a>-->
<!--          </li>-->
<!--          <li>-->
<!--            <a :href="'/app/' + app.slug + '/shop-categories'">-->
<!--              Categories-->
<!--            </a>-->
<!--          </li>-->
<!--          <li>-->
<!--            <a :href="'/app/' + app.slug + '/units'">-->
<!--              Units-->
<!--            </a>-->
<!--          </li>-->
<!--          <li>-->
<!--            <a href="">-->
<!--              Products-->
<!--            </a>-->
<!--          </li>-->
<!--          <li>-->
<!--            <a :href="'/app/' + app.slug + '/labels'">-->
<!--              Labels-->
<!--            </a>-->
<!--          </li>-->
<!--          <li>-->
<!--            <a :href="'/app/' + app.slug + '/orders'">-->
<!--              Orders-->
<!--            </a>-->
<!--          </li>-->
<!--        </ul>-->
<!--      </div>-->
<!--    </a>-->


    <router-link :to="{ name: 'publication', params: { slug: app.slug } }" tag="a">
      <div class="menu-entry">
        <div class="icon-container">
          <img src="@/assets/img/cloud-icon.svg" alt="document">
        </div>
        <span>Publication & Sources</span>
      </div>
    </router-link>

    <a :href="'/app/' + app.slug + '/orders'">
      <div class="menu-entry">
        <div class="icon-container">
          <img src="@/assets/img/printer-icon.svg" alt="printer">
        </div>
        <span>Orders</span>
      </div>
    </a>
    <a :href="'/app/' + app.slug + '/statistics'">
      <div class="menu-entry">
        <div class="icon-container">
          <img src="@/assets/img/statistics-icon.svg" alt="stats">
        </div>
        <span>Statistics</span>
      </div>
    </a>

    <a :href="'/app/' + app.slug + '/email'">
      <div class="menu-entry">
        <div class="icon-container">
          <img src="@/assets/img/email-icon.svg" alt="email">
        </div>
        <span>Email Templates</span>
      </div>
    </a>

    <router-link :to="{ name: 'app-settings', params: { slug: app.slug } }" tag="a">
      <div class="menu-entry">
        <div class="icon-container">
          <img src="@/assets/img/document-icon.svg" alt="document">
        </div>
        <span>Settings</span>
      </div>
    </router-link>

  </div>
</template>

<script>
import { switcher } from "@/helpers/general";

export default {
  name: 'ycms-drawer-menu',
  props: ['appName', 'app'],
  data() {
    return {
      act: false,
      hover: false,
      isOpen: false
    }
  },
  computed: {

    height() {
      return !this.getOpen ? '100%' : 'auto';
    },

  },

  created() {
    this.isOpen = localStorage.getItem('drawerOpen') !== 'false';
  },


  methods: {

    toggleLeftMenu() {

      if (this.isOpen === true) {
        switcher(false)
        this.isOpen = false
      } else {
        switcher(true)
        this.isOpen = true
      }

    }
  },
}
</script>

<style lang="scss" scoped>

.drawer-menu {
  width: 264px;
  background-image: linear-gradient(to bottom, #2b2b2b, #868686);
  min-height: calc(100vh - 80px);
  transition: margin-left .5s ease-in-out;
  margin-left: -240px;
  z-index: 99999;
  position: absolute;
  height: auto;
  &.active{
    margin-left: 0;
    position: relative;
  }
  .app-icon {

    width: 39px;
    height: 39px;
    border-radius: 30px;
    box-shadow: 0 5px 10px 0 rgba(0, 0, 0, 0.2);
    border: solid 1px #50b109;
    background-color: #d8d8d8;
    margin: 0 10px 0 0;
  }
  .app-name {
    display: flex;
    align-items: center;
    justify-content: flex-start;
    height: 69px;
    background-color: #0997b1;
    font-size: 12px;
    font-weight: 300;
    color: #ffffff;
    padding-left: 20px;
    padding-right: 20px;
    position: relative;
    .dropdown {
      text-align: center;
      display: block;
      width: 30px;
      height: 30px;
      line-height: 2.3;
      border-radius: 50%;
      position: absolute;
      right: -12px;
      background: linear-gradient(90deg, rgba(2,0,36,1) 0%, rgba(205,205,116,1) 0%, rgba(0,212,255,1) 100%);
      cursor: pointer;
    }
    .dropdown:hover {
      background: linear-gradient(153deg, rgba(2,0,36,1) 0%, rgba(235,235,0,1) 0%, rgba(0,212,255,1) 100%);
    }
  }
  .menu-entry {
    display: flex;
    align-items: center;
    height: 69px;
    font-size: 14px;
    font-weight: 300;
    color: #ffffff;
    position: relative;
    z-index: 0;
    cursor: pointer;
    padding: 0 20px;
    .icon-container {
      width: 69px;

    }
    &.active::after {
      content: "";
      background-image: linear-gradient(to bottom, #1de9b6, #1dc4e9);
      opacity: 0.05;
      top: 0;
      left: 0;
      bottom: 0;
      right: 0;
      position: absolute;
      z-index: -1;
    }
    .dropdown {
      position: absolute;
      top: 23px;
      right: 30px;
    }
  }
  a:hover {
    text-decoration: none;
  }
  .dropdown-box ul {
    list-style-type: none;
    a {
      color: white;
    }
    a:hover {
      color: green;
    }
  }
}
</style>
