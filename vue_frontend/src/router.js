import Vue from "vue";
import Router from "vue-router";
import { usePageText } from '@/composables/usePageText'
import HomePage from "./components/HomePage";
import CompanyProfile from "./components/CompanyProfile";
import AboutInstitutePage from "./components/AboutInstitutePage";
// ServicesPage import removed - replaced with MembershipPage
import NewsPage from "./components/NewsPage";
import FaqPage from "./components/FaqPage";
import PublicationsMemberPage from "./components/PublicationsMemberPage";
import PublicationsPublicPage from "./components/PublicationsPublicPage";
import ApplicationFormPage from "./components/ApplicationFormPage";
import SeminarRegistrationPage from "./components/SeminarRegistrationPage";
import AdminLoginPage from "./views/admin/AdminLoginPage";
import AdminResetPassword from "./views/admin/AdminResetPassword.vue";
import SimpleAdminLogin from "./views/admin/SimpleAdminLogin";
import NewAdminLogin from "./views/admin/NewAdminLogin";
import AdminDashboard from "./views/admin/AdminDashboard";
import NewAdminDashboard from "./views/admin/NewAdminDashboard";
import MemberManagement from "./views/admin/MemberManagement";
import SeminarManagement from "./views/admin/SeminarManagement";
import PublicationManagement from "./views/admin/PublicationManagement";
import EconomicReportManagement from "./views/admin/EconomicReportManagement";
import EconomicIndicatorManagement from "./views/admin/EconomicIndicatorManagement.vue";
import EconomicIndicatorCategoryManagement from "./views/admin/EconomicIndicatorCategoryManagement.vue";
import NoticeManagement from "./views/admin/NoticeManagement";
import InquiryManagement from "./views/admin/InquiryManagement";
import MediaManagement from "./views/admin/MediaManagement";
import MediaLibrary from "./views/admin/MediaLibrary.vue";
import PageEditForm from "./views/admin/PageEditForm";
import NewPageEditForm from "./views/admin/NewPageEditForm";
import SeminarEditForm from "./views/admin/SeminarEditForm";
import SeminarRegistrationApproval from "./views/admin/SeminarRegistrationApproval";
import PublicationEditForm from "./views/admin/PublicationEditForm";
import NoticeEditForm from "./views/admin/NoticeEditForm";
import MediaEditForm from "./views/admin/MediaEditForm";
import MailGroupManagement from "./views/admin/MailGroupManagement";
import EmailCampaignManagement from "./views/admin/EmailCampaignManagement";
import BlockCmsEditor from "./views/admin/BlockCmsEditor.vue";
import EmailSendNow from "./views/admin/EmailSendNow.vue";
import AdminUserManagement from "./views/admin/AdminUserManagement.vue";
import PublicationDetailPage from "./components/PublicationDetailPage";
import NewsDetailPage from "./components/NewsDetailPage";
import SeminarPage from "./components/SeminarPage";
import CurrentSeminarsPage from "./components/CurrentSeminarsPage";
import PastSeminarsPage from "./components/PastSeminarsPage";
import SeminarDetailPage from "./components/SeminarDetailPage";
import SeminarDetailReservedPage from "./components/SeminarDetailReservedPage";
import SeminarDetailJoinPage from "./components/SeminarDetailJoinPage";
import SeminarApplicationFormPage from "./components/SeminarApplicationFormPage.vue";
import SeminarApplicationConfirmPage from "./components/SeminarApplicationConfirmPage.vue";
import SeminarApplicationCompletePage from "./components/SeminarApplicationCompletePage.vue";
import MembershipApplicationFormPage from "./components/MembershipApplicationFormPage.vue";
import MembershipApplicationConfirmPage from "./components/MembershipApplicationConfirmPage.vue";
import MembershipApplicationCompletePage from "./components/MembershipApplicationCompletePage.vue";
import PremiumMembershipPage from "./components/PremiumMembershipPage.vue";
import GlossaryPage from "./components/GlossaryPage";
import EconomicIndicatorsPage from "./components/EconomicIndicatorsPage";
import EconomicStatisticsPage from "./components/EconomicStatisticsPage";
import EconomicStatisticsDetailPage from "./components/EconomicStatisticsDetailPage";
import EconomicStatisticsDetailPageGuest from "./components/EconomicStatisticsDetailPageGuest";
import TransactionLawPage from "./components/TransactionLawPage";
import PrivacyPolicyPage from "./components/PrivacyPolicyPage";
import TermsOfServicePage from "./components/TermsOfServicePage";
import CriConsultingPage from "./components/CRIConsultingPage";
import SitemapPage from "./components/SitemapPage";
import TestPage from "./components/TestPage";
import ContactFormPage from "./components/ContactFormPage";
import ContactConfirmPage from "./components/ContactConfirmPage";
import ContactCompletePage from "./components/ContactCompletePage";
import MemberLoginPage from "./views/MemberLoginPage";

