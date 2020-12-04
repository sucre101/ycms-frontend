<template>
  <div>
    <a class="small-rounded-btn blue-bg text-white" :href="'/app/'+this.app.slug+'/shop-categories/'+category_id+'/edit'">Go Back</a>
    <div class="flex-row-top">
      <div v-if="groups.length === 0">No Specification group found</div>
      <div v-else class="spec-groups"
           v-for="group in groups"
      >
        <div class="actions">
          <a v-if="group.specs.length" @click="toggleFilter('group_'+group.id)">
            <ion-icon :ref="'group_'+group.id+'open'" name="chevron-down-outline"></ion-icon>
            <ion-icon :ref="'group_'+group.id+'close'" name="chevron-forward-outline" hidden></ion-icon>
          </a>
          {{ group.name }}
        </div>
        <div :ref="'group_'+group.id">
          <div class="specs" v-for="spec in group.specs" >
            <div class="actions">
              <a  v-if="spec.filter" @click="toggleFilter('spec_'+spec.id)">
                <ion-icon :ref="'spec_'+spec.id+'open'" name="chevron-down-outline"></ion-icon>
                <ion-icon :ref="'spec_'+spec.id+'close'" name="chevron-forward-outline" hidden></ion-icon>
              </a>
              {{spec.name}}
              <span v-if="!spec.filter" class="icons">
                <ion-icon   name="add-outline" @click="showModalFilter(spec.id, null)"></ion-icon>
              </span>
              <div :ref="'spec_'+spec.id">
                <div class="specs" v-if="spec.filter">
                  Filter type: {{spec.filter.display_type}}; Selectable: {{spec.filter.selectable}}
                  <span class="icons">
                    <ion-icon   name="create-outline" @click="showModalFilter(spec.id,spec.filter)"></ion-icon>
                    <ion-icon name="duplicate-outline" @click="showModalFilterExtra(spec.filter.id, null)"></ion-icon>
                    <ion-icon name="color-filter-outline" @click="showModalFilterGrouping(spec.filter.id)"></ion-icon>
                    <ion-icon @click="showDeleteFilterWarning(spec.filter.id)" name="trash-outline"></ion-icon>
                  </span>
                  <div v-if="spec.filter.extras.length"> Extras:
                    <div   class="extras" v-for="extra in spec.filter.extras">
                      <div class="actions">
                        type: {{extra.type}}
                        name:{{extra.show}} <span v-if="extra.show !== 'always'">n:{{extra.n}}</span>
                        <span class="icons">
                        <ion-icon   name="create-outline" @click="showModalFilterExtra(extra.filter_id, extra)"></ion-icon>
                        <ion-icon @click="showDeleteExtraWarning(extra.id)" name="trash-outline"></ion-icon>
                      </span>
                      </div>
                    </div>
                  </div>

                  <div v-if="spec.filter.groupings.length"> Groupings:
                    <div  class="groupings" v-for="grouping in spec.filter.groupings">
                      <div class="actions">
                        name: {{grouping.name}}
                        More than:{{grouping.more_than}} Less than:{{grouping.less_than}}
                        <span class="icons">
                        <ion-icon name="create-outline" @click="showModalFilterGrouping(grouping.filter_id, grouping)"></ion-icon>
                        <ion-icon @click="showDeleteGroupWarning(grouping.id)" name="trash-outline"></ion-icon>
                      </span>
                      </div>
                    </div>
                  </div>
                </div>

              </div>
            </div>
          </div>
        </div>
      </div>
      <sweet-modal
        class="modal"
        ref="newFilterModal"
        width="550"
        overlay-theme="dark"
      >
        <h4>Filter form</h4>
        <select class="rounded-select" v-model="filterForm.display_type">
          <option value="list">list</option>
          <option value="button">button</option>
        </select>
        <select class="rounded-select" v-model="filterForm.selectable">
          <option value="one">one</option>
          <option value="many">many</option>
        </select>
        <a class="small-rounded-btn blue-bg text-white" @click="createFilter">{{filterForm.id?'Update':'Create'}}</a>
      </sweet-modal>
      <sweet-modal
        class="modal"
        ref="newFilterExtraModal"
        width="550"
        overlay-theme="dark"
      >
        <h4>Filter Extra Form</h4>
        <select class="rounded-select" v-model="filterExtras.type">
          <option value="search">search</option>
          <option value="fromTo">from-to</option>
          <option value="range">range</option>
          <option value="showAll">show all</option>
        </select>
        <select class="rounded-select" v-model="filterExtras.show">
          <option value="always">always</option>
          <option value="auto">auto</option>
        </select>
        <input type="number" class="rounded-input" v-model="filterExtras.n" placeholder="Maximum visible" /><br>
        <a class="small-rounded-btn blue-bg text-white" @click="createFilterExtra">{{filterExtras.id?'Update':'Create'}}</a>
      </sweet-modal>
      <sweet-modal
        class="modal"
        ref="newFilterGroupingModal"
        width="550"
        overlay-theme="dark"
      >
        <h4>Filter Group Form</h4>
        <input type="text" class="rounded-input" v-model="filterGroups.name" placeholder="Name" />
        <input type="number" class="rounded-input" v-model="filterGroups.more_than" placeholder="More than" />
        <input type="number" class="rounded-input" v-model="filterGroups.less_than" placeholder="Less than" /><br>
        <a class="small-rounded-btn blue-bg text-white" @click="createFilterGrouping">{{filterGroups.id?'Update':'Create'}}</a>
      </sweet-modal>
    </div>
  </div>
</template>

