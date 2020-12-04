<template>
  <div class="ycms-cke">
    <input type="hidden" name="description" v-model="content">
    <ckeditor
      :editor="editor"
      v-model="content"
    />
  </div>
</template>

<script>
import CKEditor from '@ckeditor/ckeditor5-vue'
import ClassicEditor from '@ckeditor/ckeditor5-build-classic'

export default {
  name: 'ycms-cke',

  components: {
    ckeditor: CKEditor.component
  },

  data() {
    return {
      editor: ClassicEditor,
      content: '',
    }
  },

  props: {
  },

  methods: {
  },

  watch: {
    example(newVal, oldVal) {
    }
  },

  computed: {
    example() {
      return 'example'
    },
  },

  mounted() {
  }
}
</script>

<style lang="scss">
.ycms-cke {
  --ck-border-radius: 22px;
  --ck-color-toolbar-border: #868686;
  --ck-color-base-border: #868686;

  .ck-editor {
    width: 346px;
    border-radius: 22px;
  }

  .ck-editor__editable {
    min-height: 200px;
  }
}
</style>
