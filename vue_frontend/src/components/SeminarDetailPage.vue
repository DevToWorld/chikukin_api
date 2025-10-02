<template>
  <div class="page-container">
    <Navigation />
    
    <!-- Hero Section -->
    <HeroSection 
      title="セミナー詳細"
      subtitle="seminar"
      heroImage="/img/Image_fx6.jpg"
    />

    <!-- Breadcrumbs -->
    <Breadcrumbs :breadcrumbs="['セミナー', 'セミナー詳細']" />

    <!-- Seminar Detail Section -->
    <section class="seminar-detail-section" v-if="seminar">
      <div class="section-header">
        <h2 class="section-title">セミナー詳細</h2>
        <div class="section-divider">
          <div class="divider-line"></div>
          <span class="divider-text">seminar</span>
          <div class="divider-line"></div>
        </div>
      </div>

      <!-- Seminar Details Card -->
      <div class="seminar-detail-card">
          <div class="seminar-content">
                         <div class="seminar-info">
              <div class="seminar-details">
              <div class="detail-row">
                 <span class="detail-label">セミナー名</span>
                 <span class="detail-value">{{ seminar.title || 'セミナー名' }}</span>
               </div>

               <div class="detail-row">
                 <span class="detail-label">講師</span>
                 <span class="detail-value">{{ seminar.instructor || 'ちくぎん地域経済研究所' }}</span>
               </div>
               
               <div class="detail-row">
                 <span class="detail-label">セミナー日時</span>
                 <span class="detail-value">{{ formatDetailDate(seminar.date) }}</span>
               </div>
               
               <div class="detail-row">
                 <span class="detail-label">開催場所</span>
                 <span class="detail-value">{{ seminar.venue || '久留米リサーチ・パーク' }}</span>
               </div>
               
                               <div class="detail-row">
                  <span class="detail-label">定員</span>
                  <span class="detail-value">{{ seminar.capacity || '30名' }}</span>
                </div>
               
               <div class="detail-row">
                 <span class="detail-label">参加費</span>
                 <span class="detail-value">{{ seminar.fee || '会員無料' }}</span>
               </div>
             </div>
           </div>
           
           <div v-if="!isPastSeminar" class="seminar-image" :class="{ blurred: shouldBlur }">
             <img :src="seminar.image || '/img/image-1.png'" :alt="seminar.title" />
             <MembershipBadge 
               v-if="seminar.membershipRequirement && seminar.membershipRequirement !== 'free'" 
               :level="seminar.membershipRequirement" 
               class="detail-badge"
             />
           </div>
         </div>

        <div class="detail-row">
          <span class="detail-label">講師紹介</span>
          <span class="detail-value">{{ seminar.fullDescription || seminar.description }}</span>
        </div>

        <div class="detail-row">
          <span class="detail-label">プログラム</span>
          <span class="detail-value">{{ seminar.fullDescription || seminar.description }}</span>
        </div>

        <!-- Action Section based on Auth State -->
        <div v-if="!isPastSeminar" class="action-section">
          <!-- 非会員の場合 -->
          <div v-if="authState.type === 'non_member'" class="members-only-section">
            <p class="members-only-notice">{{ authState.message }}</p>
            <div class="login-btn" @click="goToLogin">
              <div class="text-44 valign-text-middle inter-bold-white-15px">ログインする</div>
              <div class="pdf-icon-wrapper">
                <img class="pdf-icon" src="/img/login-arrow-icon.svg" alt="ログイン" width="24" height="24" />
              </div>
            </div>
          </div>
          
          <!-- 会員でログイン済みの場合 -->
          <div v-else-if="authState.type === 'member_logged_in'" class="registration-section">
            <p class="registration-notice">{{ authState.message }}</p>
            <div 
              class="registration-btn arrow-red" 
              @click="handleRegistration"
              :class="{ disabled: !canRegister }"
            >
              <div class="text-44 valign-text-middle inter-bold-white-15px">{{ registrationButtonText }}</div>
              <frame13213176122 />
            </div>
          </div>
          
          <!-- 会員だが権限不足の場合 -->
          <div v-else-if="authState.type === 'member_insufficient'" class="members-only-section">
            <p class="members-only-notice">{{ authState.message }}</p>
            <div class="upgrade-btn arrow-dark" @click="goToMembership">
              <div class="text-44 valign-text-middle inter-bold-white-15px">会員アップグレード</div>
              <div class="pdf-icon-wrapper">
                <img class="pdf-icon" src="/img/arrow-icon.svg" alt="アップグレード" width="24" height="24" />
              </div>
            </div>
          </div>
        </div>
        
        <!-- セミナー終了時の表示 -->
        <div v-if="isPastSeminar" class="ended-section">
          <p class="ended-notice">このセミナーは終了しました</p>
        </div>

      </div>
    </section>

    <div v-else class="loading">
      読み込み中...
    </div>

