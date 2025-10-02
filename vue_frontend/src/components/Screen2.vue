<template>
  <div class="container-center-horizontal">
    <div class="u12488u12483u12501u12442-1 screen">
      <navigation />
      <div class="overlap-group7">
        <div class="hero-image" :style="{ 'background-image': 'url(' + heroImage + ')' }">
          <div class="frame-1321317489">
            <div class="frame-1321317608">
              <div class="frame-1321317610">
                <h1 class="text valign-text-middle inter-bold-black-48px">{{ text66 }}</h1>
              </div>
              <div class="frame-1321317609">
                <div class="text valign-text-middle inter-bold-black-48px">{{ text67 }}</div>
              </div>
              <div class="frame-1321317611">
                <div class="text-3-1 valign-text-middle inter-bold-white-20px">{{ text68 }}</div>
                <div class="frame-1321317612">
                  <img
                    class="vector-2"
                    src="/img/vector-35.svg"
                    alt="Vector"
                  />
                </div>
              </div>
            </div>
          </div>
        </div>
        <div class="frame-1321317457">
          <div class="frame-1321317490" :style="{ 'background-image': 'url(' + frame13213174901 + ')' }">
            <div class="frame-1321317491">
              <div class="text-1 valign-text-middle inter-bold-white-15px">{{ text72 }}</div>
              <div class="seminar valign-text-middle inter-bold-white-24px">{{ seminar }}</div>
            </div>
          </div>
          <div class="frame-1321317491-1" :style="{ 'background-image': 'url(' + frame1321317491 + ')' }">
            <div class="frame-1321317491-2">
              <div class="text-1 valign-text-middle inter-bold-white-15px">{{ text69 }}</div>
              <div class="publications valign-text-middle inter-bold-white-24px">{{ publications1 }}</div>
            </div>
          </div>
          <div class="frame-1321317490-1" :style="{ 'background-image': 'url(' + frame13213174902 + ')' }">
            <div class="frame-1321317491-3">
              <div class="text-2 valign-text-middle inter-bold-white-16px">{{ text70 }}</div>
              <div class="Information valign-text-middle inter-bold-white-24px">{{ Information1 }}</div>
            </div>
          </div>
          <div class="frame-1321317492" :style="{ 'background-image': 'url(' + frame1321317492 + ')' }">
            <div class="frame-1321317491-4">
              <div class="text-2 valign-text-middle inter-bold-white-16px">{{ text71 }}</div>
              <div class="membership valign-text-middle inter-bold-white-24px">{{ membership }}</div>
            </div>
          </div>
        </div>
      </div>
      <frame1321317493 />
      <img
        class="vector-13"
        src="/img/vector-13.svg"
        alt="Vector 13"
      />
      <div class="overlap-group8">
        <frame1321317483 />
        <div class="main">
          <div class="about">
            <div class="flex-col-1">
              <img
                class="ico_logo-2"
                src="/img/ico-logo-2.svg"
                alt="ico_logo 2"
              />
              <div class="text-47">{{ text112 }}</div>
              <div class="group-30">
                <img
                  class="vector-16"
                  src="/img/vector-15-2.svg"
                  alt="Vector 16"
                />
                <div class="for-you valign-text-middle">{{ forYou }}</div>
                <img
                  class="vector-15"
                  src="/img/vector-15-2.svg"
                  alt="Vector 15"
                />
              </div>
              <div class="text-48" v-html="text113"></div>
            </div>
          </div>
          <div class="frame-1321317462">
            <div class="frame-1321317456">
              <div class="frame-1321317482">
                <card :image="card1Props.image" :frame1321317481Props="card1Props.frame1321317481Props" />
                <card
                  :image="card2Props.image"
                  :className="card2Props.className"
                  :frame1321317481Props="card2Props.frame1321317481Props"
                />
                <div class="card">
                  <div class="image" :style="{ 'background-image': 'url(' + image1 + ')' }">
                    <frame1321317481 :text49="frame1321317481Props.text49" :text50="frame1321317481Props.text50" />
                  </div>
                </div>
              </div>
              <div class="button">
                <div class="text-55 valign-text-middle inter-bold-white-15px">{{ text120 }}</div>
                <frame13213176122 />
              </div>
            </div>
          </div>
          <div class="view">
            <div class="frame-1321317480">
              <div class="group-10">
                <div class="Information-1 valign-text-middle inter-bold-black-48px">{{ Information2 }}</div>
                <div class="flex-row">
                  <img
                    class="vector-6"
                    src="/img/vector-6-3.svg"
                    alt="Vector 6"
                  />
                  <div class="text-56 valign-text-middle inter-bold-black-24px">{{ text121 }}</div>
                </div>
              </div>
              <x-button3 />
            </div>
            <div class="frame-1321317467">
              <div class="frame-13213174">
                <div class="frame-13">
                  <div class="date valign-text-middle inter-normal-sonic-silver-18px">{{ date1 }}</div>
                  <x-button4 />
                  <div class="hot-info valign-text-middle inter-normal-ship-gray-18px">{{ hotInformationVol319 }}</div>
                  <div class="caret-circle-right">
                    <div class="overlap-group">
                      <img
                        class="vector"
                        src="/img/vector-74.svg"
                        alt="Vector"
                      />
                    </div>
                    <img class="vector-3" :src="vector3" alt="Vector" />
                  </div>
                </div>
              </div>
              <div class="frame-13213174">
                <div class="frame-13">
                  <div class="date valign-text-middle inter-normal-sonic-silver-18px">{{ date2 }}</div>
                  <x-button4 />
                  <div class="text-58 valign-text-middle inter-normal-ship-gray-18px">{{ text123 }}</div>
                  <div class="caret-circle-right-1">
                    <div class="overlap-group">
                      <img
                        class="vector"
                        src="/img/vector-74.svg"
                        alt="Vector"
                      />
                    </div>
                    <img class="vector-4" :src="vector5" alt="Vector" />
                  </div>
                </div>
              </div>
              <div class="frame-13213174">
                <div class="frame-13">
                  <div class="date valign-text-middle inter-normal-sonic-silver-18px">{{ date3 }}</div>
                  <x-button4 />
                  <div class="hot-info valign-text-middle inter-normal-ship-gray-18px">{{ hotInformationVol318 }}</div>
                  <div class="caret-circle-right">
                    <div class="overlap-group">
                      <img
                        class="vector"
                        src="/img/vector-74.svg"
                        alt="Vector"
                      />
                    </div>
                    <img class="vector-5" :src="vector8" alt="Vector" />
                  </div>
                </div>
              </div>
              <div class="frame-13213174">
                <div class="frame-13">
                  <div class="date valign-text-middle inter-normal-sonic-silver-18px">{{ date4 }}</div>
                  <x-button4 />
                  <div class="hot-info valign-text-middle inter-normal-ship-gray-18px">{{ hotInformationVol3161 }}</div>
                  <div class="caret-circle-right">
                    <div class="overlap-group">
                      <img
                        class="vector"
                        src="/img/vector-74.svg"
                        alt="Vector"
                      />
                    </div>
                    <img class="vector-7" :src="vector10" alt="Vector" />
                  </div>
                </div>
              </div>
              <div class="frame-13213174">
                <div class="frame-13">
                  <div class="date valign-text-middle inter-normal-sonic-silver-18px">{{ date5 }}</div>
                  <x-button4 />
                  <div class="hot-info valign-text-middle inter-normal-ship-gray-18px">{{ hotInformationVol3162 }}</div>
                  <div class="caret-circle-right">
                    <div class="overlap-group">
                      <img
                        class="vector"
                        src="/img/vector-74.svg"
                        alt="Vector"
                      />
                    </div>
                    <img class="vector-8" :src="vector12" alt="Vector" />
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="frame-1321317484">
        <div class="view">
          <div class="group-25">
            <div class="group-10-1">
              <div class="group-20">
                <img
                  class="vector-6"
                  src="/img/vector-6.svg"
                  alt="Vector 6"
                />
                <div class="text-36 valign-text-middle inter-bold-black-24px">{{ text101 }}</div>
              </div>
              <div class="ation valign-text-middle inter-bold-black-48px">{{ publications2 }}</div>
            </div>
          </div>
          <div class="frame-1321317472">
            <frame1321317474 :x22="frame13213174741Props.x22" />
            <div class="frame-1321317485">
              <div class="frame-1321317487">
                <div class="group-18">
                  <img
                    class="vector-9"
                    src="/img/vector-9-1.svg"
                    alt="Vector 9"
                  />
                  <img
                    class="vector-10"
                    src="/img/vector-10-1.svg"
                    alt="Vector 10"
                  />
                </div>
                <x-button3 />
              </div>
              <div class="frame-1321317486">
                <frame1321317475
                  :x22="frame13213174751Props.x22"
                  :hotInformationVol324="frame13213174751Props.hotInformationVol324"
                />
                <frame1321317475
                  :x22="frame13213174752Props.x22"
                  :hotInformationVol324="frame13213174752Props.hotInformationVol324"
                />
                <frame1321317475
                  :x22="frame13213174752Props.x22"
                  :hotInformationVol324="frame13213174752Props.hotInformationVol324"
                />
                <frame1321317475
                  :x22="frame13213174752Props.x22"
                  :hotInformationVol324="frame13213174752Props.hotInformationVol324"
                />
                <frame1321317475
                  :x22="frame13213174752Props.x22"
                  :hotInformationVol324="frame13213174752Props.hotInformationVol324"
                />
                <frame1321317475
                  :x22="frame13213174752Props.x22"
                  :hotInformationVol324="frame13213174752Props.hotInformationVol324"
                />
                <frame1321317475
                  :x22="frame13213174752Props.x22"
                  :hotInformationVol324="frame13213174752Props.hotInformationVol324"
                />
                <frame1321317475
                  :x22="frame13213174752Props.x22"
                  :hotInformationVol324="frame13213174752Props.hotInformationVol324"
                />
                <frame1321317475
                  :x22="frame13213174752Props.x22"
                  :hotInformationVol324="frame13213174752Props.hotInformationVol324"
                />
                <div class="frame-1321317475">
                  <div class="overlap-group3">
                    <img class="x2-2" :src="x221" alt="2 2" />
                    <div class="overlap-group-3">
                      <img
                        class="rectangle-6"
                        src="/img/rectangle-6-2.svg"
                        alt="Rectangle 6"
                      />
                      <div class="date-1 valign-text-middle inter-normal-ship-gray-10px">{{ date6 }}</div>
                      <div class="viwe-more-1 valign-text-middle inter-normal-ship-gray-8px">{{ viweMore1 }}</div>
                      <div class="hot-information-vol324 valign-text-middle inter-semi-bold-ship-gray-11px">
                        {{ hotInformationVol324 }}
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <div class="frame-21">
            <div class="vector-container">
              <img
                class="vector-11"
                src="/img/vector-11-1.svg"
                alt="Vector 11"
              />
              <img
                class="vector-13-1"
                src="/img/vector-13-2.svg"
                alt="Vector 13"
              />
              <img
                class="vector-12"
                src="/img/vector-12-1.svg"
                alt="Vector 12"
              />
            </div>
          </div>
        </div>
        <div class="view">
          <div class="group-25-1">
            <div class="group-10-2">
              <div class="group-20-1">
                <img
                  class="vector-6"
                  src="/img/vector-6.svg"
                  alt="Vector 6"
                />
                <div class="text-39 valign-text-middle inter-bold-black-24px">{{ text104 }}</div>
              </div>
              <div class="ation valign-text-middle inter-bold-black-48px">{{ otherInformation }}</div>
            </div>
          </div>
          <div class="view-1">
            <frame1321317474 :x22="frame13213174742Props.x22" :className="frame13213174742Props.className" />
            <div class="frame-1321317473">
              <div class="frame-1321317473-1">
                <div class="overlap-group-container">
                  <div class="overlap-group3-1">
                    <img class="x2-2-1" :src="x222" alt="2 2" />
                    <div class="overlap-group2">
                      <div class="flex-col-2">
                        <div class="text-4 valign-text-middle inter-semi-bold-ship-gray-12px">{{ text108 }}</div>
                        <div class="viwe-more valign-text-middle inter-normal-ship-gray-10px">{{ viweMore2 }}</div>
                      </div>
                      <div class="caret-right">
                        <img
                          class="vector-14"
                          src="/img/vector-65.svg"
                          alt="Vector"
                        />
                        <img class="vector-17" :src="vector17" alt="Vector" />
                      </div>
                    </div>
                  </div>
                  <div class="overlap-group-1">
                    <img class="x2-3" :src="x23" alt="2 3" />
                    <div class="overlap-group-2">
                      <div class="flex-col">
                        <div class="text-4 valign-text-middle inter-semi-bold-ship-gray-12px">{{ text106 }}</div>
                        <div class="viwe-more valign-text-middle inter-normal-ship-gray-10px">{{ viweMore3 }}</div>
                      </div>
                      <div class="caret-right">
                        <img
                          class="vector-1"
                          src="/img/vector-61.svg"
                          alt="Vector"
                        />
                        <img class="vector-18" :src="vector19" alt="Vector" />
                      </div>
                    </div>
                  </div>
                  <div class="overlap-group-1">
                    <img class="x2-4" :src="x24" alt="2 4" />
                    <div class="overlap-group-2">
                      <div class="flex-col">
                        <div class="text-4 valign-text-middle inter-semi-bold-ship-gray-12px">{{ text107 }}</div>
                        <div class="viwe-more valign-text-middle inter-normal-ship-gray-10px">{{ viweMore4 }}</div>
                      </div>
                      <div class="caret-right">
                        <img
                          class="vector-1"
                          src="/img/vector-61.svg"
                          alt="Vector"
                        />
                        <img class="vector-19" :src="vector21" alt="Vector" />
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
      <img
        class="vector-14-1"
        src="/img/vector-14.svg"
        alt="Vector 14"
      />
      <div class="view-2">
        <group10 />
        <div class="frame-1321317467-1">
          <img class="rectangle-3" :src="rectangle3" alt="Rectangle 3" />
          <div class="frame-1321317466">
            <div class="group-container">
              <div class="group-14">
                <div class="text-3 valign-text-middle inter-bold-mandy-20px">{{ text75 }}</div>
                <div class="text-12 valign-text-middle inter-normal-ship-gray-16px">{{ text77 }}</div>
                <div class="text-11 valign-text-middle inter-normal-ship-gray-16px" v-html="text76"></div>
                <div class="phone valign-text-middle inter-normal-ship-gray-16px">{{ phone }}</div>
                <div class="x900 valign-text-middle inter-normal-ship-gray-16px">{{ x900 }}</div>
              </div>
              <div class="group-12">
                <div class="text-3 valign-text-middle inter-bold-mandy-20px">{{ text74 }}</div>
                <p class="x12km-14 valign-text-middle inter-normal-ship-gray-16px">{{ x12Km141 }}</p>
                <p class="x12km-14-1 valign-text-middle inter-normal-ship-gray-16px">{{ x12Km142 }}</p>
                <p class="x19km-23 valign-text-middle inter-normal-ship-gray-16px">{{ x19Km23 }}</p>
              </div>
              <div class="group-13">
                <div class="text-3 valign-text-middle inter-bold-mandy-20px">{{ text78 }}</div>
                <p class="x497m-6 valign-text-middle inter-normal-ship-gray-16px">{{ x497M6 }}</p>
                <div class="x811m-10 valign-text-middle inter-normal-ship-gray-16px">{{ x811M10 }}</div>
                <div class="d1-830m-10 valign-text-middle inter-normal-ship-gray-16px">{{ d1830M10 }}</div>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="view-3">
        <div class="frame-1321317457-1">
          <img class="image-1" :src="image2" alt="image" /><img class="image-2" :src="image3" alt="image" /><img
            class="image-3"
            :src="image4"
            alt="image"
          /><img class="image-4" :src="image5" alt="image" />
        </div>
        <img
          class="vector-5-1"
          src="/img/vector-5.svg"
          alt="Vector 5"
        />
      </div>
      <div class="navigation-footer">
        <frame132131753022 v-bind="frame132131753022Props" />
      </div>
    </div>
  </div>
