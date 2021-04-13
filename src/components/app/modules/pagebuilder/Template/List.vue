<template>
  <div >
    <h6>Style template list</h6>
    <div v-for="template in templates" v-bind:key="template.id">
      {{template.name}}
      <a @click="openTemplateModal(template)" >update</a>
      <a @click="deleteTemplateConfirm(template.id)" >trash</a>
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
