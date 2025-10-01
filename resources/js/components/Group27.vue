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
        〒839-0864　福岡県久留米市百年公園1番1号 久留米リサーチセンタービル6階<br />電話番号. 0942-46-5081　営業時間.
        平日9:00-17:00
      </div>
    </div>
    <p class="copyright-c-the-ch valign-text-middle">
      Copyright (C) The Chikugin Research Institute For Regional Economy Co.,Ltd All rights reserved.
    </p>
  </div>
</template>

<script>
import View3 from "./View3.vue";
export default {
  name: "Group27",
  components: {
    View3,
  },
};
</script>

<style>
.group-27 {
  height: 217px;
  position: relative;
  width: 718px;
}

.group-11 {
  align-items: center;
  display: flex;
  flex-direction: column;
  gap: 39px;
  left: 0;
  min-height: 132px;
  position: absolute;
  top: 0;
  width: 718px;
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
  height: 39px;
  letter-spacing: 0;
  line-height: 24px;
  margin-right: 2px;
  min-width: 716px;
  text-align: center;
}

.copyright-c-the-ch {
  color: var(--black);
  font-family: var(--font-family-inter);
  font-size: var(--font-size-s);
  font-weight: 500;
  height: 23px;
  left: 11px;
  letter-spacing: 0;
  line-height: 22.5px;
  position: absolute;
  top: 194px;
  white-space: nowrap;
}
</style>
