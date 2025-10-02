<template>
  <div class="view-4">
    <router-link to="/" class="logo-link">
      <div class="overlap-group-4">
        <img
          class="logo-icon"
          src="/img/ico-logo-1-1.svg"
          alt="ちくぎん地域経済研究所"
        />
      </div>
    </router-link>
  </div>
</template>

<script>
export default {
  name: "View",
};
</script>

<style>
.view-4 {
  height: 42.01px;
  position: relative;
  width: 53.99px;
}

.overlap-group-4 {
  height: 42px;
  position: relative;
  width: 54px;
}

.logo-link {
  display: block;
  text-decoration: none;
  color: inherit;
  transition: all 0.3s ease;
}

.logo-link:hover {
  opacity: 0.8;
  transform: scale(1.05);
}

.logo-icon {
  height: 42px;
  width: 54px;
  position: absolute;
  top: 0;
  left: 0;
  object-fit: contain;
}
</style>
