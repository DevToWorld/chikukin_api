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
  name: "ViewComponent",
};
</script>

<style>
.view-4 {
  height: 42.01px;
  position: relative;
  width: 53.99px;
  transition: all 0.3s ease;
}

.overlap-group-4 {
  height: 42px;
  position: relative;
  width: 54px;
  transition: all 0.3s ease;
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
  transition: all 0.3s ease;
  object-fit: contain;
}

/* レスポンシブ対応 - ロゴサイズを調整 */
@media (max-width: 1200px) {
  .view-4 {
    height: 38px;
    width: 48px;
  }
  .overlap-group-4 {
    height: 38px;
    width: 48px;
  }
  .logo-icon {
    height: 38px;
    width: 48px;
  }
}

@media (max-width: 1000px) {
  .view-4 {
    height: 35px;
    width: 44px;
  }
  .overlap-group-4 {
    height: 35px;
    width: 44px;
  }
  .logo-icon {
    height: 35px;
    width: 44px;
  }
}

@media (max-width: 800px) {
  .view-4 {
    height: 32px;
    width: 40px;
  }
  .overlap-group-4 {
    height: 32px;
    width: 40px;
  }
  .logo-icon {
    height: 32px;
    width: 40px;
  }
}

@media (max-width: 600px) {
  .view-4 {
    height: 28px;
    width: 35px;
  }
  .overlap-group-4 {
    height: 28px;
    width: 35px;
  }
  .logo-icon {
    height: 28px;
    width: 35px;
  }
}

@media (max-width: 480px) {
  .view-4 {
    height: 24px;
    width: 30px;
  }
  .overlap-group-4 {
    height: 24px;
    width: 30px;
  }
  .logo-icon {
    height: 24px;
    width: 30px;
  }
}
</style>
