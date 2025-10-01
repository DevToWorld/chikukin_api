<template>
  <div class="page-container">
    <Navigation />
    <div class="page-content">
      <div class="about-header">
        <h1>会社概要</h1>
      </div>
      <div class="about-content">
        <section class="company-info">
          <h2>株式会社ちくぎん地域経済研究所</h2>
          <table class="info-table">
            <tr>
              <th>設立</th>
              <td>1991年4月</td>
            </tr>
            <tr>
              <th>所在地</th>
              <td>〒830-0032 福岡県久留米市東町5-13</td>
            </tr>
            <tr>
              <th>電話番号</th>
              <td>0942-46-5081</td>
            </tr>
            <tr>
              <th>営業時間</th>
              <td>平日 9:00～17:00</td>
            </tr>
            <tr>
              <th>事業内容</th>
              <td>
                <ul>
                  <li>地域経済に関する調査研究</li>
                  <li>経営コンサルティング</li>
                  <li>セミナー・研修会の開催</li>
                  <li>情報提供サービス</li>
                </ul>
              </td>
            </tr>
          </table>
        </section>
        
        <section class="mission">
          <h2>私たちの使命</h2>
          <p>
            地域経済の持続的な発展と、地域企業の成長を支援することを使命としています。
            長年培ってきた知識と経験を活かし、地域に根ざした研究・コンサルティング活動を展開しています。
          </p>
        </section>
      </div>
    </div>
  </div>
</template>

<script>
import Navigation from "./Navigation.vue";

export default {
  name: "AboutPage",
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

.about-header {
  text-align: center;
  margin-bottom: 40px;
}

.about-header h1 {
  font-size: 2.5rem;
  color: #1A1A1A;
}

.about-content {
  background: white;
  padding: 40px;
  border-radius: 10px;
  box-shadow: 0 2px 10px rgba(0,0,0,0.1);
}

.company-info h2 {
  color: #007bff;
  margin-bottom: 30px;
}

.info-table {
  width: 100%;
  border-collapse: collapse;
}

.info-table th {
  background-color: #f8f9fa;
  padding: 15px;
  text-align: left;
  width: 150px;
  border-bottom: 2px solid #dee2e6;
}

.info-table td {
  padding: 15px;
  border-bottom: 1px solid #dee2e6;
}

.info-table ul {
  margin: 0;
  padding-left: 20px;
}

.mission {
  margin-top: 40px;
  padding-top: 40px;
  border-top: 2px solid #dee2e6;
}

.mission h2 {
  color: #007bff;
  margin-bottom: 20px;
}

.mission p {
  line-height: 1.8;
  color: #666;
}
</style>