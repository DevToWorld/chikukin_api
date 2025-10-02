<template>
  <div class="button-1" @click="makePhoneCall" style="cursor: pointer;">
    <div class="phone-info">
      <div class="phone-number">0942-46-5081</div>
      <div class="phone-hours">（平日 9:00～17:00）</div>
    </div>
  </div>
</template>

<script>
export default {
  name: "XButtonPhone",
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
  background: #1A1A1A;
  border-radius: 8px;
  padding: 0 20px 0 24px;
  height: 40px;
  display: flex;
  align-items: center;
  border: none;
  cursor: pointer;
  box-shadow: 0 1px 2px rgba(0, 0, 0, 0.05);
  width: auto;
}



.phone-info {
  display: flex;
  flex-direction: column;
  align-items: center;
}

.phone-number {
  color: white;
  font-size: 13px;
  font-weight: 700;
  line-height: 1.5;
  text-align: center;
}

.phone-hours {
  color: white;
  font-size: 10px;
  font-weight: 700;
  line-height: 1.5;
  text-align: center;
}
</style>
