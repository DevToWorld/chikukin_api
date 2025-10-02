<template>
  <div class="upgrade-page">
    <Navigation />
    <div class="upgrade-container">
      <div class="upgrade-header">
        <h1>会員プランをアップグレード</h1>
        <p class="subtitle">より多くのコンテンツにアクセスして、ビジネスの成長を加速させましょう</p>
      </div>
      
      <div class="current-plan-notice" v-if="currentMembershipType">
        <p>現在のプラン: <strong>{{ getMembershipLabel(currentMembershipType) }}</strong></p>
      </div>
      
      <div class="plans-grid">
        <!-- スタンダードプラン -->
        <div class="plan-card recommended" :class="{ current: currentMembershipType === 'standard', disabled: isHigherPlan('standard') }">
          <div class="recommended-badge">おすすめ</div>
          <div class="plan-header">
            <h2>スタンダード会員</h2>
            <div class="plan-price">
              <span class="currency">&yen;</span>
              <span class="price">1,000</span>
              <span class="period">/月（税別）</span>
            </div>
          </div>
          
          <div class="plan-description">
            <p>ビジネスに必要な情報を網羅</p>
          </div>
          
          <ul class="plan-features">
            <li>✓ 基本機能</li>
            <li>✓ 四半期レポート</li>
            <li>✓ 業界分析レポート</li>
            <li>✓ セミナー資料（一部）</li>
            <li>✓ 優先サポート</li>
            <li class="disabled">✗ 特別調査レポート</li>
            <li class="disabled">✗ セミナー動画アーカイブ</li>
            <li class="disabled">✗ データダウンロード</li>
          </ul>
          
          <button 
            v-if="currentMembershipType === 'standard'"
            class="plan-button current-plan"
            disabled
          >
            現在のプラン
          </button>
          <button 
            v-else-if="!isHigherPlan('standard')"
            @click="selectPlan('standard')"
            class="plan-button primary"
          >
            アップグレード
          </button>
        </div>
        
        <!-- プレミアムプラン -->
        <div class="plan-card premium" :class="{ current: currentMembershipType === 'premium' }">
          <div class="plan-header">
            <h2>プレミアム会員</h2>
            <div class="plan-price">
              <span class="currency">&yen;</span>
              <span class="price">3,000</span>
              <span class="period">/月（税別）</span>
            </div>
          </div>
          
          <div class="plan-description">
            <p>全コンテンツへの無制限アクセス</p>
          </div>
          
          <ul class="plan-features">
            <li>✓ 全刊行物へのフルアクセス</li>
            <li>✓ 特別調査レポート</li>
            <li>✓ セミナー動画アーカイブ</li>
            <li>✓ データダウンロード（Excel形式）</li>
            <li>✓ 早期アクセス権</li>
            <li>✓ 専任コンサルタント</li>
            <li>✓ カスタムレポート作成</li>
            <li>✓ VIPイベント招待</li>
          </ul>
          
          <button 
            v-if="currentMembershipType === 'premium'"
            class="plan-button current-plan"
            disabled
          >
            現在のプラン
          </button>
          <button 
            v-else
            @click="selectPlan('premium')"
            class="plan-button premium-button"
          >
            プレミアムにアップグレード
          </button>
        </div>
      </div>
      
      <div class="comparison-section">
        <h2>プラン機能比較表</h2>
        <table class="comparison-table">
          <thead>
            <tr>
              <th>機能</th>
              <th class="highlighted">スタンダード</th>
              <th>プレミアム</th>
            </tr>
          </thead>
          <tbody>
            <tr>
              <td>月次レポート</td>
              <td><span class="check">✓</span></td>
              <td><span class="check">✓</span></td>
            </tr>
            <tr>
              <td>四半期レポート</td>
              <td><span class="check">✓</span></td>
              <td><span class="check">✓</span></td>
            </tr>
            <tr>
              <td>業界分析レポート</td>
              <td><span class="check">✓</span></td>
              <td><span class="check">✓</span></td>
            </tr>
            <tr>
              <td>特別調査レポート</td>
              <td><span class="cross">✗</span></td>
              <td><span class="check">✓</span></td>
            </tr>
            <tr>
              <td>セミナー資料</td>
              <td><span class="check partial">一部</span></td>
              <td><span class="check">✓</span></td>
            </tr>
            <tr>
              <td>セミナー動画</td>
              <td><span class="cross">✗</span></td>
              <td><span class="check">✓</span></td>
            </tr>
            <tr>
              <td>データダウンロード</td>
              <td><span class="cross">✗</span></td>
              <td><span class="check">✓</span></td>
            </tr>
            <tr>
              <td>サポート</td>
              <td>優先サポート</td>
              <td>専任コンサルタント</td>
            </tr>
          </tbody>
        </table>
      </div>
      
      <div class="faq-section">
        <h2>よくある質問</h2>
        <div class="faq-list">
          <div class="faq-item" v-for="(faq, index) in faqs" :key="index">
            <div class="faq-question" @click="toggleFaq(index)">
              <span>{{ faq.question }}</span>
              <span class="faq-icon">{{ openFaq === index ? '−' : '+' }}</span>
            </div>
            <div v-if="openFaq === index" class="faq-answer">
              <p>{{ faq.answer }}</p>
            </div>
          </div>
        </div>
      </div>
    </div>
    <FooterComplete />
  </div>
