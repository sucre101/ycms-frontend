<template>
  <div>
    <div class="head-elements">
      <div class="head-inputs">
        <label for="email">Email</label>
        <input
          id="email"
          v-model="settings.email"
          type="checkbox"
        />
      </div>
      <div class="head-inputs">
        <input
          v-if="settings.email"
          v-model="settings.email_debounce"
          class="rounded-input"
          type="time"
          step="1"
        />
      </div>
      <div class="head-inputs">

        <label for="phone">Phone</label>
        <input
          id="phone"
          v-model="settings.phone"
          type="checkbox"
        />
      </div>
      <div class="head-inputs">
        <input
          v-if="settings.phone"
          v-model="settings.phone_debounce"
          class="rounded-input"
          type="time"
        />
      </div>
      <div class="head-inputs">
        <label for="facebook">Facebook</label>
        <input
          id="facebook"
          v-model="settings.facebook"
          type="checkbox"
        />
      </div>
      <div class="head-inputs" v-if="settings.facebook" >
        <input
          v-model="settings.facebook_app_id"
          class="rounded-input"
          type="text"
        />
      </div>
      <div class="head-inputs">
        <label for="google_plus">Google Plus</label>
        <input
          id="google_plus"
          v-model="settings.google_plus"
          type="checkbox"
        />
      </div>
      <div class="head-inputs" v-if="settings.google_plus">
        <input
          v-model="settings.google_plus_app_id"
          class="rounded-input"
          type="text"
        />
        <input
          v-model="settings.google_plus_android_client_id"
          class="rounded-input"
          type="text"
        />
      </div>
      <!--      <div class="head-inputs">-->

      <!--        <label for="google_plus"> Vkontakte</label>-->
      <!--        <input-->
      <!--          id="vkontakte"-->
      <!--          v-model="settings.vkontakte"-->
      <!--          type="checkbox"-->

      <!--          @click="toggleElement('vkontakte_app_id')"-->
      <!--        />-->
      <!--        <input-->
      <!--          v-if="settings.vkontakte"-->
      <!--          ref="vkontakte_app_id"-->
      <!--          v-model="settings.vkontakte_app_id"-->
      <!--          class="rounded-input"-->
      <!--          type="text"-->
      <!--        />-->
      <!--      </div>-->
      <!--      <div class="head-inputs">-->

      <!--        <label for="google_plus">Twitter</label>-->
      <!--        <input-->
      <!--          id="twitter_app_id"-->
      <!--          v-model="settings.twitter"-->
      <!--          type="checkbox"-->

      <!--          @click="toggleElement('twitter_app_id')"-->
      <!--        />-->
      <!--        <input-->
      <!--          v-if="settings.twitter"-->
      <!--          ref="twitter_app_id"-->
      <!--          v-model="settings.twitter_app_id"-->
      <!--          class="rounded-input"-->
      <!--          type="text"-->
      <!--        />-->
      <!--      </div>-->
      <div class="head-inputs">
        <label for="name">Name</label>
        <select class="rounded-input" id="name" v-model="settings.name">
          <option value="1">Visible</option>
          <option value="0">Hidden</option>
          <option value="required">Required</option>
        </select>
      </div>
      <div class="head-inputs">
        <label for="first_name">First name</label>
        <select class="rounded-input" id="first_name" v-model="settings.first_name">
          <option value="1">Visible</option>
          <option value="0">Hidden</option>
          <option value="required">Required</option>
        </select>
      </div>
      <div class="head-inputs">
        <label for="last_name">Last name</label>
        <select class="rounded-input" id="last_name" v-model="settings.last_name">
          <option value="1">Visible</option>
          <option value="0">Hidden</option>
          <option value="required">Required</option>
        </select>
      </div>
      <div class="head-inputs">
        <label for="middle_name">Middle name</label>
        <select class="rounded-input" id="middle_name" v-model="settings.middle_name">
          <option value="1">Visible</option>
          <option value="0">Hidden</option>
          <option value="required">Required</option>
        </select>
      </div>
      <div class="head-inputs">
        <label for="birthday">Birthday</label>
        <select class="rounded-input" id="birthday" v-model="settings.birthday">
          <option value="1">Visible</option>
          <option value="0">Hidden</option>
          <option value="required">Required</option>
        </select>
      </div>
      <div class="head-inputs">
        <label for="prefix">Prefix</label>
        <select class="rounded-input" id="prefix" v-model="settings.prefix_id">
          <option value="1">Visible</option>
          <option value="0">Hidden</option>
          <option value="required">Required</option>
        </select>
      </div>
      <div class="head-inputs" v-if="settings.prefix_id !== '0'">
        <DualListBox
          :source="prefix_list"
          :destination="selected_prefix_list"
          label="name"
          @onChangeList="changePrefix"
        />
      </div>
      <div class="head-inputs" >
        <label for="suffix">Suffix</label>
        <select class="rounded-input" id="suffix" v-model="settings.suffix_id">
          <option value="1">Visible</option>
          <option value="0">Hidden</option>
          <option value="required">Required</option>
        </select>
      </div>
      <div class="head-inputs" v-if="settings.suffix_id !== '0'">
        <DualListBox
          :source="suffix_list"
          :destination="selected_suffix_list"
          label="name"
          @onChangeList="changeSuffix"
        />
      </div>
      <div class="head-inputs">
        <label for="gender">Gender</label>
        <select class="rounded-input" id="gender" v-model="settings.gender_id">
          <option value="1">Visible</option>
          <option value="0">Hidden</option>
          <option value="required">Required</option>
        </select>
      </div>
      <div class="head-inputs" v-if="settings.gender_id !== '0'">
        <DualListBox
          :source="gender_list"
          :destination="selected_gender_list"
          label="name"
          @onChangeList="changeGender"
        />
      </div>
    </div>

    <a class="small-rounded-btn blue-bg text-white" @click="saveSettings" style="float: left">SAVE</a>
  </div>
