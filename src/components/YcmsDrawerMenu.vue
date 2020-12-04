<template>
  <div
    class="drawer-menu"
    :class="{active: hover, hover: !hover}"
    :style="{ height: height}"
    @mousemove="hover = true"
    @mouseleave="hover = false"
  >
    <div class="app-name">
      <div class="app-icon"></div>
      <span>{{ app.name }}</span>
      <div class="dropdown" @click="toggleLeftMenu">
        <img :src="hover ? '/img/dropleft-icon.svg' : '/img/dropright-icon.svg'">
      </div>
    </div>

    <router-link :to="{ name: 'module-list', params: { slug: app.slug } }" tag="a">
      <div class="menu-entry">
        <div class="icon-container">
          <img src="/img/document-icon.svg" alt="document">
        </div>
        <span>Modules & Pages</span>
      </div>
    </router-link>

    <a :href="'/app/' + app.slug + '/style'">
      <div class="menu-entry">
        <div class="icon-container">
          <img src="/img/style-icon.svg" alt="document">
        </div>
        <span>Styles & Appearance</span>
      </div>
    </a>
    <a href="" style="cursor: initial">
      <div
        class="menu-entry"
        :class="{ 'active': act }"
      >
        <div class="icon-container">
          <img src="/img/email-icon.svg" alt="email">
        </div>
        <span>eCommerce</span>
        <div class="dropdown" @click.prevent="act = !act">
          <img :src="act ? '/img/dropdown-icon.svg' : '/img/dropleft-icon.svg'">
        </div>
      </div>
      <div v-if="act" class="dropdown-box">
        <ul>
          <li>
            <a :href="'/app/' + app.slug + '/shops'">
              Stores
            </a>
          </li>
          <li>
            <a :href="'/app/' + app.slug + '/shop-categories'">
              Categories
            </a>
          </li>
          <li>
            <a :href="'/app/' + app.slug + '/units'">
              Units
            </a>
          </li>
          <li>
            <a href="">
              Products
            </a>
          </li>
          <li>
            <a :href="'/app/' + app.slug + '/labels'">
              Labels
            </a>
          </li>
          <li>
            <a :href="'/app/' + app.slug + '/orders'">
              Orders
            </a>
          </li>
        </ul>
      </div>
    </a>

    <a :href="'/app/' + app.slug + '/publication'">
      <div class="menu-entry">
        <div class="icon-container">
          <img src="/img/cloud-icon.svg" alt="cloud">
        </div>
        <span>Publication & Sources</span>
      </div>
    </a>
    <a :href="'/app/' + app.slug + '/orders'">
      <div class="menu-entry">
        <div class="icon-container">
          <img src="/img/printer-icon.svg" alt="printer">
        </div>
        <span>Orders</span>
      </div>
    </a>
    <a :href="'/app/' + app.slug + '/statistics'">
      <div class="menu-entry">
        <div class="icon-container">
          <img src="/img/statistics-icon.svg" alt="stats">
        </div>
        <span>Statistics</span>
      </div>
    </a>

    <a :href="'/app/' + app.slug + '/email'">
      <div class="menu-entry">
        <div class="icon-container">
          <img src="/img/email-icon.svg" alt="email">
        </div>
        <span>Email Templates</span>
      </div>
    </a>

  </div>
</template>

<script>
import { mapActions, mapGetters } from 'vuex'

export default {
  name: 'ycms-drawer-menu',
  props: ['appName', 'app'],
  data() {
    return {
      act: false,
    }
  },
  computed: {

    ...mapGetters({
      getOpen: 'getOpen'
    }),

    height() {
      return !this.getOpen ? '100%' : 'auto';
    },

    hover() {
      return this.getOpen
    }

  },

  methods: {

    ...mapActions({
      setOpen: 'setOpen',
      unsetOpen: 'unsetOpen'
    }),

    toggleLeftMenu() {

      console.log(this.getOpen)

    }
  },
}
</script>

<style lang="scss" scoped>

.hover {
  margin-left: 0 !important;
}
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
    padding-right: 20px;
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