</template>

<script>
import Navigation from '../components/Navigation.vue'
import FooterComplete from '../components/FooterComplete.vue'
import { useMemberAuth } from '../composables/useMemberAuth'

export default {
  name: 'UpgradePage',
  components: {
    Navigation,
    FooterComplete
  },
  data() {
    return {
      currentMembershipType: null,
      openFaq: null,
      faqs: [
        {
          question: 'プランはいつでも変更できますか？',
          answer: 'はい、いつでもアップグレードが可能です。アップグレード後は即座に新しいコンテンツにアクセスできます。ダウングレードは次回の更新日から適用されます。'
        },
        {
          question: '支払い方法は何が使えますか？',
          answer: 'クレジットカード（VISA、MasterCard、JCB、AMEX）および銀行振込がご利用いただけます。'
        },
        {
          question: '法人契約は可能ですか？',
          answer: 'はい、法人契約も承っております。複数アカウントの一括契約など、お得なプランもございます。詳しくはお問い合わせください。'
        },
        {
          question: '無料トライアルはありますか？',
          answer: 'スタンダードプランとプレミアムプランには14日間の無料トライアルがございます。トライアル期間中はいつでもキャンセル可能です。'
        },
        {
          question: '解約はどのようにすればよいですか？',
          answer: 'マイアカウントページから簡単に解約手続きができます。解約後も契約期間終了まではサービスをご利用いただけます。'
        }
      ]
    }
  },
  created() {
    const auth = useMemberAuth()
    this.currentMembershipType = auth.getMembershipType()
  },
  methods: {
    getMembershipLabel(type) {
      const labels = {
        basic: 'ベーシック会員',
        standard: 'スタンダード会員',
        premium: 'プレミアム会員'
      }
      return labels[type] || 'ゲスト'
    },
    
    isHigherPlan(planType) {
      const levels = { basic: 1, standard: 2, premium: 3 }
      const currentLevel = levels[this.currentMembershipType] || 0
      const planLevel = levels[planType] || 0
      return planLevel < currentLevel
    },
    
    async selectPlan(planType) {
      if (confirm(`${this.getMembershipLabel(planType)}にアップグレードしますか？`)) {
        const auth = useMemberAuth()
        const result = await auth.upgradeMembership(planType)
        if (result.success) {
          alert('プランをアップグレードしました！')
          this.currentMembershipType = planType
        } else {
          alert('アップグレードに失敗しました: ' + result.error)
        }
      }
    },
    
    toggleFaq(index) {
      this.openFaq = this.openFaq === index ? null : index
    }
  }
}
</script>

<style scoped>
.upgrade-page {
  min-height: 100vh;
  background: linear-gradient(135deg, #f5f5f5 0%, #ebebeb 100%);
}

.upgrade-container {
  max-width: 1200px;
  margin: 0 auto;
  padding: 60px 20px;
}

.upgrade-header {
  text-align: center;
  margin-bottom: 40px;
}

.upgrade-header h1 {
  font-size: 36px;
  color: #1A1A1A;
  margin: 0 0 12px 0;
}

.subtitle {
  font-size: 18px;
  color: #666;
  margin: 0;
}

.current-plan-notice {
  text-align: center;
  background: white;
  border-radius: 8px;
  padding: 16px;
  margin-bottom: 40px;
  box-shadow: 0 2px 4px rgba(0, 0, 0, 0.08);
}

.current-plan-notice p {
  font-size: 16px;
  color: #666;
  margin: 0;
}

.current-plan-notice strong {
  color: var(--mandy);
  font-weight: 600;
}

.plans-grid {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(320px, 1fr));
  gap: 30px;
  margin-bottom: 60px;
}

.plan-card {
  background: white;
  border-radius: 12px;
  padding: 32px;
  box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
  position: relative;
  transition: all 0.3s ease;
}

.plan-card:hover {
  transform: translateY(-8px);
  box-shadow: 0 12px 24px rgba(0, 0, 0, 0.12);
}

.plan-card.recommended {
  border: 2px solid var(--mandy);
}

