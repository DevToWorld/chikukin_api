<template>
  <div class="frame-132131752-2">
    <router-link v-if="frame13213175222Props.link" :to="frame13213175222Props.link" class="footer-link">
      <frame13213175222 :text19="frame13213175222Props.text19" />
    </router-link>
    <frame13213175222 v-else :text19="frame13213175222Props.text19" />
    <router-link v-if="frame13213175251Props.link && frame13213175251Props.text22" :to="frame13213175251Props.link" class="footer-link">
      <frame13213175255 :text22="frame13213175251Props.text22" />
    </router-link>
    <frame13213175255 v-else-if="frame13213175251Props.text22" :text22="frame13213175251Props.text22" />
    <router-link v-if="frame13213175252Props.link && frame13213175252Props.text22" :to="frame13213175252Props.link" class="footer-link">
      <frame13213175255 :text22="frame13213175252Props.text22" />
    </router-link>
    <frame13213175255 v-else-if="frame13213175252Props.text22" :text22="frame13213175252Props.text22" />
  </div>
</template>

<script>
import Frame13213175222 from "./Frame13213175222";
import Frame13213175255 from "./Frame13213175255";
export default {
  name: "Frame13213175273",
  components: {
    Frame13213175222,
    Frame13213175255,
  },
  props: ["frame13213175222Props", "frame13213175251Props", "frame13213175252Props"],
};
</script>

<style>
.frame-132131752-2 {
  align-items: flex-start;
  display: flex;
  flex: 0 0 auto;
  flex-direction: column;
  gap: 5px;
  position: relative;
  width: 171px;
}
</style>
