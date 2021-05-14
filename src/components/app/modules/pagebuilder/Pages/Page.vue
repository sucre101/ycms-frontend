<template>
  <div >
    <InnerTab :items="[
        { name: 'Blocks' },
        { name: 'Templates' },
        { name: 'Deleted Blocks' },
    ]"
              @change="selectTab"
    />

    <div  v-if="selectedTab === 0">
      <div class="page-buttons">
        <a  class="small-rounded-btn blue-bg " @click="openNewBlockModal(null)">Add Block</a>
        <a v-if="toggle" class="small-rounded-btn blue-bg " @click="toggleBlocks(false)">Collapse Blocks</a>
        <a v-else class="small-rounded-btn blue-bg " @click="toggleBlocks(true)">Expand Blocks</a>
      </div>
      <div class="blocks-list" >
        <Tree  v-if="!toggle"  :data="blocks_tree" :indent="60" ref="groupTree" :draggable="true"  @change="onDropBlock" >
          <div slot-scope="{data, store, vm}" >
            <template v-if="!data.isDragPlaceHolder">
            <span>
              {{data.order}}-{{data.layout}}-layout
              <a @click="removeBlockConfirm(data.id)" name="trash-outline">trash-outline</a>
            </span>

            </template>
          </div>
        </Tree>

        <blocks-grid
            v-else
            :blocks="blocks"
            :deleted="false"
            v-on:change-block-order="changeBlockOrder"
            v-on:open-block="openNewBlockModal"
            v-on:open-element="openNewElementModal"
            v-on:remove-block="removeBlockConfirm"
            v-on:delete-element="deleteElementConfirm"
        ></blocks-grid>
      </div>
    </div>
    <div class="template-list"  v-if="selectedTab === 1">
      <templates-list
          :templates="templates"
          v-on:open-template="openTemplateModal"
          v-on:delete-template-confirm="deleteTemplateConfirm"
      ></templates-list>
    </div>

    <div  class="blocks-list"  v-if="selectedTab === 2">
      <h4 v-if="blocks_trash.length < 1">There is no deleted blocks</h4>

      <blocks-grid
          v-else
          :blocks="blocks_trash"
          :deleted="true"
          v-on:restore-block="restoreBlockConfirm"
          v-on:delete-block="deleteBlockConfirm"
      ></blocks-grid>

    </div>
    <sweet-modal
        class="modal"
        ref="blockModal"
        width="850"
        overlay-theme="dark"
    >
      <block-modal
          v-if="showBlockModal"
          :block="block_"
          :templates="templates"
          v-on:store-block="storeBlock"
          v-on:update-block="updateBlock"
          v-on:open-template="openTemplateModal"
      ></block-modal>
    </sweet-modal>

    <sweet-modal
        class="modal"
        ref="templateModal"
        width="850"
        overlay-theme="dark"
    >
      <template-modal
          :template="template_"
          v-on:store-template="storeTemplate"
          v-on:update-template="updateTemplate"
      ></template-modal>
    </sweet-modal>

    <sweet-modal
        class="modal"
        ref="elementModal"
        width="850"
        overlay-theme="dark"
    >
      <element-modal
          v-if="showElementModal"
          :element="element_"
          :templates="templates"
          v-on:store-element="storeElement"
          v-on:update-element="updateElement"
          @close="closeElementModal"
      ></element-modal>
    </sweet-modal>
  </div>
