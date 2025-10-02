<template>
  <div :class="[`frame-1321317474`, className || ``]" @click="goToPublication" style="cursor: pointer;">
    <div class="frame-1321317473-2">
      <div class="overlap-group1">
        <img class="x2-2-2" :src="x22" alt="2 2" />
        <div class="overlap-group-6">
          <div class="date-2 valign-text-middle inter-normal-ship-gray-15px">{{ date || '2025.04.28' }}</div>
          <div
            class="text-8 valign-text-middle inter-semi-bold-ship-gray-16px"
            v-shrink-on-wrap="{ lines: 2, className: 'shrink-2lines' }"
          >
            {{ title || '事業継承から描く九州の未来' }}
          </div>
          <div class="viwe-more-2 valign-text-middle inter-normal-ship-gray-10px">Viwe More .</div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import shrinkOnWrap from '@/directives/shrinkOnWrap'
export default {
  name: "Frame1321317474",
  props: ["x22", "className", "date", "title"],
  directives: { shrinkOnWrap },
  methods: {
    goToPublication() {
      // 刊行物ページに遷移
      this.$router.push('/publication');
    }
  }
};
</script>

<style>
.frame-1321317474 {
  height: 398px;
  position: relative;
  width: 583px;
}

.frame-1321317473-2 {
  align-items: flex-end;
  border-radius: 10px;
  display: flex;
  height: 398px;
  overflow: hidden;
  width: 583px;
}

.overlap-group1 {
  height: 397px;
  position: relative;
  width: 583px;
}

.x2-2-2 {
  height: 350px;
  left: 0;
  object-fit: cover;
  position: absolute;
  top: 0;
  width: 583px;
}

.overlap-group-6 {
  align-items: flex-start;
  background-color: var(--white);
  border-radius: 15px 0px 0px 0px;
  display: flex;
  flex-direction: column;
  left: 76px;
  min-height: 115px;
  padding: 13px 29.1px;
  position: absolute;
  top: 282px;
  width: 507px;
}

.date-2 {
  height: 23px;
  letter-spacing: 0;
  line-height: 22.5px;
  margin-top: 3px;
  width: 141px;
}

.text-8 {
  height: 24px;
  letter-spacing: 0;
  line-height: 18px;
  margin-top: 6px;
  width: 100%;
  word-wrap: break-word;
  overflow-wrap: break-word;
  font-size: 12px;
}

/* When the title wraps to 2+ lines, gently shrink */
.text-8.shrink-2lines {
  height: auto;            /* allow 2 lines without overlap */
  line-height: 1.2em;      /* tighter line spacing on wrap */
  font-size: 12px;         /* keep at ~12px when wrapping */
}

.viwe-more-2 {
  height: 15px;
  letter-spacing: 0;
  line-height: 15px;
  margin-top: 18px;
  text-decoration: underline;
  width: 97px;
}

.frame-1321317474.frame-1321317472-1 {
  align-items: flex-start;
  display: flex;
  left: 0;
  position: absolute;
  top: 0;
}

.frame-1321317474.frame-1321317472-1 .frame-1321317473-2 {
  border-radius: unset;
  overflow: unset;
}

.frame-1321317474.frame-1321317472-1 {
  cursor: pointer;
  transition: transform 0.3s ease, opacity 0.3s ease;
}

.frame-1321317474.frame-1321317472-1:hover {
  transform: scale(1.02);
  opacity: 0.9;
}

/* レスポンシブスタイル */
@media (max-width: 1100px) {
  .text-8 {
    font-size: 12px !important;
    line-height: 18px !important;
  }
}

@media (max-width: 768px) {
  .frame-1321317474 {
    width: 100%;
    max-width: 583px;
  }
  
  .frame-1321317473-2 {
    width: 100%;
    max-width: 583px;
  }
  
  .overlap-group1 {
    width: 100%;
    max-width: 583px;
  }
  
  .x2-2-2 {
    width: 100%;
    max-width: 583px;
  }
  
  .overlap-group-6 {
    width: 75%;
    max-width: 507px;
    right: 0px;
    left: auto;
    padding: 15px 20px;
    border-radius: 15px 0px 0px 0px;
  }
  
  .text-8 {
    font-size: 12px !important;
    line-height: 18px !important;
  }
}

/* 1550px以上で横幅を900pxに変更 */
@media (min-width: 1550px) {
  .frame-1321317474 {
    width: 900px !important;
    height: 500px !important;
  }
  
  .frame-1321317473-2 {
    width: 900px !important;
    height: 500px !important;
  }
  
  .overlap-group1 {
    width: 900px !important;
    height: 499px !important;
  }
  
  .x2-2-2 {
    width: 900px !important;
    height: 450px !important;
  }
  
  .overlap-group-6 {
    width: 724px !important;
    left: 76px !important;
    top: 384px !important;
  }
}

@media (max-width: 480px) {
  .frame-1321317474 {
    height: auto !important;
  }
  
  .frame-1321317474.frame-1321317472-1 {
    height: auto !important;
  }
  
  .frame-1321317474.frame-1321317472-1:hover {
    transform: none;
    opacity: 1;
  }
  
  .frame-1321317473-2 {
    height: auto !important;
  }
  
  .overlap-group1 {
    height: 300px !important;
  }
  
  .x2-2-2 {
    height: 250px !important;
  }
  
  .overlap-group-6 {
    top: 200px !important;
    min-height: 0px !important;
    width: 100% !important;
    max-width: 100% !important;
    border-radius: 15px 15px 0px 0px !important;
  }
  
  .text-8 {
    font-size: 12px !important;
    line-height: 18px !important;
  }
}
</style>