.plan-card.premium {
  background: linear-gradient(135deg, #fff 0%, #fef5f5 100%);
}

.plan-card.current {
  opacity: 0.9;
}

.plan-card.disabled {
  opacity: 0.6;
  pointer-events: none;
}

.recommended-badge {
  position: absolute;
  top: -12px;
  left: 50%;
  transform: translateX(-50%);
  background: var(--mandy);
  color: white;
  padding: 4px 20px;
  border-radius: 20px;
  font-size: 12px;
  font-weight: 600;
  text-transform: uppercase;
  letter-spacing: 0.5px;
}

.plan-header {
  text-align: center;
  padding-bottom: 24px;
  border-bottom: 1px solid #e0e0e0;
  margin-bottom: 24px;
}

.plan-header h2 {
  font-size: 24px;
  color: #1A1A1A;
  margin: 0 0 16px 0;
}

.plan-price {
  display: flex;
  align-items: baseline;
  justify-content: center;
  gap: 4px;
}

.currency {
  font-size: 18px;
  color: #666;
}

.price {
  font-size: 36px;
  font-weight: 700;
  color: var(--mandy);
}

.period {
  font-size: 16px;
  color: #666;
}

.plan-description {
  text-align: center;
  margin-bottom: 24px;
}

.plan-description p {
  font-size: 14px;
  color: #666;
  margin: 0;
}

.plan-features {
  list-style: none;
  padding: 0;
  margin: 0 0 32px 0;
}

.plan-features li {
  padding: 10px 0;
  font-size: 14px;
  color: #1A1A1A;
  border-bottom: 1px solid #f0f0f0;
}

.plan-features li.disabled {
  color: #ccc;
}

.plan-button {
  width: 100%;
  padding: 14px 24px;
  border: 2px solid #e0e0e0;
  background: white;
  color: #666;
  border-radius: 8px;
  font-size: 16px;
  font-weight: 600;
  cursor: pointer;
  transition: all 0.3s ease;
}

.plan-button:hover:not(:disabled) {
  border-color: var(--mandy);
  color: var(--mandy);
}

.plan-button.primary {
  background: var(--mandy);
  color: white;
  border-color: var(--mandy);
}

.plan-button.primary:hover {
  background: var(--hot-pink);
  border-color: var(--hot-pink);
}

.plan-button.premium-button {
  background: linear-gradient(135deg, #9c27b0 0%, #673ab7 100%);
  color: white;
  border: none;
}

.plan-button.premium-button:hover {
  background: linear-gradient(135deg, #7b1fa2 0%, #512da8 100%);
}

.plan-button.current-plan {
  background: #f0f0f0;
  color: #999;
  border-color: #f0f0f0;
  cursor: not-allowed;
}

.comparison-section {
  background: white;
  border-radius: 12px;
  padding: 40px;
  margin-bottom: 40px;
  box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
}

.comparison-section h2 {
  font-size: 28px;
  color: #1A1A1A;
  margin: 0 0 32px 0;
  text-align: center;
}

.comparison-table {
  width: 100%;
  border-collapse: collapse;
}

.comparison-table th,
.comparison-table td {
  padding: 16px;
  text-align: center;
  border-bottom: 1px solid #e0e0e0;
}

.comparison-table th {
  background: #f5f5f5;
  font-weight: 600;
  color: #1A1A1A;
}

.comparison-table th.highlighted {
  background: var(--mandy);
  color: white;
}

.comparison-table td:first-child {
  text-align: left;
  font-weight: 500;
  color: #1A1A1A;
}

.check {
  color: #4caf50;
  font-weight: 600;
  font-size: 18px;
}

.check.partial {
  color: #ff9800;
  font-size: 14px;
}

.cross {
  color: #ccc;
  font-size: 18px;
}

.faq-section {
  background: white;
  border-radius: 12px;
  padding: 40px;
  box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
}

.faq-section h2 {
  font-size: 28px;
  color: #1A1A1A;
  margin: 0 0 32px 0;
  text-align: center;
}

.faq-list {
  max-width: 800px;
  margin: 0 auto;
}

.faq-item {
  border-bottom: 1px solid #e0e0e0;
  padding: 20px 0;
}

.faq-question {
  display: flex;
  justify-content: space-between;
  align-items: center;
  cursor: pointer;
  font-size: 16px;
  color: #1A1A1A;
  font-weight: 500;
  transition: color 0.3s ease;
}

.faq-question:hover {
  color: var(--mandy);
}

.faq-icon {
  font-size: 24px;
  color: var(--mandy);
}

.faq-answer {
  margin-top: 16px;
  padding-left: 20px;
}

.faq-answer p {
  font-size: 14px;
  color: #666;
  line-height: 1.6;
  margin: 0;
}

@media (max-width: 768px) {
  .plans-grid {
    grid-template-columns: 1fr;
  }
  
  .comparison-table {
    font-size: 14px;
  }
  
  .comparison-table th,
  .comparison-table td {
    padding: 8px;
  }
}
</style>