</template>

<script>
  import DualListBox from "dual-listbox-vue";
  import "dual-listbox-vue/dist/dual-listbox.css";

  export default {
    name: "ycms-apps-auth-settings",
    components:{
      DualListBox
    },
    props: {
      app: Object,
      settings_: Object,
      suffixes: Array,
      prefixes: Array,
      genders: Array,
      selected_suffixes: Array,
      selected_prefixes: Array,
      selected_genders: Array,
    },

    data() {
      return {
        selected_suffix_list: this.selected_suffixes,
        selected_prefix_list: this.selected_prefixes,
        selected_gender_list: this.selected_genders,
        suffix_list: this.suffixes,
        prefix_list: this.prefixes,
        gender_list: this.genders,
        settings: this.settings_
      };
    },

    created() {
    },

    methods: {
      changePrefix(data){
        if(this.selected_prefix_list.length !== data.destination.length){
          axios.post('/app/'+ this.app.slug +'/auth/settings/save_prefix', {
            app_id: this.app.id,
            destination: data.destination
          })
          .then(res => {
            this.notifier.success('Prefixes successfully saved')
          })
        }
        this.prefix_list = data.source
        this.selected_prefix_list = data.destination
      },
      changeSuffix(data){
        if(this.selected_suffix_list.length !== data.destination.length){
          axios.post('/app/'+ this.app.slug +'/auth/settings/save_suffix', {
            app_id: this.app.id,
            destination: data.destination
          })
          .then(res => {
            this.notifier.success('Suffixes successfully saved')
          })
        }
        this.suffix_list = data.source
        this.selected_suffix_list = data.destination
      },
      changeGender(data){
        if(this.selected_gender_list.length !== data.destination.length){
          axios.post('/app/'+ this.app.slug +'/auth/settings/save_gender', {
            app_id: this.app.id,
            destination: data.destination
          })
          .then(res => {
            this.notifier.success('Genders successfully saved')
          })
        }
        this.gender_list = data.source
        this.selected_gender_list = data.destination
      },
      saveSettings() {
        axios.post('/app/'+ this.app.slug +'/auth/settings/store', {
          settings: this.settings
        })
        .then(res => {
          this.notifier.success("Settings successfully saved!")
        })
      },
    },
    mounted() {
    }
  };
</script>

<style lang="scss" >
  ::placeholder {
    color: #0997b1;
    opacity: 1; /* Firefox */
  }

  .head-elements {
    position: relative;
    width: 100%;
    .head-inputs {
      width: 100%;
      justify-content: flex-start;
      display: inline-block;
    }
  }
  .list-box-wrapper{
    .actions {
      display: block !important;
      margin-left: 0 !important;
      width: auto !important;
    }
  }
</style>