</template>

<script>
import Navigation from "./Navigation";
import Frame1321317493 from "./Frame1321317493";
import Frame1321317483 from "./Frame1321317483";
import Card from "./Card";
import Frame1321317481 from "./Frame1321317481";
import Frame13213176122 from "./Frame13213176122";
import XButton3 from "./XButton3";
import XButton4 from "./XButton4";
import Frame1321317474 from "./Frame1321317474";
import Frame1321317475 from "./Frame1321317475";
import Group10 from "./Group10";
import Frame132131753022 from "./Frame132131753022";
import Group27 from "./Group27";
export default {
  name: "Screen2",
  components: {
    Navigation,
    Frame1321317493,
    Frame1321317483,
    Card,
    Frame1321317481,
    Frame13213176122,
    XButton3,
    XButton4,
    Frame1321317474,
    Frame1321317475,
    Group10,
    Frame132131753022,
    Group27,
  },
  props: [
    "heroImage",
    "text66",
    "text67",
    "text68",
    "frame13213174901",
    "text72",
    "seminar",
    "frame1321317491",
    "text69",
    "publications1",
    "frame13213174902",
    "text70",
    "Information1",
    "frame1321317492",
    "text71",
    "membership",
    "text112",
    "forYou",
    "text113",
    "image1",
    "text120",
    "Information2",
    "text121",
    "date1",
    "hotInformationVol319",
    "vector3",
    "date2",
    "text123",
    "vector5",
    "date3",
    "hotInformationVol318",
    "vector8",
    "date4",
    "hotInformationVol3161",
    "vector10",
    "date5",
    "hotInformationVol3162",
    "vector12",
    "text101",
    "publications2",
    "x221",
    "date6",
    "viweMore1",
    "hotInformationVol324",
    "text104",
    "otherInformation",
    "x222",
    "text108",
    "viweMore2",
    "vector17",
    "x23",
    "text106",
    "viweMore3",
    "vector19",
    "x24",
    "text107",
    "viweMore4",
    "vector21",
    "rectangle3",
    "text75",
    "text77",
    "text76",
    "phone",
    "x900",
    "text74",
    "x12Km141",
    "x12Km142",
    "x19Km23",
    "text78",
    "x497M6",
    "x811M10",
    "d1830M10",
    "image2",
    "image3",
    "image4",
    "image5",
    "card1Props",
    "card2Props",
    "frame1321317481Props",
    "frame13213174741Props",
    "frame13213174751Props",
    "frame13213174752Props",
    "frame13213174742Props",
    "frame132131753022Props",
  ],
};
</script>

