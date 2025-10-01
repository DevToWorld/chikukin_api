<template>
  <div :class="[`frame-132131752-4-1`, className || ``]">
    <img
      class="vector-32"
      src="/img/vector.svg"
      alt="Vector"
    />
    <div class="text-9 valign-text-middle inter-normal-black-16px">{{ text29 }}</div>
  </div>
</template>

<script>
export default {
  name: "Frame13213175223",
  props: ["text29", "className"],
};
</script>

<style>
.frame-132131752-4-1 {
  align-items: flex-start;
  display: flex;
  gap: 10px;
  height: 18px;
  width: 212px;
}

.vector-32 {
  height: 17px;
  width: 10px;
}

.text-9 {
  align-self: center;
  height: 12px;
  letter-spacing: 0;
  line-height: 32px;
  margin-bottom: 2.75px;
  min-width: 96px;
  white-space: nowrap;
}

.frame-132131752-4-1.frame-132131752-4-2 .text-9 {
  min-width: 192px;
}

.frame-132131752-4-1.frame-132131752-4-3 .text-9 {
  min-width: 160px;
}

.frame-132131752-4-1.frame-132131752-4-4 .text-9 {
  min-width: 64px;
}
</style>
