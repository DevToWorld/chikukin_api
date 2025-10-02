<template>
  <div class="footer-container">
    <div class="frame-1321317530">
      <div class="frame-1321317521">
      <router-link to="/" class="footer-link">
        <frame13213175225 :text14="footer_link_home" />
      </router-link>
      <router-link to="/company" class="footer-link">
        <frame13213175225 :text14="footer_link_company" />
      </router-link>
      <router-link to="/aboutus" class="footer-link">
        <frame13213175225 :text14="footer_link_about" />
      </router-link>
      <router-link to="/news" class="footer-link">
        <frame13213175225 :text14="footer_link_news" />
      </router-link>
      <router-link to="/faq" class="footer-link">
        <frame13213175225 :text14="footer_link_faq" />
      </router-link>
      <router-link to="/contact" class="footer-link">
        <frame13213175225 :text14="footer_link_contact" />
      </router-link>
    </div>


    
    <div class="frame-1321317522">
      <router-link to="/seminar" class="footer-link">
        <frame13213175222 :text19="footer_link_seminar" />
      </router-link>
      <router-link to="/publication" class="footer-link">
        <frame13213175222 :text19="footer_link_publication" />
      </router-link>
      <frame13213175273
        :frame13213175222Props="frame132131752731Props.frame13213175222Props"
        :frame13213175251Props="frame132131752731Props.frame13213175251Props"
        :frame13213175252Props="frame132131752731Props.frame13213175252Props"
      />
      <router-link to="/glossary" class="footer-link">
        <frame13213175222 :text19="footer_link_glossary" />
      </router-link>
    </div>



    <div class="frame-1321317523">
      <div class="frame-container">
        <div class="frame-132131752-2">
          <router-link to="/services" class="footer-link">
            <frame13213175222 :text19="footer_link_membership" />
          </router-link>
          <router-link to="/membership/standard" class="footer-link">
            <frame13213175255 :text22="footer_link_membership_standard" />
          </router-link>
          <router-link to="/membership/premium" class="footer-link">
            <frame13213175255 :text22="footer_link_membership_premium" />
          </router-link>
        </div>
        <router-link to="/cri-consulting" class="footer-link">
          <frame13213175222 :text19="footer_link_cri_consulting" />
        </router-link>
      </div>
    </div>


    
    <div class="frame-1321317524-1">
      <div class="frame-container-1">
        <router-link to="/member-login" class="footer-link">
          <frame13213175223 :text29="footer_link_member_login" />
        </router-link>
        <router-link to="/legal" class="footer-link">
          <frame13213175223 :text29="footer_link_legal" :className="frame132131752232Props.className" />
        </router-link>
        <router-link to="/privacy" class="footer-link">
          <frame13213175223 :text29="footer_link_privacy" :className="frame132131752233Props.className" />
        </router-link>
        <router-link to="/terms" class="footer-link">
          <frame13213175223 :text29="footer_link_terms" :className="frame132131752234Props.className" />
        </router-link>
        <router-link to="/sitemap" class="footer-link">
          <frame13213175223 :text29="footer_link_sitemap" />
        </router-link>
      </div>
    </div>
  </div>
  
  <!-- Separator line -->
  <div class="vector-7-1"></div>
  
  <!-- Group27 component -->
  <group27 />
</div>
</template>