<style>
.u12488u12483u12501u12442-1 {
  align-items: flex-end;
  background-color: var(--white);
  display: flex;
  flex-direction: column;
  height: 6022px;
  overflow: hidden;
  position: relative;
  width: 1440px;
}

.overlap-group7 {
  align-self: center;
  height: 766px;
  margin-left: 1px;
  margin-top: 32px;
  position: relative;
  width: 1373px;
  z-index: 1;
}

.hero-image {
  align-items: flex-start;
  background-position: 50% 50%;
  background-size: cover;
  border-radius: 10px;
  display: flex;
  height: 682px;
  left: 0;
  overflow: hidden;
  position: absolute;
  top: 0;
  width: 1373px;
}

.frame-1321317489 {
  align-items: flex-end;
  background-color: #4d4d4d33;
  border-radius: 10px;
  display: flex;
  height: 682px;
  overflow: hidden;
  padding: 146px 64px;
  width: 1373px;
}

.frame-1321317608 {
  align-items: flex-start;
  display: flex;
  flex-direction: column;
  gap: 20px;
  position: relative;
  width: 911px;
}

.frame-1321317610 {
  align-items: center;
  background-color: var(--white);
  border-radius: 15px;
  display: inline-flex;
  flex: 0 0 auto;
  gap: 10px;
  justify-content: center;
  padding: 15px 25px;
  position: relative;
}

