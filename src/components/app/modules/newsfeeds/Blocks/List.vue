<template>
  <div class="content">
    <div class="actions">
      <a class="small-rounded-btn blue-bg " @click="openCreateBlock(null)">Create Block</a>
      <a v-if="toggle" class="small-rounded-btn blue-bg " @click="toggleBlocks(false)">Collapse Blocks</a>
      <a v-else class="small-rounded-btn blue-bg " @click="toggleBlocks(true)">Expand Blocks</a>
    </div>

    <Tree v-if="!toggle" :data="blocks" :indent="60" ref="groupTree" :draggable="true"  @change="onDropBlock" >
      <div slot-scope="{data, store, vm}">
        <template v-if="!data.isDragPlaceHolder">
          <div class="node-group">
            <i class="fa fa-bars"></i>
            {{data.order}} - {{data.type}}
            <i   @click="deleteBlockConfirm(data.id)" class="fa fa-trash"></i>
          </div>
        </template>
      </div>
    </Tree>
    <div v-else v-for="data in blocks" class="block">
      <a class="small-rounded-btn blue-bg " @click="openCreateBlock(data.id)">Add block</a>
      <div v-if="data.type === 'html'">
        <ckeditor :editor="editor" v-model="data.content" ></ckeditor>
      </div>
      <div v-else-if="data.type === 'image'">
        <img v-if="data.content"  :src="'https://api.yappix.io'+data.content" width="400px" />
        <input type="file" :ref="'imageContent'+data.id" @change="updateBlock(data)" >
      </div>
      <div v-else-if="data.type === 'video'">
        <video-player
            v-if="data.content"
            :src="data.content">
        </video-player>
        <input type="text" v-model="data.content" class="rounded-input" @change="updateBlock(data)" >
      </div>
      <a  v-if="data.type === 'html'" class="small-rounded-btn green-bg " @click="updateBlock(data)">Save block</a>
      <a class="small-rounded-btn" style="background-color: orangered;color: white" @click="deleteBlockConfirm(data.id)">Delete block</a>
    </div>
    <a class="small-rounded-btn blue-bg " @click="openCreateBlock(null)">Add block</a>

    <sweet-modal
        class="modal"
        ref="createBlock"
        width="550"
        overlay-theme="dark"
    >
      <h4>Select block type</h4>
      <select class="rounded-input" v-model="newBlock.type">
        <option value="html">html</option>
        <option value="image">image</option>
        <option value="video">video</option>
      </select>
      <a class="small-rounded-btn blue-bg " @click="storeBlock(null)">Save</a>
    </sweet-modal>
  </div>
</template>

<script>
  import CKEditor from '@ckeditor/ckeditor5-vue';
  import ClassicEditor from '@ckeditor/ckeditor5-build-classic';
  import {DraggableTree} from 'vue-draggable-nested-tree'
  import * as th from 'tree-helper'
  import VideoPlayer from "../../pagebuilder/Element/VideoPlayer";
  import { getIdFromURL } from 'vue-youtube-embed';

  export default {

    name: "blocks",

    components: {
      ckeditor: CKEditor.component,
      Tree: DraggableTree,
      'video-player': VideoPlayer,
    },

    data() {
      return {
        editor: ClassicEditor,
        blocks: [],
        markedForDelete: null,
        markedForBefore: null,
        toggle: true,
        post: {},
        newBlock: {
          post_id: null,
          type: null,
          content: null,
          order: 1,
        },
        module: {}
      }
    },

    created() {
      this.module.id = this.$parent.$parent.$parent.moduleId
      this.post.id = this.$route.query.post
      this.newBlock.post_id = this.post.id
      this.loadData()
    },

    methods: {
      loadData() {
        axios.get(`/${this.$route.params.folder.toLowerCase()}/post/${this.post.id}/blocks`)
        .then((res) => {
          this.blocks = this._.cloneDeep(res.data.blocks)
        })
      },
      onDropBlock(node){
        let index = this.blocks.map(x => {
          return x.id;
        }).indexOf(node.id)


        axios.post(`/${this.$route.params.folder.toLowerCase()}/post/${this.post.id}/block/change_block`,{
          "order": index+1,
          "id": node.id,
        })
        .then(res => {
          this.blocks = this._.cloneDeep(res.data.blocks)
          this.setUndroppable()
          this.notifier.success('Block\'s order changed successfully')
        })

      },
      setUndroppable(){
        th.depthFirstSearch(this.blocks, (childNode) => {
          childNode.droppable = false;
        })
      },
      toggleBlocks(arg){
        this.toggle = arg;
      },
      updateBlock(block){
        let formData = new FormData()
        if(block.type === 'video'){
          block.content = getIdFromURL(block.content)
          formData.append('video', block.content);
        }else if (block.type === 'image'){
          formData.append('image', this.$refs['imageContent'+block.id][0].files[0]);
        }else if (block.type === 'html'){
          formData.append('html', block.content);
        }
        formData.append('id', block.id);
        axios.post(`/${this.$route.params.folder.toLowerCase()}/post/${this.post.id}/block/update`,
            formData,
            {
              headers: {
                'Content-Type': 'multipart/form-data'
              }
            })
        .then(res => {
          if(res.data.success === true){
            this.notifier.success('Block updated successfully')
          }else{
            this.notifier.success('Block not updated')
          }
        })
      },
      openCreateBlock(id){
        this.markedForBefore = id;
        this.$refs.createBlock.open()
      },
      storeBlock(block){
        axios.post(`/${this.$route.params.folder.toLowerCase()}/post/${this.post.id}/block/store`, {
          block: block?block:this.newBlock,
          before: this.markedForBefore,
        })
        .then(res => {
          if (res.data.message === "created"){
            this.notifier.success('Block created successfully')
          }else{
            this.notifier.success('Block updated successfully')
          }
          this.blocks = res.data.blocks
          this.setUndroppable()
          this.newBlock.type = null;
          this.$refs.createBlock.close()
        })
      },
      deleteBlockConfirm(id){
        this.markedForDelete = id;
        this.notifier.confirm('Are you sure?', this.deleteBlock())
      },
      deleteBlock(){
        axios.post(`/${this.$route.params.folder.toLowerCase()}/post/${this.post.id}/block/delete`, {
          id: this.markedForDelete,
        })
        .then(res => {
          this.blocks = res.data.blocks
          this.setUndroppable()
          this.notifier.success('Block deleted successfully')
        })
      },
    },
    mounted() {
      this.setUndroppable()
    }

  }
</script>

<style scoped lang="scss">
  .content {
    margin-top: 25px;
    width: 100%;
    background-color: white;
    padding: 25px 50px;

  }
  .content>a{
    margin-top: 25px;
  }
  .he-tree{
    margin-top: 25px;
  }
  .actions{
    padding-top: 25px;
    a {
      padding: 6px 12px;
      display: inline-block;
    }
  }
  .block{
    margin-top: 10px;

    a{
      margin-top:5px;
      display: inline-block;
      padding: 6px 12px;
    }
    div{
      margin-top: 5px;
    }
  }
</style>
