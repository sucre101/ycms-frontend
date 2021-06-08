<template>
  <div class="image-cropper"
       :style="{ width: fullWidth ? '100%' : undefined }"
       :class="{ 'flex-column': !cropInModal }"
  >
    <input
      ref="imageInput"
      @change="fileSelected"
      type="file"
      :name="name"
      hidden
    >
    <!-- TODO: replace name with `cropped${upcase(name)}` -->
    <input
      ref="croppedImage"
      type="file"
      @change="fileSelected"
      :name="`cropped-${name}`"
      hidden
    >
    <div
      ref="topBarBgFileName"

    >
      <span v-if="!type">{{ fileName }}</span>

      <div
        v-if="type === 'icon'"
        @click="onClick"
        class="icon-container"
        :style="{height: size, width: size, fontSize: `calc(${size}/2)`}"
      >
        <i v-if="!base64" :class="`fas fa-${icon}`"></i>
        <img v-else :src="base64">
      </div>

      <div v-if="type === 'square-preview'" class="square-preview" @click="onClick">
        <img v-bind="{src}">
        <div class="button-container">
          <div class="button">BROWSE</div>
        </div>
      </div>
      <div v-if="type === 'app-icon'" class="app-icon" >
        <div class="button-container">
          <div class="button" @click="onClick">BROWSE</div>
        </div>
        <div class="img-container">
          <img v-bind="{src}">
          <div class="inner-cropper-app" ref="innerCropperApp" hidden>
            <div class="button" @click="crop">Crop</div>
            <img ref="innerCropperAppImg" src="">
          </div>
        </div>
      </div>
      <div v-if="type === 'startup-image'" class="startup-image" >
        <div class="button-container">
          <div class="button" @click="onClick">BROWSE</div>
        </div>
        <div class="img-container">
          <img class="vert1" v-bind="{src}">
          <img class="vert2" v-bind="{src}">
          <img class="vert3" v-bind="{src}"><br>
          <img class="hor1" v-bind="{src}">
        </div>

        <div class="inner-cropper-startup" ref="innerCropperStartup" hidden>
          <div class="button" @click="crop">Crop</div>
          <img ref="innerCropperStartupImg" src="">
        </div>
      </div>
    </div>

    <div class="inner-cropper" ref="innerCropper" hidden>
      <div class="button" @click="crop">Crop</div>
      <img ref="innerCropperImg" src="">
    </div>

    <sweet-modal
      class="modal"
      ref="cropperModal"
      width="50%"
      @close="crop"
      overlay-theme="dark"
    >
      <div class="inner-container">
        <img ref="uploadedImage" src="" alt="top-bar">
      </div>
    </sweet-modal>
  </div>
</template>

