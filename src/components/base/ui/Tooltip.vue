<template>
  <span>
    <p>?</p>
    <div>
      {{ text }}
    </div>
  </span>
</template>

<script>
export default {
  name: "tooltip",

  props: {
    text: {
      type: String,
      default: 'Example text for tooltip'
    }
  }
}
</script>

<style scoped lang="scss">
span {
  display: flex;
  width: 21px;
  height: 21px;
  align-items: center;
  justify-content: center;
  border-radius: 50%;
  border: solid 0.5px #0989cc;
  cursor: pointer;
  position: relative;
  transition: 0.25s;

  :hover + div {
    display: block;
  }

  p {
    margin: 0;
    text-align: center;
    background: #7b7b7b;
    width: 80%;
    height: 80%;
    border-radius: 50%;
    font-size: 11px;
    color: white;
  }

  div {
    box-shadow: 0 5px 20px 0 rgba(0, 0, 0, 0.16);
    border: solid 1px #e5e5e5;
    background-image: -webkit-gradient(linear, left top, left bottom, from(#f7f7f7), color-stop(48%, var(--white)), to(#fafafa));
    background-image: linear-gradient(to bottom, #f7f7f7, var(--white) 48%, #fafafa);
    background-color: white;
    position: absolute;
    width: 210px;
    top: -12px;
    padding: 15px 15px;
    right: -215px;
    z-index: 999;
    text-align: center;
    border-radius: 5px;
    display: none;
    font-size: 12px;
    line-height: 1.17;
    color: #222222;
  }

  div:before {
    content: "";
    width: 12px;
    height: 12px;
    background: white;
    position: absolute;
    left: -6px;
    transform: rotate(45deg);
    box-shadow: 0 5px 20px 0 rgba(0, 0, 0, 0.16);
    background-image: -webkit-gradient(linear, left top, left bottom, from(#f7f7f7), color-stop(48%, var(--white)), to(#fafafa));
    background-image: linear-gradient(to bottom, #f7f7f7, var(--white) 48%, #fafafa);
    z-index: -99;
    display: block;
  }
}
</style>
