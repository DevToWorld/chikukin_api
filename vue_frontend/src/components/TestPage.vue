<template>
  <div style="padding: 20px; background-color: #f0f0f0; min-height: 100vh;">
    <h1 style="color: red; font-size: 2rem; margin-bottom: 20px;">🔥 Vue.js テストページ</h1>
    <p style="font-size: 1.2rem; color: #1A1A1A; margin-bottom: 10px;">このページが表示されていれば、Vue.jsは正常に動作しています！</p>
    <p style="font-size: 1rem; color: #666; margin-bottom: 20px;">現在の時刻: {{ currentTime }}</p>
    
    <button 
      @click="updateTime" 
      style="background-color: #007bff; color: white; padding: 10px 20px; border: none; border-radius: 5px; cursor: pointer; margin-right: 10px;"
    >
      時刻を更新
    </button>
    
    <button 
      @click="goToAdmin" 
      style="background-color: #28a745; color: white; padding: 10px 20px; border: none; border-radius: 5px; cursor: pointer;"
    >
      管理画面へ
    </button>
    
    <div style="margin-top: 20px; padding: 15px; background-color: white; border-radius: 5px; border: 1px solid #ddd;">
      <h3 style="color: #1A1A1A; margin-bottom: 10px;">カウンター: {{ counter }}</h3>
      <button 
        @click="counter++" 
        style="background-color: #ffc107; color: black; padding: 5px 15px; border: none; border-radius: 3px; cursor: pointer; margin-right: 5px;"
      >
        +1
      </button>
      <button 
        @click="counter--" 
        style="background-color: #dc3545; color: white; padding: 5px 15px; border: none; border-radius: 3px; cursor: pointer;"
      >
        -1
      </button>
    </div>
  </div>
</template>

<script>
export default {
  name: 'TestPage',
  data() {
    return {
      currentTime: new Date().toLocaleString('ja-JP'),
      counter: 0
    }
  },
  methods: {
    updateTime() {
      this.currentTime = new Date().toLocaleString('ja-JP')
    },
    goToAdmin() {
      this.$router.push('/admin')
    }
  },
  mounted() {
    console.log('🎉 TestPage mounted successfully!')
  }
}
</script>