<!-- Action Button Section -->
<ActionButton 
  primary-text="お問い合わせはこちら"
  secondary-text="メンバー登録はこちら"
  max-width="1500px"
  @primary-click="handleContactClick"
  @secondary-click="handleJoinClick"
/>

    <!-- Contact Section -->
    <ContactSection cms-page-key="seminars" />

    <!-- Access Section -->
    <AccessSection />

    <!-- Footer Navigation -->
    <Footer v-bind="frame132131753022Props" />

    <!-- Fixed Side Buttons -->
    <FixedSideButtons position="bottom" />
  </div>
</template>

<script>
import Navigation from "./Navigation";
import Footer from "./Footer";
import Group27 from "./Group27";
import AccessSection from "./AccessSection.vue";
import HeroSection from "./HeroSection.vue";
import Breadcrumbs from "./Breadcrumbs.vue";
import FixedSideButtons from "./FixedSideButtons.vue";
import ContactSection from "./ContactSection.vue";
import Frame13213176122 from "./Frame13213176122.vue";
import ActionButton from "./ActionButton.vue";
import { frame132131753022Data } from "../data";
import apiClient from '../services/apiClient.js';
import mockServer from '@/mockServer';
import MembershipBadge from './MembershipBadge.vue';

export default {
  name: "SeminarDetailPage",
  components: {
    Navigation,
    Footer,
    Group27,
    AccessSection,
    HeroSection,
    Breadcrumbs,
    FixedSideButtons,
    ContactSection,
    Frame13213176122,
    ActionButton,
    MembershipBadge
  },
  data() {
    return {
      frame132131753022Props: frame132131753022Data,
      seminar: null,
      loading: true,
      error: null,
      serverCanRegister: false,
      serverCanRegisterForUser: false
    };
  },
  async mounted() {
    await this.loadSeminar();
  },
  computed: {
    isPastSeminar() {
      // 過去のセミナーかどうかを判定
      return this.seminar && (this.seminar.status === 'ended' || this.seminar.status === 'completed' || this.seminar.status === 'past')
    },
    showRegistrationSection() {
      // サーバー側の登録可否を優先（会員レベルはボタン活性で制御）
      return this.serverCanRegisterForUser || this.serverCanRegister || (this.seminar && this.seminar.status === 'current')
    },
    isMembersOnly() {
      return this.seminar && (this.seminar.membershipRequirement && this.seminar.membershipRequirement !== 'free')
    },
    canAccessSeminar() {
      if (!this.seminar) return false
      const requiredLevel = this.seminar.membershipRequirement || 'free'
      return this.$store.getters['auth/canAccess'](requiredLevel)
    },
    shouldBlur() {
      // プレミアム・スタンダードは厳格に会員限定扱い
      const level = this.seminar?.membershipRequirement || 'free'
      const strict = ['premium','standard'].includes(level)
      if (!strict && this.serverCanRegister) return false
      return this.isMembersOnly && !this.canAccessSeminar
    },
    canRegister() {
      if (!this.seminar) return false;
      const requiredLevel = this.seminar.membershipRequirement || 'free';
      // 無料セミナーまたは一般公開セミナーの場合は常に有効
      if (requiredLevel === 'free' || (!['premium','standard'].includes(requiredLevel) && this.serverCanRegister)) {
        return true;
      }
      return this.$store.getters['auth/canAccess'](requiredLevel);
    },
    registrationButtonText() {
      if (!this.seminar) return '予約する';
      const level = this.seminar.membershipRequirement || 'free'
      const strict = ['premium','standard'].includes(level)
      if (!strict && this.serverCanRegister) return '予約する';
      const requiredLevel = this.seminar.membershipRequirement || 'free';
      const canAccess = this.$store.getters['auth/canAccess'](requiredLevel);
      const isRestricted = this.$store.getters['auth/canViewButRestricted'](requiredLevel);
      if (requiredLevel === 'free') return 'セミナーを予約する';
      if (canAccess) return '予約する';
      if (isRestricted) return `${this.getMembershipText(requiredLevel)}会員限定`;
      return 'ログインする';
    },
    authState() {
      if (!this.seminar) return { type: 'loading', message: '読み込み中...' };
      // サーバーが申込可 かつ プレミアム/ベーシック以外は一般扱い
      const level = this.seminar.membershipRequirement || 'free'
      const strict = ['premium','standard'].includes(level)
      if (this.serverCanRegister && !strict) {
        return {
          type: 'member_logged_in',
          message: 'このセミナーに参加するには予約が必要です。'
        }
      }
      
      const requiredLevel = level;
      const isAuthenticated = this.$store.getters['auth/isAuthenticated'];
      const canAccess = this.$store.getters['auth/canAccess'](requiredLevel);
      
      // 非会員の場合
      if (!isAuthenticated) {
        if (requiredLevel === 'free') {
          return {
            type: 'member_logged_in',
            message: 'このセミナーに参加するには予約が必要です。'
          };
        } else {
          return {
            type: 'non_member',
            message: `このセミナーは${this.getMembershipText(requiredLevel)}会員限定です。参加するにはログインが必要です。`
          };
        }
      }
      
      // 会員でログイン済みの場合
      if (isAuthenticated && canAccess) {
        return {
          type: 'member_logged_in',
          message: 'このセミナーに参加するには予約が必要です。'
        };
      }
      
      // 会員だが権限不足の場合
      if (isAuthenticated && !canAccess) {
        return {
          type: 'member_insufficient',
          message: `このセミナーは${this.getMembershipText(requiredLevel)}会員限定です。アップグレードをご検討ください。`
        };
      }
      
      return { type: 'loading', message: '読み込み中...' };
    }
  },
  methods: {
    async loadSeminar() {
      try {
        this.loading = true;
        const seminarId = this.$route.params.id;
        
        // まずAPIから取得を試みる
        try {
          const response = await apiClient.getSeminar(seminarId);
          if (response && response.data) {
            const payload = response.data
            const s = payload.seminar || payload
            this.serverCanRegister = !!(payload.can_register ?? s?.can_register)
            this.serverCanRegisterForUser = !!(payload.can_register_for_user)
            this.seminar = {
              id: s.id,
              title: s.title,
              description: s.description,
              fullDescription: s.detailed_description || s.description,
              date: s.date,
              start_time: s.start_time,
              end_time: s.end_time,
              venue: s.location,
              location: s.location,
              capacity: s.capacity ? `${s.capacity}名` : '',
              fee: payload.formatted_fee || s.formatted_fee || (s.fee == 0 ? '無料' : (s.fee != null ? `${s.fee}円` : '')),
              status: (s.status === 'scheduled' || s.status === 'ongoing') ? 'current' : s.status,
              image: s.featured_image || '/img/image-1.png',
              instructor: s.instructor || 'ちくぎん地域経済研究所',
              notes: s.notes,
              application_deadline: s.application_deadline,
              membershipRequirement: s.membership_requirement || 'free'
            }
            return
          }
        } catch (apiError) {
          console.log('API failed, trying mockServer:', apiError.message);
        }
        
        // APIが失敗した場合はmockServerから取得
        const seminar = await mockServer.getSeminar(seminarId);
        this.seminar = {
          id: seminar.id,
          title: seminar.title,
          description: seminar.description,
          fullDescription: seminar.detailed_description || seminar.description,
          date: seminar.date,
          start_time: seminar.start_time,
          end_time: seminar.end_time,
          venue: seminar.location,
          capacity: seminar.capacity,
          price: seminar.price,
          organizer: seminar.organizer,
          membershipRequirement: seminar.membership_requirement,
          applicationDeadline: seminar.application_deadline,
          currentParticipants: seminar.current_participants,
          isOnline: seminar.is_online,
          featuredImage: seminar.featured_image
        };
      } catch (error) {
        console.error('セミナー詳細の読み込みに失敗しました:', error);
        this.error = 'セミナー詳細の読み込みに失敗しました。';
        // フォールバック: モックデータを使用
        await this.loadFallbackData();
      } finally {
        this.loading = false;
      }
    },
    
    formatSeminarData(seminar) {
      return {
        id: seminar.id,
        title: seminar.title,
        description: seminar.description,
        fullDescription: seminar.detailed_description || seminar.description,
        date: seminar.date,
        fee: this.formatFee(seminar.fee, seminar.membership_requirement),
        status: seminar.status === 'scheduled' ? 'current' : 'past',
        instructor: seminar.instructor || 'ちくぎん地域経済研究所',
        venue: seminar.location,
        target: this.formatTarget(seminar.membership_requirement),
        image: seminar.featured_image || '/img/image-1.png',
        start_time: seminar.start_time,
        end_time: seminar.end_time,
        membershipRequirement: seminar.membership_requirement || 'free',
        capacity: seminar.capacity,
        current_participants: seminar.current_participants,
        application_deadline: seminar.application_deadline,
        contact_email: seminar.contact_email,
        contact_phone: seminar.contact_phone
      };
    },
    
    formatFee(fee, membershipRequirement) {
      if (fee === '0.00' || fee === 0) {
        return membershipRequirement === 'none' ? '無料' : '会員無料';
      }
      return `¥${parseFloat(fee).toLocaleString()}`;
    },
    
    formatTarget(membershipRequirement) {
      switch(membershipRequirement) {
        case 'none': return '一般参加可能';
        case 'basic': return 'ベーシック会員以上';
        case 'standard': return 'スタンダード会員以上';
        case 'premium': return 'プレミアム会員限定';
        default: return '経営者・管理職';
      }
    },
    
    async loadFallbackData() {
      const seminarId = this.$route.params.id;
      
      // フォールバック用のモックデータ
      const seminars = [
        {
          id: 1,
          title: '採用力強化！経営・人事向け　面接官トレーニングセミナー',
          description: '久留米リサーチ・パーク（国立研究機関都市開発）１ー１　２F　特別会議室',
          fullDescription: 'バーソル・ビジネス・プロセス・アドバイシング（株）コンサルティング部門経営部では、人材紹介事業をはじめ、多様な中途採用領域にご経験・実績がございます。今回は、人材紹介業界の現状と転職者、求職者の特徴をお伝えし、今回は人材業界のトレンドを踏まえ、現在の転職業界の実情をプロジェクト視点で、中小企業の採用コンサルティングをはじめとして、皆様の採用を支援してまいります。また今回は、人材業界のトレンドを踏まえ、現在の転職業界の実情をプロジェクト視点で説明いたします。そして、中小企業の採用コンサルティングをはじめとして、皆様の採用プロジェクトを支援いたします。',
          date: '2025-06-15',
          fee: '会員無料',
          status: 'current',
          instructor: 'バーソル・ビジネス・プロセス・アドバイシング（株）コンサルティング部門経営部　山根人一朗',
          venue: '久留米リサーチ・パーク（国立研究機関都市開発）１ー１　２F　特別会議室',
          target: '経営者・人事担当者',
          image: '/img/image-1.png'
        },
        {
          id: 2,
          title: 'バーソル・ビジネス・プロセス・アドバイシング（株）コンサルティング部門経営部 山根人一朗',
          description: '時期',
          fullDescription: 'バーソル・ビジネス・プロセス・アドバイシング（株）では、人材紹介事業をはじめとして、多様な中途採用領域において豊富な経験と実績を有しています。本セミナーでは、人材紹介業界の最新動向と転職者・求職者の特徴について詳しく解説いたします。',
          date: '2025-06-20',
          fee: '会員無料',
          status: 'current',
          instructor: '山根人一朗',
          venue: '久留米リサーチ・パーク',
          target: '経営者・人事担当者',
          image: '/img/image-1.png'
        },
        {
          id: '手形・小切手の全面的な電子化セミナー-2025年7月15日',
          title: '手形・小切手の全面的な電子化セミナー',
          description: '当セミナーでは、手形の電子化に向けた金融界の取組みや、代替手段である「でんさい」や「法人インターネットバンキング（ビジネスWeb）」の仕組みや導入方法、でんさいの基本的な操作方法についてご説明します。',
          fullDescription: '当セミナーでは、手形の電子化に向けた金融界の取組みや、代替手段である「でんさい」や「法人インターネットバンキング（ビジネスWeb）」の仕組みや導入方法、でんさいの基本的な操作方法についてご説明します。手形の電子化は、企業の業務効率化とコスト削減に大きく貢献します。本セミナーでは、実践的な知識と具体的な導入ステップをご提供いたします。',
          date: '2025-07-15',
          fee: '会員無料',
          status: 'current',
          instructor: 'ちくぎん地域経済研究所',
          venue: '久留米リサーチ・パーク',
          target: '経営者・人事担当者',
          image: '/img/image-1.png'
        }
      ];
      
      // 数値IDまたは文字列IDで検索
      let foundSeminar = seminars.find(s => s.id == seminarId);
      
      // 文字列IDで見つからない場合は、タイトルと日付から生成されたIDを検索
      if (!foundSeminar) {
        foundSeminar = seminars.find(s => s.id === seminarId);
      }
      
      // それでも見つからない場合は、デフォルトのセミナーを表示
      if (!foundSeminar) {
        foundSeminar = seminars[0];
      }
      
      this.seminar = foundSeminar;
      this.loading = false;
    },
    formatDetailDate(dateString) {
      const date = new Date(dateString);
      const year = date.getFullYear();
      const month = String(date.getMonth() + 1).padStart(2, '0');
      const day = String(date.getDate()).padStart(2, '0');
      const weekdays = ['日', '月', '火', '水', '木', '金', '土'];
      const weekday = weekdays[date.getDay()];
      return `${year}年${month}月${day}日（${weekday}）`;
    },
    getMembershipText(level) {
      switch (level) {
        case 'standard':
          return 'スタンダード';
        case 'premium':
          return 'プレミアム';
        default:
          return '会員';
      }
    },
    handleRegistration() {
      if (!this.seminar) return;
      
      const requiredLevel = this.seminar.membershipRequirement || 'free';
      const strict = ['premium','standard'].includes(requiredLevel)
      const canAccess = this.$store.getters['auth/canAccess'](requiredLevel);
      
      // 無料セミナーまたは一般公開セミナーの場合は申込フォームへ遷移
      if (requiredLevel === 'free' || (!strict && this.serverCanRegister)) {
        this.$router.push(`/seminars/${this.seminar.id}/apply`)
        return
      }
      
      // 会員限定セミナーの場合
      if (canAccess) {
        // 会員限定・権限あり：即時予約登録
        this.directRegister(this.seminar)
      } else if (!this.$store.getters['auth/isAuthenticated']) {
        const redirect = encodeURIComponent(this.$route.fullPath)
        this.$router.push(`/member-login?redirect=${redirect}`);
      } else {
        alert(`このセミナーは${this.getMembershipText(requiredLevel)}会員限定です。アップグレードをご検討ください。`);
        this.$router.push('/membership');
      }
    },
    goToLogin() {
      const redirect = encodeURIComponent(this.$route.fullPath);
      this.$router.push(`/member-login?redirect=${redirect}`);
    },
    goToMembership() {
      this.$router.push('/membership');
    },
    async directRegister(seminar) {
      try {
        const profile = this.getLoggedInProfile()
        if (!profile || !profile.email) {
          const redirect = encodeURIComponent(this.$route.fullPath)
          this.$router.push(`/member-login?redirect=${redirect}`)
          return
        }
        const payload = {
          name: profile.name || '会員',
          email: profile.email,
          company: profile.company || '',
          phone: profile.phone || '',
          special_requests: ''
        }
        const res = await apiClient.registerForSeminar(this.seminar.id, payload)
        if (res && res.success) {
          alert('予約を受け付けました。マイページの申込状況で確認できます。')
        } else {
          throw new Error(res?.error || '予約に失敗しました')
        }
      } catch (e) {
        console.error('directRegister error', e)
        alert('予約に失敗しました。時間をおいて再度お試しください。')
      }
    },
    getLoggedInProfile() {
      try {
        const user = this.$store?.state?.auth?.user || this.$store?.state?.auth?.member
        if (user && user.email) {
          return {
            name: user.full_name || user.name || user.company_name || '会員',
            email: user.email,
            company: user.company_name || '',
            phone: user.phone || ''
          }
        }
        const ls = localStorage.getItem('memberUser')
        if (ls) {
          const u = JSON.parse(ls)
          return {
            name: u.full_name || u.name || u.company_name || '会員',
            email: u.email,
            company: u.company_name || '',
            phone: u.phone || ''
          }
        }
      } catch (e) { /* noop */ }
      return null
    },
    goToContact() {
      this.$router.push('/contact');
    },
    goToMember() {
      this.$router.push('/application-form');
    },
    scrollToContact() {
      this.$router.push('/contact');
    },
    handleContactClick() {
      this.$router.push('/contact');
    },
    handleJoinClick() {
      this.$router.push('/application-form');
    }
  }
};
</script>

