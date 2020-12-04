<template>
  <div>
      <div v-if="units.length" v-for="unit in units" style="display: flex; flex-direction: row; justify-content: space-evenly">
        <template v-if="edit.id !== unit.id">
          {{ unit.name }} ({{ unit.type }})
        </template>
        <div class="action-group">
          <div class="actions">
            <ion-icon
              v-if="unit.id"
              title="edit element"
              @click="openUpdate(unit)"
              name="create-outline"/>
          </div>
          <div class="actions">
            <ion-icon
              title="delete element"
              @click="openDelete(unit.id)"
              name="trash-outline"/>
          </div>
        </div>
      </div>
    <h4 v-else>You haven't units.</h4>
    <a class="unit_add" @click="openCreate">Add Unit</a>
    <sweet-modal
      class="modal"
      ref="createModal"
      width="550"
      overlay-theme="dark"
    >
      <h4>Add Unit</h4>

      <input
        v-model="newUnit.name"
        class="rounded-input"
        width="100%"
        type="text"
        placeholder="Name of Specification Group"
      />
      <select v-model="newUnit.type" class="rounded-input">
        <option value="float">Float</option>
        <option value="varchar">Varchar</option>
      </select>
      <a v-if="newUnit.id" class="small-rounded-btn blue-bg text-white" @click="update">Update</a>
      <a v-else class="small-rounded-btn blue-bg text-white" @click="create">Create</a>
    </sweet-modal>
  </div>
</template>

<script>
  export default {
    name: 'ycms-store-product-units',

    props: ['unitList', 'app'],
    data() {
      return {
        units: this.unitList,
        newUnit: {
          id: '',
          name: '',
          type: ''
        }
      }
    },
    methods: {

      addUnit(){
        this.units.push({title: '', id: null})
        this.edit.id = null;
      },
      edit(unit){
        this.edit.id = unit.id;
        this.edit.title = unit.title;
      },
      cancelEdit(){
        this.edit.id = false;
        this.edit.title = '';
        this.units = _.filter(this.units, (val) => {
          return val.id !== null;
        })
      },
      openCreate(){
        this.$refs.createModal.open()
      },
      create(){
        axios.post('/app/'+this.app.slug+'/unit/store', this.newUnit)
        .then((res) => {
          if (res.data) {
            this.units = _.cloneDeep(res.data.units);
          }
          this.$refs.createModal.close()
          this.renewNewUnit()
        })
      },
      openUpdate(unit){
        this.newUnit.id = unit.id
        this.newUnit.name = unit.name
        this.newUnit.type = unit.type

        this.$refs.createModal.open()
      },
      update(){
        axios.post('/app/'+this.app.slug+'/unit/update', this.newUnit)
        .then((res) => {
          if (res.data) {
            this.units = _.cloneDeep(res.data.units);
          }
          this.$refs.createModal.close()
          this.renewNewUnit()
        })
      },
      openDelete(id){
        this.newUnit.id = id
        this.notifier.confirm('Are you sure?', this.destroy())
      },
      destroy(){
        axios.post('/app/'+this.app.slug+'/unit/destroy', {
          id: this.newUnit.id
        })
        .then((res) => {
          if (res.data.units) {
            this.units = _.cloneDeep(res.data.units);
          }
          this.renewNewUnit()
        })
      },
      renewNewUnit(){
        this.newUnit.id = ""
        this.newUnit.name = ""
        this.newUnit.type = ""
      }
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
  .unit_add{
    color: white;
    height: 30px;
    border-radius: 24px;
    font-size: 12px;
    font-weight: 300;
    padding: 6px 20px 0 20px;
    background-color: #0997b1;
    margin-top: 5px;
    margin-bottom: 5px;
    display: inline-block;
    cursor: pointer;
    position: relative;
  }
</style>
