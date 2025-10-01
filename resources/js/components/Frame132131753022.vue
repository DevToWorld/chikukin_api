<template>
  <div class="frame-1321317530">
    <div class="frame-1321317521">
      <frame13213175225 :text14="frame132131752251Props.text14" />
      <frame13213175225 :text14="frame132131752252Props.text14" />
      <frame13213175225 :text14="frame132131752253Props.text14" />
      <frame13213175225 :text14="frame132131752254Props.text14" />
      <frame13213175225 :text14="frame132131752255Props.text14" />
    </div>
    <div class="frame-1321317522">
      <frame13213175222 :text19="frame132131752221Props.text19" />
      <frame13213175222 :text19="frame132131752222Props.text19" />
      <frame13213175273
        :frame13213175222Props="frame132131752731Props.frame13213175222Props"
        :frame13213175251Props="frame132131752731Props.frame13213175251Props"
        :frame13213175252Props="frame132131752731Props.frame13213175252Props"
      />
      <frame13213175273
        :frame13213175222Props="frame132131752732Props.frame13213175222Props"
        :frame13213175251Props="frame132131752732Props.frame13213175251Props"
        :frame13213175252Props="frame132131752732Props.frame13213175252Props"
      />
    </div>
    <div class="frame-1321317523">
      <div class="frame-container">
        <div class="frame-1321317527">
          <div class="frame-1321317524">
            <img
              class="vector-27"
              src="/img/vector.svg"
              alt="Vector"
            />
            <div class="text-25 valign-text-middle inter-normal-black-16px">会員サービスのご案内</div>
          </div>
          <frame132131752522 :text26="frame1321317525221Props.text26" />
          <frame132131752522 :text26="frame1321317525222Props.text26" />
        </div>
        <div class="frame-1321317522-1">
          <img
            class="vector-27"
            src="/img/vector.svg"
            alt="Vector"
          />
          <div class="cri valign-text-middle inter-normal-black-16px">CRI 経営コンサルティング</div>
        </div>
        <div class="frame-1321317523-1">
          <img
            class="vector-27"
            src="/img/vector.svg"
            alt="Vector"
          />
          <div class="text-28 valign-text-middle inter-normal-black-16px">決算報告</div>
        </div>
      </div>
    </div>
    <div class="frame-1321317524-1">
      <div class="frame-container-1">
        <frame13213175223 :text29="frame132131752231Props.text29" />
        <frame13213175223 :text29="frame132131752232Props.text29" :className="frame132131752232Props.className" />
        <frame13213175223 :text29="frame132131752233Props.text29" :className="frame132131752233Props.className" />
        <frame13213175223 :text29="frame132131752234Props.text29" :className="frame132131752234Props.className" />
        <frame13213175223 :text29="frame132131752235Props.text29" />
      </div>
    </div>
  </div>
</template>

<script>
import Frame13213175225 from "./Frame13213175225.vue";
import Frame13213175222 from "./Frame13213175222.vue";
import Frame13213175273 from "./Frame13213175273.vue";
import Frame132131752522 from "./Frame132131752522.vue";
import Frame13213175223 from "./Frame13213175223.vue";
export default {
  name: "Frame132131753022",
  components: {
    Frame13213175225,
    Frame13213175222,
    Frame13213175273,
    Frame132131752522,
    Frame13213175223,
  },
  props: [
    "frame132131752251Props",
    "frame132131752252Props",
    "frame132131752253Props",
    "frame132131752254Props",
    "frame132131752255Props",
    "frame132131752221Props",
    "frame132131752222Props",
    "frame132131752731Props",
    "frame132131752732Props",
    "frame1321317525221Props",
    "frame1321317525222Props",
    "frame132131752231Props",
    "frame132131752232Props",
    "frame132131752233Props",
    "frame132131752234Props",
    "frame132131752235Props",
  ],
};
</script>

<style>
.frame-1321317530 {
  align-items: flex-start;
  display: inline-flex;
  flex: 0 0 auto;
  gap: 60px;
  position: relative;
}

.frame-1321317521 {
  align-items: flex-start;
  display: flex;
  flex-direction: column;
  gap: 20px;
  position: relative;
  width: 151px;
}

.frame-1321317522 {
  align-items: flex-start;
  display: flex;
  flex-direction: column;
  gap: 20px;
  position: relative;
  width: 171px;
}

.frame-1321317523 {
  height: 134px;
  position: relative;
  width: 210px;
}

.frame-container {
  align-items: flex-start;
  display: flex;
  flex-direction: column;
  gap: 20px;
  min-height: 134px;
}

.frame-1321317527 {
  align-items: flex-start;
  display: flex;
  flex-direction: column;
  gap: 5px;
  height: 58px;
  position: relative;
  width: 191px;
}

.frame-1321317524 {
  align-items: flex-start;
  display: flex;
  gap: 10px;
  height: 18px;
  width: 191px;
}

.vector-27 {
  height: 17px;
  width: 10px;
}

.text-25 {
  align-self: center;
  height: 12px;
  letter-spacing: 0;
  line-height: 32px;
  margin-bottom: 2.75px;
  min-width: 160px;
  white-space: nowrap;
}

.frame-1321317522-1 {
  align-items: flex-start;
  display: flex;
  gap: 10px;
  height: 18px;
  width: 210px;
}

.cri {
  align-self: center;
  height: 12px;
  letter-spacing: 0;
  line-height: 32px;
  margin-bottom: 2.75px;
  min-width: 190px;
  white-space: nowrap;
}

.frame-1321317523-1 {
  align-items: flex-start;
  display: flex;
  gap: 10px;
  height: 18px;
  width: 84px;
}

.text-28 {
  align-self: center;
  height: 12px;
  letter-spacing: 0;
  line-height: 32px;
  margin-bottom: 2.75px;
  min-width: 64px;
  white-space: nowrap;
}

.frame-1321317524-1 {
  height: 170px;
  position: relative;
  width: 212px;
}

.frame-container-1 {
  align-items: flex-start;
  display: flex;
  flex-direction: column;
  gap: 20px;
  min-height: 170px;
  position: relative;
}
</style>
