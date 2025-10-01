<template>
  <div class="page-container">
    <Navigation />
    <div class="page-content">
      <div class="services-header">
        <h1>会員サービスのご案内</h1>
      </div>
      <div class="services-grid">
        <div class="service-card">
          <div class="service-icon">📊</div>
          <h3>経済レポート</h3>
          <p>地域経済の最新動向を詳しく分析したレポートを定期的にお届けします。</p>
          <ul>
            <li>月次経済レポート</li>
            <li>四半期業界分析</li>
            <li>年次経済白書</li>
          </ul>
        </div>
        
        <div class="service-card">
          <div class="service-icon">💼</div>
          <h3>コンサルティング</h3>
          <p>経営課題の解決に向けた専門的なアドバイスを提供します。</p>
          <ul>
            <li>経営戦略立案支援</li>
            <li>事業計画策定</li>
            <li>市場調査・分析</li>
          </ul>
        </div>
        
        <div class="service-card">
          <div class="service-icon">🎓</div>
          <h3>セミナー・研修</h3>
          <p>最新の経済動向や経営手法を学べるセミナーを開催しています。</p>
          <ul>
            <li>経営者向けセミナー</li>
            <li>管理職研修</li>
            <li>専門スキル講座</li>
          </ul>
        </div>
        
        <div class="service-card">
          <div class="">📈</div>
          <h3>データベース</h3>
          <p>地域経済に関する豊富なデータベースをご利用いただけます。</p>
          <ul>
            <li>企業情報データベース</li>
            <li>経済統計データ</li>
            <li>業界動向資料</li>
          </ul>
        </div>
      </div>
      
      <div class="membership-info">
        <h2>会員特典</h2>
        <div class="benefits">
          <div class="benefit-item">
            <span class="check">✓</span>
            <span>全てのレポートへのアクセス</span>
          </div>
          <div class="benefit-item">
            <span class="check">✓</span>
            <span>セミナー参加費の割引</span>
          </div>
          <div class="benefit-item">
            <span class="check">✓</span>
            <span>個別相談サービス（月1回）</span>
          </div>
          <div class="benefit-item">
            <span class="check">✓</span>
            <span>データベースの無制限利用</span>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import Navigation from "./Navigation.vue";

export default {
  name: "ServicesPage",
  components: {
    Navigation
  }
};
</script>

<style scoped>
.page-container {
  min-height: 100vh;
  background-color: #f8f9fa;
}

.page-content {
  max-width: 1200px;
  margin: 0 auto;
  padding: 40px 20px;
}

.services-header {
  text-align: center;
  margin-bottom: 40px;
}

.services-header h1 {
  font-size: 2.5rem;
  color: #1A1A1A;
}

.services-grid {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
  gap: 30px;
  margin-bottom: 60px;
}

.service-card {
  background: white;
  padding: 30px;
  border-radius: 10px;
  box-shadow: 0 2px 10px rgba(0,0,0,0.1);
  transition: transform 0.3s;
}

.service-card:hover {
  transform: translateY(-5px);
  box-shadow: 0 5px 20px rgba(0,0,0,0.15);
}

.service-icon {
  font-size: 3rem;
  margin-bottom: 20px;
  text-align: center;
}

.service-card h3 {
  color: #007bff;
  margin-bottom: 15px;
}

.service-card p {
  color: #666;
  margin-bottom: 20px;
  line-height: 1.6;
}

.service-card ul {
  list-style: none;
  padding: 0;
}

.service-card li {
  padding: 5px 0;
  color: #555;
  position: relative;
  padding-left: 20px;
}

.service-card li::before {
  content: "•";
  color: #007bff;
  position: absolute;
  left: 0;
}

.membership-info {
  background: white;
  padding: 40px;
  border-radius: 10px;
  box-shadow: 0 2px 10px rgba(0,0,0,0.1);
}

.membership-info h2 {
  color: #007bff;
  margin-bottom: 30px;
  text-align: center;
}

.benefits {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
  gap: 20px;
}

.benefit-item {
  display: flex;
  align-items: center;
  gap: 10px;
}

.check {
  color: #28a745;
  font-size: 1.5rem;
  font-weight: bold;
}
</style>