import MyAccountPage from "./views/MyAccountPage";
import UpgradePage from "./views/UpgradePage";
import MembershipPage from "./components/MembershipPage";

Vue.use(Router);

// Avoid NavigationDuplicated errors on redundant navigations in Vue Router v3
const originalPush = Router.prototype.push;
Router.prototype.push = function push(location, onResolve, onReject) {
  if (onResolve || onReject) return originalPush.call(this, location, onResolve, onReject);
  return originalPush.call(this, location).catch(err => {
    if (err && (err.name === 'NavigationDuplicated' || (typeof err.message === 'string' && err.message.includes('redundant navigation')))) return;
    throw err;
  });
};

const originalReplace = Router.prototype.replace;
Router.prototype.replace = function replace(location, onResolve, onReject) {
  if (onResolve || onReject) return originalReplace.call(this, location, onResolve, onReject);
  return originalReplace.call(this, location).catch(err => {
    if (err && (err.name === 'NavigationDuplicated' || (typeof err.message === 'string' && err.message.includes('redundant navigation')))) return;
    throw err;
  });
};

const router = new Router({
  mode: "hash",
  scrollBehavior(to, from, savedPosition) {
    // ページ遷移時に常にページトップにスクロール
    return { x: 0, y: 0 };
  },
  routes: [
    {
      path: "/",
      name: "top",
      component: HomePage,
      meta: { title: "ちくぎん地域経済研究所" }
    },
    {
      path: "/company",
      name: "company",
      component: CompanyProfile,
      meta: { title: "会社概要 - ちくぎん地域経済研究所" }
    },
    {
      path: "/aboutus",
      name: "aboutus",
      component: AboutInstitutePage,
      meta: { title: "ちくぎん地域経済研究所について - ちくぎん地域経済研究所" }
    },
    // 旧URLからのリダイレクト
    {
      path: "/about",
      redirect: "/company"
    },
    {
      path: "/about-institute",
      redirect: "/aboutus"
    },
    {
      path: "/company-profile",
      redirect: "/company"
    },
    {
      path: "/services",
      name: "services",
      component: MembershipPage,
      meta: { title: "サービス案内 - ちくぎん地域経済研究所" }
    },
    {
      path: "/news",
      name: "news",
      component: NewsPage,
      meta: { title: "お知らせ - ちくぎん地域経済研究所" }
    },
    {
      path: "/news/:id",
      name: "newsDetail",
      component: NewsDetailPage,
    },
    {
      path: "/faq",
      name: "faq",
      component: FaqPage,
      meta: { title: "よくある質問 - ちくぎん地域経済研究所" }
    },
    {
      path: "/publication",
      name: "publication",
      component: PublicationsMemberPage,
      meta: { title: "刊行物 - ちくぎん地域経済研究所" }
    },
    {
      path: "/publications-public",
      name: "publicationsPublic",
      component: PublicationsPublicPage,
      meta: { title: "刊行物（一般） - ちくぎん地域経済研究所" }
    },
    {
      path: "/publications/:id",
      name: "publicationDetail",
      component: PublicationDetailPage,
    },
    {
      path: "/publications-public/:id",
      name: "publicationPublicDetail",
      component: PublicationDetailPage,
    },
    {
      path: "/seminar",
      name: "seminar",
      component: SeminarPage,
      meta: { title: "セミナー - ちくぎん地域経済研究所" }
    },
    {
      path: "/seminars/current",
      name: "currentSeminars",
      component: CurrentSeminarsPage,
      meta: { title: "受付中のセミナー - ちくぎん地域経済研究所" }
    },
    {
      path: "/seminars/:id/apply",
      name: "seminarApplyForm",
      component: SeminarApplicationFormPage,
      meta: { title: "セミナー申し込み - 入力" }
    },
    {
      path: "/seminars/:id/apply/confirm",
      name: "seminarApplyConfirm",
      component: SeminarApplicationConfirmPage,
      meta: { title: "セミナー申し込み - 確認" }
    },
    {
      path: "/seminars/:id/apply/complete",
      name: "seminarApplyComplete",
      component: SeminarApplicationCompletePage,
      meta: { title: "セミナー申し込み - 完了" }
    },
    {
      path: "/membership/apply",
      name: "membershipApplyForm",
      component: MembershipApplicationFormPage,
      meta: { title: "入会申し込み - 入力" }
    },
    {
      path: "/membership/apply/confirm",
      name: "membershipApplyConfirm",
      component: MembershipApplicationConfirmPage,
      meta: { title: "入会申し込み - 確認" }
    },
    {
      path: "/membership/apply/complete",
      name: "membershipApplyComplete",
      component: MembershipApplicationCompletePage,
      meta: { title: "入会申し込み - 完了" }
    },
    {
      path: "/seminar/archive",
      name: "seminarArchive",
      component: PastSeminarsPage,
      meta: { title: "受付中のセミナー - ��くぎん地域経済研究所" }
    },
    {
      path: "/seminar/:id",
      name: "seminarDetail",
      component: SeminarDetailPage,
      meta: { title: "過去のセミナー - ちくぎん地域経済研究所" }
    },
    {
      path: "/seminars/:id",
      name: "seminarDetailCurrent",
      component: SeminarDetailPage,
      meta: { title: "セミナー詳細（予約受付中） - ちくぎん地域経済研究所" }
    },
    {
      path: "/seminars/:id/reserved",
      name: "seminarDetailReserved",
      component: SeminarDetailReservedPage,
      meta: { title: "セミナー詳細（予約済み） - ちくぎん地域経済研究所" }
    },
    {
      path: "/seminars/:id/join",
      name: "seminarDetailJoin",
      component: SeminarDetailJoinPage,
      meta: { title: "セミナー詳細（今すぐ参加） - ちくぎん地域経済研究所" }
    },
    {
      path: "/apply",
      name: "apply",
      component: ApplicationFormPage,
    },
    {
      path: "/application-form",
      redirect: "/membership/apply"
    },
    {
      path: "/glossary",
      name: "glossary",
      component: GlossaryPage,
      meta: { title: "用語集 - ちくぎん地域経済研究所" }
    },
    {
      path: "/economic-indicators",
      name: "economicIndicators",
      component: EconomicIndicatorsPage,
      meta: { title: "経済指標一覧 - ちくぎん地域経済研究所" }
    },
    {
      path: "/economic-research",
      name: "economicResearch",
      component: EconomicStatisticsPage,
      meta: { title: "経済調査統計一覧 - ちくぎん地域経済研究所" }
    },
    {
      path: "/economic-statistics/:id/detail",
      name: "economicStatisticsDetail",
      component: EconomicStatisticsDetailPage,
      meta: { title: "経済・調査統計詳細 - ちくぎん地域経済研究所" }
    },
    {
      path: "/economic-statistics/:id/detail-guest",
      name: "economicStatisticsDetailGuest",
      component: EconomicStatisticsDetailPageGuest,
      meta: { title: "経済・調査統計詳細 - ちくぎん地域経済研究所" }
    },
    {
      path: "/legal",
      name: "legal",
      component: TransactionLawPage,
      meta: { title: "特定商取引法に関する表記 - ちくぎん地域経済研究所" }
    },
    {
      path: "/privacy",
      name: "privacy",
      component: PrivacyPolicyPage,
      meta: { title: "プライバシーポリシー - ちくぎん地域経済研究所" }
    },
    {
      path: "/terms",
      name: "terms",
      component: TermsOfServicePage,
      meta: { title: "利用規約 - ちくぎん地域経済研究所" }
    },
    {
      path: "/cri-consulting",
      name: "criConsulting",
      component: CriConsultingPage,
      meta: { title: "CRI経営コンサルティング - ちくぎん地域経済研究所" }
    },
    {
      path: "/contact",
      name: "contact",
      component: ContactFormPage,
      meta: { title: "お問い合わせ - ちくぎん地域経済研究所" }
    },
    {
      path: "/contact/confirm",
      name: "contactConfirm",
      component: ContactConfirmPage,
      meta: { title: "お問い合わせ確認 - ちくぎん地域経済研究所" }
    },
    {
      path: "/contact/complete",
      name: "contactComplete",
      component: ContactCompletePage,
      meta: { title: "お問い合わせ完了 - ちくぎん地域経済研究所" }
    },
    {
      path: "/sitemap",
      name: "sitemap",
      component: SitemapPage,
      meta: { title: "サイトマップ - ちくぎん地域経済研究所" }
    },
    {
      path: "/seminar-registration",
      name: "seminarRegistration",
      component: SeminarRegistrationPage,
    },
    {
      path: "/admin",
      name: "adminLogin",
      component: AdminLoginPage,
      meta: { title: "管理者ログイン - ちくぎん地域経済研究所" }
    },
    {
      path: "/admin/reset-password",
      name: "adminResetPassword",
      component: AdminResetPassword,
      meta: { title: "管理者パスワード再設定 - ちくぎん地域経済研究所" }
    },
    {
      path: "/admin/dashboard",
      name: "adminDashboard",
      component: MemberManagement,
      meta: { title: "管理ダッシュボード - ちくぎん地��経済研究所" }
    },
    {
      path: "/admin/member-list",
      name: "memberManagement",
      component: MemberManagement,
      meta: { title: "会員管理 - ちくぎん地域経済研究所" }
    },
    {
      path: "/admin/seminar",
      name: "seminarManagement",
      component: SeminarManagement,
      meta: { title: "セミナー管理 - ちくぎん地域経済研究所" }
    },
    {
      path: "/admin/seminar/register",
      name: "seminarNew",
      component: SeminarEditForm,
      meta: { title: "セミナー新規作成 - ちくぎん地域経済研究所" }
    },
    {
      path: "/admin/seminar/edit/:id",
      name: "seminarEdit", 
      component: SeminarEditForm,
      meta: { title: "セミナー編集 - ちくぎん地域経済研究所" }
    },
    {
      path: "/admin/seminar/manage",
      name: "seminarRegistrations",
      component: SeminarRegistrationApproval,
      meta: { title: "セミナー申込承認 - ちくぎん地域経済研究所" }
    },
    {
      path: "/admin/publication",
      name: "publicationManagement",
      component: PublicationManagement,
      meta: { title: "刊行物管理 - ちくぎん地域経済研究所" }
    },
    {
      path: "/admin/publication/register",
      name: "publicationNew",
      component: PublicationEditForm,
      meta: { title: "刊行物新規作成 - ちくぎん地域経済研究所" }
    },
    {
      path: "/admin/publication/edit/:id", 
      name: "publicationEdit",
      component: PublicationEditForm,
      meta: { title: "刊行物編集 - ちくぎん地域経済研究所" }
    },
    {
      path: "/admin/economic-reports",
      name: "economicReportManagement",
      component: EconomicReportManagement,
      meta: { title: "経済統計レポート管理 - ちくぎん地域経済研究所" }
    },
    {
      path: "/admin/economic-indicators",
      name: "economicIndicatorManagement",
      component: EconomicIndicatorManagement,
      meta: { title: "経済指標管理 - ちくぎん地域経済研究所" }
    },
    {
      path: "/admin/economic-indicator-categories",
      name: "economicIndicatorCategoryManagement",
      component: EconomicIndicatorCategoryManagement,
      meta: { title: "経済指標カテゴリ管理 - ちくぎん地域経済研究所" }
    },
    {
      path: "/admin/news",
      name: "noticeManagement",
      component: NoticeManagement,
      meta: { title: "お知らせ管理 - ちくぎん地域経済研究所" }
    },
    {
      path: "/admin/admin-users",
      name: "adminUserManagement",
      component: AdminUserManagement,
      meta: { title: "管理者管理 - ちくぎん地域経済研究所" }
    },
    {
      path: "/admin/mail-groups",
      name: "mailGroupManagement",
      component: MailGroupManagement,
      meta: { title: "メールグループ管理 - ちくぎん地域経済研究所" }
    },
    {
      path: "/admin/emails",
      name: "emailCampaignManagement",
      component: EmailCampaignManagement,
      meta: { title: "メール配信 - ちくぎん地域経済研究所" }
    },
    {
      path: "/admin/cms-v2/:slug?",
      name: "blockCmsEditor",
      component: BlockCmsEditor,
      meta: { title: "各ページ管理 - ちくぎん地域経済研究所" }
    },
    {
      path: "/admin/emails/send-now",
      name: "emailSendNow",
      component: EmailSendNow,
      meta: { title: "即時メール送信 - ちくぎん地域経済研究所" }
    },
    // Mail magazine route aliases
    {
      path: "/admin/mailmagazine",
      name: "mailMagazine",
      component: EmailCampaignManagement,
      meta: { title: "一括メール管理 - ちくぎん地域経済研究所" }
    },
    {
      path: "/admin/mailmagazine/new",
      name: "mailMagazineNew",
      component: EmailCampaignManagement,
      meta: { title: "一括メール新規作成 - ちくぎん地域経済研究所" }
    },
    {
      path: "/admin/mailmagazine/edit",
      name: "mailMagazineEdit",
      component: EmailCampaignManagement,
      meta: { title: "一括メール編集 - ちくぎん地域経済研究所" }
    },
    {
      path: "/admin/mailmagazine/preview",
      name: "mailMagazinePreview",
      component: EmailCampaignManagement,
      meta: { title: "一括メールプレビュー - ちくぎん地域経済研究所" }
    },
    {
      path: "/admin/mailmagazine/group",
      name: "mailMagazineGroup",
      component: MailGroupManagement,
      meta: { title: "一括メール送信グループ管理 - ちくぎん地域経済研究所" }
    },
    {
      path: "/admin/news/register",
      name: "noticeNew",
      component: NoticeEditForm,
      meta: { title: "お知らせ新規作成 - ちくぎん地域経済研究所" }
    },
    {
      path: "/admin/news/edit/:id",
      name: "noticeEdit", 
      component: NoticeEditForm,
      meta: { title: "お知らせ編集 - ちくぎん地域経済研究所" }
    },
    {
      path: "/admin/inquiries",
      name: "inquiryManagement",
      component: InquiryManagement,
      meta: { title: "お問い合わせ管理 - ちくぎん地域経済研究所" }
    },
    {
      path: "/admin/media",
      name: "mediaManagement",
      component: MediaManagement,
      meta: { title: "メディア管理 - ちくぎん地域経済研究所" }
    },
    {
      path: "/admin/media-library",
      name: "mediaLibrary",
      component: MediaLibrary,
      meta: { title: "メディアライブラリ - ちくぎん地域経済研究所" }
    },
    {
      path: "/admin/media/new",
      name: "mediaNew",
      component: MediaEditForm,
      meta: { title: "メディア新規追加 - ちくぎん地域経済研究所" }
    },
    {
      path: "/admin/media/:id/edit",
      name: "mediaEdit",
      component: MediaEditForm,
      meta: { title: "メディア編集 - ちくぎん地域経済研究所" }
    },
    {
      path: "/admin/pages",
      name: "pageManagement",
      component: NewAdminDashboard,
      meta: { title: "ページ管理 - ちくぎん地域経済研究所" }
    },
    {
      path: "/admin/pages/:pageKey/edit",
      name: "pageEdit",
      component: NewPageEditForm,
      meta: { title: "ページ編集 - ちくぎん地域経済研究所" }
    },
    {
      path: "/admin/pages/new",
      name: "pageNew",
      component: NewPageEditForm,
      meta: { title: "ページ新規作成 - ちくぎん地域経済研究所" }
    },
    {
      path: "/member-login",
      name: "memberLogin",
      component: MemberLoginPage,
      meta: { title: "会員ログイン - ちくぎん地域経済研究所" }
    },
    {
      path: "/register",
      redirect: "/membership/apply"
    },
    {
      path: "/membership",
      name: "membership",
      component: MembershipPage,
      meta: { title: "入会案内 - ちくぎん地域経済研究所" }
    },
    {
      path: "/premium-membership",
      name: "premiumMembership",
      component: PremiumMembershipPage,
      meta: { title: "プレミアム会員 - ちくぎん地域経済研究所" }
    },
    // Short alias for premium page
    {
      path: "/premium",
      name: "premiumMembershipShort",
      component: PremiumMembershipPage,
      meta: { title: "プレミアム会員 - ちくぎん地域経済研究所" }
    },
    {
      path: "/membership/standard",
      name: "standardMembership",
      component: MembershipPage,
      meta: { title: "スタンダード会員のご案内 - ちくぎん地域経済研究所" }
    },
    {
      path: "/membership/premium",
      name: "premiumMembershipOld",
      component: PremiumMembershipPage,
      meta: { title: "プレミアム会員の特典 - ちくぎん地域経済研究所" }
    },
    {
      path: "/myaccount", 
      name: "myAccount",
      component: MyAccountPage,
      meta: { title: "マイアカウント - ちくぎん地域経済研究所" }
    },
    {
      path: "/member-directory",
      name: "memberDirectory",
      component: () => import('./views/MemberDirectoryPage.vue'),
      meta: { title: "会員名簿 - ちくぎん地域経済研究所" }
    },
    {
      path: "/member-favorites",
      name: "memberFavorites",
      component: () => import('./views/MemberFavoritesPage.vue'),
      meta: { title: "お気に入り会員 - ちくぎん地域経済研究所" }
    },
    {
      path: "/upgrade",
      name: "upgrade",
      component: UpgradePage,
      meta: { title: "プランアップグレード - ちくぎん地域経済研究所" }
    },
    {
      path: "/seminar-join/:id",
      name: "seminarJoin",
      component: SeminarDetailJoinPage,
      meta: { title: "セミナー参加 - ちくぎん地域経済研究所" }
    },
    {
      path: "/seminar-room/:id",
      name: "seminarRoom",
      component: SeminarDetailJoinPage,
      meta: { title: "セミナールーム - ちくぎん地域経済研究所" }
    },
    // 旧URLからのリダイレクト設定
    {
      path: "/seminars",
      redirect: "/seminar"
    },
    {
      path: "/seminars/past",
      name: "pastSeminars",
      component: PastSeminarsPage,
      meta: { title: "過去のセミナー - ちくぎん地域経済研究所" }
    },
    {
      path: "/seminars/:id",
      redirect: to => `/seminar/${to.params.id}`
    },
    {
      path: "/publications",
      redirect: "/publication"
    },
    {
      path: "/publications-public",
      redirect: "/publication"
    },
    {
      path: "/statistics",
      redirect: "/economic-research"
    },
    {
      path: "/login",
      redirect: "/member-login"
    },
    {
      path: "/transaction-law",
      redirect: "/legal"
    },
    {
      path: "/privacy-policy",
      redirect: "/privacy"
    },
    {
      path: "/terms-of-service",
      redirect: "/terms"
    },
    {
      path: "/my-account",
      redirect: "/myaccount"
    },
    // 管理者ページの旧URLからのリダイレクト
    {
      path: "/admin/login",
      redirect: "/admin"
    },
    {
      path: "/admin/members",
      redirect: "/admin/member-list"
    },
    {
      path: "/admin/seminars",
      redirect: "/admin/seminar"
    },
    {
      path: "/admin/seminars/new",
      redirect: "/admin/seminar/register"
    },
    {
      path: "/admin/seminars/:id/edit",
      redirect: "/admin/seminar/edit"
    },
    {
      path: "/admin/seminars/:id/registrations",
      redirect: "/admin/seminar/manage"
    },
    {
      path: "/admin/publications",
      redirect: "/admin/publication"
    },
    {
      path: "/admin/publications/new",
      redirect: "/admin/publication/register"
    },
    {
      path: "/admin/publications/:id/edit",
      redirect: (to) => `/admin/publication/edit/${to.params.id}`
    },
    {
      path: "/admin/notices",
      redirect: "/admin/news"
    },
    {
      path: "/admin/notices/new",
      redirect: "/admin/news/register"
    },
    {
      path: "/admin/notices/:id/edit",
      redirect: (to) => `/admin/news/edit/${to.params.id}`
    },
    {
      path: "*",
      redirect: "/",
    },
  ],
});

