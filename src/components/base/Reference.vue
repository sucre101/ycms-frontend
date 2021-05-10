<template>
  <div class="reference" >
    <template v-for="(ref, idx) in references" v-if="page && references.length">
      <div :key="idx">
        <span>{{ ref.title }}</span>
        <p>{{ ref.description }}</p>
        <p v-if="ref.video">
          {{ ref.video }}
        </p>
      </div>
    </template>
  </div>
</template>

<script>
export default {
  name: "reference",

  data() {
    return {
      page: false,
      references: []
    }
  },

  updated() {
    this.setReference()
  },

  methods: {

    setReference() {
      if (this.$root.reference.hasOwnProperty(this.page)) {
        this.references = this.$root.reference[this.page]
      }
    },

    setPage(page) {
      this.page = this._.cloneDeep(page)
    }

  }
}
</script>

<style scoped lang="scss">
.reference {
  position: absolute;
  right: 0;
  height: 100vh;
  width: 25%;
  background-color: #b2bcc2cf;
  z-index: 999;
  padding: 25px 40px;
  overflow-y: scroll;
  overflow-x: hidden;

  > div {
    margin-bottom: 15px;
    display: block;
    border-bottom: 1px solid;

    span {
      font-size: 15px;
      background: white;
      padding: 0 10px;
      border-radius: 25px;
      margin-bottom: 10px;
      display: inline-block;
    }

    p {
      background: white;
      font-size: 13px;
      padding: 10px;
      border-radius: 10px;
    }
  }
}

.reference::before {
  content: "";
  display: block;
  width: 200px;
  height: 200px;
  background-image: url(@/img/ycms/yappix-background.png);
  background-repeat: no-repeat;
  background-position: center;
  background-size: 100%;
  position: fixed;
  right: 7%;
  top: 35%;
  opacity: 0.2;
}
</style>
