import Vue from "vue";
import Router from "vue-router";
import HomePage from "./components/HomePage";
import AboutPage from "./components/AboutInstitutePage";
import AboutInstitutePage from "./components/AboutInstitutePage";
import ServicesPage from "./components/ServicesPage";
import NewsPage from "./components/NewsPage";
import FaqPage from "./components/FaqPage";
import CompanyProfile from "./components/CompanyProfile";
import PrivacyPolicyPage from "./components/PrivacyPolicyPage";
import TermsOfServicePage from "./components/TermsOfServicePage";
import TransactionLawPage from "./components/TransactionLawPage";
import SitemapPage from "./components/SitemapPage";
import CriConsultingPage from "./components/CriConsultingPage";
import { homePageData } from "./data";

Vue.use(Router);

export default new Router({
  mode: "history",
  routes: [
    {
      path: "/",
      name: "top",
      component: HomePage,
      props: { ...homePageData },
    },
    {
      path: "/about",
      name: "about",
      component: AboutPage,
    },
    {
      path: "/about-institute",
      name: "aboutInstitute",
      component: AboutInstitutePage,
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
      path: "/company-profile",
      name: "companyProfile",
      component: CompanyProfile,
    },
    {
      path: "/privacy-policy",
      name: "privacyPolicy",
      component: PrivacyPolicyPage,
    },
    {
      path: "/terms-of-service",
      name: "termsOfService",
      component: TermsOfServicePage,
    },
    {
      path: "/transaction-law",
      name: "transactionLaw",
      component: TransactionLawPage,
    },
    {
      path: "/sitemap",
      name: "sitemap",
      component: SitemapPage,
    },
    {
      path: "/cri-consulting",
      name: "criConsulting",
      component: CriConsultingPage,
    },
    {
      path: "*",
      redirect: "/",
    },
  ],
});