<style scoped>

.page-container {
  min-height: 100vh;
  background-color: #ECECEC;
}

/* Hero Section and Breadcrumb styles are now handled by components */

/* Seminar Detail Section */
.seminar-detail-section {
  padding: 70px 50px 50px 50px;
  display: flex;
  flex-direction: column;
  align-items: center;
  gap: 40px;
  background: #ECECEC;
}

.section-header {
  display: flex;
  flex-direction: column;
  align-items: center;
  gap: 29px;
}

.section-title {
  color: #1A1A1A;
  font-size: 36px;
  font-weight: 700;
  text-align: center;
  letter-spacing: -0.72px;
}

.section-divider {
  display: flex;
  justify-content: center;
  align-items: center;
  gap: 15px;
}

.divider-line {
  width: 69px;
  height: 2px;
  background: #DA5761;
}

.divider-text {
  color: #DA5761;
  font-size: 20px;
  font-weight: 700;
}

/* Seminar Detail Card */
.seminar-detail-card {
  background: white;
  border-radius: 20px;
  overflow: hidden;
  box-shadow: 0 5px 20px rgba(0,0,0,0.1);
  width: 100%;
  max-width: 1500px;
  padding: 50px;
}

.seminar-content {
  display: flex;
  flex-direction: row;
  align-items: stretch;
  gap: 50px;
  height: auto;
}

