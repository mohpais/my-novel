<template>
  <v-lazy-image v-bind="$attrs" :src="props.src" />
</template>

<script setup>
import VLazyImage from "v-lazy-image";
const props = defineProps({
  src: {
    type: String,
    default: "",
    required: true,
  },
});
</script>

<style lang="scss">
.v-lazy-image {
  filter: blur(20px);
  transition: filter 0.7s;
}

.v-lazy-image-loaded {
  filter: blur(0);
}
</style>