.text {
  letter-spacing: 0;
  line-height: normal;
  margin-top: -1px;
  position: relative;
  width: fit-content;
}

.frame-1321317609 {
  align-items: center;
  background-color: var(--white);
  border-radius: 15px;
  display: inline-flex;
  flex: 0 0 auto;
  gap: 10px;
  justify-content: center;
  overflow: hidden;
  padding: 15px 25px;
  position: relative;
}

.frame-1321317611 {
  align-items: center;
  background-color: var(--mandy);
  border-radius: 10px;
  display: inline-flex;
  flex: 0 0 auto;
  gap: 20px;
  justify-content: center;
  overflow: hidden;
  padding: 15px 25px;
  position: relative;
}

.text-3-1 {
  letter-spacing: 0;
  line-height: normal;
  position: relative;
  white-space: nowrap;
  width: fit-content;
}

.frame-1321317612 {
  background-color: var(--white);
  border-radius: 8px;
  height: 27px;
  overflow: hidden;
  position: relative;
  width: 27px;
}

.vector-2 {
  height: 12px;
  left: 6px;
  position: absolute;
  top: 8px;
  width: 14px;
}

.frame-1321317457 {
  align-items: flex-end;
  background-color: var(--white);
  border-radius: 10px;
  display: flex;
  height: 168px;
  left: 69px;
  overflow: hidden;
  padding: 0 20px;
  position: absolute;
  top: 598px;
  width: 1235px;
}