.seminar-image {
  width: 40%;
  height: auto;
  overflow: hidden;
  flex-shrink: 0;
  align-self: stretch;
}

.seminar-image img {
  width: 100%;
  height: 100%;
  object-fit: cover;
  min-height: 400px;
}

/* Members-only blur (image only) */
.seminar-image.blurred img {
  filter: blur(4px);
  transform: scale(1.03);
}

.seminar-info {
  width: 60%;
  flex: 1;
  height: 100%;
}

.seminar-details {
  height: 100%;
  display: flex;
  flex-direction: column;
  justify-content: center;
}

.detail-row {
  display: flex;
  flex-direction: column;
  margin-bottom: 15px;
  align-items: flex-start;
  border-bottom: 0.5px dashed #D0D0D0;
  padding-bottom: 15px;
}

.detail-row:first-child {
  border-top: 0.5px dashed #D0D0D0;
  padding-top: 15px;
}

.detail-label {
  width: 200px;
  font-weight: normal;
  color: white;
  font-size: 0.9rem;
  background-color: #727272;
  padding: 5px 0;
  border-radius: 5px;
  text-align: center;
  margin-bottom: 5px;
}

.detail-value {
  width: 100%;
  color: #666;
  font-size: 0.9rem;
  line-height: 1.5;
  padding-left: 10px;
}



