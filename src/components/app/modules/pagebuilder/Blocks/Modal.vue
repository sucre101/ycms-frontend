<template>
  <div>
    <div v-show="p_step === 1">
      <span v-if="block_.id">
        <h6>Layout type</h6>
        {{block_.layout}}
        <br>
      </span>
      <span v-else>
        <h6>Choose layout</h6>
        <div class="block-types">
          <input id="radio-1" class="radio-custom" value="1"  v-model="block_.layout"  name="radio-1" type="radio" checked>
          <span>
            <label for="radio-1"  class="radio-custom-label radio-label-1" >1</label>
          </span>
        </div>
        <div  class="block-types">
          <input id="radio-2" class="radio-custom" value="2"  v-model="block_.layout"  name="radio-1" type="radio">
          <span >
            <label for="radio-2" class="radio-custom-label radio-label-2">1-2</label>
            <label for="radio-2" class="radio-custom-label radio-label-2">1-2</label>
          </span>
        </div>
        <div  class="block-types">
          <input id="radio-4" class="radio-custom" value="4"  v-model="block_.layout"  name="radio-1" type="radio">
          <span >
            <label for="radio-4" class="radio-custom-label radio-label-4">1-4</label>
            <label for="radio-4" class="radio-custom-label radio-label-4">1-4</label>
            <label for="radio-4" class="radio-custom-label radio-label-4">1-4</label>
            <label for="radio-4" class="radio-custom-label radio-label-4">1-4</label>
          </span>
        </div>
        <div  class="block-types">
          <input id="radio-1-4" class="radio-custom" value="1-4"  v-model="block_.layout"  name="radio-1" type="radio">
          <span >
            <label for="radio-1-4" class="radio-custom-label radio-label-4">1-4</label>
            <label for="radio-1-4" class="radio-custom-label radio-label-3-4">4-4</label>
          </span>
        </div>
        <div  class="block-types">
          <input id="radio-3-4" class="radio-custom" value="3-4"  v-model="block_.layout"  name="radio-1" type="radio">
          <span >
            <label for="radio-3-4" class="radio-custom-label radio-label-3-4">3-4</label>
            <label for="radio-3-4" class="radio-custom-label radio-label-4">1-4</label>
          </span>
        </div>
        <div  class="block-types">
          <input id="radio-3" class="radio-custom" value="3"  v-model="block_.layout"  name="radio-1" type="radio">
          <span >
            <label for="radio-3" class="radio-custom-label radio-label-3">1-3</label>
            <label for="radio-3" class="radio-custom-label radio-label-3">1-3</label>
            <label for="radio-3" class="radio-custom-label radio-label-3">1-3</label>
          </span>
        </div>
        <div  class="block-types">
          <input id="radio-1-3" class="radio-custom" value="1-3"  v-model="block_.layout"  name="radio-1" type="radio">
          <span >
            <label for="radio-1-3" class="radio-custom-label radio-label-3">1-3</label>
            <label for="radio-1-3" class="radio-custom-label radio-label-2-3">2-3</label>
          </span>
        </div>
        <div  class="block-types">
          <input id="radio-2-3" class="radio-custom" value="2-3"  v-model="block_.layout"  name="radio-1" type="radio">
          <span >
            <label for="radio-2-3" class="radio-custom-label radio-label-2-3">2-3</label>
            <label for="radio-2-3" class="radio-custom-label radio-label-3">1-3</label>
          </span>
        </div>
      </span>
      <a class="small-rounded-btn code-bg text-white"  @click="goToPStep(2)">Next <i class="fa fa-chevron-right"></i> </a>
    </div>
    <div v-show="p_step === 2">
      <h6>Choose template</h6>
      <div v-for="template in templates" v-bind:key="template.id">
        <input type="radio" v-model="block_.template_id"  name="template" :value="template.id" :id="'template'+template.id">
        <label :for="'template'+template.id">{{template.name}}</label>
      </div>
      <br>
      <a class="small-rounded-btn code-bg text-white"  @click="goToPStep(1)"> <i class="fa fa-chevron-left"></i> Back</a>
      <a v-if="block_.id" class="small-rounded-btn code-bg text-white"  @click="updateBlock">Update block</a>
      <a v-else class="small-rounded-btn code-bg text-white"  @click="storeBlock">Store block</a>
    </div>
  </div>
</template>
<script>
  export default {
    name: 'block-modal',
    props: {
      block: Object,
      templates: Array,
    },
    data() {
      return {
        p_step: 1,
        block_: this._.cloneDeep(this.block),
      }
    },
    watch: {
      block: function (newv, oldv) {
        this.block_ = newv
      }
    },
    methods: {
      goToPStep(st){
        this.p_step = st;
      },
      updateBlock(){
        this.blockDeleteAttr()
        this.$emit('update-block', this.block_);
      },
      storeBlock(){
        this.blockDeleteAttr()
        delete this.block_.id

        this.$emit('store-block', this.block_);
      },
      blockDeleteAttr(){
        delete this.block_.template
        delete this.block_.droppable
        delete this.block_.elements

        this.p_step = 1;
      }
    },
    mounted() {
    }
  }
</script>

<style>
  .block-types{
    width: 400px;
    margin: 0 auto;
  }

  .radio-custom {
    opacity: 0;
    position: absolute;
  }

  .radio-custom, .radio-custom-label{
    display: inline-block;
    vertical-align: middle;
    margin: 0 5px;
    cursor: pointer;
  }

  .radio-custom-label{
    position: relative;
    color: white;
  }



  /*.radio-custom:focus + span> .radio-custom-label {*/
  /*  outline: 1px solid #ddd; !* focus style *!*/
  /*}*/
  .radio-custom + span > .radio-label-1{
    width: 96%;
    content: '1';
    background: #0997b1;
    border: 2px solid #ddd;
    display: inline-block;
    vertical-align: middle;
    height: 50px;
    padding: 10.5px;

    text-align: center;
  }
  .radio-custom + span > .radio-label-2{
    width: 46.7%;
    content: '1-2';
    background: #0997b1;
    border: 2px solid #ddd;
    display: inline-block;
    vertical-align: middle;
    height: 50px;
    padding: 10.5px;

    text-align: center;
  }
  .radio-custom + span > .radio-label-3{
    width: 30.3%;
    content: '1-3';
    background: #0997b1;
    border: 2px solid #ddd;
    display: inline-block;
    vertical-align: middle;
    height: 50px;
    padding: 10.5px;

    text-align: center;
  }
  .radio-custom + span > .radio-label-2-3{
    width: 63%;
    content: '2-3';
    background: #0997b1;
    border: 2px solid #ddd;
    display: inline-block;
    vertical-align: middle;
    height: 50px;
    padding: 10.5px;

    text-align: center;
  }
  .radio-custom + span > .radio-label-4{
    width: 22.1%;
    content: '1-4';
    background: #0997b1;
    border: 2px solid #ddd;
    display: inline-block;
    vertical-align: middle;
    height: 50px;
    padding: 10.5px;

    text-align: center;
  }
  .radio-custom + span > .radio-label-3-4{
    width: 71.2%;
    content: '3-4';
    background: #0997b1;
    border: 2px solid #ddd;
    display: inline-block;
    vertical-align: middle;
    height: 50px;
    padding: 10.5px;

    text-align: center;
  }

  .radio-custom:checked + span> .radio-custom-label {
    content: "";
    background: #0997f9;
    color: #fff;
  }
</style>
