<template>
  <div>
    <a  class="small-rounded-btn blue-bg " @click="openNewBlockModal(null)">Add Block</a>
    <a v-if="toggle" class="small-rounded-btn blue-bg " @click="toggleBlocks(false)">Collapse Blocks</a>
    <a v-else class="small-rounded-btn blue-bg " @click="toggleBlocks(true)">Expand Blocks</a>
    <div class="blocks-list">

      <Tree  v-if="!toggle"  :data="blocks_tree" :indent="60" ref="groupTree" :draggable="true"  @change="onDropBlock" >
        <div slot-scope="{data, store, vm}" >
          <template v-if="!data.isDragPlaceHolder">
            <span>
              {{data.order}}-{{data.layout}}-layout
              <ion-icon @click="deleteBlockConfirm(data.id)" name="trash-outline"></ion-icon>
            </span>
          </template>
        </div>
      </Tree>
      <div v-else v-for="(block, index) in blocks" :key="block.id" :class="'block-item block-'+block.layout"  >
        <span  class="block-items" >
          <ion-icon v-if="block.s_order !== 1" @click="changeBlockOrder(false, block.id)" name="arrow-back-outline"></ion-icon>
          {{block.order}}-{{block.layout}}-layout
          <ion-icon @click="openNewBlockModal(block)" name="create-outline"></ion-icon>
          <ion-icon @click="deleteBlockConfirm(block.id)" name="trash-outline"></ion-icon>
          <ion-icon @click="openNewElementModal(null, block.id)" name="add-outline"></ion-icon>
          <ion-icon
            v-if="blocks[index+1] && blocks[index+1].s_order > block.s_order"
            @click="changeBlockOrder(true, block.id)"
            style="float: right"
            name="arrow-forward-outline"
          >
          </ion-icon>
        </span>
        <span
          class="element-items"
          :style="elem.template?'width:'+ elem.template.width:''"
          v-for="elem in block.elements"
          :key="elem.id"
        >
            <span class="element-icons">
              element - {{elem.type}}
              <ion-icon @click="openNewElementModal(elem, block.id)" name="create-outline"></ion-icon>
              <ion-icon @click="deleteElementConfirm(elem.id)" name="trash-outline"></ion-icon>
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
             :src="elem.content" width="100%"
           />
          <ycms-video-player
              v-else-if="elem.type === 'video'"
              :style="elem.template?elem.template.style+'width:100%':''"
              :src="elem.content">
          </ycms-video-player>
          <button
            v-else-if="elem.type === 'button'"
            :style="elem.template?elem.template.style+'width:100%':''"
          >
            {{elem.content}}
          </button>
        </span>
      </div>
    </div>
    <sweet-modal
      class="modal"
      ref="blockModal"
      width="850"
      overlay-theme="dark"
    >
      <div v-show="p_step === 1">
        <h6>Choose layout</h6>
        <input type="radio" name="layout" v-model="block_.layout" value="1" id="layout-1" /><label for="layout-1">1</label><br>
        <input type="radio" name="layout" v-model="block_.layout" value="2" id="layout-2" /><label for="layout-2">1/2 | 1/2</label><br>
        <input type="radio" name="layout" v-model="block_.layout" value="3" id="layout-3" /><label for="layout-3">1/3 | 1/3 | 1/3</label><br>
        <input type="radio" name="layout" v-model="block_.layout" value="4" id="layout-4" /><label for="layout-4">1/4 | 1/4 | 1/4 | 1/4</label><br>
        <a class="small-rounded-btn code-bg text-white"  @click="goToPStep(2)">Next <i class="fa fa-chevron-right"></i> </a>
      </div>
      <div v-show="p_step === 2">
        <h6>Choose template</h6>
        <div v-for="template in templates" v-bind:key="template.id">
          <input type="radio" v-model="block_.template_id"  name="template" :value="template.id" :id="'template'+template.id">
          <label :for="'template'+template.id">{{template.name}}</label>
          <ion-icon @click="openTemplateModal(template,'block')" name="create-outline"></ion-icon>
        </div>
        <div class="text-muted"> If you don't find needed style, create your own</div>
        <a class="small-rounded-btn code-bg text-white"  @click="openTemplateModal(null, 'block')">Create template</a>
        <br>
        <a class="small-rounded-btn code-bg text-white"  @click="goToPStep(1)"> <i class="fa fa-chevron-left"></i> Back</a>
        <a v-if="block_.id" class="small-rounded-btn code-bg text-white"  @click="updateBlock">Update block</a>
        <a v-else class="small-rounded-btn code-bg text-white"  @click="storeBlock">Store block</a>
      </div>
    </sweet-modal>
    <sweet-modal
      class="modal"
      ref="templateModal"
      width="850"
      overlay-theme="dark"
    >
      <h6>Choose style properties</h6>
      <label for="st_name">Name</label>
      <input class="rounded-input" v-model="style_.name" placeholder="name" id="st_name" />
      <label for="st_width">Width</label>
      <input class="rounded-input" v-model="style_.width" placeholder="width"  id="st_width" />
      <label for="st_height">Height</label>
      <input class="rounded-input" v-model="style_.height" placeholder="height" id="st_height" />
      <label for="st_margin">Margin</label>
      <input class="rounded-input" v-model="style_.margin" placeholder="margin" id="st_margin" />
      <label for="st_padding">Padding</label>
      <input class="rounded-input" v-model="style_.padding" placeholder="padding" id="st_padding" />
      <label for="st_border_width">Border width</label>
      <input class="rounded-input" v-model="style_.border_width" placeholder="border width" id="st_border_width" />
      <label for="st_border_color">Border color</label>
      <input type="color" v-model="style_.border_color" id="st_border_color" />
      <label for="st_border_style">Border style</label>
      <input class="rounded-input" v-model="style_.border_type" placeholder="border style"  id="st_border_style"/>
      <label for="st_border_radius">Border radius</label>
      <input class="rounded-input" v-model="style_.border_radius" placeholder="border radius"  id="st_border_radius" />
      <label for="st_color">Color</label>
      <input type="color" v-model="style_.color"  id="st_color"/>
      <label for="st_bg_color">Background color</label>
      <input type="color" v-model="style_.bg_color"  id="st_bg_color"/>
      <label for="st_text_shadow">Text shadow</label>
      <input class="rounded-input" v-model="style_.text_shadow" placeholder="text shadow"  id="st_text_shadow" />
      <label for="st_box_shadow">Box shadow</label>
      <input class="rounded-input" v-model="style_.box_shadow" placeholder="box shadow" id="st_box_shadow" />
      <label for="st_overflow">Overflow</label>
      <input class="rounded-input" v-model="style_.overflow" placeholder="overflow"  id="st_overflow" />
      <label for="st_text_align">Text align</label>
      <input class="rounded-input" v-model="style_.text_align" placeholder="text align "  id="st_text_align" />
      <a class="small-rounded-btn code-bg text-white"  @click="closeTemplateModal()"> <i class="fa fa-chevron-left"></i> Back</a>
      <a v-if="style_.id" class="small-rounded-btn code-bg text-white"  @click="updateTemplate">Update template</a>
      <a v-else class="small-rounded-btn code-bg text-white"  @click="storeTemplate">Store template</a>
    </sweet-modal>
    <sweet-modal
      class="modal"
      ref="elementModal"
      width="850"
      overlay-theme="dark"
    >
      <div v-show="step === 1">
        <h6>Element type</h6>
        <input type="radio" name="elementType" v-model="element_.type" value="image" id="elementImage"  /> <label for="elementImage">Image</label>
        <input type="radio" name="elementType" v-model="element_.type" value="html" id="elementHtml"  /> <label for="elementHtml">Html</label>
        <input type="radio" name="elementType" v-model="element_.type" value="video" id="elementVideo"  /> <label for="elementVideo">Video</label>
        <input type="radio" name="elementType" v-model="element_.type" value="button" id="elementButton"  /> <label for="elementButton">Button</label>
        <input type="radio" name="elementType" v-model="element_.type" value="map" id="elementMap"  /> <label for="elementMap">Map</label>

        <div v-if="element_.type === 'image'">
          <input type="file"  />
        </div>
        <div v-else-if="element_.type === 'html'">
          <ckeditor :editor="editor" v-model="element_.content" ></ckeditor>
        </div>
        <div v-else-if="element_.type === 'video'">
          <input type="text" v-model="element_.content" class="rounded-input">
          <br>
          <ycms-video-player
            v-if="element_.content"
            :src="element_.content"></ycms-video-player>
        </div>
        <div v-else-if="element_.type === 'button'">
          <input type="text" v-model="element_.content" class="rounded-input">
          <br>
          <button
            :style="element_.template?element_.template.style+'width:'+element_.template.width:''"
          >{{element_.content}}</button>
        </div>
        <div v-else-if="element_.type === 'map'">
          <input type="text" v-model="element_.content" class="rounded-input">
        </div>
        <a class="small-rounded-btn code-bg text-white"  @click="goToStep(2)"> Next <i class="fa fa-chevron-right"></i></a>
      </div>
      <div v-show="step === 2">
        <h6>Template</h6>
        <div class="flex" v-for="template in templates" v-bind:key="template.id">
          <input type="radio" v-model="element_.template_id"  name="template" :value="template.id" :id="'temp'+template.id">
          <label :for="'temp'+template.id">{{template.name}}</label>
          <ion-icon @click="openTemplateModal(template,'element')" name="create-outline"></ion-icon>
        </div>
        <div class="text-muted"> If you don't find needed style, create your own</div>
        <a class="small-rounded-btn code-bg text-white"  @click="openTemplateModal(null,'element')">Create template</a>
        <br>
        <a class="small-rounded-btn code-bg text-white"  @click="goToStep(1)"> <i class="fa fa-chevron-left"></i> Back</a>
        <a v-if="element_.id" class="small-rounded-btn code-bg text-white"  @click="updateElement">Update element</a>
        <a v-else class="small-rounded-btn code-bg text-white"  @click="storeElement">Store element</a>
      </div>
    </sweet-modal>
  </div>
