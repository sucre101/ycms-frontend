<template>
  <div style="width: 100%">
    <h6>Set style properties</h6>
    <div class="template-element">
      <label for="st_name">Name</label>
      <input class="rounded-input" v-model="template_.name" placeholder="name" id="st_name" />
    </div><br>
    <div class="template-element">
      <label for="st_width">Width</label>
      <input class="rounded-input" v-model="template_.width" placeholder="width"  id="st_width" />
    </div>
    <div class="template-element">
      <label for="st_height">Height</label>
      <input class="rounded-input" v-model="template_.height" placeholder="height" id="st_height" />
    </div>
    <div class="template-element">
      <label for="st_margin">Margin</label>
      <input class="rounded-input" v-model="template_.margin" placeholder="margin" id="st_margin" />
    </div>
    <div class="template-element">
      <label for="st_padding">Padding</label>
      <input class="rounded-input" v-model="template_.padding" placeholder="padding" id="st_padding" />
    </div>
    <div class="template-element">
      <label for="st_border_width">Border width</label>
      <input class="rounded-input" v-model="template_.border_width" placeholder="border width" id="st_border_width" />
    </div>
    <div class="template-element">
      <label for="st_border_color">Border color</label>
      <input type="color" v-model="template_.border_color" id="st_border_color" />
    </div>
    <div class="template-element">
      <label for="st_border_style">Border style</label>
      <input class="rounded-input" v-model="template_.border_type" placeholder="border style"  id="st_border_style"/>
    </div>
    <div class="template-element">
      <label for="st_border_radius">Border radius</label>
      <input class="rounded-input" v-model="template_.border_radius" placeholder="border radius"  id="st_border_radius" />
    </div>
    <div class="template-element">
      <label for="st_color">Color</label>
      <input type="color" v-model="template_.color"  id="st_color"/>
    </div>
    <div class="template-element">
      <label for="st_bg_color">Background color</label>
      <input type="color" v-model="template_.bg_color"  id="st_bg_color"/>
    </div>
    <div class="template-element">
      <label for="st_text_shadow">Text shadow</label>
      <input class="rounded-input" v-model="template_.text_shadow" placeholder="text shadow"  id="st_text_shadow" />
    </div>
    <div class="template-element">
      <label for="st_box_shadow">Box shadow</label>
      <input class="rounded-input" v-model="template_.box_shadow" placeholder="box shadow" id="st_box_shadow" />
    </div>
    <div class="template-element">
      <label for="st_overflow">Overflow</label>
      <input class="rounded-input" v-model="template_.overflow" placeholder="overflow"  id="st_overflow" />
    </div>
    <div class="template-element">
      <label for="st_text_align">Text align</label>
      <input class="rounded-input" v-model="template_.text_align" placeholder="text align "  id="st_text_align" />
    </div>
    <a v-if="template_.id" class="small-rounded-btn code-bg text-white"  @click="updateTemplate">Update template</a>
    <a v-else class="small-rounded-btn code-bg text-white"  @click="storeTemplate">Store template</a>
  </div>
</template>
<script>
  export default {
    name: 'template-modal',
    props: {
      template: Object,
    },
    watch: {
      template: function (newv, oldv) {
        this.template_ = newv
      }
    },
    data() {
      return {
        template_: this._.cloneDeep(this.template),
      }
    },
    methods: {
      storeTemplate(){
        this.$emit('store-template', this.template_);
      },
      updateTemplate(){
        this.$emit('update-template', this.template_);
      },
    },
  }
</script>

<style>
  .template-element{
    width: 49%;
    display: inline-block;
  }
  .template-element>label{
    width: 100%;
  }
</style>
