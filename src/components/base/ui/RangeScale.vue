<template>
  <div class="__range __range-step">
    <div class="scale">
      <template v-for="n in 5">
          <span
            :key="n"
          ></span>
      </template>
    </div>
    <input type='range' min='1' max='5' value='1' step='1' class="n n5"/>
  </div>
</template>

<script>
export default {
  name: "range-scale"
}
</script>

<style scoped lang="scss">
.__range-step {
  position: relative;
  width: 50%;

  > div.scale {
    position: absolute;
    top: -20px;
    width: 100%;
    left: 0;
    display: flex;
    flex-direction: row;
    justify-content: space-between;
    height: 20px;

    span {
      height: 15px;
      width: 2px;
      background: grey;
      border-radius: 25px;
      z-index: -99;
    }

    span:nth-child(odd) {
      background: #0989cc;
    }

  }
}

input[type=range] {
  width: 102%;
  background-color: transparent;
  -webkit-appearance: none;
  display: block;
  margin-left: -1%;
  z-index: 0;
}

input[type=range]:focus {
  outline: none;
}

input[type=range]::-webkit-slider-runnable-track {
  background: #a4a4a4;
  border: 0;
  height: 3.2px;
  cursor: pointer;
}

input[type=range]::-webkit-slider-thumb {
  margin-top: -10px;
  background-image: url("@/assets/img/ycms/thumb-scale.svg");
  background-position: 0 center;
  background-repeat: no-repeat;
  width: 50px;
  height: 50px;
  cursor: grab;
  -webkit-appearance: none;
  z-index: 9999;
  margin-right: 50px;
}

input[type=range]:focus::-webkit-slider-runnable-track {
  background: #367ebd;
}

input[type=range]::-moz-range-track {
  background: #3071a9;
  border: 0;
  width: 100%;
  height: 3.2px;
  cursor: pointer;
}

input[type=range]::-moz-range-thumb {
  width: 16px;
  height: 36px;
  background: #ffffff;
  border: 2px solid #000000;
  border-radius: 3px;
  cursor: pointer;
}

input[type=range]::-ms-track {
  background: transparent;
  border-color: transparent;
  border-width: 17.4px 0;
  color: transparent;
  width: 100%;
  height: 3.2px;
  cursor: pointer;
}

input[type=range]::-ms-fill-lower {
  background: #2a6495;
  border: 0;
}

input[type=range]::-ms-fill-upper {
  background: #3071a9;
  border: 0;
}

input[type=range]::-ms-thumb {
  width: 16px;
  height: 36px;
  background: #ffffff;
  border: 1px solid #000000;
  border-radius: 3px;
  cursor: pointer;
  margin-top: 0;
  /*Needed to keep the Edge thumb centred*/
}

input[type=range]:focus::-ms-fill-lower {
  background: #3071a9;
}

input[type=range]:focus::-ms-fill-upper {
  background: #367ebd;
}

/*TODO: Use one of the selectors from https://stackoverflow.com/a/20541859/7077589 and figure out
how to remove the virtical space around the range input in IE*/
@supports (-ms-ime-align:auto) {
  /* Pre-Chromium Edge only styles, selector taken from hhttps://stackoverflow.com/a/32202953/7077589 */
  input[type=range] {
    margin: 0;
    /*Edge starts the margin from the thumb, not the track as other browsers do*/
  }
}
</style>
