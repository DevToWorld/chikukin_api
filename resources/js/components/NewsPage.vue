<template>
  <div class="page-container">
    <Navigation />
    <div class="page-content">
      <div class="news-header">
        <h1>お知らせ</h1>
      </div>
      <div class="news-list">
        <article class="news-item">
          <div class="news-date">2024.03.15</div>
          <div class="news-category">セミナー</div>
          <div class="news-content">
            <h3>「地域経済の未来を考える」セミナー開催のお知らせ</h3>
            <p>4月20日（土）に経営者向けセミナーを開催いたします。最新の経済動向と今後の展望について解説します。</p>
          </div>
        </article>
        
        <article class="news-item">
          <div class="news-date">2024.03.01</div>
          <div class="news-category">レポート</div>
          <div class="news-content">
            <h3>2024年第1四半期経済レポート発行</h3>
            <p>最新の地域経済動向をまとめたレポートを発行しました。会員の皆様はマイページよりダウンロード可能です。</p>
          </div>
        </article>
        
        <article class="news-item">
          <div class="news-date">2024.02.15</div>
          <div class="news-category">お知らせ</div>
          <div class="news-content">
            <h3>新サービス「オンライン相談」開始</h3>
            <p>遠方の会員様向けにオンライン相談サービスを開始しました。詳細はお問い合わせください。</p>
          </div>
        </article>
        
        <article class="news-item">
          <div class="news-date">2024.02.01</div>
          <div class="news-category">セミナー</div>
          <div class="news-content">
            <h3>DXセミナー満員御礼</h3>
            <p>2月開催のDXセミナーは定員に達しました。次回開催は5月を予定しております。</p>
          </div>
        </article>
        
        <article class="news-item">
          <div class="news-date">2024.01.15</div>
          <div class="news-category">レポート</div>
          <div class="news-content">
            <h3>2023年度年次経済白書を発行</h3>
            <p>2023年の地域経済を総括した年次白書を発行しました。主要産業の動向と今後の展望を詳しく解説しています。</p>
          </div>
        </article>
      </div>
      
      <div class="pagination">
        <button class="page-btn">前へ</button>
        <span class="page-numbers">
          <span class="page-num active">1</span>
          <span class="page-num">2</span>
          <span class="page-num">3</span>
        </span>
        <button class="page-btn">次へ</button>
      </div>
    </div>
  </div>
</template>

<script>
import Navigation from "./Navigation.vue";

export default {
  name: "NewsPage",
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
  max-width: 1000px;
  margin: 0 auto;
  padding: 40px 20px;
}

.news-header {
  text-align: center;
  margin-bottom: 40px;
}

.news-header h1 {
  font-size: 2.5rem;
  color: #1A1A1A;
}

.news-list {
  background: white;
  border-radius: 10px;
  overflow: hidden;
  box-shadow: 0 2px 10px rgba(0,0,0,0.1);
}

.news-item {
  padding: 30px;
  border-bottom: 1px solid #e9ecef;
  display: grid;
  grid-template-columns: 100px 80px 1fr;
  gap: 20px;
  align-items: start;
  transition: background-color 0.3s;
}

.news-item:hover {
  background-color: #FDF6F7;
}

.news-item:last-child {
  border-bottom: none;
}

.news-date {
  color: #1A1A1A;
  font-size: 0.9rem;
  font-weight: 500;
}

.news-category {
  background: #007bff;
  color: white;
  padding: 4px 12px;
  border-radius: 20px;
  font-size: 0.85rem;
  text-align: center;
}

.news-content h3 {
  color: #1A1A1A;
  margin-bottom: 10px;
  font-size: 1.2rem;
  cursor: pointer;
}

.news-content h3:hover {
  color: #007bff;
}

.news-content p {
  color: #666;
  line-height: 1.6;
}

.pagination {
  display: flex;
  justify-content: center;
  align-items: center;
  gap: 20px;
  margin-top: 40px;
}

.page-btn {
  padding: 8px 16px;
  background: white;
  border: 1px solid #dee2e6;
  border-radius: 5px;
  cursor: pointer;
  transition: all 0.3s;
}

.page-btn:hover {
  background: #007bff;
  color: white;
  border-color: #007bff;
}

.page-numbers {
  display: flex;
  gap: 10px;
}

.page-num {
  width: 35px;
  height: 35px;
  display: flex;
  align-items: center;
  justify-content: center;
  border: 1px solid #dee2e6;
  border-radius: 5px;
  cursor: pointer;
  transition: all 0.3s;
}

.page-num:hover {
  background: #f8f9fa;
}

.page-num.active {
  background: #007bff;
  color: white;
  border-color: #007bff;
}

@media (max-width: 768px) {
  .news-item {
    grid-template-columns: 1fr;
    gap: 10px;
  }
  
  .news-category {
    width: fit-content;
  }
}
</style>