<template>
  <footer class="footer">
    <div class="footer-content">
      <div class="footer-section">
        <h3>会社情報</h3>
        <ul>
          <li><router-link to="/">トップ</router-link></li>
          <li><router-link to="/company">会社概要</router-link></li>
          <li><router-link to="/services">会員サービスのご案内</router-link></li>
          <li><router-link to="/news">お知らせ</router-link></li>
          <li><router-link to="/faq">よくあるご質問</router-link></li>
        </ul>
      </div>

      <div class="footer-section">
        <h3>サービス</h3>
        <ul>
          <li><a href="#">セミナー</a></li>
          <li><a href="#">刊行物</a></li>
          <li><a href="#">経済指標一覧</a></li>
          <li><a href="#">経済調査統計一覧</a></li>
        </ul>
      </div>

      <div class="footer-section">
        <h3>会員サービス</h3>
        <ul>
          <li><a href="#">スタンダード会員</a></li>
          <li><a href="#">プレミアム会員</a></li>
          <li><a href="#">会員ログイン</a></li>
          <li><a href="#">入会案内</a></li>
        </ul>
      </div>

      <div class="footer-section">
        <h3>お問い合わせ</h3>
        <p class="contact-info">
          株式会社ちくぎん地域経済研究所<br>
          〒839-0864<br>
          福岡県久留米市百年公園1番1号<br>
          久留米リサーチセンタービル6階<br>
          TEL: 0942-46-5081<br>
          営業時間: 平日 9:00～17:00
        </p>
      </div>
    </div>

    <div class="footer-bottom">
      <div class="footer-bottom-content">
        <div class="footer-links">
          <a href="#">プライバシーポリシー</a>
          <span class="separator">|</span>
          <a href="#">利用規約</a>
          <span class="separator">|</span>
          <a href="#">特定商取引法に関する表記</a>
          <span class="separator">|</span>
          <a href="#">サイトマップ</a>
        </div>
        <div class="footer-logo">
          <img src="/img/ico-logo-1-1.svg" alt="ちくぎん地域経済研究所" />
        </div>
        <p class="copyright">© 2025 Chikugin Regional Economic Research Institute. All rights reserved.</p>
      </div>
    </div>
  </footer>
</template>

<script>
export default {
  name: "Footer"
};
</script>

<style scoped>
.footer {
  background: #2c3e50;
  color: white;
  margin-top: 80px;
}

.footer-content {
  max-width: 1200px;
  margin: 0 auto;
  padding: 60px 20px 40px;
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
  gap: 40px;
}

.footer-section h3 {
  color: white;
  font-size: 1.2rem;
  margin-bottom: 20px;
  padding-bottom: 10px;
  border-bottom: 2px solid #dc3545;
}

.footer-section ul {
  list-style: none;
  padding: 0;
  margin: 0;
}

.footer-section li {
  margin-bottom: 10px;
}

.footer-section a {
  color: #b0bec5;
  text-decoration: none;
  transition: color 0.3s;
}

.footer-section a:hover {
  color: #dc3545;
}

.contact-info {
  color: #b0bec5;
  line-height: 1.8;
  font-size: 0.95rem;
}

.footer-bottom {
  background: #1a252f;
  padding: 30px 20px;
}

.footer-bottom-content {
  max-width: 1200px;
  margin: 0 auto;
  text-align: center;
}

.footer-links {
  margin-bottom: 20px;
}

.footer-links a {
  color: #b0bec5;
  text-decoration: none;
  font-size: 0.9rem;
  transition: color 0.3s;
  margin: 0 10px;
}

.footer-links a:hover {
  color: #dc3545;
}

.separator {
  color: #546e7a;
  margin: 0 5px;
}

.footer-logo {
  margin: 20px 0;
}

.footer-logo img {
  height: 40px;
  opacity: 0.8;
}

.copyright {
  color: #7f8c8d;
  font-size: 0.85rem;
  margin-top: 20px;
}

@media (max-width: 768px) {
  .footer-content {
    grid-template-columns: 1fr;
  }
  
  .footer-links {
    display: flex;
    flex-direction: column;
    gap: 10px;
  }
  
  .separator {
    display: none;
  }
}
</style>