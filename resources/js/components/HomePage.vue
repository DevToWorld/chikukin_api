<template>
  <div class="page-container">
    <Navigation />
    <div class="page-content">
      <h1>トップページ</h1>
      <div class="hero-section">
        <h2>ちくぎん地域経済研究所へようこそ</h2>
        <p>地域経済の発展をサポートします</p>
      </div>
      <Screen2 v-bind="screen2Data" />
    </div>
  </div>
</template>

<script>
import Navigation from "./Navigation.vue";
import Screen2 from "./Screen2.vue";
import { screen2Data } from "../data";

export default {
  name: "HomePage",
  components: {
    Navigation,
    Screen2
  },
  data() {
    return {
      screen2Data
    };
  }
};
</script>

<style scoped>
.page-container {
  min-height: 100vh;
}

.page-content {
  padding: 20px;
}

.hero-section {
  text-align: center;
  padding: 60px 20px;
  background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
  color: white;
  margin-bottom: 40px;
  border-radius: 10px;
}

.hero-section h2 {
  font-size: 2.5rem;
  margin-bottom: 20px;
}

.hero-section p {
  font-size: 1.2rem;
}
</style>