/* Registration Section */
.registration-section {
  text-align: center;
  margin-top: 30px;
}

.registration-btn {
  align-items: center;
  background-color: var(--mandy);
  border-radius: 10px;
  box-shadow: 0px 1px 2px #0000000d;
  display: flex;
  gap: 10px;
  justify-content: center;
  padding: 10px 0px;
  width: 380px;
  border: none;
  cursor: pointer;
  color: white;
  font-family: var(--font-family-inter);
  font-size: 1.1rem;
  font-weight: bold;
  transition: all 0.3s;
  margin: 0 auto;
}


.text-44 {
  letter-spacing: 0;
  line-height: 22.5px;
  margin-top: 0px;
  position: relative;
  white-space: nowrap;
  width: fit-content;
  display: flex;
  align-items: center;
  color: var(--white);
  font-family: var(--font-family-inter);
  font-size: 15px;
  font-weight: 700;
}

.ended-section {
  text-align: center;
  padding: 20px;
  background-color: #f8f9fa;
  border-radius: 10px;
}

.ended-notice {
  color: #666;
  font-size: 1rem;
  font-weight: 500;
}

/* Action Section */
.action-section {
  margin-top: 20px;
}

/* Members-only notice */
.members-only-section {
  text-align: center;
  padding: 20px;
  background-color: #f8f9fa;
  border-radius: 10px;
  margin-top: 10px;
}

