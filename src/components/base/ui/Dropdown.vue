<template>
  <div
    class="dropdown-select"
    v-click-outside="close"
  >
    <div
      class="selected"
      @click="open = !open"
      v-if="title"
    >
      {{ title }}
    </div>
    <ul v-if="open">
      <template v-for="(item, index) in list">
        <li
          :class="{'selected-item': item.selected }"
          :key="index"
          @click="select(item, index)"
        >
          {{ item.name }}
        </li>
      </template>
    </ul>
  </div>
</template>

<script>
export default {
  name: "dropdown",

  props: {

    multiple: {
      type: Boolean,
      default: false
    },

    list: {
      type: Array,
      required: true,
      default: () => {
        return []
      }
    },

    selected: {
      type: Array,
      default: () => {
        return []
      }
    },

    title: {
      type: String,
      default: ''
    }

  },

  data() {
    return {
      open: false,
      localSelected: this.selected,
    }
  },

  created() {
    if (this.localSelected.length && this.multiple) {

      this.localSelected.forEach(v => {
        let index = this._.findIndex(this.list, {value: v})

        if (index !== -1) {
          this.$set(this.list[index], 'selected', true)
        } else {
          this.$set(this.list[index], 'selected', false)
        }

      })
    } else if (this.localSelected.length && !this.multiple) {
      let index = this._.findIndex(this.list, {value: this.localSelected[0]})

      if (index !== -1) {
        this.$set(this.list[index], 'selected', true)
      } else {
        this.$set(this.list[index], 'selected', false)
      }

    } else if (this.localSelected.length > 1 && !this.multiple) {
      console.error('If props multiple is false, then selected must be of 1 element')
      return false
    }

  },

  methods: {

    close() {
      this.open = false
    },

    select(item, index) {
      // eslint-disable-next-line no-prototype-builtins
      if (this.multiple) {
        if (item.selected) {
          this._.pull(this.localSelected, item.value)
          this.$set(this.list[index], 'selected', false)
        } else {
          this.localSelected.push(item.value)
          this.$set(this.list[index], 'selected', true)
        }
      } else {

        this.localSelected.pop()

        this.localSelected.push(item.value)

        this.list.forEach(v => {
          this.$set(v, 'selected', false)
        })

        this.$set(this.list[index], 'selected', true)

      }

      this.complete(this.localSelected)
    },

    complete(data) {
      this.$emit('complete', data)
    }
  }
}
/*
*   Example for use
*
*    <Dropdown :list="[
*        { name: 'item 1', value: 1 },
*        { name: 'item 2', value: 2 },
*        { name: 'item 3', value: 3 },
*        { name: 'item 4', value: 4 },
*        { name: 'item 5', value: 6 },
*      ]" :multiple="false" :selected="[1]" @complete="check"
*    />
*/
</script>

<style scoped lang="scss">
.dropdown-select {
  max-width: 200px;
  width: 180px;
  box-shadow: 0 5px 11px 0 rgba(0, 0, 0, 0.16);
  background-color: #b5b5b5;
  font-size: 13px;
  position: absolute;
  top: 30px;

  .selected {
    text-align: center;
    color: white;
    position: relative;
    cursor: pointer;
  }

  .selected::after {
    content: "";
    width: 7px;
    height: 7px;
    margin-left: 5px;
    -webkit-transform: rotate(45deg);
    transform: rotate(45deg);
    border: 0 solid #0e88cc;
    border-right-width: 1.5px;
    border-bottom-width: 1.5px;
    display: inline-block;
    position: absolute;
    right: 8px;
    top: 5px;
  }

  ul {
    margin: 0;
    list-style-type: none;
    padding: 0 0 5px 30px;
    font-weight: 500;

    li {
      cursor: pointer;
      position: relative;
      line-height: 2;
    }

    li::before {
      content: "";
      width: 13px;
      height: 13px;
      border: 1px solid #0e88cc;
      display: inline-block;
      top: 7px;
      position: absolute;
      left: -20px;
    }

    li.selected-item::after {
      content: "";
      width: 7px;
      height: 7px;
      background: #0e88cc;
      display: inline-block;
      top: 10px;
      position: absolute;
      left: -17px;
    }
  }
}
</style>