.frame-1321317490 {
  align-items: flex-start;
  background-position: 50% 50%;
  background-size: cover;
  border-radius: 10px;
  display: flex;
  overflow: hidden;
  width: 284px;
}

.frame-1321317491 {
  align-items: flex-end;
  background-color: var(--abbey);
  display: flex;
  flex-direction: column;
  height: 148px;
  justify-content: center;
  padding: 0px 70px;
  position: relative;
  width: 284px;
}

.text-1 {
  align-self: stretch;
  letter-spacing: -0.3px;
  line-height: 60px;
  position: relative;
  text-align: center;
}

.seminar,
.publications,
.Information {
  align-self: stretch;
  letter-spacing: -0.48px;
  line-height: 60px;
  margin-top: -30px;
  position: relative;
  text-align: center;
}

.frame-1321317491-1 {
  align-items: flex-start;
  background-position: 50% 50%;
  background-size: cover;
  border-radius: 10px;
  display: flex;
  margin-left: 20px;
  overflow: hidden;
  width: 284px;
}

.frame-1321317491-2 {
  align-items: center;
  background-color: var(--abbey);
  display: flex;
  flex-direction: column;
  height: 148px;
  justify-content: center;
  padding: 0px 27px;
  position: relative;
  width: 284px;
}

.frame-1321317490-1 {
  align-items: flex-start;
  background-position: 50% 50%;
  background-size: cover;
  border-radius: 10px;
  display: flex;
  margin-left: 20px;
  overflow: hidden;
  width: 283px;
}

.frame-1321317491-3 {
  align-items: center;
  background-color: var(--abbey);
  display: flex;
  flex-direction: column;
  height: 148px;
  justify-content: center;
  padding: 0px 42px;
  position: relative;
  width: 284px;
}

.text-2 {
  align-self: stretch;
  letter-spacing: -0.32px;
  line-height: 60px;
  position: relative;
  text-align: center;
}

.frame-1321317492 {
  align-items: flex-start;
  background-position: 50% 50%;
  background-size: cover;
  border-radius: 10px;
  display: flex;
  margin-left: 21px;
  overflow: hidden;
  width: 284px;
}

.frame-1321317491-4 {
  align-items: center;
  background-color: var(--abbey);
  display: flex;
  flex-direction: column;
  height: 148px;
  justify-content: center;
  padding: 0px 70px;
  position: relative;
  width: 284px;
}

.membership {
  letter-spacing: -0.48px;
  line-height: 60px;
  margin-left: -6.5px;
  margin-right: -6.5px;
  margin-top: -30px;
  position: relative;
  text-align: center;
  white-space: nowrap;
  width: fit-content;
}

.vector-13 {
  height: 120px;
  margin-top: 81px;
  width: 1440px;
  z-index: 7;
}

.overlap-group8 {
  height: 1841px;
  position: relative;
  width: 1440px;
  z-index: 6;
}

.main {
  align-items: flex-start;
  background-color: var(--cararra);
  display: flex;
  flex-direction: column;
  gap: 50px;
  left: 2px;
  padding: 0px 50px 50px;
  position: absolute;
  top: 0;
  width: 1438px;
}

.about {
  align-self: stretch;
  height: 279.01px;
  position: relative;
  width: 100%;
}

.flex-col-1 {
  align-items: center;
  display: flex;
  flex-direction: column;
  min-height: 278px;
  width: 1340px;
}

.ico_logo-2 {
  height: 35px;
  position: relative;
  width: 1340px;
}

.text-47 {
  color: var(--hot-pink);
  font-family: var(--font-family-inter);
  font-size: 36px;
  font-weight: 600;
  letter-spacing: -0.72px;
  line-height: normal;
  margin-top: 31px;
  min-height: 44px;
  text-align: center;
  width: 1340px;
}

.group-30 {
  align-items: center;
  display: flex;
  margin-left: 2px;
  margin-top: 33px;
  min-width: 270px;
}

.vector-16 {
  height: 2px;
  width: 69px;
}

.for-you {
  color: var(--hot-pink);
  font-family: var(--font-family-inter);
  font-size: var(--font-size-xl);
  font-weight: 700;
  height: 30px;
  letter-spacing: 0;
  line-height: 30px;
  margin-left: 21px;
  min-width: 89px;
  white-space: nowrap;
}

.vector-15 {
  height: 2px;
  margin-left: 20px;
  width: 69px;
}