<script>
  import Cropper from 'cropperjs'

  export default {
    name: 'ycms-image-uploader',

    data() {
      return {
        originalName: '',
        imgType: '',
        cropper: {},
        cropped: {},
        croppedBlob: {},
        blob: {},
        base64: '',
        innerCropperHidden: true,
        minWidth: 0,
        minHeight: 0,
        dimesionsFine: undefined,
      }
    },

    props: {
      label: String,
      name: String,
      type: String,
      ratio: Number,
      cropInModal: {type: Boolean, default: true},
      fullWidth: Boolean,
      previewImg: String,
      size: {type: String, default: '31px'},
      icon: {type: String, default: 'tag'},
      minDimensions: String,
      imageUrl: String,
    },

    computed: {

      fileName() {
        let label = this.label !== 'null' ? this.label : false
        return this.originalName || label || 'please select a file'
      },

      src() {
        return this.base64 || this.previewImg || this.imageUrl || `/img/no-image.png`
      }
    },

    mounted() {
      if (this.minDimensions) {
        if (this.minDimensions.includes('x')) {
          let match = this.minDimensions.match(/(\d+)x(\d+)/)
          this.minWidth = generic(match[1])
          this.minHeight = generic(match[2])
        } else {
          this.minWidth = generic(this.minDimensions)
          this.minHeight = generic(this.minDimensions)
        }
      }
    },

    methods: {

      onClick() {
        this.$refs.imageInput.click();
      },

      fileSelected($event) {
        let file = this.$refs.imageInput.files[0]
        this.originalName = file.name
        this.imgType = file.type

        let reader = new FileReader();
        reader.readAsDataURL(this.$refs.imageInput.files[0])
        reader.onload = event => {
          if (this.minDimensions) {
            this.checkDimensions(event)
            let warningMessage =
                `
            Minimal dimension is
            ${this.minDimensions.includes('x')
                    ? this.minDimensions
                    : `${this.minDimensions}x${this.minDimensions}`
                }
            px'
            `
            this.openCropper(event, reader)
          } else {
            this.openCropper(event, reader)
          }
        }
      },

      crop() {
        // TODO: fix big image uploads
        this.cropper.getCroppedCanvas().toBlob(blob => {
          let ext = this.imgType.split('/')[1]

          let file = new File(
              [blob],
              `cropped.${ext}`,
              {type: this.imgType, lastModified: Date.now()}
          )

          let list = new DataTransfer()
          list.items.add(file)

          this.$refs.croppedImage.files = list.files

          // Add types to array to calculate base64 from blob
          if (['icon', 'square-preview', 'app-icon', 'startup-image'].includes(this.type)) {
            let reader = new FileReader()
            reader.readAsDataURL(blob)
            reader.onloadend = () => {
              this.base64 = reader.result
              // dimensions(reader.result)
            }
          }
          // this.locStor.put(this.name, this.$refs.croppedImage.files[0])
        }, this.imgType)

        if (this.type === 'app-icon'){
          this.$refs.innerCropperApp.hidden = true
        }
        else if (this.type === 'startup-image'){
          this.$refs.innerCropperStartup.hidden = true
        }
        else{
          this.$refs.innerCropper.hidden = true
        }
      },

      checkDimensions(e) {
        let img = new Image
        img.src = e.target.result
        let checked
        let comp = this
        img.onload = function() {
          comp.dimesionsFine =
              this.width >= comp.minWidth && this.height >= comp.minHeight
        }
      },

      openCropper(event, reader) {
        let img
        console.log("openCropper")
        if (this.type === "app-icon" && !this.cropInModal) {
          img = this.$refs.innerCropperAppImg
          this.$refs.innerCropperApp.hidden = false
        } else if (this.type === "startup-image") {
          img = this.$refs.innerCropperStartupImg
          this.$refs.innerCropperStartup.hidden = false
        } else {
          if (this.cropInModal) {
            img = this.$refs.uploadedImage
            this.$refs.cropperModal.open()
          } else {
            img = this.$refs.innerCropperImg
            this.$refs.innerCropper.hidden = false
          }
        }

        img.src = event.target.result
        if (typeof this.cropper.destroy === 'function') {
          this.cropper.destroy()
        }
        this.cropper = new Cropper(img, {aspectRatio: this.ratio})
        // FIXME: not working (exact cropped image dimensions)
        // this.cropper.getCroppedCanvas({width: this.minWidth})
      }
    },
  }
</script>