<!--<template>-->
<!--  <div class="sidebar-menu">-->
<!--    <ul>-->
<!--      <li>-->
<!--        <div>-->
<!--          <img src="/img/ycms/nav.png">-->
<!--          <span class="title">menu</span>-->
<!--          <div>-->
<!--            <span>-->
<!--              e-commerce-->
<!--            </span>-->
<!--            <ul>-->
<!--              <li>-->
<!--                <a :href="'/app/' + app.slug + '/shops'">-->
<!--                  Shops-->
<!--                </a>-->
<!--              </li>-->
<!--            </ul>-->
<!--          </div>-->
<!--        </div>-->
<!--      </li>-->
<!--      <li>-->
<!--        <div>-->
<!--          <img src="/img/ycms/plus_page.svg">-->
<!--          <span class="title">app page</span>-->
<!--        </div>-->
<!--      </li>-->
<!--      <li>-->
<!--        <router-link :to="{ name: 'pages', params: { slug: app.slug } }" tag="div">-->
<!--          <img src="/img/ycms/icon_pages.svg">-->
<!--          <span class="title">pages</span>-->
<!--        </router-link>-->
<!--      </li>-->
<!--      <li v-active="'shop'">-->
<!--        <div>-->
<!--          <img src="/img/ycms/cart-icon.svg">-->
<!--          <span class="title">e-commerce</span>-->
<!--          <span class="caret"></span>-->
<!--          <div>-->
<!--            <span>-->
<!--              e-commerce-->
<!--            </span>-->
<!--            <ul>-->
<!--              <template v-if="app.stores.length">-->
<!--                <li>-->
<!--                  <a href="" @click.prevent="$router.push({ name: 'shop-list', params: { slug: app.slug } })">-->
<!--                    Shops-->
<!--                  </a>-->
<!--                </li>-->
<!--                <li>-->
<!--                  <a :href="'/app/' + app.slug + '/shop-categories'">-->
<!--                    Categories-->
<!--                  </a>-->
<!--                </li>-->
<!--                <li>-->
<!--                  <a :href="'/app/' + app.slug + '/units'">-->
<!--                    Units-->
<!--                  </a>-->
<!--                </li>-->
<!--                <li>-->
<!--                  <a :href="'/app/' + app.slug + '/shop-products'">-->
<!--                    Products-->
<!--                  </a>-->
<!--                </li>-->
<!--                <li>-->
<!--                  <a :href="'/app/' + app.slug + '/labels'">-->
<!--                    Labels-->
<!--                  </a>-->
<!--                </li>-->
<!--                <li>-->
<!--                  <a :href="'/app/' + app.slug + '/orders'">-->
<!--                    Orders-->
<!--                  </a>-->
<!--                </li>-->
<!--                <li>-->
<!--                  <a href="" @click.prevent="$router.push({ name: 'shop-settings', params: { slug: app.slug } })">-->
<!--                    Settings-->
<!--                  </a>-->
<!--                </li>-->
<!--              </template>-->
<!--              <li v-else>-->
<!--                <a href="#">-->
<!--                  Create first shop-->
<!--                </a>-->
<!--              </li>-->
<!--            </ul>-->
<!--          </div>-->
<!--        </div>-->
<!--      </li>-->
<!--      <li>-->
<!--        <div>-->
<!--          <img src="/img/ycms/icon_styles.svg">-->
<!--          <span class="title">customizer</span>-->
<!--        </div>-->
<!--      </li>-->
<!--      <li>-->
<!--        <div>-->
<!--          <img src="/img/ycms/icon_user.svg">-->
<!--          <span class="title">app-users</span>-->
<!--        </div>-->
<!--      </li>-->
<!--      <li>-->
<!--        <div>-->
<!--          <img src="/img/ycms/Statistics-Icon.svg">-->
<!--          <span class="title">analitycs</span>-->
<!--        </div>-->
<!--      </li>-->
<!--      <li>-->
<!--        <div>-->
<!--          <img src="/img/ycms/Cloud-Icon.svg">-->
<!--          <span class="title">publication</span>-->
<!--        </div>-->
<!--      </li>-->

<!--    </ul>-->
<!--  </div>-->
<!--</template>-->