.text-48 {
  color: var(--ship-gray);
  font-family: var(--font-family-inter);
  font-size: var(--font-size-xl);
  font-weight: 400;
  letter-spacing: -0.4px;
  line-height: 40px;
  margin-top: 25px;
  min-height: 80px;
  text-align: center;
  width: 1340px;
}

.frame-1321317462 {
  align-items: flex-start;
  align-self: stretch;
  display: flex;
  flex: 0 0 auto;
  flex-direction: column;
  gap: 10px;
  position: relative;
  width: 100%;
}

.frame-1321317456 {
  align-items: center;
  display: inline-flex;
  flex: 0 0 auto;
  flex-direction: column;
  gap: 40px;
  margin-right: -2px;
  position: relative;
}

.frame-1321317482 {
  align-items: flex-start;
  display: flex;
  flex: 0 0 auto;
  gap: 20px;
  overflow: hidden;
  position: relative;
  width: 1340px;
}

.card {
  height: 330px;
  position: relative;
  width: 434px;
}

.image {
  align-items: flex-end;
  background-position: 50% 50%;
  background-size: cover;
  border-radius: 10px;
  display: flex;
  height: 330px;
  overflow: hidden;
  position: relative;
  width: 429px;
}

.button {
  align-items: center;
  background-color: var(--black);
  border-radius: 10px;
  box-shadow: 0px 1px 2px #0000000d;
  display: inline-flex;
  flex: 0 0 auto;
  gap: 10px;
  justify-content: center;
  padding: 10px 100px;
  position: relative;
}

.text-55 {
  letter-spacing: 0;
  line-height: 22.5px;
  margin-top: -1px;
  position: relative;
  white-space: nowrap;
  width: fit-content;
}

.view {
  align-items: flex-start;
  align-self: stretch;
  background-color: var(--white);
  border-radius: 20px;
  display: flex;
  flex: 0 0 auto;
  flex-direction: column;
  gap: 30px;
  overflow: hidden;
  padding: 50px;
  position: relative;
  width: 100%;
}

.frame-1321317480 {
  align-items: center;
  align-self: stretch;
  display: flex;
  flex: 0 0 auto;
  justify-content: space-between;
  position: relative;
  width: 100%;
}

.group-10 {
  height: 120px;
  position: relative;
  width: 313px;
}

.Information-1 {
  height: 72px;
  left: 0;
  letter-spacing: 0;
  line-height: 72px;
  position: absolute;
  top: 48px;
  white-space: nowrap;
}

.flex-row {
  align-items: center;
  display: flex;
  gap: 23px;
  height: 36px;
  left: 0;
  min-width: 209px;
  position: absolute;
  top: 0;
}

.vector-6 {
  height: 1px;
  object-fit: cover;
  width: 91px;
}

.text-56 {
  height: 36px;
  letter-spacing: 0;
  line-height: 36px;
  min-width: 95px;
  white-space: nowrap;
}

.frame-1321317467 {
  align-items: flex-start;
  align-self: stretch;
  display: flex;
  flex: 0 0 auto;
  flex-direction: column;
  position: relative;
  width: 100%;
}

.frame-13213174 {
  align-self: stretch;
  height: 80px;
  position: relative;
  width: 100%;
}

.frame-13 {
  align-items: center;
  border-bottom: 0.5px dashed;
  border-bottom-width: 1px;
  border-color: var(--mandy);
  display: flex;
  height: 79px;
  min-width: 1238px;
  position: relative;
  top: 1px;
}

.date {
  height: 13px;
  letter-spacing: 0;
  line-height: 36px;
  margin-bottom: 2px;
  min-width: 93px;
  white-space: nowrap;
}

.hot-info {
  height: 13px;
  letter-spacing: 0;
  line-height: 36px;
  margin-bottom: 2px;
  margin-left: 20px;
  min-width: 326px;
  white-space: nowrap;
}

.caret-circle-right {
  align-items: flex-start;
  display: flex;
  gap: 944px;
  margin-left: 650px;
  overflow: hidden;
  width: 33px;
}

.overlap-group {
  align-items: center;
  align-self: center;
  background-image: url(/img/vector-73.svg);
  background-size: 100% 100%;
  display: flex;
  height: 27px;
  justify-content: flex-end;
  margin-left: 3px;
  min-width: 27px;
  padding: 0 9.3px;
}

.vector {
  height: 12px;
  width: 7px;
}

.vector-3 {
  height: 33px;
  margin-top: -2103px;
  width: 33px;
}

.text-58 {
  height: 13px;
  letter-spacing: 0;
  line-height: 36px;
  margin-bottom: 2px;
  margin-left: 20px;
  min-width: 486px;
  white-space: nowrap;
}

.caret-circle-right-1 {
  align-items: flex-start;
  display: flex;
  gap: 944px;
  margin-left: 490px;
  overflow: hidden;
  width: 33px;
}

.vector-4 {
  height: 33px;
  margin-top: -2183px;
  width: 33px;
}

.vector-5 {
  height: 33px;
  margin-top: -2263px;
  width: 33px;
}

.vector-7 {
  height: 33px;
  margin-top: -2343px;
  width: 33px;
}

.vector-8 {
  height: 33px;
  margin-top: -2423px;
  width: 33px;
}