.members-only-notice {
  color: #666;
  font-size: 0.95rem;
  font-weight: 500;
  margin: 0 0 15px 0;
}

/* Registration section */
.registration-section {
  text-align: center;
  padding: 20px;
  background-color: #f8f9fa;
  border-radius: 10px;
  margin-top: 10px;
}

.registration-notice {
  color: #666;
  font-size: 0.95rem;
  font-weight: 500;
  margin: 0 0 15px 0;
}

/* Button styles - EconomicStatisticsDetailPageと同じスタイル */
.login-btn {
  align-items: center;
  background-color: #DA5761;
  border-radius: 10px;
  box-shadow: 0px 1px 2px #0000000d;
  display: flex;
  gap: 10px;
  justify-content: center;
  padding: 10px 0px;
  width: 380px;
  border: none;
  cursor: pointer;
  color: white;
  font-family: var(--font-family-inter);
  font-size: 1.1rem;
  font-weight: bold;
  transition: all 0.3s;
  margin: 0 auto;
}

.upgrade-btn {
  align-items: center;
  background-color: #1A1A1A;
  border-radius: 10px;
  box-shadow: 0px 1px 2px #0000000d;
  display: flex;
  gap: 10px;
  justify-content: center;
  padding: 10px 0px;
  width: 380px;
  border: none;
  cursor: pointer;
  color: white;
  font-family: var(--font-family-inter);
  font-size: 1.1rem;
  font-weight: bold;
  transition: all 0.3s;
  margin: 0 auto;
}

