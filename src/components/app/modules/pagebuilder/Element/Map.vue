<template>
  <div class="container" :style="style_">
    <div class="google-map" ref="map"></div>
  </div>
</template>

<script>
  export default {
    name: 'google-map',
    data: function () {
      return {
        map: null
      }
    },
    props: {
      lat: {
        type: String
      },
      lng: {
        type: String
      },
      style_: {
        type: String
      }
    },
    mounted() {
      this.r()
    },
    methods: {
      async r(){
        this.map = new gMaps.Map(this.$refs.map, {
          center: {lat: parseFloat(this.lat), lng: parseFloat(this.lng)},
          zoom: 15,
        });
        new gMaps.Marker({
          position: {lat: parseFloat(this.lat), lng: parseFloat(this.lng)},
          map: this.map,
        });
      },
    }
  }
</script>

<style scoped>
  .google-map {
    width: 100%;
    height: 100%;
    background: gray;
  }
</style>
