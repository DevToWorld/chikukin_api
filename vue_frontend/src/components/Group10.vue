<template>
  <div class="group-10-4">
    <div class="group-20">
      <img
        class="vector-6"
        :src="vector11"
        alt="Vector 6"
      />
      <div class="text-36 valign-text-middle inter-bold-black-24px">アクセス</div>
    </div>
    <div class="ation valign-text-middle inter-bold-black-48px">ACCESS</div>
  </div>
</template>

<script>
import vector11 from "../../public/img/vector-11.svg";

export default {
  name: "Group10",
  data() {
    return {
      vector11: vector11
    };
  }
};
</script>

<style>
.group-10-4 {
  align-items: flex-start;
  display: flex;
  flex-direction: column;
  gap: 20px;
  height: auto;
  width: auto;
}

.group-20 {
  align-items: center;
  display: flex;
  gap: 23px;
  min-width: 209px;
}

.vector-6 {
  height: 2px;
  object-fit: cover;
  width: 60px;
  min-height: 2px;
}

.text-36 {
  height: 36px;
  letter-spacing: 0;
  line-height: 36px;
  min-width: 95px;
  white-space: nowrap;
  font-size: 24px !important;
}

.ation {
  letter-spacing: 0;
  line-height: 72px;
  white-space: nowrap;
  font-size: 48px;
}

@media (max-width: 1150px) {
  .group-10-4 {
    gap: 15px !important;
  }

  .ation {
    font-size: 44px !important;
    line-height: 52px !important;
  }

  .text-36 {
    font-size: 22px !important;
  }

  .vector-6 {
    height: 2px !important;
    min-height: 2px !important;
  }
}

@media (max-width: 900px) {
  .ation {
    font-size: 40px !important;
    line-height: 48px !important;
  }

  .text-36 {
    font-size: 20px !important;
  }

  .vector-6 {
    height: 2px !important;
    min-height: 2px !important;
  }
}

@media (max-width: 768px) {
  .group-10-4 {
    gap: 15px !important;
  }

  .ation {
    font-size: 36px !important;
    line-height: 44px !important;
  }

  .text-36 {
    font-size: 19px !important;
  }

  .vector-6 {
    height: 2px !important;
    min-height: 2px !important;
  }
}

@media (max-width: 480px) {
  .group-10-4 {
    gap: 10px !important;
  }

  .ation {
    font-size: 32px !important;
    line-height: 40px !important;
  }

  .text-36 {
    font-size: 18px !important;
  }

  .vector-6 {
    width: 40px !important;
    height: 2px !important;
    min-height: 2px !important;
  }
}
</style>
