<template>
  <div v-if="loading" class="loading-container">
    <p>読み込み中...</p>
  </div>
  <div v-else-if="error" class="error-container">
    <p>{{ error }}</p>
  </div>
  <Screen2 v-else v-bind="screen2Props" />
</template>

<script>
import Screen2 from './Screen2.vue';
import { screen2Data } from '../data';
import axios from 'axios';
import { getApiUrl } from '@/config/api';

export default {
  name: 'HomePage',
  components: {
    Screen2
  },
  data() {
    return {
      screen2Props: { ...screen2Data },
      pageData: null,
      loading: true,
      error: null
    };
  },
  async mounted() {
    try {
      const response = await axios.get(getApiUrl('/api/pages/home'));
      this.pageData = response.data;
      
      // CMSデータがある場合は、該当するプロパティを上書き
      if (this.pageData && this.pageData.content) {
        const content = this.pageData.content;
        
        // ヒーローセクションのテキストを更新
        if (content.hero_title) {
          this.screen2Props.text66 = content.hero_title;
        }
        if (content.hero_subtitle) {
          this.screen2Props.text67 = content.hero_subtitle;
        }
        
        // About セクションを更新
        if (content.about_text) {
          this.screen2Props.text113 = content.about_text;
        }
        
        // その他のコンテンツも必要に応じて更新
      }
      
      this.loading = false;
    } catch (err) {
      console.error('Failed to fetch page data:', err);
      // CMSデータ取得に失敗してもデフォルトデータで表示
      this.loading = false;
    }
  }
};
</script>

<style scoped>
.loading-container,
.error-container {
  display: flex;
  justify-content: center;
  align-items: center;
  min-height: 100vh;
  font-size: 1.2rem;
}

.error-container {
  color: red;
}
</style>