.frame-1321317484 {
  align-items: flex-start;
  background-color: var(--cararra);
  display: flex;
  flex-direction: column;
  gap: 50px;
  margin-right: -1px;
  padding: 50px;
  position: relative;
  width: 1439px;
  z-index: 5;
}

.group-25 {
  height: 126px;
  position: relative;
  width: 356px;
}

.group-10-1 {
  align-items: flex-start;
  display: flex;
  flex-direction: column;
  gap: 18px;
  min-height: 126px;
  width: 358px;
}

.group-20 {
  align-items: center;
  display: flex;
  gap: 26px;
  min-width: 191px;
}

.text-36 {
  height: 36px;
  letter-spacing: 0;
  line-height: 36px;
  min-width: 72px;
  white-space: nowrap;
}

.ation {
  height: 72px;
  letter-spacing: 0;
  line-height: 72px;
  white-space: nowrap;
}

.frame-1321317472 {
  align-items: flex-end;
  align-self: stretch;
  display: flex;
  flex: 0 0 auto;
  gap: 20px;
  position: relative;
  width: 100%;
}

.frame-1321317485 {
  align-items: flex-start;
  align-self: stretch;
  background-color: var(--white);
  display: flex;
  flex-direction: column;
  gap: 20px;
  justify-content: flex-end;
  overflow: hidden;
  position: relative;
  width: 634px;
}

.frame-1321317487 {
  align-items: flex-end;
  align-self: stretch;
  background-color: var(--white);
  display: flex;
  height: 91px;
  justify-content: space-between;
  position: relative;
  width: 100%;
}

.group-18 {
  height: 43px;
  position: relative;
  width: 88px;
}

.vector-9 {
  height: 43px;
  left: 0;
  position: absolute;
  top: 0;
  width: 23px;
}

.vector-10 {
  height: 43px;
  left: 65px;
  position: absolute;
  top: 0;
  width: 24px;
}

.frame-1321317486 {
  align-items: center;
  background-color: var(--white);
  display: inline-flex;
  flex: 0 0 auto;
  gap: 20px;
  margin-right: -183px;
  position: relative;
}

.frame-1321317475 {
  height: 286px;
  overflow: hidden;
  position: relative;
  width: 259px;
}

.overlap-group3 {
  height: 286px;
  position: relative;
  width: 265px;
}

.x2-2 {
  height: 252px;
  left: 0;
  object-fit: cover;
  position: absolute;
  top: 0;
  width: 76px;
}

.overlap-group-3 {
  height: 69px;
  left: 49px;
  position: absolute;
  top: 217px;
  width: 158px;
}

.rectangle-6 {
  height: 69px;
  left: 0;
  position: absolute;
  top: 0;
  width: 27px;
}

.date-1 {
  height: 15px;
  left: 8px;
  letter-spacing: 0;
  line-height: 15px;
  position: absolute;
  top: 5px;
  width: 58px;
}

.viwe-more-1 {
  height: 12px;
  left: 8px;
  letter-spacing: 0;
  line-height: 12px;
  position: absolute;
  text-decoration: underline;
  top: 49px;
  white-space: nowrap;
}

.hot-information-vol324 {
  height: 17px;
  left: 8px;
  letter-spacing: 0;
  line-height: 16.5px;
  position: absolute;
  top: 20px;
  width: 150px;
}

.frame-21 {
  height: 1px;
  margin-bottom: -1px;
  position: relative;
  width: 1239px;
}

.vector-container {
  align-items: flex-start;
  display: flex;
  min-width: 1239px;
}

.vector-11 {
  height: 1px;
  object-fit: cover;
  width: 603px;
}

.vector-13-1 {
  height: 1px;
  object-fit: cover;
  width: 184px;
}

.vector-12 {
  height: 1px;
  object-fit: cover;
  width: 452px;
}

.group-25-1 {
  height: 126px;
  position: relative;
  width: 485px;
}

.group-10-2 {
  align-items: flex-start;
  display: flex;
  flex-direction: column;
  gap: 18px;
  min-height: 126px;
  width: 487px;
}

.group-20-1 {
  align-items: center;
  display: flex;
  gap: 26px;
  min-width: 263px;
}

.text-39 {
  height: 36px;
  letter-spacing: 0;
  line-height: 36px;
  min-width: 144px;
  white-space: nowrap;
}

.view-1 {
  height: 398px;
  position: relative;
  width: 1239px;
}

.frame-1321317473 {
  align-items: flex-start;
  display: flex;
  flex-direction: column;
  gap: 10px;
  left: 609px;
  position: absolute;
  top: 0;
  width: 630px;
}

.frame-1321317473-1 {
  align-self: stretch;
  height: 398px;
  overflow: hidden;
  position: relative;
  width: 100%;
}

.overlap-group-container {
  align-items: flex-start;
  display: flex;
  flex-direction: column;
  gap: 15px;
  min-height: 398px;
  position: relative;
  top: 1px;
  width: 634px;
}

.overlap-group3-1 {
  height: 122px;
  position: relative;
  width: 634px;
}

.x2-2-1,
.x2-3,
.x2-4 {
  height: 122px;
  left: 0;
  object-fit: cover;
  position: absolute;
  top: 0;
  width: 630px;
}

