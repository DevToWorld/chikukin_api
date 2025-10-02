<template>
  <div class="group-1">
    <div class="text-59 valign-text-middle inter-bold-black-15px">筑邦銀行グループ</div>
    <div class="text-60 valign-text-middle inter-bold-black-24px">株式会社ちくぎん地域経済研究所</div>
  </div>
</template>

<script>
export default {
  name: "Group1",
};
</script>

<style>
.group-1 {
  height: auto;
  min-height: 43px;
  position: relative;
  width: auto;
  max-width: none;
  flex-shrink: 1;
  transition: all 0.3s ease;
  display: flex;
  flex-direction: column;
  justify-content: flex-start;
  gap: 10px;
  overflow: visible;
}

.text-59 {
  height: 13px;
  letter-spacing: 0;
  line-height: 13px;
  position: relative;
  white-space: nowrap;
  width: auto;
  min-width: 120px;
  font-size: 15px !important;
  transition: font-size 0.3s ease;
  margin: 0;
  padding: 0;
}

.text-60 {
  height: 20px;
  letter-spacing: 0;
  line-height: 20px;
  position: relative;
  white-space: nowrap;
  width: auto;
  font-size: 24px !important;
  transition: font-size 0.3s ease;
  margin: 0;
  padding: 0;
}

/* レスポンシブ対応 - 画面幅に応じてフォントサイズとサイズを調整 */
@media (max-width: 1200px) {
  .logo-section {
    gap: 10px !important;
  }
  .group-1 {
    width: auto !important;
    height: auto !important;
    min-height: 38px !important;
    gap: 10px !important;
  }
  .text-59 {
    font-size: 14px !important;
    width: auto !important;
    min-width: 90px !important;
    line-height: 14px !important;
  }
  .text-60 {
    font-size: 22px !important;
    width: auto !important;
    line-height: 22px !important;
  }
}

@media (max-width: 1000px) {
  .logo-section {
    gap: 5px !important;
  }
  .group-1 {
    width: auto !important;
    height: auto !important;
    min-height: 35px !important;
    gap: 5px !important;
  }
  .text-59 {
    font-size: 13px !important;
    width: auto !important;
    min-width: 80px !important;
    line-height: 13px !important;
  }
  .text-60 {
    font-size: 20px !important;
    width: auto;
    line-height: 20px;
  }
}

@media (max-width: 800px) {
  .logo-section {
    gap: 10px !important;
  }
  .group-1 {
    width: auto !important;
    height: auto !important;
    min-height: 0px !important;
    gap: 5px !important;
  }
  .text-59 {
    font-size: 12px !important;
    width: auto !important;
    min-width: 70px !important;
    line-height: 12px;
  }
  .text-60 {
    font-size: 16px !important;
    width: auto !important;
    line-height: 18px !important;
  }
}

@media (max-width: 600px) {
  .group-1 {
    width: auto !important;
    height: auto !important;
    min-height: 28px !important;
  }
  .text-59 {
    font-size: 11px !important;
    width: auto !important;
    min-width: 60px !important;
    line-height: 11px !important;
  }
  .text-60 {
    font-size: 14px !important;
    width: auto !important;
    min-width: 150px !important;
    line-height: 14px !important;
  }
}

@media (max-width: 480px) {
  .group-1 {
    width: auto !important;
    height: auto !important;
    min-height: 0px !important;
    gap: 5px !important;
  }
  .text-59 {
    font-size: 10px !important;
    width: auto !important;
    min-width: 50px !important;
    line-height: 10px !important;
  }
  .text-60 {
    font-size: 14px !important;
    width: auto !important;
    min-width: 120px !important;
    line-height: 16px !important;
  }
}

@media (max-width: 350px) {
  .logo-section {
    gap: 5px !important;
  }
  .group-1 {
    width: auto !important;
    height: auto !important;
    min-height: 0px !important;
    gap: 0px !important;
  }
  .text-59 {
    font-size: 9px !important;
    width: auto !important;
    min-width: 40px !important;
    line-height: 9px !important;
  }
  .text-60 {
    font-size: 11px !important;
    width: auto !important;
    min-width: 120px !important;
    line-height: 11px !important;
  }
}
</style>
