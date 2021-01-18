<template>
  <div class="vue_component__upload--image" :class="{ 'dragover': onDragover }">
    <form :id="name" enctype="multipart/form-data">
      <div class="upload_image_form__thumbnails">

        <Tree v-if="url" :data="images_" draggable="draggable" :ref="name" cross-tree="cross-tree" @drop="dropImageTree">
          <div slot-scope="{data, store}">
            <template v-if="!data.isDragPlaceHolder">
              <div >
                <span @click="fileDeleteP($event, data.id)">
                  &#x2716;
                </span>
                <img :src="data.url_original"
                     width="100px"
                     :class="{ 'show': data.url_original }"
                     @click="fileClick(data.url_original, null)"
                >
              </div>
            </template>
          </div>
        </Tree>
        <!--        <div v-for="(value, key) in images_" class="upload_image_form__thumbnail uploaded"-->

        <!--        >-->
        <!--                    <span v-on:click="fileDeleteP($event, value.id)">-->
        <!--                    &#x2716;-->
        <!--                    </span>-->
        <!--          <img v-bind:src="value.url_original" v-bind:class="{ 'show': value.url_original}"  v-on:click="fileClick(value.url_original, null)">-->
        <!--        </div>-->

        <Tree v-if="!url" :data="create_images" draggable="draggable" cross-tree="cross-tree">
          <div slot-scope="{data, store}">
            <template >
              <div >
                                    <span v-on:click="fileDelete(null, data)">
                                    &#x2716;
                                    </span>
                <img :src="data.url_original" width="100px" @click="fileClick(data.url_original, null)">
                <!--                    <span v-on:click="fileDelete($event, key)">-->
                <!--                    &#x2716;-->
                <!--                    </span>-->
<!--                                <img v-bind:src="data.url_original" v-bind:class="{ 'show': data }"   v-on:click="fileClick(null,data)" >-->
              </div>
            </template>
          </div>
        </Tree>
        <!--        <div v-if="!url" v-for="(value, key) in files" class="upload_image_form__thumbnail"-->
        <!--             v-bind:class="{ 'uploaded': value.uploaded, 'bad-size': value.bad_size }">-->
        <!--                    <span v-on:click="fileDelete($event, key)">-->
        <!--                    &#x2716;-->
        <!--                    </span>-->
        <!--          <img v-bind:src="image[key]" v-bind:class="{ 'show': image[key]}"   v-on:click="fileClick(null,key)" >-->
        <!--        </div>-->
      </div>
      <input type="file" :id="name+'__input'" multiple/>
      <!--            <div>-->
      <!--                <button type="submit"-->
      <!--                        v-on:click="submit"-->
      <!--                        v-bind:disabled="onUploading"-->
      <!--                        class="small-rounded-btn blue-bg text-white">Upload image</button>-->
      <!--            </div>-->
    </form>
  </div>
</template>

<script>
import {DraggableTree} from 'vue-draggable-nested-tree'
import * as th from 'tree-helper'

