<template>
  <div class="breadcrumbs" v-if="breadcrumbs && breadcrumbs.length > 0">
    <router-link to="/" class="breadcrumb-item">トップ</router-link>
    <template v-for="(item, index) in breadcrumbs">
      <span :key="`separator-${index}`" class="breadcrumb-separator">></span>
      <router-link 
        v-if="item.link && index !== breadcrumbs.length - 1"
        :key="`item-${index}`"
        :to="item.link"
        class="breadcrumb-item"
      >
        {{ item.text || item }}
      </router-link>
      <span 
        v-else
        :key="`item-${index}`"
        class="breadcrumb-item"
        :class="{ current: index === breadcrumbs.length - 1 }"
      >
        {{ item.text || item }}
      </span>
    </template>
  </div>
</template>

<script>
export default {
  name: "Breadcrumbs",
  props: {
    breadcrumbs: {
      type: Array,
      default: () => []
    }
  }
};
</script>

<style scoped>
/* Breadcrumbs */
.breadcrumbs {
  padding: 24px 46px;
  display: flex;
  align-items: center;
  gap: 8px;
}

.breadcrumb-item {
  color: #3F3F3F;
  font-size: 12px;
  font-weight: 300;
  line-height: 5;
  text-decoration: none;
  transition: color 0.3s ease;
}

.breadcrumb-item:hover {
  color: #DA5761;
  text-decoration: underline;
}

.breadcrumb-item.current {
  color: #1A1A1A;
  font-weight: 500;
  cursor: default;
}

.breadcrumb-item.current:hover {
  color: #1A1A1A;
  text-decoration: none;
}

.breadcrumb-separator {
  color: #3F3F3F;
  font-size: 12px;
  font-weight: 300;
  line-height: 5;
}

/* レスポンシブ対応 */
@media (max-width: 1150px) {
  .breadcrumbs {
    padding: 22px 35px;
    gap: 6px;
  }
  
  .breadcrumb-item,
  .breadcrumb-separator {
    font-size: 11px;
  }
}

@media (max-width: 800px) {
  .breadcrumbs {
    padding: 20px 25px;
    gap: 5px;
  }
  
  .breadcrumb-item,
  .breadcrumb-separator {
    font-size: 10px;
    line-height: 4;
  }
}

@media (max-width: 600px) {
  .breadcrumbs {
    padding: 18px 20px;
    gap: 4px;
  }
  
  .breadcrumb-item,
  .breadcrumb-separator {
    font-size: 9px;
    line-height: 3.5;
  }
}

@media (max-width: 480px) {
  .breadcrumbs {
    padding: 15px 15px;
    gap: 3px;
  }
  
  .breadcrumb-item,
  .breadcrumb-separator {
    font-size: 8px;
    line-height: 3;
  }
}
</style>
