<template>
  <div class="button-4">
    <div class="text-7 valign-text-middle inter-bold-white-15px">一覧を表示</div>
    <frame13213176122 />
  </div>
</template>

<script>
import Frame13213176122 from "./Frame13213176122.vue";
export default {
  name: "XButton3",
  components: {
    Frame13213176122,
  },
};
</script>

<style>
.button-4 {
  align-items: center;
  background-color: var(--black);
  border-radius: 10px;
  box-shadow: 0px 1px 2px #0000000d;
  display: inline-flex;
  flex: 0 0 auto;
  gap: 10px;
  padding: 10px 40px;
  position: relative;
}

.text-7 {
  letter-spacing: 0;
  line-height: 22.5px;
  margin-top: -1px;
  position: relative;
  white-space: nowrap;
  width: fit-content;
}
</style>
