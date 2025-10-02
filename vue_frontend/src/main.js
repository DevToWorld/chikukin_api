import Vue from "vue";
import App from "./App.vue";
import router from "./router";
import store from "./store";
import restrictedDirective from "./directives/restricted";
import "../styleguide.css"
import "../globals.css"
import "./assets/styles/restrictions.css"

Vue.config.productionTip = false;

// グローバルディレクティブを登録
Vue.directive('restricted', restrictedDirective);

new Vue({
  render: h => h(App),
  router,
  store
}).$mount("#app");