<script>
import Frame13213175225 from "./Frame13213175225";
import Frame13213175222 from "./Frame13213175222";
import Frame13213175273 from "./Frame13213175273";
import Frame132131752522 from "./Frame132131752522";
import Frame13213175223 from "./Frame13213175223";
import Frame13213175255 from "./Frame13213175255";
import Group27 from "./Group27";
import { usePageText } from '@/composables/usePageText'
export default {
  name: "Footer",
  components: {
    Frame13213175225,
    Frame13213175222,
    Frame13213175273,
    Frame132131752522,
    Frame13213175223,
    Frame13213175255,
    Group27,
  },
  props: [
    "frame132131752251Props",
    "frame132131752252Props",
    "frame132131752253Props",
    "frame132131752254Props",
    "frame132131752255Props",
    "frame132131752221Props",
    "frame132131752222Props",
    "frame132131752731Props",
    "frame132131752732Props",
    "frame1321317525221Props",
    "frame1321317525222Props",
    "frame132131752231Props",
    "frame132131752232Props",
    "frame132131752233Props",
    "frame132131752234Props",
    "frame132131752235Props",
  ],
  data() {
    return {
      _pageText: null,
    }
  },
  computed: {
    footer_link_home() { return this._pageText?.getText('link_home', this.frame132131752251Props?.text14 || 'トップページ') || 'トップページ' },
    footer_link_company() { return this._pageText?.getText('link_company', this.frame132131752252Props?.text14 || '会社概要') || '会社概要' },
    footer_link_about() { return this._pageText?.getText('link_about', '私たちについて') || '私たちについて' },
    footer_link_news() { return this._pageText?.getText('link_news', this.frame132131752253Props?.text14 || 'お知らせ') || 'お知らせ' },
    footer_link_faq() { return this._pageText?.getText('link_faq', this.frame132131752254Props?.text14 || 'よくある質問') || 'よくある質問' },
    footer_link_contact() { return this._pageText?.getText('link_contact', this.frame132131752255Props?.text14 || 'お問い合わせ') || 'お問い合わせ' },
    footer_link_seminar() { return this._pageText?.getText('link_seminar', this.frame132131752221Props?.text19 || 'セミナー') || 'セミナー' },
    footer_link_publication() { return this._pageText?.getText('link_publication', this.frame132131752222Props?.text19 || '刊行物') || '刊行物' },
    footer_link_glossary() { return this._pageText?.getText('link_glossary', '用語集') || '用語集' },
    footer_link_membership() { return this._pageText?.getText('link_membership', '入会案内') || '入会案内' },
    footer_link_membership_standard() { return this._pageText?.getText('link_membership_standard', this.frame1321317525221Props?.text26 || 'スタンダード') || 'スタンダード' },
    footer_link_membership_premium() { return this._pageText?.getText('link_membership_premium', this.frame1321317525222Props?.text26 || 'プレミアム') || 'プレミアム' },
    footer_link_cri_consulting() { return this._pageText?.getText('link_cri_consulting', 'CRI 経営コンサルティング') || 'CRI 経営コンサルティング' },
    footer_link_member_login() { return this._pageText?.getText('link_member_login', this.frame132131752231Props?.text29 || '会員ログイン') || '会員ログイン' },
    footer_link_legal() { return this._pageText?.getText('link_legal', this.frame132131752232Props?.text29 || '特定商取引法に関する表記') || '特定商取引法に関する表記' },
    footer_link_privacy() { return this._pageText?.getText('link_privacy', this.frame132131752233Props?.text29 || 'プライバシーポリシー') || 'プライバシーポリシー' },
    footer_link_terms() { return this._pageText?.getText('link_terms', this.frame132131752234Props?.text29 || '利用規約') || '利用規約' },
    footer_link_sitemap() { return this._pageText?.getText('link_sitemap', this.frame132131752235Props?.text29 || 'サイトマップ') || 'サイトマップ' },
  },
  mounted() {
    try {
      const p = usePageText('footer')
      this._pageText = p
      p.load({ force: true })
    } catch (e) { /* noop */ }
  }
};
</script>

<style>
/* Footer container */
.footer-container {
  display: flex;
  flex-direction: column;
  align-items: center;
  width: 100%;
  padding: 50px 100px;
  gap: 50px;
  background-color: #CFCFCF;
}

/* Vector separator line */
.vector-7-1 {
  height: 1px;
  background-color: #B2B2B2;
  position: relative;
  width: 100%;
  max-width: 1240px;
  margin: 0 auto;
}

/* Group27 component styling */
group27 {
  display: block;
  width: 100%;
}

.frame-1321317530 {
  align-items: flex-start;
  display: flex;
  flex: 0 0 auto;
  gap: 60px;
  position: relative;
  width: 100%;
  max-width: 1200px;
  flex-wrap: wrap;
  justify-content: center;
  min-height: auto;
  overflow: visible;
}

.frame-1321317521 {
  align-items: flex-start;
  display: flex;
  flex-direction: column;
  gap: 20px;
  position: relative;
  width: auto;
  min-width: 160px;
  max-width: 200px;
  flex: 0 0 auto;
}

.frame-1321317522 {
  align-items: flex-start;
  display: flex;
  flex-direction: column;
  gap: 20px;
  position: relative;
  width: auto;
  min-width: 180px;
  max-width: 220px;
  flex: 0 0 auto;
}

.frame-1321317523 {
  height: auto;
  min-height: 134px;
  position: relative;
  width: auto;
  min-width: 200px;
  max-width: 250px;
  flex: 0 0 auto;
}

