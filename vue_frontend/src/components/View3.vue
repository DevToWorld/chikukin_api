<template>
  <div class="view-5">
    <div class="text-35 valign-text-middle inter-bold-black-15px">筑邦銀行グループ</div>
    <div class="text-34 valign-text-middle inter-bold-black-24px">株式会社ちくぎん地域経済研究所</div>
  </div>
</template>

<script>
export default {
  name: "View3",
};
</script>

<style>
.view-5 {
  align-items: flex-start;
  display: flex;
  flex-direction: column;
  justify-content: center;
  position: relative;
  width: auto;
}

.text-34 {
  letter-spacing: 0;
  line-height: 36px;
  margin-top: -1px;
  position: relative;
  white-space: nowrap;
  width: fit-content;
}

.text-35 {
  letter-spacing: 0;
  line-height: 22.5px;
  margin-top: -5px;
  position: relative;
  white-space: nowrap;
  width: fit-content;
}

/* レスポンシブ対応 */
@media (max-width: 800px) {
  .text-34 {
    font-size: 20px !important;
    line-height: 30px !important;
  }
  
  .text-35 {
    font-size: 13px !important;
    line-height: 20px !important;
  }
}

@media (max-width: 600px) {
  .text-34 {
    font-size: 18px !important;
    line-height: 26px !important;
  }
  
  .text-35 {
    font-size: 12px !important;
    line-height: 18px !important;
  }
}

@media (max-width: 480px) {
  .text-34 {
    font-size: 16px !important;
    line-height: 24px !important;
  }
  
  .text-35 {
    font-size: 11px !important;
    line-height: 16px !important;
  }
}

@media (max-width: 400px) {
  .text-34 {
    font-size: 14px !important;
    line-height: 20px !important;
  }
  
  .text-35 {
    font-size: 10px !important;
    line-height: 14px !important;
  }
}
</style>