.login-btn:hover, .upgrade-btn:hover {
  opacity: 0.8;
}

.login-btn .pdf-icon {
  color: #DA5761;
}

.upgrade-btn .pdf-icon {
  color: #1A1A1A;
}

.registration-btn {
  align-items: center;
  background-color: #DA5761;
  border-radius: 10px;
  box-shadow: 0px 1px 2px #0000000d;
  display: flex;
  gap: 10px;
  justify-content: center;
  padding: 10px 0px;
  width: 380px;
  border: none;
  cursor: pointer;
  color: white;
  font-family: var(--font-family-inter);
  font-size: 1.1rem;
  font-weight: bold;
  transition: all 0.3s;
  margin: 0 auto;
}

.registration-btn:hover {
  opacity: 0.8;
}

.registration-btn.disabled {
  opacity: 0.6;
  cursor: not-allowed;
  background-color: #e0e0e0;
}

.loading {
  text-align: center;
  padding: 60px;
  color: #666;
  font-size: 1.1rem;
}

/* 会員制限スタイル */
.detail-badge {
  position: absolute;
  top: 15px;
  right: 15px;
  z-index: 2;
}

.seminar-image {
  position: relative;
}

.registration-btn.disabled {
  opacity: 0.6;
  cursor: not-allowed;
  background-color: #e0e0e0;
}

.seminar-detail-card {
  position: relative;
}

/* Contact Banner styles are now handled by ContactSection component */

/* Access Section styles are now handled by AccessSection component */

/* Floating Action Buttons styles are now handled by FixedSideButtons component */

/* Responsive Design */
@media (max-width: 1150px) {
  .seminar-detail-section {
    padding: 50px 30px !important;
  }
  
  .seminar-title {
    font-size: 32px !important;
  }
  
  .seminar-subtitle {
    font-size: 18px !important;
  }
  
  .seminar-description p {
    font-size: 18px !important;
  }
  
  .detail-label {
    font-size: 18px !important;
  }
  
  .detail-value {
    font-size: 18px !important;
  }
  
  .action-btn {
    font-size: 18px !important;
    padding: 15px 30px !important;
  }
  
  .seminar-image {
    height: 350px !important;
  }
}

