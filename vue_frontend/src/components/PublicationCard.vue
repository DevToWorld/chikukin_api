<template>
  <div class="publication-card-wrapper">
    <!-- アクセス制限がある場合 -->
    <div v-if="!canAccess" class="restricted-card" @click="handleRestrictedClick">
      <div class="restricted-content">
        <div class="publication-image blurred">
          <img :src="cardImage" :alt="publication.title" />
          <div class="restriction-overlay">
            <div class="lock-icon">&#x1F512;</div>
            <p class="restriction-message">{{ restrictionMessage }}</p>
            <button class="upgrade-btn" @click.stop="handleUpgrade">
              {{ isLoggedIn ? 'プランをアップグレード' : '会員登録して閲覧' }}
            </button>
          </div>
        </div>
        <div class="publication-content blurred-text">
          <div class="publication-date">{{ formatDate(publication.publication_date) }}</div>
          <h3>{{ publication.title }}</h3>
          <p class="publication-description">{{ publication.description }}</p>
          <div class="membership-badge">
            {{ getMembershipLabel(publication.membershipLevel) }}限定
          </div>
        </div>
      </div>
    </div>

    <!-- アクセス可能な場合 -->
    <div v-else class="publication-card" @click="goToPublication">
      <div class="publication-image">
        <img :src="cardImage" :alt="publication.title" />
        <div v-if="isNew" class="publication-badge">NEW</div>
      </div>
      <div class="publication-content">
        <div class="publication-date">{{ formatDate(publication.publication_date) }}</div>
        <h3>{{ publication.title }}</h3>
        <p class="publication-description">{{ publication.description }}</p>
        <div class="publication-actions">
          <button class="view-btn" @click.stop="goToPublication">詳細を見る</button>
          <button class="download-btn" @click.stop="downloadPDF">PDFダウンロード</button>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import { useMemberAuth } from '@/composables/useMemberAuth'

export default {
  name: 'PublicationCard',
  props: {
    publication: {
      type: Object,
      required: true
    },
    defaultImage: {
      type: String,
      default: '/img/-----2-2-5.png'
    },
    isNew: {
      type: Boolean,
      default: false
    }
  },
  setup() {
    const { 
      isLoggedIn, 
      canAccessContent, 
      getMembershipLabel, 
      getAccessMessage 
    } = useMemberAuth()
    
    return {
      isLoggedIn,
      canAccessContent,
      getMembershipLabel,
      getAccessMessage
    }
  },
  computed: {
    canAccess() {
      return this.canAccessContent(this.publication.membershipLevel || 'free')
    },
    restrictionMessage() {
      return this.getAccessMessage(this.publication.membershipLevel)
    },
    cardImage() {
      const p = this.publication || {}
      return (
        p.cover_image_url || p.cover_image || p.image_url || p.image || this.defaultImage
      )
    }
  },
  methods: {
    formatDate(dateString) {
      const date = new Date(dateString)
      return `${date.getFullYear()}年${date.getMonth() + 1}月${date.getDate()}日`
    },
    goToPublication() {
      this.$router.push(`/publications/${this.publication.id}`)
    },
    downloadPDF() {
      // PDF ダウンロード処理
      if (this.publication.file_url) {
        window.open(this.publication.file_url, '_blank')
      }
    },
    handleRestrictedClick() {
      // 制限コンテンツがクリックされた時の処理
      if (!this.isLoggedIn()) {
        this.$router.push('/member-login')
      }
    },
    handleUpgrade() {
      if (this.isLoggedIn()) {
        // アップグレードページへ（今後実装）
        alert('プランのアップグレード機能は準備中です')
      } else {
        this.$router.push('/member-login')
      }
    }
  }
}
</script>

<style scoped>
.publication-card-wrapper {
  width: 100%;
}

/* 制限カード */
.restricted-card {
  position: relative;
  cursor: pointer;
  transition: transform 0.3s ease;
}

.restricted-card:hover {
  transform: translateY(-2px);
}

.restricted-content {
  position: relative;
}

.blurred {
  filter: blur(4px);
  user-select: none;
  pointer-events: none;
}

.blurred-text {
  filter: blur(2px);
  opacity: 0.6;
}

.restriction-overlay {
  position: absolute;
  top: 50%;
  left: 50%;
  transform: translate(-50%, -50%);
  background: rgba(255, 255, 255, 0.95);
  padding: 24px;
  border-radius: 12px;
  text-align: center;
  box-shadow: 0 4px 20px rgba(0, 0, 0, 0.15);
  min-width: 200px;
}

.lock-icon {
  font-size: 48px;
  margin-bottom: 12px;
}

.restriction-message {
  color: #1A1A1A;
  font-size: 14px;
  line-height: 1.5;
  margin-bottom: 16px;
}

.upgrade-btn {
  background: linear-gradient(135deg, #da5761 0%, #c44853 100%);
  color: white;
  border: none;
  padding: 10px 20px;
  border-radius: 6px;
  font-size: 14px;
  font-weight: 600;
  cursor: pointer;
  transition: all 0.3s ease;
}

.upgrade-btn:hover {
  background: linear-gradient(135deg, #c44853 0%, #b33843 100%);
  transform: translateY(-1px);
  box-shadow: 0 4px 12px rgba(218, 87, 97, 0.3);
}

.membership-badge {
  display: inline-block;
  background: linear-gradient(135deg, #fbbf24 0%, #f59e0b 100%);
  color: white;
  padding: 4px 12px;
  border-radius: 20px;
  font-size: 12px;
  font-weight: 600;
  margin-top: 8px;
}

/* 通常のカード */
.publication-card {
  background: white;
  border-radius: 12px;
  overflow: hidden;
  box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
  transition: all 0.3s ease;
  cursor: pointer;
}

.publication-card:hover {
  transform: translateY(-4px);
  box-shadow: 0 8px 20px rgba(0, 0, 0, 0.12);
}

.publication-image {
  position: relative;
  width: 100%;
  height: 200px;
  overflow: hidden;
}

.publication-image img {
  width: 100%;
  height: 100%;
  object-fit: cover;
}

.publication-badge {
  position: absolute;
  top: 12px;
  right: 12px;
  background: linear-gradient(135deg, #da5761 0%, #c44853 100%);
  color: white;
  padding: 4px 12px;
  border-radius: 20px;
  font-size: 12px;
  font-weight: 600;
}

.publication-content {
  padding: 20px;
}

.publication-date {
  color: #666;
  font-size: 12px;
  margin-bottom: 8px;
}

.publication-content h3 {
  font-size: 16px;
  font-weight: 600;
  color: #1A1A1A;
  margin-bottom: 12px;
  line-height: 1.4;
}

.publication-description {
  font-size: 14px;
  color: #666;
  line-height: 1.5;
  margin-bottom: 16px;
  display: -webkit-box;
  -webkit-line-clamp: 2;
  -webkit-box-orient: vertical;
  overflow: hidden;
}

.publication-actions {
  display: flex;
  gap: 8px;
}

.view-btn,
.download-btn {
  flex: 1;
  padding: 8px 16px;
  border-radius: 6px;
  font-size: 13px;
  font-weight: 500;
  cursor: pointer;
  transition: all 0.2s ease;
}

.view-btn {
  background: white;
  color: #da5761;
  border: 1px solid #da5761;
}

.view-btn:hover {
  background: #da5761;
  color: white;
}

.download-btn {
  background: #da5761;
  color: white;
  border: none;
}

.download-btn:hover {
  opacity: 0.8;
}
</style>