.overlap-group2 {
  align-items: center;
  background-color: var(--white);
  border-radius: 10px 0px 0px 0px;
  display: flex;
  gap: 41px;
  height: 60px;
  justify-content: flex-end;
  left: 321px;
  min-width: 309px;
  padding: 1.3px 12.7px;
  position: absolute;
  top: 62px;
}

.flex-col-2 {
  align-items: flex-start;
  align-self: flex-end;
  display: flex;
  flex-direction: column;
  gap: 7px;
  min-height: 41px;
  width: 220px;
}

.text-4 {
  height: 13px;
  letter-spacing: 0;
  line-height: 18px;
  white-space: nowrap;
}

.viwe-more {
  height: 16px;
  letter-spacing: 0;
  line-height: 15px;
  text-decoration: underline;
  white-space: nowrap;
}

.caret-right {
  align-items: flex-start;
  display: flex;
  gap: 957px;
  margin-bottom: 0.96px;
  overflow: hidden;
  width: 17px;
}

.vector-14 {
  align-self: center;
  height: 13px;
  margin-left: 5px;
  margin-top: 0;
  width: 7px;
}

.vector-17 {
  height: 17px;
  margin-top: -4002.07px;
  width: 17px;
}

.overlap-group-1 {
  height: 123px;
  position: relative;
  width: 634px;
}

.overlap-group-2 {
  align-items: center;
  background-color: var(--white);
  border-radius: 10px 0px 0px 0px;
  display: flex;
  gap: 42px;
  height: 60px;
  justify-content: flex-end;
  left: 316px;
  min-width: 314px;
  padding: 1.3px 13px;
  position: absolute;
  top: 63px;
}

.flex-col {
  align-items: flex-start;
  align-self: flex-end;
  display: flex;
  flex-direction: column;
  gap: 7px;
  min-height: 41px;
  width: 224px;
}

.vector-1 {
  align-self: center;
  height: 13px;
  margin-left: 5px;
  width: 7px;
}

.vector-18 {
  height: 17px;
  margin-top: -4140px;
  width: 17px;
}

.vector-19 {
  height: 17px;
  margin-top: -4278px;
  width: 17px;
}

.vector-14-1 {
  height: 120px;
  width: 1440px;
  z-index: 8;
}

.view-2 {
  align-items: flex-start;
  background-color: var(--white);
  display: flex;
  flex-direction: column;
  gap: 24px;
  padding: 0px 50px 80px;
  position: relative;
  width: 1440px;
  z-index: 3;
}

.frame-1321317467-1 {
  align-items: center;
  align-self: stretch;
  display: flex;
  flex: 0 0 auto;
  gap: 40px;
  position: relative;
  width: 100%;
}

.rectangle-3 {
  height: 398px;
  object-fit: cover;
  position: relative;
  width: 692px;
}

.frame-1321317466 {
  height: 398px;
  position: relative;
  width: 608px;
}

.group-container {
  align-items: flex-start;
  display: flex;
  flex-direction: column;
  gap: 30px;
  min-height: 391px;
  position: relative;
  top: 2px;
  width: 532px;
}

.group-14 {
  align-items: flex-start;
  display: flex;
  flex-direction: column;
  min-height: 140px;
  width: 515px;
}

.text-3 {
  height: 14px;
  letter-spacing: 0;
  line-height: 40px;
  margin-bottom: -26px;
  white-space: nowrap;
}

.text-12,
.phone,
.x900,
.x12km-14,
.x12km-14-1,
.x19km-23,
.x497m-6,
.x811m-10,
.d1-830m-10 {
  height: 12px;
  letter-spacing: 0;
  line-height: 32px;
  margin-bottom: -20px;
  margin-top: 15px;
  white-space: nowrap;
}

.text-11 {
  height: 30px;
  letter-spacing: 0;
  line-height: 17.6px;
  margin-top: 15px;
}

.group-12 {
  align-items: flex-start;
  display: flex;
  flex-direction: column;
  min-height: 95px;
  width: 399px;
}

.group-13 {
  align-items: flex-start;
  display: flex;
  flex-direction: column;
  min-height: 95px;
  width: 532px;
}

.view-3 {
  align-items: center;
  background-color: var(--celeste);
  display: flex;
  flex-direction: column;
  gap: 50px;
  margin-right: -1px;
  padding: 50px;
  position: relative;
  width: 1440px;
  z-index: 2;
}

.frame-1321317457-1 {
  align-items: center;
  align-self: stretch;
  display: flex;
  flex: 0 0 auto;
  gap: 22px;
  position: relative;
  width: 100%;
}

.image-1 {
  height: 118px;
  object-fit: cover;
  position: relative;
  width: 318px;
}

.image-2 {
  height: 96px;
  object-fit: cover;
  position: relative;
  width: 319px;
}

.image-3 {
  height: 129px;
  object-fit: cover;
  position: relative;
  width: 319px;
}

.image-4 {
  height: 129px;
  object-fit: cover;
  position: relative;
  width: 318px;
}

.vector-5-1 {
  height: 1px;
  margin-bottom: -0.5px;
  object-fit: cover;
  position: relative;
  width: 1240px;
}

.navigation-footer {
  align-items: center;
  background-color: var(--celeste);
  display: flex;
  flex-direction: column;
  gap: 50px;
  padding: 0px 100px 50px;
  position: relative;
  width: 1450px;
  z-index: 4;
}


</style>
