<template>
  <div class="frame-132131747">
    <div class="overlap-group-7">
      <img class="x2-2-3" :src="x22" alt="2 2" />
      <div class="overlap-group-8">
        <div class="date-3 valign-text-middle inter-normal-ship-gray-10px">{{ date || '2025.04.28' }}</div>
        <div
          class="x-vol valign-text-middle inter-semi-bold-ship-gray-11px"
          v-shrink-on-wrap="{ lines: 2, className: 'shrink-2lines' }"
        >
          {{ hotInformationVol324 }}
        </div>
        <div class="viwe-more-3 valign-text-middle inter-normal-ship-gray-8px">Viwe More .</div>
      </div>
    </div>
  </div>
</template>

<script>
import shrinkOnWrap from '@/directives/shrinkOnWrap'
export default {
  name: "Frame1321317475",
  props: ["x22", "hotInformationVol324", "date"],
  directives: { shrinkOnWrap },
};
</script>

<style>
.frame-132131747 {
  height: 286px;
  overflow: hidden;
  position: relative;
  width: 259px;
}

.overlap-group-7 {
  height: 286px;
  position: relative;
  width: 265px;
}

.x2-2-3 {
  height: 252px;
  left: 0;
  object-fit: cover;
  position: absolute;
  top: 0;
  width: 259px;
}

.overlap-group-8 {
  align-items: flex-start;
  background-image: url(/img/rectangle-6.svg);
  background-size: 100% 100%;
  display: flex;
  flex-direction: column;
  left: 49px;
  min-height: 69px;
  padding: 5px 8px;
  position: absolute;
  top: 217px;
  width: 210px;
}

.date-3 {
  height: 15px;
  letter-spacing: 0;
  line-height: 15px;
  width: 58px;
}

.x-vol {
  height: 17px;
  letter-spacing: 0;
  line-height: 12px;
  width: 150px;
  font-size: 9px; /* override global 11px */
}

.x-vol.shrink-2lines {
  height: auto;
  line-height: 1.2em;
  font-size: 9px;  /* keep at 9px when wrapping */
}

.viwe-more-3 {
  height: 12px;
  letter-spacing: 0;
  line-height: 12px;
  margin-top: 12px;
  text-decoration: underline;
  white-space: nowrap;
}
</style>
