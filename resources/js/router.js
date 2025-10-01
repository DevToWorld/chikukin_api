import Vue from "vue";
import Router from "vue-router";
import HomePage from "./components/HomePage.vue";
import AboutPage from "./components/AboutPage.vue";
import ServicesPage from "./components/ServicesPage.vue";
import NewsPage from "./components/NewsPage.vue";
import FaqPage from "./components/FaqPage.vue";

Vue.use(Router);

export default new Router({
  mode: "history",
  routes: [
    {
      path: "/",
      name: "home",
      component: HomePage,
    },
    {
      path: "/about",
      name: "about",
      component: AboutPage,
    },
    {
      path: "/services",
      name: "services",
      component: ServicesPage,
    },
    {
      path: "/news",
      name: "news",
      component: NewsPage,
    },
    {
      path: "/faq",
      name: "faq",
      component: FaqPage,
    },
    {
      path: "*",
      redirect: "/"
    },
  ],
  scrollBehavior(to, from, savedPosition) {
    if (savedPosition) {
      return savedPosition;
    } else if (to.hash) {
      return {
        selector: to.hash,
        behavior: 'smooth'
      };
    } else {
      return { x: 0, y: 0 };
    }
  }
});