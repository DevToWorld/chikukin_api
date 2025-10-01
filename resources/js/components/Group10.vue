<template>
  <div class="group-10-3">
    <div class="group-10-4">
      <div class="flex-row-1">
        <img
          class="vector-6-1"
          src="/img/vector-6.svg"
          alt="Vector 6"
        />
        <div class="text-8-1 valign-text-middle inter-bold-black-24px">アクセス</div>
      </div>
      <div class="place valign-text-middle inter-bold-black-48px">ACCESS</div>
    </div>
  </div>
</template>

<script>
export default {
  name: "Group10",
};
</script>

<style>
.group-10-3 {
  height: 126px;
  position: relative;
  width: 209px;
}

.group-10-4 {
  align-items: flex-start;
  display: flex;
  flex-direction: column;
  gap: 18px;
  min-height: 126px;
  width: 213px;
}

.flex-row-1 {
  align-items: center;
  display: flex;
  gap: 23px;
  min-width: 209px;
}

.vector-6-1 {
  height: 1px;
  object-fit: cover;
  width: 91px;
}

.text-8-1 {
  height: 36px;
  letter-spacing: 0;
  line-height: 36px;
  min-width: 95px;
  white-space: nowrap;
}

.place {
  height: 72px;
  letter-spacing: 0;
  line-height: 72px;
  white-space: nowrap;
}
</style>