/* 1150px以下（タブレット横向き） */
@media (max-width: 1150px) {
  .seminar-detail-section {
    padding: 50px 30px !important;
  }
  
  .seminar-detail-card {
    padding: 40px !important;
  }
  
  .seminar-content {
    flex-direction: column !important;
    gap: 0 !important;
  }
  
  .seminar-image {
    width: 100% !important;
    height: 300px !important;
    border-radius: 20px 20px 0 0 !important;
    order: -1 !important;
  }
  
  .seminar-info {
    width: 100% !important;
    border-radius: 0 0 20px 20px !important;
    padding: 30px 0 0 0 !important;
    order: 1 !important;
  }
  
  .section-title {
    font-size: 32px !important;
  }
  
  .divider-text {
    font-size: 18px !important;
  }
  
  .detail-label,
  .detail-value {
    font-size: 18px !important;
  }
  
  .section-header {
    gap: 25px !important;
  }
}

/* 900px以下（タブレット） */
@media (max-width: 900px) {
  .seminar-detail-section {
    padding: 30px 20px !important;
  }
  
  .seminar-detail-card {
    padding: 35px !important;
  }
  
  .seminar-image {
    height: 280px !important;
    order: -1 !important;
  }
  
  .seminar-info {
    padding: 30px 0 0 0 !important;
    order: 1 !important;
  }
  
  .section-title {
    font-size: 29px !important;
  }
  
  .divider-text {
    font-size: 17px !important;
  }
  
  .detail-label,
  .detail-value {
    font-size: 17px !important;
  }
  
  .section-header {
    gap: 22px !important;
  }
}

/* 768px以下（タブレット縦向き） */
@media (max-width: 768px) {
  .seminar-detail-section {
    padding: 30px 20px !important;
  }
  
  .seminar-detail-card {
    padding: 30px !important;
  }
  
  .seminar-content {
    flex-direction: column !important;
    gap: 0 !important;
  }
  
  .seminar-image {
    width: 100% !important;
    height: 250px !important;
    border-radius: 20px 20px 0 0 !important;
    order: -1 !important;
  }
  
  .seminar-info {
    width: 100% !important;
    padding: 30px 0 0 0 !important;
    border-radius: 0 0 20px 20px !important;
    order: 1 !important;
  }
  
  .section-title {
    font-size: 27px !important;
  }
  
  .divider-text {
    font-size: 16px !important;
  }
  
  .detail-label,
  .detail-value {
    font-size: 16px !important;
  }
  
  .section-header {
    gap: 20px !important;
  }
  
  .detail-row {
    flex-direction: column !important;
  }
  
  .action-buttons {
    flex-direction: column !important;
    align-items: center !important;
  }
  
  .action-btn {
    width: 100% !important;
    max-width: 300px !important;
    font-size: 16px !important;
    padding: 13px 26px !important;
  }
}

/* 480px以下（スマートフォン） */
@media (max-width: 480px) {
  .seminar-detail-section {
    padding: 20px 15px !important;
  }
  
  .seminar-detail-card {
    padding: 20px !important;
  }
  
  .seminar-image {
    height: 200px !important;
    border-radius: 15px 15px 0 0 !important;
    order: -1 !important;
  }
  
  .seminar-info {
    padding: 20px 0 0 0 !important;
    border-radius: 0 0 15px 15px !important;
    order: 1 !important;
  }
  
  .section-title {
    font-size: 22px !important;
  }
  
  .divider-text {
    font-size: 13px !important;
  }
  
  .detail-label,
  .detail-value {
    font-size: 13px !important;
  }
  
  .section-header {
    gap: 18px !important;
  }
  
  .action-btn {
    font-size: 13px !important;
    padding: 12px 24px !important;
  }
  
  .registration-btn {
    width: 100% !important;
    padding: 15px 20px !important;
  }
  
  /* ボタンのレスポンシブ対応 */
  .login-btn, .upgrade-btn, .registration-btn {
    font-size: 13px !important;
    padding: 12px 0px !important;
    width: 100% !important;
    max-width: 280px !important;
  }
}

/* Footer Navigation */
</style>
