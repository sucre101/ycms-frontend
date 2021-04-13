<template>

  <div>

    <ListService v-if="!editService"/>

    <EditService v-else/>

  </div>


</template>

<script>
import YcmsActionButtons from "@/components/YcmsActionButtons"
import ListService from "./ListService"
import EditService from "./EditService"

export default {
  name: "index",

  components: {
    YcmsActionButtons, ListService, EditService
  },

  data() {
    return {
      loading: false,
      editService: false,
    }
  },

  created() {
    this.editService = this.$route.query.hasOwnProperty('service')
  }

}
</script>
