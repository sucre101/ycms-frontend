<template>
    <div class="blocks-grid">
        <div  v-for="(block, index) in blocks"
              :style="block.template?block.template.style:''"
              :key="block.id" :class="'bl-tooltip-box block-'+block.layout" >
        <span  class="bl-tooltip" :ref="'block-'+block.id" >
          <i v-if="!deleted && block.s_order !== 1" @click="changeBlockOrder(false, block.id)" class="fa fa-arrow-circle-left"></i>
          {{block.order}}-{{block.layout}}-layout
          <i v-if="!deleted" @click="openNewBlockModal(block)" class="fa fa-edit" ></i>
          <i v-if="!deleted"  @click="removeBlockConfirm(block.id)" class="fa fa-trash" ></i>
          <span v-if="!deleted"  @click="openNewElementModal(null, block.id)" class="fa fa-plus-circle" ></span>
          <span v-if="deleted"  @click="restoreBlockConfirm(block.id)" class="fa fa-trash-restore" ></span>
          <span v-if="deleted"  @click="deleteBlockConfirm(block.id)" class="fa fa-trash"></span>
          <i
              v-if="!deleted && blocks[index+1] && blocks[index+1].s_order > block.s_order"
              @click="changeBlockOrder(true, block.id)"

              class="fa fa-arrow-circle-right"
          ></i>
        </span>
            <span
                class="el-tooltip-box"
                :style="elem.template?'width:'+ elem.template.width:'width:100%'"
                v-for="elem in block.elements"
                :key="elem.id"
            >
            <span v-if="!deleted" class="el-tooltip"  :ref="'element-'+elem.id" >
              element - {{elem.type}}
              <i  @click="openNewElementModal(elem, block.id)" class="fa fa-edit"></i>
              <i  @click="deleteElementConfirm(elem.id, block.id)" class="fa fa-trash" ></i>
            </span>

          <div
              v-if="elem.type === 'html'"
              v-html="elem.content"
              :style="elem.template?elem.template.style:''"
          >
          </div>
           <img
               v-else-if="elem.type === 'image'"
               :style="elem.template?elem.template.style+'width:100%':''"
               :src="elem.images[0]?elem.images[0].image_url:''" width="100%"
           />
          <video-player
              v-else-if="elem.type === 'video'"
              :style="elem.template?elem.template.style+'width:100%':''"
              :src="elem.content">
          </video-player>
          <button
              v-else-if="elem.type === 'button'"
              :style="elem.template?elem.template.style+'width:100%':''"
              :href="JSON.parse(elem.content).button_link"
          >
            {{JSON.parse(elem.content).button_title}}
          </button>
          <iframe
              v-else-if="elem.type === 'iframe'"
              :style="elem.template?elem.template.style+'width:100%':''"
              :src="elem.content"
          >
          </iframe>
              <VueCarousel
                  v-else-if="elem.type === 'slider'"
                  :autoplay="false"
                  :data="getArray(elem)"
              >
              </VueCarousel>

          <div :style="elem.template?elem.template.style+'width:'+elem.template.width:'height:100%;width:100%'" v-else-if="elem.type === 'map'" >
              <GmapMap
                  :center="{lat:JSON.parse(elem.content).lat, lng:JSON.parse(elem.content).lng}"
                  :zoom="13"
                  map-type-id="terrain"
                  :style="elem.template?elem.template.style+'width:'+elem.template.width:'height:300px;width:100%'"
              >
                  <GmapMarker
                      :key="index"
                      :position="{
                          lat: JSON.parse(elem.content).lat,
                          lng: JSON.parse(elem.content).lng
                        }"
                  />
              </GmapMap>
          </div>
        </span>
            <span v-if="block.elements.length === 0">
          No elements found! <a v-if="!deleted" @click="openNewElementModal(null, block.id)">Create element</a>
      </span>
        </div>
    </div>
</template>