.frame-container {
  align-items: flex-start;
  display: flex;
  flex-direction: column;
  gap: 20px;
  min-height: auto;
  height: auto;
}

.frame-132131752-2 {
  align-items: flex-start;
  display: flex;
  flex: 0 0 auto;
  flex-direction: column;
  gap: 5px;
  position: relative;
  width: 171px;
}



.frame-1321317522-1 {
  align-items: flex-start;
  display: flex;
  gap: 10px;
  height: 18px;
  width: 210px;
}

.text19 {
  align-self: center;
  height: auto;
  letter-spacing: 0;
  line-height: 1.5;
  margin-bottom: 2.75px;
  min-width: 190px;
  white-space: nowrap;
  font-size: 16px;
}

.frame-1321317523-1 {
  align-items: flex-start;
  display: flex;
  gap: 10px;
  height: 18px;
  width: 84px;
}



.frame-1321317524-1 {
  height: auto;
  position: relative;
  width: auto;
  min-width: 200px;
  max-width: 250px;
  flex: 0 0 auto;
}

.frame-container-1 {
  align-items: flex-start;
  display: flex;
  flex-direction: column;
  gap: 20px;
  min-height: auto;
  height: auto;
  position: relative;
}
.footer-link {
  text-decoration: none;
  color: inherit;
  display: block;
  transition: opacity 0.3s;
  word-wrap: break-word;
  overflow-wrap: break-word;
  hyphens: auto;
}

.footer-link:hover {
  opacity: 0.9;
}

/* navigation-footer スタイル */
.navigation-footer {
  background: #CFCFCF;
  padding: 100px;
  display: flex;
  flex-direction: column;
  align-items: center;
  gap: 50px;
  width: 100%;
  box-sizing: border-box;
  position: relative;
  z-index: 4;
}

.navigation-footer .vector-7-1 {
  height: 1px;
  background-color: #B2B2B2;
  position: relative;
  width: 100%;
  max-width: 1240px;
}

.navigation-footer .frame132131753022 {
  width: 100%;
  max-width: 1000px;
}

.navigation-footer .group27 {
  width: 100%;
  max-width: 1000px;
}

/* レスポンシブ対応 */
@media (max-width: 1150px) {
  .footer-container {
    padding: 40px 80px !important;
    gap: 40px !important;
  }
  
  .frame-1321317530 {
    gap: 40px !important;
    padding: 0 20px !important;
    max-width: 100% !important;
  }
  
  .frame-1321317521,
  .frame-1321317522 {
    width: auto !important;
    min-width: 140px !important;
    max-width: 180px !important;
  }
  
  .frame-1321317523 {
    width: auto !important;
    min-width: 160px !important;
    max-width: 200px !important;
  }
  
  .frame-1321317524-1 {
    width: auto !important;
    min-width: 160px !important;
    max-width: 200px !important;
    height: auto !important;
  }
  
  .navigation-footer {
    padding: 80px 50px !important;
    gap: 40px !important;
  }
}

@media (max-width: 800px) {
  .footer-container {
    padding: 30px 60px !important;
    gap: 30px !important;
  }
  
  .frame-1321317530 {
    gap: 30px !important;
    padding: 0 15px !important;
    min-height: auto !important;
  }
  
  .frame-1321317521,
  .frame-1321317522 {
    width: auto !important;
    min-width: 120px !important;
    max-width: 160px !important;
    gap: 15px !important;
  }
  
  .frame-1321317523 {
    width: auto !important;
    min-width: 140px !important;
    max-width: 180px !important;
    height: auto !important;
  }
  
  .frame-1321317524-1 {
    width: auto !important;
    min-width: 140px !important;
    max-width: 180px !important;
    height: auto !important;
  }
  
  .frame-container,
  .frame-container-1 {
    gap: 15px !important;
  }
  
  /* フッター全体のフォントサイズ統一 */
  .text19 {
    font-size: 14px !important;
    line-height: 20px !important;
  }
  
  /* フッターセクション全体のフォントサイズ調整 */
  .frame-1321317530 * {
    font-size: 14px !important;
  }
  
  .navigation-footer {
    padding: 60px 30px !important;
    gap: 30px !important;
  }
}

