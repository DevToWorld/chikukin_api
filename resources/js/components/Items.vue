<template>
  <div class="items-1 inter-bold-black-15px">
    <router-link to="/" class="text-6 valign-text-middle nav-link" exact-active-class="active">トップ</router-link>
    <img
      class="vector-22"
      src="/img/vector-1.svg"
      alt="Vector 1"
    />
    <router-link to="/services" class="text-6 valign-text-middle nav-link" active-class="active">会員サービスのご案内</router-link>
    <img
      class="vector-22"
      src="/img/vector-1.svg"
      alt="Vector 2"
    />
    <router-link to="/about" class="text-6 valign-text-middle nav-link" active-class="active">会社概要</router-link>
    <img
      class="vector-22"
      src="/img/vector-1.svg"
      alt="Vector 3"
    />
    <router-link to="/news" class="text-6 valign-text-middle nav-link" active-class="active">お知らせ</router-link>
    <img
      class="vector-22"
      src="/img/vector-1.svg"
      alt="Vector 4"
    />
    <router-link to="/faq" class="text-6 valign-text-middle nav-link" active-class="active">よくあるご質問</router-link>
  </div>
</template>

<script>
export default {
  name: "Items",
};
</script>

<style>
.items-1 {
  align-items: center;
  display: inline-flex;
  gap: 15px;
  left: 0;
  position: absolute;
  top: 45px;
}

.text-6 {
  letter-spacing: 0;
  line-height: 22.5px;
  margin-top: -1px;
  position: relative;
  white-space: nowrap;
  width: fit-content;
}

.vector-22 {
  height: 10px;
  position: relative;
  width: 1px;
}

.nav-link {
  text-decoration: none;
  color: inherit;
  transition: color 0.3s ease;
}

.nav-link:hover {
  color: #007bff;
}

.nav-link.active {
  color: #007bff;
  font-weight: 700;
}
</style>