</template>
<script>
  import CKEditor from '@ckeditor/ckeditor5-vue';
  import ClassicEditor from '@ckeditor/ckeditor5-build-classic';
  import {DraggableTree} from 'vue-draggable-nested-tree'
  import * as th from 'tree-helper'
  import { getIdFromURL } from 'vue-youtube-embed'
  import TemplateList from './../Template/List'
  import ElementModal from './../Element/Modal'
  import TemplateModal from './../Template/Modal'
  import BlockGrid from './../Blocks/Grid'
  import BlockModal from './../Blocks/Modal'
  import InnerTab from "@/components/base/ui/InnerTab";

  export default {
    name: 'ycms-page-builder-list',
    props: {
    },
    components:{
      ckeditor: CKEditor.component,
      Tree: DraggableTree,
      'templates-list': TemplateList,
      'template-modal': TemplateModal,
      'element-modal': ElementModal,
      'blocks-grid': BlockGrid,
      'block-modal': BlockModal,
      InnerTab
    },
    data() {
      return {
        module: {},
        selectedTab: 0,
        editor: ClassicEditor,
        blocks: [],
        blocks_trash: [],
        blocks_tree: [],
        templates: [],
        deleteIdBlock: null,
        removeIdBlock: null,
        restoreIdBlock: null,
        deleteIdElement: null,
        deleteIdTemplate: null,
        toggle: true,
        showElementModal: false,
        showBlockModal: false,
        block_: {},
        element_: {},
        template_: {}
      }
    },
    created() {
      this.module.id = this.$parent.moduleId
      console.log(this.module.id)
      this.loadPage()
      this.loadTemplates()
    },
    methods: {
      selectTab(index) {
        this.selectedTab = index
      },
      loadPage() {
        axios.get(`/${this.$route.params.folder.toLowerCase()}/${this.module.id}/edit`)
        .then((res) => {
          this.setBlocks(res.data.blocks)
          this.setNull();
        })
      },
      loadTemplates() {
        axios.get(`/${this.$route.params.folder.toLowerCase()}/${this.module.id}/templates`)
        .then((res) => {
          this.templates = this._.cloneDeep(res.data.templates)
        })
      },
      getArray(obj){
        let arr = [];
        let style;

        for(let i=0;i<obj.images.length;i++){
          style = obj.template?obj.template.style+'width:'+obj.template.width:'';
          arr.push('<img  style="'+style+'"  src="'+obj.images[i].url_original+'" />')
        }

        return arr;
      },
      setUndroppable(){
        th.depthFirstSearch(this.blocks_tree, (childNode) => {
          childNode.droppable = false;
        })
      },
      setBlocks(blocks){
        this.blocks = this._.cloneDeep(blocks.filter(p => p.is_deleted === 0));
        this.blocks_trash = this._.cloneDeep(blocks.filter(p => p.is_deleted === 1));
        this.blocks_tree = this._.cloneDeep(blocks.filter(p => p.s_order === 1 && p.is_deleted === 0));

        this.setUndroppable()
      },
      toggleBlocks(arg){
        this.toggle = arg;
      },
      onDropBlock(node){
        let index = this.blocks_tree.map(x => {
          return x.id;
        }).indexOf(node.id)

        axios.post(`/${this.$route.params.folder.toLowerCase()}/block/change_block`,{
          "order": index+1,
          "id": node.id,
        })
        .then(res => {
          this.setBlocks(res.data.blocks)
          this.notifier.success('Block\'s order changed successfully')
        })

      },
      changeBlockOrder(add, id){
        axios.post(`/${this.$route.params.folder.toLowerCase()}/block/change_block_order`,{
          "add": add,
          "id": id,
        })
        .then(res => {
          this.setBlocks(res.data.blocks)
          this.notifier.success('Block\'s order changed successfully')
        })
      },
      openNewBlockModal(block){
        this.setNull()
        if(block){
          this.block_ = block;
        }

        this.showBlockModal = true;
        this.$refs.blockModal.open()
      },
      openTemplateModal(template){
        this.setNull('style')

        if(template){
          this.template_ = this._.cloneDeep(template);
        }

        this.$refs.templateModal.open()
      },
      openNewElementModal(element, block_id){
        this.setNull()

        this.element_.block_id = block_id;
        if(element){
          this.element_ = this._.cloneDeep(element);
        }
        this.showElementModal = true;
        this.$refs.elementModal.open()
      },
      closeElementModal(){
        this.showElementModal = false;
        this.setNull()
      },
      closeBlockModal(){
        this.showBlockModal = false;
        this.setNull()
      },
      deleteBlockConfirm(id){
        this.deleteIdBlock = id;
        this.notifier.confirm('All blocks and elements in the row will be deleted. Are you sure?', this.deleteBlock())
      },
      removeBlockConfirm(id){
        this.removeIdBlock = id;
        this.notifier.confirm('All blocks and elements in the row will be in trash. Are you sure?', this.removeBlock())
      },
      restoreBlockConfirm(id){
        this.restoreIdBlock = id;
        this.notifier.confirm('All blocks and elements in the row will restored. Are you sure?', this.restoreBlock())
      },
      deleteElementConfirm(id, block_id){
        this.deleteIdElement = id;
        this.deleteIdBlock = block_id;
        this.notifier.confirm('Are you sure?', this.deleteElement())
      },
      deleteSliderImageConfirm(id){
        this.deleteIdElement = id;
        this.notifier.confirm('Are you sure?', this.deleteSliderImage())
      },
      deleteTemplateConfirm(id){
        this.deleteIdTemplate = id;
        this.notifier.confirm('Are you sure?', this.deleteTemplate())
      },
      storeBlock(block){
        axios.post(`/${this.$route.params.folder.toLowerCase()}/block/store`,{
          block: block
        })
        .then(res => {
          if(res.data.success === true){
            this.setBlocks(res.data.blocks)
            this.notifier.success('Block created successfully')
          }else{
            this.notifier.warning('Error')
          }
          this.$refs.blockModal.close()
          this.setNull();
          this.showBlockModal = false;
        });
      },
      updateBlock(block){
        axios.post(`/${this.$route.params.folder.toLowerCase()}/block/update`,{
          block: block
        })
        .then(res => {
          if(res.data.success === true){
            this.setBlocks(res.data.blocks)
            this.notifier.success('Block updated successfully')
          }else{
            this.notifier.warning('Error')
          }
          this.$refs.blockModal.close()
          this.setNull();
        });
        this.showBlockModal = false;
      },
      storeElement(element_, images){
        let index = this.blocks.map(x => {
          return x.id;
        }).indexOf(this.element_.block_id)

        let element = element_;

        if(element.type === 'video'){
          element.content = getIdFromURL(element.content)
        }

        axios.post(`/${this.$route.params.folder.toLowerCase()}/element/store`,{
          element: element,
        }).then(res => {
          if(res.data.success === true){
            if(res.data.element.type === 'image' || res.data.element.type === 'slider'){
              const config = {
                headers: { 'content-type': 'multipart/form-data' }
              };

              let formData = new FormData();
              for(let i=0; i< images.length; i++){
                formData.append("image[]",images[i]);
              }
              formData.append("id", res.data.element.id);

              axios.post(`/${this.$route.params.folder.toLowerCase()}/element/save_image`, formData ,config)
              .then(result => {
                this.blocks[index].elements.push(result.data.element)
              })
            }else {
              this.blocks[index].elements.push(res.data.element)
            }
          }else{
            this.notifier.warning('Error')
            return false;
          }
        }).then(() => {
          this.notifier.success('Element created successfully')
          this.$refs.elementModal.close()
          this.showElementModal = false;
          this.setNull();
        });
      },
      updateElement(element_, images){
        let element = element_;

        if(element.type === 'video'){
          element.content = getIdFromURL(element.content)
        }

        let index = this.blocks.map(x => {
          return x.id;
        }).indexOf(this.element_.block_id)

        axios.post(`/${this.$route.params.folder.toLowerCase()}/element/update`,{
          element: element
        })
        .then(res => {
          if(res.data.success === true){
            if ((res.data.element.type === 'image' || res.data.element.type === 'slider') && images.length > 0){

              const config = {
                headers: { 'content-type': 'multipart/form-data' }
              };

              let formData = new FormData();
              for(let i=0; i< images.length; i++){
                formData.append("image[]",images[i]);
              }
              formData.append("id", res.data.element.id);

              axios.post(`/${this.$route.params.folder.toLowerCase()}/element/save_image`, formData ,config)
              .then(result => {
                this.blocks[index].elements = result.data.elements
              })
            }else {
              this.blocks[index].elements = res.data.elements
            }
          }else{
            this.notifier.warning('Error')
            return false;
          }
        }).then(() => {
          this.notifier.success('Element updated successfully')
          this.$refs.elementModal.close()
          this.showElementModal = false;
          this.setNull();
        });
      },
      storeTemplate(template){
        delete template.style;
        delete template.id;

        template.user_module_id = this.module.id

        axios.post(`/${this.$route.params.folder.toLowerCase()}/template/store`,{
          style: template,
        })
        .then(res => {
          if(res.data.success === true){
            this.templates = res.data.templates
            this.notifier.success('Template created successfully')
          }else{
            this.notifier.warning('Error')
          }
        });

        this.$refs.templateModal.close()
      },
      updateTemplate(template){
        console.log(template)
        delete template.style;

        axios.post(`/${this.$route.params.folder.toLowerCase()}/template/update`,{
          style: template,
        })
        .then(res => {
          if(res.data.success === true){
            this.templates = res.data.templates
            this.notifier.success('Template updated successfully')
          }else{
            this.notifier.warning('Error')
          }
        });

        this.$refs.templateModal.close()
      },
      deleteBlock(){
        axios.post(`/${this.$route.params.folder.toLowerCase()}/block/delete`,{
          id: this.deleteIdBlock,
        })
        .then(res => {
          if(res.data.success === true){
            this.notifier.success('Block deleted successfully')
            this.setBlocks(res.data.blocks)

          }else{
            this.notifier.warning('Error')
          }
        });
        this.setNull();
      },
      removeBlock(){
        axios.post(`/${this.$route.params.folder.toLowerCase()}/block/remove`,{
          id: this.removeIdBlock,
        })
        .then(res => {
          if(res.data.success === true){
            this.notifier.success('Block removed successfully')
            this.setBlocks(res.data.blocks)

          }else{
            this.notifier.warning('Error')
          }
        });
        this.setNull();
      },
      restoreBlock(){
        axios.post(`/${this.$route.params.folder.toLowerCase()}/block/restore`,{
          id: this.restoreIdBlock,
        })
        .then(res => {
          if(res.data.success === true){
            this.notifier.success('Block restored successfully')
            this.setBlocks(res.data.blocks);

          }else{
            this.notifier.warning('Error')
          }
        });
        this.setNull();
      },
      deleteElement(){
        let index = this.blocks.map(x => {
          return x.id;
        }).indexOf(this.deleteIdBlock)

        axios.post(`/${this.$route.params.folder.toLowerCase()}/element/delete`,{
          id: this.deleteIdElement,
        })
        .then(res => {
          if(res.data.success === true){
            this.notifier.success('Element deleted successfully')
            this.blocks[index].elements = res.data.elements

          }else{
            this.notifier.warning('Error')
          }
        });
        this.setNull();
      },
      deleteSliderImage(){
        let index = this.blocks.map(x => {
          return x.id;
        }).indexOf(this.element_.block_id)

        axios.post(`/${this.$route.params.folder.toLowerCase()}/element/delete_image`,{
          id: this.deleteIdElement,
        })
        .then(res => {
          if(res.data.success === true){
            this.notifier.success('Slider image deleted successfully')
            this.blocks[index].elements = res.data.elements

          }else{
            this.notifier.warning('Error')
          }
          this.deleteIdElement = null
        });
      },
      deleteTemplate(){
        axios.post(`/${this.$route.params.folder.toLowerCase()}/template/delete`,{
          id: this.deleteIdTemplate,
        })
        .then(res => {
          if(res.data.success === true){
            this.notifier.success('Template deleted successfully')
            this.templates = res.data.templates
          }else{
            this.notifier.warning('Error')
          }
        });
        this.setNull();
      },
      setNull(style){
        if(style !== 'style'){
          this.block_.id = null;
          this.block_.user_module_id = this.module.id;
          this.block_.layout = 1;
          this.block_.is_active = true;
          this.block_.template_id = null;
          this.block_.order = 1;

          this.element_.id = null;
          this.element_.type = 'html';
          this.element_.block_id = null;
          this.element_.template_id = null;
          this.element_.content = null;

          delete this.element_.template;
          delete this.element_.images;

        }

        this.template_.id = null;
        this.template_.user_module_id = this.module.id;
        this.template_.width = null;
        this.template_.height = null;
        this.template_.border_width = null;
        this.template_.border_color = '#000000';
        this.template_.border_type = null;
        this.template_.border_radius = null;
        this.template_.margin = null;
        this.template_.padding = null;
        this.template_.color = '#000000';
        this.template_.text_shadow = null;
        this.template_.box_shadow = null;
        this.template_.text_align = null;
        this.template_.overflow = null;
        this.template_.bg_color = '#FFFFFF';
        this.template_.name = null;
      },
    },
  }
</script>

<style scoped lang="scss">
  .blocks-list{
    width: 500px;
    border: 1px solid black;
    border-radius: 5px;
    margin: 20px auto;
    padding: 5px;
    box-shadow: 1px 1px 7px black;
  }
  .template-list{
    width: 500px;
    border: 1px solid black;
    box-shadow: 1px 1px 7px black;
    margin: 20px auto;
    padding: 15px;
    border-radius: 5px;
  }
  .he-tree{
    margin: 0 auto;
    width: 50%;
  }
  .page-buttons{
    margin-top: 20px;

    a{
      display: inline-block;
      padding: 6px 10px;
    }
  }

</style>
