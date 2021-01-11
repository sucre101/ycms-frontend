<template>
  <div >
    <div v-show="step === 1">
      <h6>Element type</h6>
      <span  class="element-types">
        <input id="elementHtml" class="radio-element" value="html"  v-model="element_.type"  name="radio-element" type="radio">
        <span>
          <label for="elementHtml" class="radio-element-label">
             html
          </label>
        </span>
      </span>
      <span  class="element-types">
        <input id="elementImage" class="radio-element" value="image"  v-model="element_.type"  name="radio-element" type="radio">
        <span>
          <label for="elementImage" class="radio-element-label ">
             image
          </label>
        </span>
      </span>
      <span  class="element-types">
        <input id="elementVideo" class="radio-element" value="video"  v-model="element_.type"  name="radio-element" type="radio">
        <span>
          <label for="elementVideo" class="radio-element-label">
             youtube
          </label>
        </span>
      </span>
      <span  class="element-types">
        <input id="elementButton" class="radio-element" value="button"  v-model="element_.type"  name="radio-element" type="radio">
        <span>
          <label for="elementButton" class="radio-element-label">
             button
          </label>
        </span>
      </span>
      <span  class="element-types">
        <input id="elementMap" class="radio-element" value="map"  v-model="element_.type"  name="radio-element" type="radio">
        <span>
          <label for="elementMap" class="radio-element-label ">
             map
          </label>
        </span>
      </span>
      <span  class="element-types">
        <input id="elementFrame" class="radio-element" value="iframe"  v-model="element_.type"  name="radio-element" type="radio">
        <span>
          <label for="elementFrame" class="radio-element-label ">
             iframe
          </label>
        </span>
      </span>
      <span  class="element-types">
        <input id="elementSlider" class="radio-element" value="slider"  v-model="element_.type"  name="radio-element" type="radio">
        <span>
          <label for="elementSlider" class="radio-element-label radio-label-slider">
             slider
          </label>
        </span>
      </span>
      <span  class="element-types">
        <h4>{{element_.type}}</h4>
      </span>
      <div v-if="element_.type === 'image' || element_.type === 'slider' ">
        <div v-if="element_.type === 'slider' && element_.images" >
            <span  v-for="image in element_.images" :key="image.id">
              <img :src="image.url_original"   width="150px" />
              <a @click="deleteSliderImageConfirm(image.id)" >delete</a>
            </span>
        </div>
        <input type="file"
               ref="elementImage"
               :multiple="element_.type === 'slider'"
        />
      </div>
      <div v-else-if="element_.type === 'html'">
        <ckeditor :editor="editor" v-model="element_.content" ></ckeditor>
      </div>
      <div v-else-if="element_.type === 'video'">
        <input type="text" v-model="element_.content" class="rounded-input">
        <br>
        <VideoPlayer
          v-if="element_.content"
          :src="element_.content"></VideoPlayer>
      </div>
      <div v-else-if="element_.type === 'button'">
        <input type="text" :value="element_.content?JSON.parse(element_.content).button_title:''" ref="button_title" @change="setButton" placeholder="title" class="rounded-input">
        <input type="text" :value="element_.content?JSON.parse(element_.content).button_link:''"  ref="button_link" @change="setButton" placeholder="link" class="rounded-input">
      </div>
      <div v-else-if="element_.type === 'map'" class="element-map">
        <gmap-place-input @place_changed="updatePlace" >
        </gmap-place-input>
      </div>
      <div v-else-if="element_.type === 'iframe'">
        <input type="text" v-model="element_.content" class="rounded-input">
      </div>
      <a class="small-rounded-btn code-bg text-white"  @click="goToStep(2)"> Next <i class="fa fa-chevron-right"></i></a>
    </div>
    <div v-show="step === 2">
      <h6>Template</h6>
      <div class="flex" v-for="template in templates" v-bind:key="template.id">
        <input type="radio" v-model="element_.template_id"  name="template" :value="template.id" :id="'temp'+template.id">
        <label :for="'temp'+template.id">{{template.name}}</label>
      </div>
      <br>
      <a class="small-rounded-btn code-bg text-white"  @click="goToStep(1)"> <i class="fa fa-chevron-left"></i> Back</a>
      <a v-if="element_.id" class="small-rounded-btn code-bg text-white"  @click="updateElement">Update element</a>
      <a v-else class="small-rounded-btn code-bg text-white"  @click="storeElement">Store element</a>
    </div>
  </div>
</template>
<script>
  import CKEditor from '@ckeditor/ckeditor5-vue';
  import ClassicEditor from '@ckeditor/ckeditor5-build-classic';
  import VideoPlayer from "./VideoPlayer";

  export default {
    name: 'element-modal',
    props: {
      element: Object,
      templates: Array,
    },
    components:{
      VideoPlayer,
      ckeditor: CKEditor.component
    },
    data() {
      return {
        place: null,
        editor: ClassicEditor,
        element_: this._.cloneDeep(this.element),
        step: 1,
      }
    },
    watch: {
      element: function (newv, oldv) {
        this.element_ = newv
      }
    },
    methods: {
      updatePlace(what) {
        this.element_.content = {
          address: what.name,
          lat: what.geometry.location.lat(),
          lng: what.geometry.location.lng()
        };
        this.element_.content = JSON.stringify(this.element_.content)
      },
      goToStep(st){
        this.step = st;
      },
      setButton(){
        let elem = {
          button_title: this.$refs.button_title.value,
          button_link: this.$refs.button_link.value,
        };
        this.element_.content = JSON.stringify(elem)
      },
      deleteSliderImageConfirm(id){
        this.deleteIdElement = id;
        this.notifier.confirm('Are you sure?', this.deleteSliderImage())
      },
      deleteSliderImage(){
        axios.post('/app/'+ this.slug +'/page-builder/element/delete_image', {
          id: this.deleteIdElement,
        })
        .then(res => {
          if(res.data.success === true){
            notifier.success('Slider image deleted successfully')
            this.element_.images = res.data.images
          }else{
            this.notifier.success('Error')
          }
          this.deleteIdElement = null
        });
      },
      updateElement(){
        this.deleteElementAttr()
        let images;
        if (this.element_.type === 'image' || this.element_.type === 'slider'){
          images = this.$refs.elementImage.files;
        }
        this.$emit('update-element', this.element_, images);
      },
      storeElement(){
        this.deleteElementAttr()
        delete this.element_.id

        let images;
        if (this.element_.type === 'image' || this.element_.type === 'slider'){
          images = this.$refs.elementImage.files;
        }
        this.$emit('store-element', this.element_, images);
      },
      deleteElementAttr(){
        delete this.element_.template
        delete this.element_.images

        this.step = 1;
      }
    },
  }
</script>

<style>
  .element-map *{
    width: 100%;
  }
  .element-map input{
    height: 43px;
    border-radius: 22px;
    padding-left: 22px;
  }
  .element-types{
    width: 100%;
    margin: 0 auto;
  }

  .element-types>h4{
    text-transform: capitalize;
  }

  .radio-element {
    opacity: 0;
    position: absolute;
  }

  .radio-element, .radio-element-label{
    display: inline-block;
    margin: 0 5px;
    cursor: pointer;
  }

  .radio-element-label{
    position: relative;
    color: #0997b1;
  }

  .radio-element:checked + span> .radio-element-label {
    color: #5518e7;
  }

</style>
