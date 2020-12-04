<template>
  <div>
    <a class="small-rounded-btn blue-bg " :href="'/app/'+app.slug+'/feed/'+post.module_id+'/posts'">Go Back</a>
    <a class="small-rounded-btn blue-bg " @click="openCreateBlock(null)">Create Block</a>
    <a v-if="toggle" class="small-rounded-btn blue-bg " @click="toggleBlocks(false)">Collapse Blocks</a>
    <a v-else class="small-rounded-btn blue-bg " @click="toggleBlocks(true)">Expand Blocks</a>
    <small v-if="post.published_at" >published at {{post.published_at}}</small>
    <a class="small-rounded-btn blue-bg "  @click="publishPost()">Publish Post</a>

    <Tree :data="blocks" :indent="60" ref="groupTree" :draggable="true"  @change="onDropBlock" >
      <div slot-scope="{data, store, vm}">
        <template v-if="!data.isDragPlaceHolder">
          <div class="node-group">
            <span v-if="!toggle">
              <ion-icon name="expand-outline"></ion-icon>
            </span>
            {{data.order}} - {{data.type}}
            <ion-icon  v-if="!toggle" @click="deleteBlockConfirm(data.id)" name="trash-outline"></ion-icon>
            <div v-if="toggle">
              <a class="small-rounded-btn blue-bg " @click="openCreateBlock(data.id)">Add block</a>
              <div v-if="data.type === 'html'">
                <ckeditor :editor="editor" v-model="data.content" ></ckeditor>
                <a class="small-rounded-btn green-bg " @click="updateBlock(data)">Save block</a>
              </div>
              <div v-else-if="data.type === 'image'">
                <img v-if="data.content"  :src="data.content" width="400px" />
                <input type="file" :ref="'imageContent'+data.id" @change="updateBlock(data)" >
              </div>
              <div v-else-if="data.type === 'video'">
                <ycms-video-player
                  v-if="data.content"
                  :src="data.content"></ycms-video-player>
                <input type="text" v-model="data.content" class="rounded-input" @change="updateBlock(data)" >
              </div>
              <a class="small-rounded-btn" style="background-color: orangered;color: white" @click="deleteBlockConfirm(data.id)">Delete block</a>
            </div>
          </div>
        </template>
      </div>
    </Tree>
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

  export default {
    name: 'ycms-feed-blocks',
    props: {
      app: Object,
      post: Object
    },
    components:{
      ckeditor: CKEditor.component,
      Tree: DraggableTree
    },
    data() {
      return {
        editor: ClassicEditor,
        blocks: this.post.blocks,
        markedForDelete: null,
        markedForBefore: null,
        toggle: true,
        newBlock: {
          post_id: this.post.id,
          type: null,
          content: null,
          order: 1,
        }
      }
    },
    methods: {
      publishPost(){
        axios.post('/app/' + this.app.slug + '/feed/post/publish', {
          id: this.post.id,
        })
        .then(res => {
          if(res.data.success === true){
            this.notifier.success('Post published successfully')
          }
        })
      },
      onDropBlock(node){
        console.log(node)

        let index = this.blocks.map(x => {
          return x.id;
        }).indexOf(node.id)


        axios.post('/app/'+ this.app.slug +'/feed/block/change_block',{
          "order": index+1,
          "id": node.id,
        })
        .then(res => {
          this.blocks = res.data.blocks
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
      addBlockBefore(id){
        if(id){
          axios.post('/app/'+ this.app.slug +'/feed/block/store_file',{
            "id":id
          })
          .then(res => {
            console.log(res)
            this.notifier.success('Block added successfully')
          })
        }
      },
      updateBlock(block){
        let formData = new FormData()
        if(block.type === 'video'){
          console.log("in d")
          formData.append('video', block.content);
        }else if (block.type === 'image'){
          formData.append('image', this.$refs['imageContent'+block.id].files[0]);
        }else if (block.type === 'html'){
          formData.append('html', block.content);
        }
        formData.append('id', block.id);
        console.log(formData)
        axios.post('/app/'+ this.app.slug +'/feed/block/update',
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
        axios.post('/app/' + this.app.slug + '/feed/block/store', {
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
        axios.post('/app/'+ this.app.slug +'/feed/block/delete', {
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

<style lang="scss" scoped>

</style>
