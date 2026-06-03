<template functional>
  <button
      type="button"
      class="colormate-field__color"
      :class="{ '-selected-color': props.color.handle === props.selectedColorHandle }"
      @click="listeners.select && listeners.select(props.color)"
  >
    <span
        class="colormate-field__color-inner"
        :style="'background-color:' + props.color.color + '; opacity:' + props.globalOpacity + ';'"
    ></span>
  </button>
</template>

<script>
export default {
  name: 'ColorButton',
  props: {
    color: { type: Object, required: true },
    selectedColorHandle: { type: String, default: null },
    globalOpacity: { type: [Number, String], default: 1 },
  },
};
</script>
