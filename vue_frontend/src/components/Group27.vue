<template>
  <div class="group-27">
    <div class="group-11">
      <div class="group-8">
        <img
          class="ico_logo-1"
          src="/img/ico-logo-1-1.svg"
          alt="ico_logo 1"
        />
        <view3 />
      </div>
        <div class="x839-0864 valign-text-middle">
          <span class="postal-code">〒839-0864</span><span class="address">福岡県久留米市百年公園1番1号 久留米リサーチセンタービル6階</span><br />電話番号. 0942-46-5081　営業時間.
          平日9:00-17:00
        </div>
    </div>
    <p class="copyright-c-the-ch valign-text-middle">
      Copyright (C) The Chikugin Research Institute For Regional Economy Co.,Ltd All rights reserved.
    </p>
  </div>
</template>

<script>
import View3 from "./View3";
export default {
  name: "Group27",
  components: {
    View3,
  },
};
</script>

<style>
.group-27 {
  height: auto;
  position: relative;
  width: 100%;
  max-width: 718px;
  display: flex;
  flex-direction: column;
  align-items: center;
}

.group-11 {
  align-items: center;
  display: flex;
  flex-direction: column;
  gap: 39px;
  min-height: 132px;
  width: 100%;
  max-width: 718px;
}

.group-8 {
  align-items: center;
  display: flex;
  gap: 14px;
  height: 54px;
  margin-right: 2px;
  min-width: 428px;
  position: relative;
}

.ico_logo-1 {
  height: 42px;
  width: 54px;
}

.x839-0864 {
  color: var(--black);
  font-family: var(--font-family-inter);
  font-size: var(--font-size-xl);
  font-weight: 500;
  height: auto;
  letter-spacing: 0;
  line-height: 24px;
  text-align: center;
  width: 100%;
  max-width: 716px;
}

.copyright-c-the-ch {
  color: var(--black);
  font-family: var(--font-family-inter);
  font-size: var(--font-size-s);
  font-weight: 500;
  height: auto;
  letter-spacing: 0;
  line-height: 22.5px;
  text-align: center;
  white-space: normal;
  word-wrap: break-word;
  overflow-wrap: break-word;
  margin-top: 20px;
  max-width: 100%;
}

/* レスポンシブ対応 */
@media (max-width: 800px) {
  .group-11 {
    gap: 20px !important;
  }
  .x839-0864 {
    font-size: 14px !important;
    line-height: 20px !important;
    height: auto !important;
    min-height: 39px !important;
    text-align: center !important;
  }
  
  .copyright-c-the-ch {
    font-size: 12px !important;
    line-height: 18px !important;
    text-align: left !important;
  }
  
  /* モバイルで郵便番号を改行 */
  .postal-code {
    display: block !important;
  }
  
  /* ロゴサイズ調整 */
  .ico_logo-1 {
    height: 36px !important;
    width: 46px !important;
  }
}

@media (max-width: 600px) {
  .group-11 {
    gap: 15px !important;
  }
  .x839-0864 {
    font-size: 13px !important;
    line-height: 18px !important;
    padding: 0 10px !important;
    text-align: left !important;
  }
  
  .copyright-c-the-ch {
    font-size: 11px !important;
    line-height: 16px !important;
    padding: 0 10px !important;
    text-align: left !important;
  }
  
  /* ロゴサイズ調整 */
  .ico_logo-1 {
    height: 32px !important;
    width: 41px !important;
  }
}

@media (max-width: 480px) {
  .group-11 {
    gap: 10px;
  }
  .group-8 {
    min-width: 100%;
  }
  .x839-0864 {
    font-size: 12px !important;
    line-height: 16px !important;
    padding: 0 8px !important;
    text-align: left !important;
  }
  
  .copyright-c-the-ch {
    font-size: 10px !important;
    line-height: 14px !important;
    padding: 0 8px !important;
    text-align: left !important;
  }
  
  /* ロゴサイズ調整 */
  .ico_logo-1 {
    height: 28px !important;
    width: 36px !important;
  }
}

@media (max-width: 400px) {
  .x839-0864 {
    font-size: 11px !important;
    line-height: 15px !important;
    padding: 0 5px !important;
    text-align: left !important;
  }
  
  .copyright-c-the-ch {
    font-size: 9px !important;
    line-height: 12px !important;
    padding: 0 5px !important;
    text-align: left !important;
  }
  
  /* ロゴサイズ調整 */
  .ico_logo-1 {
    height: 24px !important;
    width: 31px !important;
  }
}
</style>
