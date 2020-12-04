<template>
  <div>
    <ul v-if="labelList.length">
      <li v-for="label in labelList" style="display: flex; flex-direction: row; justify-content: space-evenly">
        <template v-if="edit.id !== label.id">
          {{ label.title }}
        </template>
        <template v-if="edit.id === label.id || !label.id">
          <input type="text" v-model.trim="edit.title" @keyup.enter="">
          <a
            href=""
            @click.prevent="saveLabel"
          >
            <i class="fas fa-check" title="complete"></i>
          </a>
          <a
            href=""
            @click.prevent="cancelEdit"
            style="color: red"
          >
            <i class="fas fa-window-close" title="cancel"></i>
          </a>
        </template>
        <div class="action-group">
          <template v-if="label.id">
            <div class="actions">
              <ion-icon
                v-if="label.id"
                title="edit element"
                @click="editLabel(label)"
                name="create-outline"/>
            </div>
            <div class="actions">
              <ion-icon
                title="delete element"
                @click="deleteLabel(label.id)"
                name="trash-outline"/>
            </div>
          </template>
        </div>
      </li>
    </ul>
    <h4 v-else>You haven't labels.</h4>
    <ycms-action-buttons
      v-if="edit.id !== null"
      :buttons-list="[
      {
        title: 'ADD LABEL',
        handler: 'eval:this.$parent.$emit(`newLabel`)',
        class: 'bg-green-gradient'
      },
    ]"
      align="left"
    />
  </div>
</template>

<script>
  export default {
    name: "ycms-store-labels",
    data() {
      return {
        labelList: [],
        edit: {
          id: false,
          title: '',
          app_id: this.app.id
        }
      }
    },

    props: ['labels', 'app'],

    created() {
      this.labelList = _.cloneDeep(this.labels)
      this.$on('newLabel', this.createNewLabel)
    },

    methods: {

      createNewLabel(){
        this.labelList.push({title: '', id: null})
        this.edit.id = null;
      },

      editLabel(label){
        this.edit.id = label.id;
        this.edit.title = label.title;
      },

      cancelEdit(){
        this.edit.id = false;
        this.edit.title = '';
        this.labelList = _.filter(this.labelList, (val) => {
          return val.id !== null;
        })
      },

      saveLabel(){

        axios.post('/app/'+this.app.slug+'/label/save-label', this.edit)
          .then((res) => {
            if (res.data.success) {
              this.labelList = _.cloneDeep(res.data.responseData);
              this.edit.id = false;
              this.edit.title = '';
            }
          })

      },

      deleteLabel(id){
        axios.post('/app/'+this.app.slug+'/label/delete-label', {id: id})
          .then((res) => {
            if (res.data.success) {
              this.labelList = _.cloneDeep(res.data.responseData);
            }
          })
      }
    },

    destroyed() {
      this.$off('newLabel', this.createNewLabel);
    },

  }
</script>

<style lang="scss" scoped>


  .action-group {
    display: flex;
    flex-direction: row;
    margin-left: auto;
    min-width: 100px;
    font-size: 21px;
    padding: 5px;
  }

  .actions {

    color: #0997b1;
    margin-left: 10px;
    width: 30px;
    font-size: 21px;
  }
  ul {
    li {
      display: flex;
      flex-direction: row;
      justify-content: space-evenly;
      width: 50%;
      align-items: center;
      a {
        padding: 0 10px;
      }
    }
  }
</style>
