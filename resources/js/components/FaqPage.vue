<template>
  <div class="page-container">
    <Navigation />
    <div class="page-content">
      <div class="faq-header">
        <h1>よくあるご質問</h1>
        <p>お客様からよくいただくご質問をまとめました</p>
      </div>
      
      <div class="faq-categories">
        <button 
          v-for="category in categories" 
          :key="category"
          :class="['category-btn', { active: selectedCategory === category }]"
          @click="selectedCategory = category"
        >
          {{ category }}
        </button>
      </div>
      
      <div class="faq-list">
        <div v-for="(item, index) in filteredFaqs" :key="index" class="faq-item">
          <div class="faq-question" @click="toggleAnswer(index)">
            <span class="q-mark">Q</span>
            <span class="question-text">{{ item.question }}</span>
            <span class="toggle-icon" :class="{ open: openItems.includes(index) }">▼</span>
          </div>
          <transition name="slide">
            <div v-if="openItems.includes(index)" class="faq-answer">
              <span class="a-mark">A</span>
              <span class="answer-text">{{ item.answer }}</span>
            </div>
          </transition>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import Navigation from "./Navigation.vue";

export default {
  name: "FaqPage",
  components: {
    Navigation
  },
  data() {
    return {
      selectedCategory: "会員登録",
      openItems: [],
      categories: ["会員登録", "サービス内容", "料金", "その他"],
      faqs: [
        {
          category: "会員登録",
          question: "会員登録の方法を教えてください。",
          answer: "オンラインフォームまたは郵送にてお申し込みいただけます。必要書類をご提出後、審査を経て会員登録が完了します。"
        },
        {
          category: "会員登録",
          question: "入会金や年会費はいくらですか？",
          answer: "法人会員は入会金10万円、年会費36万円です。個人会員は入会金3万円、年会費12万円となっております。"
        },
        {
          category: "サービス内容",
          question: "どのようなレポートが閲覧できますか？",
          answer: "月次経済レポート、四半期業界分析レポート、年次経済白書など、地域経済に関する各種レポートをご覧いただけます。"
        },
        {
          category: "サービス内容",
          question: "セミナーの開催頻度はどのくらいですか？",
          answer: "毎月2～3回のペースで各種セミナーを開催しています。会員様は優先的にご参加いただけます。"
        },
        {
          category: "料金",
          question: "支払い方法を教えてください。",
          answer: "銀行振込、口座振替、クレジットカードでのお支払いが可能です。年払い・月払いをお選びいただけます。"
        },
        {
          category: "料金",
          question: "途中解約は可能ですか？",
          answer: "可能です。ただし、年会費を一括でお支払いいただいた場合の返金はございません。"
        },
        {
          category: "その他",
          question: "パスワードを忘れてしまいました。",
          answer: "ログイン画面の「パスワードを忘れた方」から再設定が可能です。登録メールアドレスに再設定用のリンクをお送りします。"
        },
        {
          category: "その他",
          question: "退会手続きの方法を教えてください。",
          answer: "マイページから退会申請を行うか、お電話にてご連絡ください。退会処理完了まで約1週間かかります。"
        }
      ]
    };
  },
  computed: {
    filteredFaqs() {
      return this.faqs.filter(faq => faq.category === this.selectedCategory);
    }
  },
  methods: {
    toggleAnswer(index) {
      const position = this.openItems.indexOf(index);
      if (position > -1) {
        this.openItems.splice(position, 1);
      } else {
        this.openItems.push(index);
      }
    }
  }
};
</script>

<style scoped>
.page-container {
  min-height: 100vh;
  background-color: #f8f9fa;
}

.page-content {
  width: 100%;
  margin: 0 auto;
  padding: 70px 50px;
}

.faq-header {
  text-align: center;
  margin-bottom: 40px;
}

.faq-header h1 {
  font-size: 2.5rem;
  color: #1A1A1A;
  margin-bottom: 10px;
}

.faq-header p {
  color: #666;
}

.faq-categories {
  display: flex;
  justify-content: center;
  gap: 10px;
  margin-bottom: 40px;
  flex-wrap: wrap;
}

.category-btn {
  padding: 10px 20px;
  background: white;
  border: 2px solid #dee2e6;
  border-radius: 25px;
  cursor: pointer;
  transition: all 0.3s;
  font-size: 1rem;
}

.category-btn:hover {
  background: #f8f9fa;
}

.category-btn.active {
  background: #007bff;
  color: white;
  border-color: #007bff;
}

.faq-list {
  background: white;
  border-radius: 10px;
  overflow: hidden;
  box-shadow: 0 2px 10px rgba(0,0,0,0.1);
}

.faq-item {
  border-bottom: 1px solid #e9ecef;
}

.faq-item:last-child {
  border-bottom: none;
}

.faq-question {
  padding: 20px;
  cursor: pointer;
  display: flex;
  align-items: center;
  gap: 15px;
  transition: background-color 0.3s;
}

.faq-question:hover {
  background-color: #f8f9fa;
}

.q-mark, .a-mark {
  width: 30px;
  height: 30px;
  border-radius: 50%;
  display: flex;
  align-items: center;
  justify-content: center;
  font-weight: bold;
  flex-shrink: 0;
}

.q-mark {
  background: #007bff;
  color: white;
}

.a-mark {
  background: #28a745;
  color: white;
}

.question-text {
  flex: 1;
  font-weight: 500;
  color: #1A1A1A;
}

.toggle-icon {
  color: #666;
  transition: transform 0.3s;
}

.toggle-icon.open {
  transform: rotate(180deg);
}

.faq-answer {
  padding: 20px;
  background: #f8f9fa;
  display: flex;
  gap: 15px;
  align-items: flex-start;
}

.answer-text {
  flex: 1;
  line-height: 1.6;
  color: #555;
}

.slide-enter-active, .slide-leave-active {
  transition: all 0.3s ease;
}

.slide-enter, .slide-leave-to {
  opacity: 0;
  transform: translateY(-10px);
}
</style>