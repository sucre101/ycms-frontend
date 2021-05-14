<template>
  <div >
    <h6>Style template list</h6>
    <div v-for="template in templates" v-bind:key="template.id">
      {{template.name}}
      <i class="fa fa-edit"  v-if="template.user_module_id" @click="openTemplateModal(template)" ></i>
      <i class="fa fa-trash" v-if="template.user_module_id" @click="deleteTemplateConfirm(template.id)" ></i>
    </div>
    <div class="text-muted"> If you don't find needed style, create your own</div>
    <a class="small-rounded-btn code-bg text-white"  @click="openTemplateModal(null)">Create template</a>
  </div>
</template>
<script>

  export default {
    name: 'templates-list',
    props: {
      templates: Array
    },
    data() {
      return {
      }
    },
    watch: {
      templates: function (newv, oldv) {
        this.templates = newv
      }
    },
    methods: {
      openTemplateModal(template){
        this.$emit('open-template', template, 'block');
      },
      deleteTemplateConfirm(id){
        this.$emit('delete-template-confirm', id);
      },
    },
    mounted() {
    }
  }
</script>

<style>

</style>