export default {
  name: 'upload-image',
  components: {
    Tree: DraggableTree
  },
  props: {
    url: {
      type: String,
      required: false,
      default: null
    },
    name: {
      type: String,
      required: false,
      default: 'images'
    },
    disable_upload: {
      type: Boolean,
      required: false,
      default: false
    },
    max_batch: {
      type: Number,
      required: false,
      default: 0
    },
    max_files: {
      type: Number,
      required: false,
      default: 10
    },
    max_filesize: {
      type: Number,
      required: false,
      default: 8000
    },
    resize_enabled: {
      type: Boolean,
      required: false,
      default: false
    },
    resize_max_width: {
      type: Number,
      required: false,
      default: 800
    },
    resize_max_height: {
      type: Number,
      required: false,
      default: 600
    },
    p_images: Array,
    entity_id: Number,
    slug: String
  },

  watch: {
    p_images(newv, oldv) {
      this.images_ = newv
      th.depthFirstSearch(this.images_, (childNode) => {
        childNode.droppable = false;
      })
    }
  },

  data() {
    return {
      form: null,
      input: null,
      index: 0,
      total: 0,
      files: {},
      image: {},
      create_images: [],
      batch: {},
      onDragover: false,
      images_: this._.cloneDeep(this.p_images),
      onUploading: false
    }
  },

  mounted() {

    if (this.url) {
      th.depthFirstSearch(this.images_, (childNode) => {
        childNode.droppable = false;
      })
    }

    this.form = document.getElementById(this.name);
    this.input = document.getElementById(this.name + '__input');

    ['drag', 'dragstart', 'dragend',
      'dragover', 'dragenter', 'dragleave', 'drop'].forEach(event => this.form.addEventListener(event, (e) => {
      e.preventDefault();
      e.stopPropagation();
    }));

    ['dragover', 'dragenter']
        .forEach(event => this.form.addEventListener(event, this.dragEnter));

    ['dragleave', 'dragend', 'drop']
        .forEach(event => this.form.addEventListener(event, this.dragLeave));

    ['drop']
        .forEach(event => this.form.addEventListener(event, this.fileDrop));

    ['change']
        .forEach(event => this.input.addEventListener(event, this.fileDrop));

  },

  methods: {

    dropImageTree(node, cur, prev) {
      let index_g = this.images_.map(x => {
        return x.id;
      }).indexOf(node.id)
      index_g = parseInt(index_g);

      if (node.entity_id === this.entity_id
          && node.entity === this.name
          && node.order == index_g + 1
      ) {
        return false;
      }
      if (index_g >= 0) {
        let entity = node.entity
        if (node.entity_id !== this.entity_id) {
          entity = node.entity === "union" ? "product" : "union"
        }
        axios.post('/app/' + this.slug + '/shop-product-union/change-image', {
          'id': node.id,
          'entity_id': this.entity_id,
          'entity': entity,
          'order': index_g + 1,
        })
            .then((res) => {
              if (res.data.success) {
                this.notifier.success('Image changed position success')
                if (node.entity_id !== this.entity_id) {
                  this.$emit('update-images');
                }
              } else {
                this.notifier.success('Image not found')
              }
            });
      }
    },

    _can_xhr() {
      return this.total < this.max_files;
    },

    _can_upload_file(key) {
      console.log('_can_upload_file')
      let file = this.files[key];

      return !(file.attempted || file.bad_size);
    },

    _xhr(formData, keys, callback) {
      this.onUploading = true;
      this.$emit('upload-image-attempt', formData);

      keys.forEach((key) => {
        this.$set(this.files[key], 'attempted', true);
      });


      const config = {
        headers: {'content-type': 'multipart/form-data'}
      };
      axios.post(this.url, formData, config).then((res) => {
        keys.forEach((key) => {
          this.$set(this.files[key], 'uploaded', true);

          this.total++;
        });
        this.images_ = _.cloneDeep(res.data.images)
        th.depthFirstSearch(this.images_, (childNode) => {
          childNode.droppable = false;
        })

        this.$emit('upload-image-success', [formData, response]);
      }, (response) => {
        this.$emit('upload-image-failure', [formData, response]);
      }).then((response) => {
        this.onUploading = false;

        callback();
      });
    },

    upload() {
      console.log('upload')
      if (!this._can_xhr()) return false;

      for (let key in this.files) {
        if (!this._can_upload_file(key)) continue;

        let formData = new FormData();
        formData.append("image", this.files[key].file);
        this._xhr(formData, [key], this.upload);

        return true;
      }
    },

    upload_batch() {
      console.log('upload_batch')
      if (!this._can_xhr()) return false;

      for (let key in this.batch) {
        this._xhr(this.batch[key].form, this.batch[key].keys, this.upload_batch);

        delete this.batch[key];

        return true;
      }
    },

    create_batch() {
      console.log('create_batch')
      let index = 0;
      let count = 0;

      this.batch = {};

      for (let key in this.files) {
        if (!this._can_upload_file(key)) continue;

        if (this.batch[index] == null || count == this.max_batch) {
          index++;
          count = 0;
          this.batch[index] = {form: new FormData(), keys: []};
        }

        count++;
        this.batch[index]['keys'].push(key);
        this.batch[index]['form'].append(this.name, this.files[key].file, this.files[key].name);
      }
    },

    submit(e) {
      console.log('submit')

      e.preventDefault();
      e.stopPropagation();

      this.$emit('upload-image-submit', this.files);

      if (!this.disable_upload && !this.onUploading) {
        if (this.max_batch > 1) {
          this.create_batch();
          return this.upload_batch();
        }
        this.upload();
      }
    },

    dragEnter: function (e) {
      console.log('dragEnter')
      e.preventDefault();
      this.onDragover = true;
    },

    dragLeave: function (e) {
      console.log('dragLeave')
      e.preventDefault();
      this.onDragover = false;
    },

    fileDrop: function (e) {
      console.log('fileDrop')
      e.preventDefault();

      let newFiles = e.target.files || e.dataTransfer.files;

      for (let i = 0; i < newFiles.length; i++) {
        this.$set(this.files, this.index, newFiles[i]);

        if (newFiles[i].type.match(/image.*/)) {
          this.fileInit(this.index);
          this.fileRead(this.index);

          this.index++;
        }

      }
      e.target.value = '';
      if (this.url) {
        this.upload();
      }
    },

    fileInit: function (key) {
      console.log('fileInit')
      let file = this.files[key];

      this.files[key] = {
        name: this.files[key].name,
        file: this.files[key]
      };

      if ((file.size * 0.001) > this.max_filesize) {
        this.$set(this.files[key], 'bad_size', true);
      }
    },

    fileRead(key) {
      console.log('fileRead')
      let reader = new FileReader();

      reader.addEventListener("load", (e) => {
        this.$set(this.image, key, reader.result);

        if (this.resize_enabled) {
          let imager = new Image();

          imager.onload = () => {
            let width = imager.width;
            let height = imager.height;

            if (width > this.resize_max_width || height > this.resize_max_height) {
              if ((height / width) - (this.resize_max_height / this.resize_max_width) > 0) {
                width = this.resize_max_height / height * width;
                height = this.resize_max_height;
              } else {
                height = this.resize_max_width / width * height;
                width = this.resize_max_width;
              }
            }

            let canvas = document.createElement("canvas");
            canvas.width = width;
            canvas.height = height;

            let ctx = canvas.getContext("2d");
            ctx.drawImage(imager, 0, 0, width, height);

            let newImageData = canvas.toDataURL("image/png");

            this.$set(this.image, key, newImageData);

            //
            let img = atob(newImageData.split(',')[1]);
            let img_buffer = [];
            let i = 0;
            while (i < img.length) {
              img_buffer.push(img.charCodeAt(i));
              i++;
            }
            let u8Image = new Uint8Array(img_buffer);

            this.$set(this.files, key, {
              file: this.files[key]
            });

          };

          imager.src = reader.result;
        }

        this.create_images.push({
          "url_original": reader.result,
          "droppable": false,
          "file": this.files[key].file,
        })
        this.$emit('upload-image-loaded', this.create_images);
      });


      reader.readAsDataURL(this.files[key].file);
    },

    fileDeleteP(e, key) {
      console.log('fileDelete')
      this.$emit('upload-image-removed', key);
      let index = this.images_.map(x => {
        return x.id;
      }).indexOf(key);
      this.images_.splice(index, 1)
    },

    fileDelete(e, key) {
      console.log('fileDelete')
      // this.$emit('upload-image-removed', key);
      this.$delete(this.files, key);
      let index = this.create_images.map(x => {
        return x.url_original;
      }).indexOf(key)
      this.create_images.splice(index, 1)
    },

    fileClick(path, key) {
      let img_url = path
      if (key) {
        img_url = key;
      }
      this.$emit('upload-image-clicked', img_url);
    }
  }
}
</script>

