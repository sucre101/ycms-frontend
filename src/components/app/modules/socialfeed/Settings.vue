<template>
  <div >
    <InnerTab :items="[
        { name: 'Settings' },
        // { name: 'Templates' },
        // { name: 'Deleted Blocks' },
    ]"
              @change="selectTab"
    />
    <div  v-if="selectedTab === 0">
      <div class="col ">
        <div class="form-group">
          <div class="input-field">
            <label>Can message</label>
            <toggle-check :value="settings.can_message" @complete="switchActive('can_message')"/>
          </div>
          <div class="input-field">
            <label>Can block</label>
            <toggle-check :value="settings.can_block" @complete="switchActive('can_block')"/>
          </div>
          <div class="input-field">
            <label>Can react to post</label>
            <toggle-check :value="settings.can_react" @complete="switchActive('can_react')"/>
          </div>
          <div class="input-field">
            <label>Can react to comment</label>
            <toggle-check :value="settings.can_comment_react" @complete="switchActive('can_comment_react')"/>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>
<script>
  import ToggleCheck from "../../../base/ui/ToggleCheck";
  import InnerTab from "@/components/base/ui/InnerTab";

  export default {
    name: 'Settings',
    props: {
    },
    components:{
      ToggleCheck, InnerTab
    },
    data() {
      return {
        selectedTab: 0,
        module: {},
        settings: {
          can_message: true,
          can_block: true,
          can_react: true,
          can_comment_react: true,
        }
      }
    },
    created() {
      this.module.id = this.$parent.moduleId
      this.loadPage()
    },
    methods: {
      selectTab(index) {
        this.selectedTab = index
      },
      loadPage() {
        // axios.get(`/${this.$route.params.folder.toLowerCase()}/${this.module.id}/settings`)
        // .then((res) => {
        //   this.settings = res.data.settings
        // })
      },
      switchActive(type) {
        switch (type) {
          case 'can_message':
            this.settings.can_message = !this.settings.can_message;
            break;
          case 'can_block':
            this.settings.can_block = !this.settings.can_block;
            break;
          case 'can_react':
            this.settings.can_react = !this.settings.can_react;
            break;
          case 'can_react_comment':
            this.settings.can_react_comment = !this.settings.can_react_comment;
            break;
        }
      },
      updateSettings() {
        // axios.post(`/${this.$route.params.folder.toLowerCase()}/${this.module.id}/settings`, this.settings)
        // .then((res) => {
        //   this.settings = res.data.settings
        // })
      },
    },
  }
</script>

<style scoped lang="scss">

  .col {
    margin-top: 30px;
    display: flex;

    .form-group {
      display: flex;
      width: 100%;
      flex-wrap: wrap;

      .input-field {
        width: 100%;
        display: flex;
        flex-direction: column;
        align-items: flex-start;
        justify-content: center;

        input {
          width: 70%;
          border-radius: 22px;
          border: solid 1px #868686;
          color: #0997b1;
          font-size: 16px;
          padding: 5px 20px;
        }

        input::-webkit-input-placeholder {
          color: #0997b1;
        }

        input:focus {
          border-width: 1.5px;
          border-color: #0997b1;
          outline: none;
        }
      }
    }
  }
</style>