</template>
<script>
  import CKEditor from '@ckeditor/ckeditor5-vue';
  import ClassicEditor from '@ckeditor/ckeditor5-build-classic';
  import {DraggableTree} from 'vue-draggable-nested-tree'
  import * as th from 'tree-helper'
  import { getIdFromURL } from 'vue-youtube-embed'

  export default {
    name: 'ycms-page-builder-list',
    props: {
      app: Object,
      page: Object,
      templates_: Array,
    },
    components:{
      ckeditor: CKEditor.component,
      Tree: DraggableTree
    },
    data() {
      return {
        editor: ClassicEditor,
        blocks: this.page.blocks,
        blocks_tree: this.page.blocks.filter(p => p.s_order === 1),
        templates: this.templates_,
        deleteIdBlock: null,
        deleteIdElement: null,
        step: 1,
        p_step: 1,
        toggle: true,
        template_type: null,
        block_: {
          page_id: this.page.id,
          layout: 1,
          is_active: true,
          template_id: null,
          order: 1,
        },
        element_: {
          type: 'image',
          block_id: '',
          template_id: '',
          content: '',
        },
        style_: {
          app_id: this.app.id,
          width: '',
          height: '',
          border_width: '',
          border_color: '',
          border_type: '',
          border_radius: '',
          margin: '',
          padding: '',
          color: '',
          text_shadow: '',
          box_shadow: '',
          overflow: '',
          text_align: '',
          name: '',
        }
      }
    },
    methods: {
      setUndroppable(){
        th.depthFirstSearch(this.blocks_tree, (childNode) => {
          childNode.droppable = false;
        })
      },
      setBlocks(blocks){
        this.blocks = blocks;
        this.blocks_tree = this.blocks.filter(p => p.s_order === 1);

        this.setUndroppable()
      },
      toggleBlocks(arg){
        this.toggle = arg;
      },
      onDropBlock(node){
        let index = this.blocks_tree.map(x => {
          return x.id;
        }).indexOf(node.id)

        axios.post('/app/'+ this.app.slug +'/page-builder/block/change_block',{
          "order": index+1,
          "id": node.id,
        })
        .then(res => {
          this.setBlocks(res.data.blocks)
          this.notifier.success('Block\'s order changed successfully')
        })

      },
      changeBlockOrder(add, id){
        axios.post('/app/'+ this.app.slug +'/page-builder/block/change_block_order',{
          "add": add,
          "id": id,
        })
        .then(res => {
          this.setBlocks(res.data.blocks)
          this.notifier.success('Block\'s order changed successfully')
        })

      },
      openNewBlockModal(block){
        if(block){
          this.block_ = block;
        }
        this.$refs.blockModal.open()
      },
      openTemplateModal(template, type){
        this.setNull('style')
        if(template){
          this.style_ = template;
        }
        this.template_type = type;
        if(this.template_type === 'block'){
          this.$refs.blockModal.close()
        }else if(this.template_type === 'element'){
          this.$refs.elementModal.close()
        }
        this.$refs.templateModal.open()
      },
      closeTemplateModal(){
        this.$refs.templateModal.close()
        if(this.template_type === 'block'){
          this.$refs.blockModal.open()
        }else if(this.template_type === 'element'){
          this.$refs.elementModal.open()
        }

        this.template_type = null;
      },
      openNewElementModal(element, block_id){
        if(element){
          this.element_ = element;
        }
        this.element_.block_id = block_id;
        this.$refs.elementModal.open()
      },
      deleteBlockConfirm(id){
        this.deleteIdBlock = id;
        this.notifier.confirm('All blocks and elements in the row will be deleted. Are you sure?', this.deleteBlock())
      },
      deleteElementConfirm(id){
        this.deleteIdElement = id;
        this.notifier.confirm('Are you sure?', this.deleteElement())
      },
      goToStep(st, template){
        if (template){
          this.style_ = template
        }
        this.step = st;
      },
      goToPStep(st, template){
        if (template){
          this.style_ = template
        }
        this.p_step = st;
      },
      storeBlock(){
        let block = this.block_;
        delete block.template;
        delete block.elements;

        axios.post('/app/'+ this.app.slug +'/page-builder/block/store', {
          block: block,
          style: this.style_,
        })
        .then(res => {
          if(res.data.success === true){
            this.setBlocks(res.data.blocks)
            this.notifier.success('Block created successfully')
          }else{
            this.notifier.success('Error')
          }
          this.$refs.blockModal.close()
          this.setNull();
        });
      },
      updateBlock(){
        let block = this.block_;
        delete block.template;
        delete block.elements;
        axios.post('/app/'+ this.app.slug +'/page-builder/block/update', {
          block: block,
          style: this.style_
        })
        .then(res => {
          if(res.data.success === true){
            this.setBlocks(res.data.blocks)
            this.notifier.success('Block updated successfully')
          }else{
            this.notifier.success('Error')
          }
          this.$refs.blockModal.close()
          this.setNull();
        });
      },
      storeElement(){
        let index = this.blocks.map(x => {
          return x.id;
        }).indexOf(this.element_.block_id)

        let element = this.element_;

        if(element.type === 'video'){
          element.content = getIdFromURL(element.content)
        }

        axios.post('/app/'+ this.app.slug +'/page-builder/element/store', {
          element: element,
          style: this.style_,
        })
        .then(res => {
          if(res.data.success === true){
            this.blocks[index].elements = res.data.elements
            this.notifier.success('Element created successfully')
          }else{
            this.notifier.success('Error')
          }
          this.$refs.elementModal.close()
          this.setNull();
        });
      },
      updateElement(){
        let element = this.element_;
        delete element.template;

        if(element.type === 'video'){
          element.content = getIdFromURL(element.content)
        }

        let index = this.blocks.map(x => {
          return x.id;
        }).indexOf(this.element_.block_id)

        axios.post('/app/'+ this.app.slug +'/page-builder/element/update', {
          element: element,
          style: this.style_
        })
        .then(res => {
          if(res.data.success === true){
            this.blocks[index].elements = res.data.elements
            this.notifier.success('Element updated successfully')
          }else{
            this.notifier.success('Error')
          }
          this.$refs.elementModal.close()
          this.setNull();
        });
      },
      storeTemplate(){
        let style = this.style_;
        delete style.style;
        axios.post('/app/'+ this.app.slug +'/page-builder/template/store', {
          style: style,
        })
        .then(res => {
          if(res.data.success === true){
            this.templates = res.data.templates
            this.notifier.success('Template created successfully')
          }else{
            this.notifier.success('Error')
          }
          this.closeTemplateModal()
        });
      },
      updateTemplate(){
        let style = this.style_;
        delete style.style;
        axios.post('/app/'+ this.app.slug +'/page-builder/template/update', {
          style: style,
        })
        .then(res => {
          if(res.data.success === true){
            this.templates = res.data.templates
            this.notifier.success('Template updated successfully')
          }else{
            this.notifier.success('Error')
          }
          this.closeTemplateModal()
        });
      },
      deleteBlock(){
        axios.post('/app/'+ this.app.slug +'/page-builder/block/delete', {
          id: this.deleteIdBlock,
        })
        .then(res => {
          if(res.data.success === true){
            this.notifier.success('Block deleted successfully')
            this.setBlocks(res.data.blocks)
            this.newPage = '';
          }else{
            this.notifier.success('Error')
          }
        });
        this.setNull();
      },
      deleteElement(){
        axios.post('/app/'+ this.app.slug +'/page-builder/element/delete', {
          id: this.deleteIdElement,
        })
        .then(res => {
          if(res.data.success === true){
            this.notifier.success('Element deleted successfully')
            this.blocks = res.data.blocks
            this.newPage = '';
          }else{
            this.notifier.success('Error')
          }
        });
        this.setNull();
      },
      setNull(style){
        if(style !== 'style'){
          this.block_.page_id = this.page.id;
          this.block_.layout = 1;
          this.block_.is_active = true;
          this.block_.template_id = null;
          this.block_.order = 1;

          this.element_.type = 'image';
          this.element_.block_id = null;
          this.element_.template_id = null;
          this.element_.content = null;

          this.step = 1;
          this.p_step = 1;
        }

        this.style_.app_id = this.app.id;
        this.style_.width = null;
        this.style_.height = null;
        this.style_.border_width = null;
        this.style_.border_color = null;
        this.style_.border_type = null;
        this.style_.border_radius = null;
        this.style_.margin = null;
        this.style_.padding = null;
        this.style_.color = null;
        this.style_.text_shadow = null;
        this.style_.box_shadow = null;
        this.style_.text_align = null;
        this.style_.overflow = null;
        this.style_.bg_color = null;
        this.style_.name = null;

      },
    },
    mounted() {
      this.setUndroppable()
    }
  }
</script>

<style>
  .he-tree{
    margin: 0 auto;
    width: 50%;
  }
</style>