<style lang="css" scoped>
.vue_component__upload--image {
  padding: 5px;
  cursor: pointer;
  border: 1px dashed;
  background-color: lightgray;
  min-height: 80px;
  border-radius: 5px;
}

.vue_component__upload--image.dragover {
}

.vue_component__upload--image form > div {
  text-align: center;
}

.vue_component__upload--image .upload_image_form__thumbnails {
  margin-bottom: 1em;
}

.vue_component__upload--image .upload_image_form__thumbnail {
  border-radius: 2.5px;
  position: relative;
  width: 20%;
  padding: 20% 0 0;
  overflow: hidden;
  margin: 10px;
  display: inline-block;
}

.vue_component__upload--image .upload_image_form__thumbnail img {
  position: absolute;
  top: 50%;
  left: 50%;
  min-width: 50%;
  min-height: 50%;
  max-height: 100%;
  opacity: 0;
  transform: translateX(-50%) translateY(-50%);
  transition: 1s opacity;
}

.vue_component__upload--image .upload_image_form__thumbnail img.show {
  opacity: 1;
}

.vue_component__upload--image .upload_image_form__thumbnail img:hover {
  filter: blur(2px);
}

.vue_component__upload--image .upload_image_form__thumbnail.bad-size img {
  filter: grayscale(100%);
}

.vue_component__upload--image .upload_image_form__thumbnail.uploaded img {
  opacity: 1;
}

.vue_component__upload--image .upload_image_form__thumbnail span {
  position: absolute;
  top: -5px;
  left: 0px;
  z-index: 100;
  padding: 0px 1px;
  border-radius: 2px;
  background-color: grey;
}
</style>
