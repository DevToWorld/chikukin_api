<template>
  <div :class="[`card-1-1`, className || ``]">
    <div class="image-5" :style="{ 'background-image': 'url(' + image + ')' }">
      <frame1321317481 :text49="frame1321317481Props.text49" :text50="frame1321317481Props.text50" />
    </div>
  </div>
</template>

<script>
import Frame1321317481 from "./Frame1321317481.vue";
export default {
  name: "Card",
  components: {
    Frame1321317481,
  },
  props: ["image", "className", "frame1321317481Props"],
};
</script>

<style>
.card-1-1 {
  height: 330px;
  position: relative;
  width: 434px;
}

.image-5 {
  align-items: flex-end;
  background-position: 50% 50%;
  background-size: cover;
  border-radius: 10px;
  display: flex;
  height: 330px;
  overflow: hidden;
  position: relative;
  width: 434px;
}
</style>