<script>
  export default {
    props: {
      groupList: Array,
      app: Object,
      category_id: Number,
    },

    data() {
      return {
        groups: this.groupList,
        filterForm:{
          id: null,
          spec_id: null,
          display_type: null,
          selectable: null,
        },
        filterExtras:{
          id: null,
          filter_id: null,
          type: null,
          show: null,
          n: null,
        },
        filterGroups:{
          id: null,
          name: null,
          filter_id: null,
          more_than: null,
          less_than: null,
        },
        toDelete: null,
        toggle_spec: true,
      };
    },

    methods: {
      toggleFilter(id){
        this.$refs[id][0].hidden = !this.$refs[id][0].hidden;
        this.$refs[id+"open"][0].hidden = this.$refs[id][0].hidden;
        this.$refs[id+"close"][0].hidden = !this.$refs[id][0].hidden;
      },
      showModalFilter(id, filt){
        this.filterForm.spec_id = id;

        if (filt){
          this.filterForm.id = filt.id;
          this.filterForm.display_type = filt.display_type;
          this.filterForm.selectable = filt.selectable;
        }

        this.$refs.newFilterModal.open()
      },
      showModalFilterExtra(id, extra){
        this.filterExtras.filter_id = id;

        if (extra){
          this.filterExtras.id = extra.id;
          this.filterExtras.type = extra.type;
          this.filterExtras.show = extra.show;
          this.filterExtras.n = extra.n;
        }
        this.$refs.newFilterExtraModal.open()
      },
      showModalFilterGrouping(id, grouping){
        this.filterGroups.filter_id = id;

        if (grouping){
          this.filterGroups.id = grouping.id;
          this.filterGroups.less_than = grouping.less_than;
          this.filterGroups.more_than = grouping.more_than;
          this.filterGroups.name = grouping.name;
        }

        this.$refs.newFilterGroupingModal.open()
      },
      createFilter(){
        axios.post('/app/' + this.app.slug + '/shop-categories/filter-save', this.filterForm)
        .then((res) => {
          if(res.data.groups){
            this.groups = res.data.groups
          }

          this.filterForm.id = null;
          this.filterForm.spec_id = null;
          this.filterForm.display_type = null;
          this.filterForm.selectable = null;

          this.$refs.newFilterModal.close()
          this.notifier.success('filter added successfully')
        });
      },
      createFilterExtra(){
        axios.post('/app/' + this.app.slug + '/shop-categories/filter-extra-save', this.filterExtras)
        .then((res) => {
          if(res.data.groups){
            this.groups = res.data.groups
          }

          this.filterExtras.id = null;
          this.filterExtras.filter_id = null;
          this.filterExtras.type = null;
          this.filterExtras.show = null;
          this.filterExtras.n = null;

          this.$refs.newFilterExtraModal.close()
          this.notifier.success('filter extra added successfully')
        });
      },
      createFilterGrouping(){
        axios.post('/app/' + this.app.slug + '/shop-categories/filter-grouping-save', this.filterGroups)
        .then((res) => {
          if(res.data.groups){
            this.groups = res.data.groups
          }

          this.filterGroups.id = null;
          this.filterGroups.filter_id = null;
          this.filterGroups.less_than = null;
          this.filterGroups.more_than = null;
          this.filterGroups.name = null;

          this.$refs.newFilterGroupingModal.close()
          this.notifier.success('filter grouping added successfully')
        });
      },
      showDeleteFilterWarning(id) {
        this.toDelete = id;
        this.notifier.confirm('Are you sure?', this.deleteFilter)
      },
      showDeleteExtraWarning(id) {
        this.toDelete = id;
        this.notifier.confirm('Are you sure?', this.deleteFilterExtra)
      },
      showDeleteGroupWarning(id) {
        this.toDelete = id;
        this.notifier.confirm('Are you sure?', this.deleteFilterGroup)
      },
      deleteFilter(){
        axios.post('/app/' + this.app.slug + '/shop-categories/filter-delete', {
          id: this.toDelete
        })
        .then((res) => {
          if(res.data.groups){
            this.groups = res.data.groups
          }
          this.notifier.success('filter deleted successfully')
        });
      },
      deleteFilterExtra(){
        axios.post('/app/' + this.app.slug + '/shop-categories/filter-extra-delete', {
          id: this.toDelete
        })
        .then((res) => {
          if(res.data.groups){
            this.groups = res.data.groups
          }
          this.notifier.success('filter extra deleted successfully')
        });
      },
      deleteFilterGroup(){
        axios.post('/app/' + this.app.slug + '/shop-categories/filter-group-delete', {
          id: this.toDelete
        })
        .then((res) => {
          if(res.data.groups){
            this.groups = res.data.groups
          }
          this.notifier.success('filter grouping deleted successfully')
        });
      },
    },
    mounted() {
    }
  };
</script>

<style lang="scss" scoped>


  .spec-groups {
    margin-top: 30px;
    padding: 5px;
    background-color: rgba(#868686, .06);

    .spec_group_name {
      font-weight: bold;
      font-size: 18px;
    }

    .specs {
      margin-left: 50px;

      .extras {
        justify-content: flex-start;
        margin-left: 50px;
      }
      .groupings {
        justify-content: flex-start;
        margin-left: 50px;
      }
    }
  }
  .actions {
    display: block;
    margin-left: 20px;
    min-width: 600px;
    color: #0a1520;
    .icons{
      color: #0997b1;
    }
  }
  .title {
    justify-content: flex-start;
    width: 30%;
    height: 31px;
    font-size: 14px;
    font-weight: 300;
    padding-left: 14px;
  }
  .rounded-select {
    width: 346px;
    height: 43px;
    margin-left: auto;
    font-size: 14px;
    font-weight: 300;
    color: #0997b1;
    border-radius: 22px;
    border: solid 1px #868686;
    background-color: white;
    margin-bottom: 14px;
    outline: none;
    padding-left: 15px;
  }
</style>
