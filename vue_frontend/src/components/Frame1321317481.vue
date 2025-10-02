<template>
  <div class="frame-1321317481">
    <div class="copy">
      <div class="text-5 inter-bold-mandy-20px">{{ text49 }}</div>
      <div class="text-5-1 valign-text-middle inter-normal-ship-gray-15px">{{ text50 }}</div>
    </div>
    <img
      class="vector-26"
      src="/img/vector-28.svg"
      alt="Vector"
    />
  </div>
</template>

<script>
export default {
  name: "Frame1321317481",
  props: ["text49", "text50"],
};
</script>

<style>
.frame-1321317481 {
  align-items: center;
  background-color: var(--white);
  border-radius: 0px 0px 10px 10px;
  display: flex;
  gap: 10px;
  overflow: hidden;
  padding: 10px 20px;
  position: relative;
  width: 100%;
}

.copy {
  align-items: flex-start;
  display: flex;
  flex: 1;
  flex-direction: column;
  flex-grow: 1;
  position: relative;
}

.text-5 {
  align-self: stretch;
  letter-spacing: 0;
  line-height: 30px;
  margin-top: -1px;
  position: relative;
}

.text-5-1 {
  align-self: stretch;
  letter-spacing: 0;
  line-height: 22.5px;
  position: relative;
}

.vector-26 {
  height: 16.5px;
  position: relative;
  width: 9px;
}
</style>
