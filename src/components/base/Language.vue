<template>
  <div>
    <img src="@/assets/img/ycms/lang_icon.svg" alt="">
    <a href="" @click.prevent="changeLang">Language<span></span></a>

    <div v-if="showLang" class="lang-dropdown">
      <template v-for="(lang, idx) in languages">
        <div :key="idx">
          <input
            v-if="lang.selected === langId"
            type="radio"
            name="lang"
            class="change-lang"
            :id="'lang-'+idx"
            :value="lang.value"
            v-model="langId"
            checked="checked"
            @change="clickSelect($event)"
          >
          <input
            v-else
            type="radio"
            name="lang"
            class="change-lang"
            :id="'lang-'+idx"
            :value="lang.value"
            v-model="langId"
            @change="clickSelect($event)"
          >
          <label :for="'lang-'+idx">{{ lang.name }}</label>
        </div>
      </template>
    </div>
  </div>
</template>

<script>
export default {
  name: "language",

  data() {
    return {
      languages: [
        {name: 'Russian', value: 1, selected: true},
        {name: 'English', value: 2, selected: false}
      ],
      showLang: false,
      langId: 1
    }
  },

  created() {
    this._.forEach(this.languages, v => {
      v.selected = v.value === this.langId;
    })
  },

  methods: {
    changeLang() {
      this.showLang = !this.showLang
    },

    clickSelect(e) {
      this.langId = e.target.value
      this.showLang = false
    }
  }
}
</script>

<style scoped lang="scss">
.lang-dropdown {
  position: absolute;
  top: 30px;
  width: 180px;
  box-shadow: 0 5px 11px 0 rgba(0, 0, 0, 0.16);
  background-color: #b5b5b5;
  padding: 7px 15px 0 15px;

  > div {
    position: relative;
  }

  input {
    display: none;
  }

  label {
    cursor: pointer;
    display: flex;
    margin: 0 0 10px 20px;
  }

  label::before {
    content: '';
    width: 13px;
    height: 13px;
    border-radius: 7px;
    border: solid 1px black;
    display: inline-block;
    margin-right: 8px;
    position: absolute;
    top: 1px;
    left: 0;
  }

  input[type="radio"]:checked + label::after {
    content: "";
    width: 7px;
    height: 7px;
    border-radius: 50%;
    display: inline-block;
    background: #0e88cc;
    position: absolute;
    left: 3px;
    top: 4px;
  }
}

img {
  padding-right: 10px;
}

a {
  color: black;
  text-decoration: none;
}

span::after {
  content: "";
  background-image: url("@/assets/img/ycms/choose_lang.svg");
  width: 5px;
  height: 5px;
  display: inline-block;
  background-repeat: no-repeat;
  margin-left: 5px;
}
</style>