// ルートごとに必要なCMSページキーを定義（初回描画前に事前取得）
const PAGE_KEYS_BY_ROUTE = {
  // Public pages with CMS text usage
  top: ['home'],
  company: ['company-profile'],
  aboutus: ['about-institute'],
  privacy: ['privacy'],
  terms: ['terms'],
  legal: ['transaction-law'],
  news: ['news'],
  newsDetail: ['news'],
  contact: ['contact'],
  membership: ['membership'],
  standardMembership: ['standard-membership'],
  premiumMembership: ['premium-membership'],
  premiumMembershipShort: ['premium-membership'],
  glossary: ['glossary'],
  faq: ['faq'],
}

// ページタイトル更新 + 必要なCMSテキストの事前読み込み
router.beforeEach(async (to, from, next) => {
  // ルートのメタ情報からタイトルを取得
  const title = to.meta?.title || 'ちくぎん地域経済研究所 - CMS管理システム';
  document.title = title;
  // Admin guard for Block CMS
  if (to.path.startsWith('/admin/cms-v2')) {
    const hasAdmin = !!localStorage.getItem('admin_token')
    if (!hasAdmin) {
      return next('/admin')
    }
  }
  try {
    // Prefetch media registry once before first route resolves
    try {
      if (typeof window !== 'undefined' && !window.__MEDIA_READY__) {
        const mod = await import('@/composables/useMedia')
        const { useMedia } = mod
        const m = useMedia()
        await m.ensure()
        window.__MEDIA_READY__ = true
      }
    } catch (_) { /* ignore media prefetch errors */ }

    const keys = PAGE_KEYS_BY_ROUTE[to.name] || []
    if (keys.length) {
      await Promise.all(keys.map(k => {
        try { return usePageText(k).load() } catch (e) { return Promise.resolve() }
      }))
    }
  } catch (e) { /* ignore prefetch errors */ }
  next();
});

export default router;