<!--<script>-->
<!--  export default {-->
<!--    name: 'ycms-drawer-menu',-->

<!--    props: ['app'],-->

<!--    data() {-->
<!--      return {-->
<!--        act: false,-->
<!--        hover: this.$root.drawerOpen,-->
<!--        ecommerceList: ['orders', 'labels', 'shop-products', 'units', 'shop-categories', 'shops'],-->
<!--      }-->
<!--    },-->

<!--    mounted() {-->

<!--    },-->

<!--    methods: {-->
<!--      toggleLeftMenu() {-->
<!--        this.$root.drawerOpen = !this.$root.drawerOpen-->
<!--        this.locStor.put('drawerOpen', this.$root.drawerOpen)-->
<!--      }-->
<!--    },-->
<!--  }-->
<!--</script>-->

<!--<style lang="scss" scoped>-->
<!--  -->

<!--.sidebar-menu {-->
<!--  width: 111px;-->
<!--  background: #222222;-->
<!--  font-size: 13px;-->
<!--  z-index: 99;-->

<!--  .router-link-active {-->
<!--    background: #787979;-->
<!--  }-->

<!--  ul li:first-child {-->
<!--    background-color: #0989cc;-->

<!--    div {-->
<!--      cursor: initial;-->
<!--    }-->
<!--  }-->

<!--  ul li:not(:first-child) > div:hover {-->
<!--    background: #787979;-->
<!--  }-->

<!--  ul {-->
<!--    list-style-type: none;-->
<!--    margin: 0;-->
<!--    padding: 0;-->

<!--    li.active {-->
<!--      background: #787979;-->
<!--    }-->

<!--    li {-->
<!--      div {-->
<!--        display: flex;-->
<!--        align-items: center;-->
<!--        height: 93px;-->
<!--        justify-content: center;-->
<!--        color: white;-->
<!--        flex-direction: column;-->
<!--        cursor: pointer;-->
<!--        position: relative;-->

<!--        img {-->
<!--          width: 45%;-->
<!--          margin-bottom: 10px;-->
<!--        }-->

<!--        span.title {-->
<!--          text-transform: uppercase;-->
<!--          text-shadow: 0 0 6px rgba(0, 0, 0, 0.16);-->
<!--          font-size: 13px;-->
<!--          font-weight: 500;-->
<!--          font-stretch: normal;-->
<!--          font-style: normal;-->
<!--          line-height: 1.15;-->
<!--          letter-spacing: normal;-->
<!--          color: var(&#45;&#45;white);-->
<!--        }-->

<!--        .caret {-->
<!--          position: absolute;-->
<!--          right: 10px;-->
<!--          top: 48%;-->

<!--          img {-->
<!--            width: 100%;-->
<!--            margin: 0;-->
<!--          }-->
<!--        }-->

<!--        .caret::after {-->
<!--          content: "";-->
<!--          background-image: url("/img/ycms/choose_lang.svg");-->
<!--          width: 5px;-->
<!--          height: 5px;-->
<!--          z-index: 9999;-->
<!--          display: block;-->
<!--          background-repeat: no-repeat;-->
<!--        }-->

<!--        div {-->
<!--          display: none;-->
<!--          position: absolute;-->
<!--          top: 0;-->
<!--          width: 242px;-->
<!--          height: 100vh;-->
<!--          left: 100%;-->
<!--          padding-left: 29px;-->
<!--          padding-top: 35px;-->
<!--          text-transform: uppercase;-->
<!--          backdrop-filter: blur(9.6px);-->
<!--          background-color: rgba(34, 34, 34, 0.65);-->

<!--          span {-->
<!--            font-weight: bold;-->
<!--            display: inline-block;-->
<!--            vertical-align: middle;-->
<!--          }-->

<!--          span::after {-->
<!--            content: "";-->
<!--            background-image: url("/img/ycms/choose_lang.svg");-->
<!--            width: 5px;-->
<!--            height: 5px;-->
<!--            z-index: 9999;-->
<!--            display: inline-block;-->
<!--            background-repeat: no-repeat;-->
<!--          }-->

<!--          ul {-->
<!--            padding-left: 15px;-->
<!--            padding-top: 5px;-->

<!--            li {-->
<!--              background: none !important;-->

<!--              a {-->
<!--                color: white;-->
<!--              }-->
<!--            }-->
<!--          }-->
<!--        }-->
<!--      }-->

<!--      div:hover > div {-->
<!--        display: block;-->
<!--      }-->
<!--    }-->
<!--  }-->
<!--}-->

<!--</style>-->
