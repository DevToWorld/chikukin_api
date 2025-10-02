<template>
  <span 
    v-if="level && level !== 'free'"
    :class="['membership-badge', `badge-${level}`]"
  >
    {{ badgeText }}
  </span>
</template>

<script>
export default {
  name: 'MembershipBadge',
  props: {
    level: {
      type: String,
      required: true,
      validator: value => ['free', 'standard', 'premium'].includes(value)
    },
    compact: {
      type: Boolean,
      default: false
    }
  },
  computed: {
    badgeText() {
      if (this.compact) {
        switch (this.level) {
          case 'standard':
            return 'S'
          case 'premium':
            return 'P'
          default:
            return ''
        }
      }
      
      switch (this.level) {
        case 'standard':
          return 'スタンダード会員限定'
        case 'premium':
          return 'プレミアム会員限定'
        default:
          return ''
      }
    }
  }
}
</script>

<style scoped>
.membership-badge {
  display: inline-block;
  padding: 4px 12px;
  border-radius: 20px;
  font-size: 12px;
  font-weight: 600;
  white-space: nowrap;
}

.badge-free {
  background: #e8f5e9;
  color: #2e7d32;
}

.badge-standard {
  background: #f3e5f5;
  color: #7b1fa2;
}

.badge-premium {
  background: #fff3e0;
  color: #f57c00;
}
</style>