@media (max-width: 600px) {
  .footer-container {
    padding: 25px 40px !important;
    gap: 25px !important;
  }
  
  .frame-1321317530 {
    gap: 25px !important;
    padding: 0 10px !important;
    min-height: auto !important;
    display: grid !important;
    grid-template-columns: 1fr 1fr !important;
    grid-template-rows: auto auto !important;
    justify-items: center !important;
    align-items: flex-start !important;
  }
  
  .frame-1321317521,
  .frame-1321317522,
  .frame-1321317523,
  .frame-1321317524-1 {
    width: auto !important;
    min-width: 120px !important;
    max-width: 280px !important;
    text-align: left !important;
    height: auto !important;
  }
  
  .frame-container,
  .frame-container-1 {
    align-items: flex-start !important;
    gap: 12px !important;
  }
  
  .frame-132131752-2 {
    align-items: flex-start !important;
    width: 100% !important;
  }
  
  .frame-1321317522-1,
  .frame-1321317523-1 {
    justify-content: flex-start !important;
    width: 100% !important;
  }
  
  /* フッター全体のフォントサイズ統一 */
  .text19 {
    font-size: 13px !important;
    line-height: 18px !important;
    min-width: auto !important;
  }
  
  /* フッターセクション全体のフォントサイズ調整 */
  .frame-1321317530 * {
    font-size: 13px !important;
  }
  
  .navigation-footer {
    padding: 40px 20px !important;
    gap: 25px !important;
  }
}

@media (max-width: 480px) {
  .footer-container {
    padding: 30px !important;
    gap: 20px !important;
  }
  
  .frame-1321317530 {
    gap: 20px !important;
    padding: 0 8px !important;
    min-height: auto !important;
  }
  
  .frame-1321317521,
  .frame-1321317522,
  .frame-1321317523,
  .frame-1321317524-1 {
    width: auto !important;
    min-width: 100px !important;
    max-width: 260px !important;
    height: auto !important;
  }
  
  .frame-container,
  .frame-container-1 {
    gap: 10px !important;
  }
  
  .frame-132131752-2 {
    gap: 4px !important;
  }
  
  /* フッター全体のフォントサイズ統一 */
  .text19 {
    font-size: 12px !important;
    line-height: 16px !important;
  }
  
  /* フッターセクション全体のフォントサイズ調整 */
  .frame-1321317530 * {
    font-size: 12px !important;
  }
  
  .navigation-footer {
    padding: 30px 15px !important;
    gap: 20px !important;
  }
}

@media (max-width: 400px) {
  .footer-container {
    padding: 30px 20px !important;
    gap: 15px !important;
  }
  
  .frame-1321317530 {
    gap: 20px !important;
    padding: 0 5px !important;
    min-height: auto !important;
  }
  
  .frame-1321317521,
  .frame-1321317522,
  .frame-1321317523,
  .frame-1321317524-1 {
    width: auto !important;
    min-width: 90px !important;
    max-width: 240px !important;
    height: auto !important;
  }
  
  .frame-container,
  .frame-container-1 {
    gap: 8px !important;
  }
  
  /* フッター全体のフォントサイズ統一 */
  .text19 {
    font-size: 11px !important;
    line-height: 14px !important;
  }
  
  /* フッターセクション全体のフォントサイズ調整 */
  .frame-1321317530 * {
    font-size: 11px !important;
  }
  
  .navigation-footer {
    padding: 25px 10px !important;
    gap: 15px !important;
  }
}

@media (max-width: 350px) {
  .footer-container {
    padding: 40px 15px !important;
    gap: 10px !important;
  }
  
  .frame-1321317530 {
    gap: 15px !important;
    padding: 0 5px !important;
    min-height: auto !important;
    display: grid !important;
    grid-template-columns: 1fr !important;
    grid-template-rows: auto auto auto auto !important;
    justify-items: flex-start !important;
    align-items: flex-start !important;
  }
  
  .frame-1321317521,
  .frame-1321317522,
  .frame-1321317523,
  .frame-1321317524-1 {
    width: auto !important;
    min-width: 80px !important;
    max-width: 220px !important;
    text-align: left !important;
    height: auto !important;
  }
  
  .frame-container,
  .frame-container-1 {
    gap: 6px !important;
    align-items: flex-start !important;
  }
  
  .text19 {
    font-size: 10px !important;
    line-height: 12px !important;
  }
  
  .navigation-footer {
    padding: 20px 8px !important;
    gap: 10px !important;
  }
}


</style>
