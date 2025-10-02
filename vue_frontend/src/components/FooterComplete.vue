<template>
  <div class="footer-wrapper">
    <div class="view-3">
      <div class="frame-1321317457-1">
        <img class="image-1" src="/img/---3.png" alt="image" />
        <img class="image-2" src="/img/---4.png" alt="image" />
        <img class="image-3" src="/img/---5.png" alt="image" />
        <img class="image-4" src="/img/---6.png" alt="image" />
      </div>
      <img
        class="vector-5-1"
        src="/img/vector-5.svg"
        alt="Vector 5"
      />
    </div>
    <div class="navigation-footer">
      <frame132131753022 v-bind="footerData" />
      <img
        class="vector-7-1"
        src="/img/vector-7.svg"
        alt="Vector 7"
      />
      <group27 />
    </div>
  </div>
</template>

<script>
import Frame132131753022 from "./Frame132131753022";
import Group27 from "./Group27";
import { frame132131753022Data } from "../data";

export default {
  name: "FooterComplete",
  components: {
    Frame132131753022,
    Group27
  },
  data() {
    return {
      footerData: frame132131753022Data
    };
  }
};
</script>

<style scoped>
.footer-wrapper {
  margin-top: 80px;
}

.view-3 {
  align-items: center;
  background-color: #CFCFCF;
  display: flex;
  flex-direction: column;
  gap: 50px;
  padding: 50px;
  position: relative;
  width: 100%;
}

.frame-1321317457-1 {
  align-items: center;
  align-self: stretch;
  display: flex;
  flex: 0 0 auto;
  gap: 22px;
  position: relative;
  width: 100%;
  justify-content: center;
}

.image-1 {
  height: 118px;
  object-fit: cover;
  position: relative;
  width: 318px;
}

.image-2 {
  height: 96px;
  object-fit: cover;
  position: relative;
  width: 319px;
}

.image-3 {
  height: 129px;
  object-fit: cover;
  position: relative;
  width: 319px;
}

.image-4 {
  height: 129px;
  object-fit: cover;
  position: relative;
  width: 318px;
}

.vector-5-1 {
  height: 1px;
  margin-bottom: -0.5px;
  object-fit: cover;
  position: relative;
  width: 100%;
  max-width: 1240px;
}

.navigation-footer {
  align-items: center;
  background-color: #CFCFCF;
  display: flex;
  flex-direction: column;
  gap: 50px;
  padding: 0px 100px 50px;
  position: relative;
  width: 100%;
}

.vector-7-1 {
  height: 1px;
  object-fit: cover;
  position: relative;
  width: 100%;
  max-width: 1240px;
}

@media (max-width: 1200px) {
  .navigation-footer {
    padding: 0px 50px 50px;
  }
  
  .view-3 {
    padding: 30px;
  }
  
  .image-1, .image-2, .image-3, .image-4 {
    width: 240px;
    height: auto;
  }
}

@media (max-width: 768px) {
  .navigation-footer {
    padding: 0px 20px 30px;
    gap: 30px;
  }
  
  .frame-1321317457-1 {
    flex-wrap: wrap;
    gap: 15px;
  }
  
  .image-1, .image-2, .image-3, .image-4 {
    width: 150px;
    height: auto;
  }
}
</style>