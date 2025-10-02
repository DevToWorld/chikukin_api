<template>
  <div class="page-header">
    <!-- Navigation -->
    <navigation />
  </div>
</template>

<script>
import Navigation from "./Navigation.vue";

export default {
  name: "PageHeader",
  components: {
    Navigation,
  }
};
</script>

<style scoped>
.page-header {
  width: 100%;
  min-width: 0;
  overflow: hidden;
}

/* レスポンシブ対応 */
@media (max-width: 600px) {
  .page-header {
    position: sticky;
    top: 0;
    z-index: 1000;
  }
}
</style>