<script>
  import VideoPlayer from "../Element/VideoPlayer";
  import VueCarousel from '@chenfengyuan/vue-carousel';
  export default {
    name: "blocks-grid",

    components:{
      'video-player': VideoPlayer,
      VueCarousel,
    },
    props: {
      blocks: Array,
      deleted: Boolean,
    },
    data() {
      return {
      }
    },
    watch: {
      blocks: function (newv, oldv) {
        this.blocks = newv
      }
    },
    methods: {
      getArray(obj){
        let arr = [];
        let style;

        for(let i=0;i<obj.images.length;i++){
          style = obj.template?obj.template.style+'width:'+obj.template.width:'';
          arr.push('<img  style="'+style+'"  src="'+obj.images[i].image_url+'" />')
        }

        return arr;
      },
      goToPStep(st, template){
        if (template){
          this.style_ = template
        }
        this.p_step = st;
      },
      openNewBlockModal(block){
        this.$emit('open-block', block);
      },
      openNewElementModal(element, block_id){
        this.$emit('open-element', element, block_id);
      },
      restoreBlockConfirm(id){
        this.$emit('restore-block', id);
      },
      removeBlockConfirm(id){
        this.$emit('remove-block', id);
      },
      deleteBlockConfirm(id){
        this.$emit('delete-block', id);
      },
      deleteElementConfirm(id, block_id){
        this.$emit('delete-element', id, block_id);
      },
      changeBlockOrder(add, id){
        this.$emit('change-block-order', add, id);
      }
    },
    mounted() {
    }
  }
</script>

<style>
    .bl-tooltip-box {
        position: relative;
        display: inline-block;
    }

    .bl-tooltip-box:hover .bl-tooltip{
        opacity: 1;
    }

    .bl-tooltip {
        color: #ffffff;
        text-align: center;
        padding: 5px 0;
        border-radius: 2px;

        width: 180px;
        bottom: 100%;

        opacity: 0;
        transition: opacity 0.5s;

        position: absolute;
        z-index: 1;

        background: #1dc4e9;
    }
    .el-tooltip-box {
        position: relative;
        display: inline-block;
    }

    .el-tooltip-box:hover .el-tooltip{
        opacity: 1;
    }

    .el-tooltip {
        color: #ffffff;
        text-align: center;
        padding: 5px 0;
        border-radius: 2px;
        height: 30px;

        width: 180px;
        bottom: 100%;
        left: 100%;
        top: 1px;

        opacity: 0;
        transition: opacity 0.5s;

        position: absolute;
        z-index: 1;

        background: #2a6495;
    }

    .blocks-grid{
        width: 100%;
        display: flex;
        flex-direction: row;
        flex-wrap: wrap;
    }
    .bl-tooltip-box:hover{
        border: 1px solid black;
        border-radius: 5px;
        margin: 0.4%;
        padding: 5px;
        box-shadow: 1px 1px 7px black;
    }
    .block-1{
        width: 100%;
    }
    .block-2:hover{
        width: 50%;
    }
    .block-2{
        width: 49.2%;
    }
    .block-3{
        width: 33.3333%;
    }
    .block-3:hover{
        width: 32.53%;
    }
    .block-1-3:hover{
        width: 32.53%;
    }
    .block-1-3{
        width: 33.33333%;
    }
    .block-2-3{
        width: 66.6666%;
    }
    .block-2-3:hover{
        width: 65.87%;
    }
    .block-4{
        width: 25%;
    }
    .block-4:hover{
        width: 24.2%;
    }
    .block-1-4{
        width: 25%;
    }
    .block-1-4:hover{
        width: 24.2%;
    }
    .block-3-4:hover{
        width: 74.2%;
    }
    .block-3-4{
        width: 75%;
    }
    .block-item>div{
        width: 100%;
        overflow: scroll;
        -ms-overflow-style: none;  /* IE and Edge */
        scrollbar-width: none;  /* Firefox */
    }
    .block-item>div::-webkit-scrollbar {
        display: none;
    }
</style>
