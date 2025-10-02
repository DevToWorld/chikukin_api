<template>
  <div class="button-1" @click="makePhoneCall" style="cursor: pointer;">
    <img
      class="vector-21"
      src="/img/vector-31.svg"
      alt="Vector"
    />
    <div class="group-3">
      <div class="overlap-group-5">
        <div class="phone-1 valign-text-middle inter-bold-white-13px">0942-46-5081</div>
        <div class="text-61 valign-text-middle">（平日 9:00～17:00）</div>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  name: "XButton",
  methods: {
    makePhoneCall() {
      console.log('Making phone call to: 0942-46-5081');
      // 電話をかける処理
      window.location.href = 'tel:0942-46-5081';
    }
  }
};
</script>

<style>
.button-1 {
  align-items: center;
  background-color: #9C3940;
  border-radius: 8px;
  box-shadow: 0px 1px 2px #0000000d;
  display: inline-flex;
  flex: 0 0 auto;
  gap: 5px;
  height: 40px;
  justify-content: center;
  padding: 0px 20px 0px 24px;
  position: relative;
}

.vector-21 {
  height: 24px;
  position: relative;
  width: 16px;
}

.group-3 {
  height: 34px;
  margin-right: -4px;
  position: relative;
  width: 107px;
}

.overlap-group-5 {
  height: 34px;
  position: relative;
  width: 103px;
}

.phone-1 {
  height: 20px;
  left: 6px;
  letter-spacing: 0;
  line-height: 19.5px;
  position: absolute;
  top: 0;
  white-space: nowrap;
}

.text-61 {
  color: var(--white);
  font-family: var(--font-family-inter);
  font-size: var(--font-size-xs);
  font-weight: 700;
  height: 15px;
  left: 0;
  letter-spacing: 0;
  line-height: 15px;
  position: absolute;
  top: 19px;
  white-space: nowrap;
}
</style>