<style lang="scss" scoped>


  .image-cropper {
    font-size: 12px;
    font-weight: 300;
    color: #0997b1;
    cursor: pointer;
  }

  .modal {
    .inner-container {
      width: 100%;
      height: 100%;
    }

    img {
      height: 50vh;
    }
  }

  .icon-container {

    background-image: linear-gradient(45deg, #2ccae6, #0997b1);
    box-shadow: 0 10px 20px 0 rgba(0, 0, 0, 0.2);
    // width: 31px;
    // height: 31px;
    border-radius: 50%;
    color: white;
    // font-size: 16px;
    margin-left: 32px;
    overflow: hidden;

    >img {
      width: 100%;
      height: 100%;
      object-fit: cover;
    }
  }

  .square-preview {
    display: flex;
    flex-direction: column;
    align-items: center;
    width: 204px;
    height: 204px;
    border-radius: 10px;
    box-shadow: 0 3px 6px 0 rgba(0, 0, 0, 0.16);
    background-color: white;

    > img {
      margin-top: 22px;
      width: 149px;
      height: 149px;
      object-fit: contain;
      border: solid 1px #707070;
    }

    .button-container {

      flex: 1;

      .button {

        width: 78px;
        height: 17.2px;
        object-fit: contain;
        border-radius: 22px;
        background-image: linear-gradient(258deg, #50b109, #0997b1);
        font-size: 9px;
        font-weight: 600;
        color: white;
      }
    }
  }

  .app-icon {
    /*display: flex;*/
    width: 404px;
    height: 404px;

    .img-container {

      align-items: initial;
      flex: 1;
      padding-top: 5px;

      > img {
        background-position: center;
        background-repeat: no-repeat;
        background-size: cover;
        border-radius: 10px;
        width: 149px;
        height: 149px;
        border: solid 1px #707070;
      }
    }

    .button-container {

      align-items: initial;
      flex: 1;

      .button {

        width: 128px;
        height: 37.2px;
        object-fit: contain;
        border-radius: 22px;
        background-image: linear-gradient(258deg, #50b109, #0997b1);
        font-size: 9px;
        font-weight: 600;
        color: white;
      }
    }
  }

  .startup-image {
    text-align: center;
    width: 404px;
    height: 304px;

    .img-container {
      align-items: initial;
      flex: 1;
      height: 204px;
      padding-top: 5px;
      margin: auto;
      width: 60%;


      > img.vert1 {
        margin: 2px;
        vertical-align: top;
        border-radius: 5px;
        width: 43px;
        height: 83px;
        border: solid 1px #707070;
      }
      > img.vert2 {
        margin: 2px;
        vertical-align: top;
        border-radius: 5px;
        width: 43px;
        height: 71px;
        border: solid 1px #707070;
      }
      > img.vert3 {
        margin: 2px;
        vertical-align: top;
        border-radius: 5px;
        width: 97px;
        height: 140px;
        border: solid 1px #707070;
      }
      > img.hor1 {
        margin: -48px 20px 2px 22px;
        float: left;
        vertical-align: top;
        border-radius: 5px;
        width: 98px;
        height: 47px;
        border: solid 1px #707070;
      }
    }

    .button-container {

      align-items: initial;
      flex: 1;

      .button {

        width: 128px;
        height: 37.2px;
        object-fit: contain;
        border-radius: 22px;
        background-image: linear-gradient(258deg, #50b109, #0997b1);
        font-size: 9px;
        font-weight: 600;
        font-size: 9px;
        font-weight: 600;
        color: white;
      }
    }
  }

  .inner-cropper {
    display: flex;
    flex-direction: column;
    margin-top: 10px;
    width: 100%;
    height: 100%;

    .button {

      background-image: linear-gradient(250deg, #50b109, #0997b1);
      color: white;
      border-radius: 5px;
      margin-bottom: 10px;
      font-weight: 700;
      height: 31px;
      font-size: 14px;
    }
  }
  .inner-cropper-app {
    display: flex;
    flex-direction: column;
    margin-top: -100px;
    margin-left: -500px;
    width: 100%;

    .button {

      background-image: linear-gradient(250deg, #50b109, #0997b1);
      color: white;
      border-radius: 5px;
      margin-bottom: 10px;
      font-weight: 700;
      height: 31px;
      font-size: 14px;
    }
  }
  .inner-cropper-startup {
    display: flex;
    flex-direction: column;
    margin-top: -500px;
    margin-left: -200px;
    width: 100%;

    .button {

      background-image: linear-gradient(250deg, #50b109, #0997b1);
      color: white;
      border-radius: 5px;
      margin-bottom: 10px;
      font-weight: 700;
      height: 31px;
      font-size: 14px;
    }
  }
</style>
