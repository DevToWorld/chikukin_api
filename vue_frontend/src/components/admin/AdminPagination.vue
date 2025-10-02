<template>
  <div v-if="computedLastPage > 1" class="pagination">
    <button
      class="page-btn"
      :disabled="page <= 1"
      @click="goTo(1)"
    >最初</button>
    <button
      class="page-btn"
      :disabled="page <= 1"
      @click="goTo(page - 1)"
    >前へ</button>

    <template v-for="item in items">
      <button
        v-if="item.type === 'page'"
        :key="item.key"
        class="page-number"
        :class="{ active: item.value === page }"
        @click="goTo(item.value)"
      >{{ item.value }}</button>
      <span v-else :key="item.key" class="page-dots">…</span>
    </template>

    <button
      class="page-btn"
      :disabled="page >= computedLastPage"
      @click="goTo(page + 1)"
    >次へ</button>
    <button
      class="page-btn"
      :disabled="page >= computedLastPage"
      @click="goTo(computedLastPage)"
    >最後</button>
  </div>
  <div v-else class="pagination single">1</div>
</template>

<script>
export default {
  name: 'AdminPagination',
  props: {
    page: { type: Number, required: true },
    lastPage: { type: Number, default: 1 },
    total: { type: Number, default: 0 },
    perPage: { type: Number, default: 0 },
    // どれくらいの番号を中心に見せるか（両隣1ずつなど）
    neighborCount: { type: Number, default: 1 }
  },
  computed: {
    computedLastPage() {
      if (this.lastPage && this.lastPage > 0) return this.lastPage
      if (this.total > 0 && this.perPage > 0) return Math.max(1, Math.ceil(this.total / this.perPage))
      return 1
    },
    items() {
      const last = this.computedLastPage
      const current = Math.min(Math.max(this.page || 1, 1), last)
      const set = new Set()
      const add = n => { if (n >= 1 && n <= last) set.add(n) }

      // Always include boundaries
      add(1); add(2); add(last - 1); add(last)
      // Include neighbors around current
      for (let d = this.neighborCount; d >= 1; d--) add(current - d)
      add(current)
      for (let d = 1; d <= this.neighborCount; d++) add(current + d)

      const pages = Array.from(set).sort((a, b) => a - b)
      // Build sequence with ellipses
      const out = []
      let prev = 0
      for (const p of pages) {
        if (prev && p > prev + 1) {
          out.push({ type: 'dots', key: `dots-${prev}-${p}` })
        }
        out.push({ type: 'page', value: p, key: `p-${p}` })
        prev = p
      }
      return out
    }
  },
  methods: {
    goTo(n) {
      const last = this.computedLastPage
      const next = Math.min(Math.max(1, n), last)
      if (next === this.page) return
      this.$emit('update:page', next)
      this.$emit('change', next)
    }
  }
}
</script>

<style scoped>
.pagination {
  padding: 16px 24px;
  text-align: center;
  border-top: 1px solid #e5e5e5;
}

.page-btn {
  background-color: #fff;
  border: 1px solid #d0d0d0;
  padding: 6px 12px;
  border-radius: 4px;
  font-size: 14px;
  color: #1A1A1A;
  cursor: pointer;
  transition: all 0.2s;
  margin: 0 4px;
}

.page-btn:hover:not(:disabled) {
  background-color: #f8f8f8;
  border-color: #1A1A1A;
}

.page-btn:disabled {
  opacity: 0.5;
  cursor: not-allowed;
}

.page-number {
  background-color: #fff;
  border: 1px solid #d0d0d0;
  padding: 6px 12px;
  margin: 0 4px;
  border-radius: 4px;
  font-size: 14px;
  color: #1A1A1A;
  cursor: pointer;
  transition: all 0.2s;
}

.page-number:hover {
  background-color: #f8f8f8;
  border-color: #1A1A1A;
}

.page-number.active {
  background-color: #da5761;
  border-color: #da5761;
  color: #fff;
}

.page-dots {
  padding: 6px 8px;
  color: #666;
}

.single {
  color: #666;
}
</style>

