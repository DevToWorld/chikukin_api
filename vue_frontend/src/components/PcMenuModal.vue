<template>
  <div v-if="isOpen" class="pc-menu-modal" @click="closeModal">
    <div class="modal-container" @click.stop>
      <!-- Header Section -->
      <div class="modal-header" :style="{ height: navigationHeight }">
        <div class="logo-section">
          <view-component />
          <group1 />
        </div>
        <div class="header-controls">
          <button class="close-button" @click="closeModal">
            <svg width="24" height="25" viewBox="0 0 24 25" fill="none" xmlns="http://www.w3.org/2000/svg">
              <path d="M21.8182 2.68182L2.18182 22.3182" stroke="#1A1A1A" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"/>
              <path d="M21.8182 22.3182L2.18182 2.68182" stroke="#1A1A1A" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"/>
            </svg>
          </button>
        </div>
      </div>

      <!-- Navigation Section -->
      <div class="navigation-section">
        <div class="nav-grid">
          <!-- Single nav-row with all 5 items that will wrap -->
          <div class="nav-row">
            <div class="nav-item" @click="navigateToRoute('/aboutus')">
              <svg class="nav-icon" width="45" height="45" viewBox="0 0 45 45" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M22.5 0C18.0499 0 13.6998 1.3196 9.99968 3.79193C6.29957 6.26426 3.41569 9.77828 1.71272 13.8896C0.0097489 18.001 -0.435826 22.525 0.432341 26.8895C1.30051 31.2541 3.44342 35.2632 6.59011 38.4099C9.73679 41.5566 13.7459 43.6995 18.1105 44.5677C22.475 45.4358 26.999 44.9902 31.1104 43.2873C35.2217 41.5843 38.7357 38.7004 41.2081 35.0003C43.6804 31.3002 45 26.9501 45 22.5C44.9937 16.5346 42.6211 10.8153 38.4029 6.59705C34.1847 2.37885 28.4654 0.00629961 22.5 0ZM32.3784 23.7245L25.4553 30.6476C25.1305 30.9724 24.6901 31.1548 24.2308 31.1548C23.7715 31.1548 23.331 30.9724 23.0063 30.6476C22.6815 30.3228 22.499 29.8824 22.499 29.4231C22.499 28.9638 22.6815 28.5233 23.0063 28.1985L26.9762 24.2308H13.8462C13.3871 24.2308 12.9469 24.0484 12.6223 23.7238C12.2977 23.3993 12.1154 22.959 12.1154 22.5C12.1154 22.041 12.2977 21.6007 12.6223 21.2762C12.9469 20.9516 13.3871 20.7692 13.8462 20.7692H26.9762L23.0063 16.8014C22.6815 16.4767 22.499 16.0362 22.499 15.5769C22.499 15.1176 22.6815 14.6772 23.0063 14.3524C23.331 14.0276 23.7715 13.8452 24.2308 13.8452C24.6901 13.8452 25.1305 14.0276 25.4553 14.3524L32.3784 21.2755C32.5393 21.4362 32.667 21.6271 32.754 21.8372C32.8411 22.0473 32.886 22.2725 32.886 22.5C32.886 22.7274 32.8411 22.9527 32.754 23.1628C32.667 23.3729 32.5393 23.5638 32.3784 23.7245Z" fill="#DA5761"/>
              </svg>
              <span class="nav-text"><CmsText pageKey="navigation" fieldKey="nav_about" tag="span" :fallback="'私たちについて'" /></span>
            </div>
            
            <div class="nav-item" @click="navigateToRoute('/seminars')">
              <svg class="nav-icon" width="45" height="45" viewBox="0 0 45 45" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M22.5 0C18.0499 0 13.6998 1.3196 9.99968 3.79193C6.29957 6.26426 3.41569 9.77828 1.71272 13.8896C0.0097489 18.001 -0.435826 22.525 0.432341 26.8895C1.30051 31.2541 3.44342 35.2632 6.59011 38.4099C9.73679 41.5566 13.7459 43.6995 18.1105 44.5677C22.475 45.4358 26.999 44.9902 31.1104 43.2873C35.2217 41.5843 38.7357 38.7004 41.2081 35.0003C43.6804 31.3002 45 26.9501 45 22.5C44.9937 16.5346 42.6211 10.8153 38.4029 6.59705C34.1847 2.37885 28.4654 0.00629961 22.5 0ZM32.3784 23.7245L25.4553 30.6476C25.1305 30.9724 24.6901 31.1548 24.2308 31.1548C23.7715 31.1548 23.331 30.9724 23.0063 30.6476C22.6815 30.3228 22.499 29.8824 22.499 29.4231C22.499 28.9638 22.6815 28.5233 23.0063 28.1985L26.9762 24.2308H13.8462C13.3871 24.2308 12.9469 24.0484 12.6223 23.7238C12.2977 23.3993 12.1154 22.959 12.1154 22.5C12.1154 22.041 12.2977 21.6007 12.6223 21.2762C12.9469 20.9516 13.3871 20.7692 13.8462 20.7692H26.9762L23.0063 16.8014C22.6815 16.4767 22.499 16.0362 22.499 15.5769C22.499 15.1176 22.6815 14.6772 23.0063 14.3524C23.331 14.0276 23.7715 13.8452 24.2308 13.8452C24.6901 13.8452 25.1305 14.0276 25.4553 14.3524L32.3784 21.2755C32.5393 21.4362 32.667 21.6271 32.754 21.8372C32.8411 22.0473 32.886 22.2725 32.886 22.5C32.886 22.7274 32.8411 22.9527 32.754 23.1628C32.667 23.3729 32.5393 23.5638 32.3784 23.7245Z" fill="#DA5761"/>
              </svg>
              <span class="nav-text"><CmsText pageKey="navigation" fieldKey="nav_seminars" tag="span" :fallback="'セミナー'" /></span>
            </div>
            
            <div class="nav-item" @click="navigateToRoute('/publications')">
              <svg class="nav-icon" width="45" height="45" viewBox="0 0 45 45" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M22.5 0C18.0499 0 13.6998 1.3196 9.99968 3.79193C6.29957 6.26426 3.41569 9.77828 1.71272 13.8896C0.0097489 18.001 -0.435826 22.525 0.432341 26.8895C1.30051 31.2541 3.44342 35.2632 6.59011 38.4099C9.73679 41.5566 13.7459 43.6995 18.1105 44.5677C22.475 45.4358 26.999 44.9902 31.1104 43.2873C35.2217 41.5843 38.7357 38.7004 41.2081 35.0003C43.6804 31.3002 45 26.9501 45 22.5C44.9937 16.5346 42.6211 10.8153 38.4029 6.59705C34.1847 2.37885 28.4654 0.00629961 22.5 0ZM32.3784 23.7245L25.4553 30.6476C25.1305 30.9724 24.6901 31.1548 24.2308 31.1548C23.7715 31.1548 23.331 30.9724 23.0063 30.6476C22.6815 30.3228 22.499 29.8824 22.499 29.4231C22.499 28.9638 22.6815 28.5233 23.0063 28.1985L26.9762 24.2308H13.8462C13.3871 24.2308 12.9469 24.0484 12.6223 23.7238C12.2977 23.3993 12.1154 22.959 12.1154 22.5C12.1154 22.041 12.2977 21.6007 12.6223 21.2762C12.9469 20.9516 13.3871 20.7692 13.8462 20.7692H26.9762L23.0063 16.8014C22.6815 16.4767 22.499 16.0362 22.499 15.5769C22.499 15.1176 22.6815 14.6772 23.0063 14.3524C23.331 14.0276 23.7715 13.8452 24.2308 13.8452C24.6901 13.8452 25.1305 14.0276 25.4553 14.3524L32.3784 21.2755C32.5393 21.4362 32.667 21.6271 32.754 21.8372C32.8411 22.0473 32.886 22.2725 32.886 22.5C32.886 22.7274 32.8411 22.9527 32.754 23.1628C32.667 23.3729 32.5393 23.5638 32.3784 23.7245Z" fill="#DA5761"/>
              </svg>
              <span class="nav-text"><CmsText pageKey="navigation" fieldKey="nav_publications" tag="span" :fallback="'刊行物'" /></span>
            </div>
            
            <div class="nav-item dropdown" @mouseenter="showDropdown('info')" @mouseleave="hideDropdown('info')">
              <svg class="nav-icon" width="45" height="45" viewBox="0 0 45 45" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M22.5 0C18.0499 0 13.6998 1.3196 9.99968 3.79193C6.29957 6.26426 3.41569 9.77828 1.71272 13.8896C0.0097489 18.001 -0.435826 22.525 0.432341 26.8895C1.30051 31.2541 3.44342 35.2632 6.59011 38.4099C9.73679 41.5566 13.7459 43.6995 18.1105 44.5677C22.475 45.4358 26.999 44.9902 31.1104 43.2873C35.2217 41.5843 38.7357 38.7004 41.2081 35.0003C43.6804 31.3002 45 26.9501 45 22.5C44.9937 16.5346 42.6211 10.8153 38.4029 6.59705C34.1847 2.37885 28.4654 0.00629961 22.5 0ZM32.3784 23.7245L25.4553 30.6476C25.1305 30.9724 24.6901 31.1548 24.2308 31.1548C23.7715 31.1548 23.331 30.9724 23.0063 30.6476C22.6815 30.3228 22.499 29.8824 22.499 29.4231C22.499 28.9638 22.6815 28.5233 23.0063 28.1985L26.9762 24.2308H13.8462C13.3871 24.2308 12.9469 24.0484 12.6223 23.7238C12.2977 23.3993 12.1154 22.959 12.1154 22.5C12.1154 22.041 12.2977 21.6007 12.6223 21.2762C12.9469 20.9516 13.3871 20.7692 13.8462 20.7692H26.9762L23.0063 16.8014C22.6815 16.4767 22.499 16.0362 22.499 15.5769C22.499 15.1176 22.6815 14.6772 23.0063 14.3524C23.331 14.0276 23.7715 13.8452 24.2308 13.8452C24.6901 13.8452 25.1305 14.0276 25.4553 14.3524L32.3784 21.2755C32.5393 21.4362 32.667 21.6271 32.754 21.8372C32.8411 22.0473 32.886 22.2725 32.886 22.5C32.886 22.7274 32.8411 22.9527 32.754 23.1628C32.667 23.3729 32.5393 23.5638 32.3784 23.7245Z" fill="#DA5761"/>
              </svg>
              <span class="nav-text"><CmsText pageKey="navigation" fieldKey="nav_information" tag="span" :fallback="'各種情報'" /></span>
              <div class="dropdown-menu" v-show="activeDropdown === 'info'" @mouseenter="showDropdown('info')" @mouseleave="hideDropdown('info')">
                <div class="dropdown-item" @click="navigateToRoute('/economic-indicators')">経済指標</div>
                <div class="dropdown-item" @click="navigateToRoute('/economic-statistics')">経済・調査統計</div>
              </div>
            </div>
            
            <div v-if="!loggedIn" class="nav-item dropdown" @mouseenter="showDropdown('membership')" @mouseleave="hideDropdown('membership')">
              <svg class="nav-icon" width="45" height="45" viewBox="0 0 45 45" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M22.5 0C18.0499 0 13.6998 1.3196 9.99968 3.79193C6.29957 6.26426 3.41569 9.77828 1.71272 13.8896C0.0097489 18.001 -0.435826 22.525 0.432341 26.8895C1.30051 31.2541 3.44342 35.2632 6.59011 38.4099C9.73679 41.5566 13.7459 43.6995 18.1105 44.5677C22.475 45.4358 26.999 44.9902 31.1104 43.2873C35.2217 41.5843 38.7357 38.7004 41.2081 35.0003C43.6804 31.3002 45 26.9501 45 22.5C44.9937 16.5346 42.6211 10.8153 38.4029 6.59705C34.1847 2.37885 28.4654 0.00629961 22.5 0ZM32.3784 23.7245L25.4553 30.6476C25.1305 30.9724 24.6901 31.1548 24.2308 31.1548C23.7715 31.1548 23.331 30.9724 23.0063 30.6476C22.6815 30.3228 22.499 29.8824 22.499 29.4231C22.499 28.9638 22.6815 28.5233 23.0063 28.1985L26.9762 24.2308H13.8462C13.3871 24.2308 12.9469 24.0484 12.6223 23.7238C12.2977 23.3993 12.1154 22.959 12.1154 22.5C12.1154 22.041 12.2977 21.6007 12.6223 21.2762C12.9469 20.9516 13.3871 20.7692 13.8462 20.7692H26.9762L23.0063 16.8014C22.6815 16.4767 22.499 16.0362 22.499 15.5769C22.499 15.1176 22.6815 14.6772 23.0063 14.3524C23.331 14.0276 23.7715 13.8452 24.2308 13.8452C24.6901 13.8452 25.1305 14.0276 25.4553 14.3524L32.3784 21.2755C32.5393 21.4362 32.667 21.6271 32.754 21.8372C32.8411 22.0473 32.886 22.2725 32.886 22.5C32.886 22.7274 32.8411 22.9527 32.754 23.1628C32.667 23.3729 32.5393 23.5638 32.3784 23.7245Z" fill="#DA5761"/>
              </svg>
              <span class="nav-text"><CmsText pageKey="navigation" fieldKey="nav_membership" tag="span" :fallback="'入会案内'" /></span>
              <div class="dropdown-menu" v-show="activeDropdown === 'membership'" @mouseenter="showDropdown('membership')" @mouseleave="hideDropdown('membership')">
                <div class="dropdown-item" @click="navigateToRoute('/membership/standard')">スタンダード</div>
                <div class="dropdown-item" @click="navigateToRoute('/membership/premium')">プレミアム</div>
              </div>
            </div>
            <div v-if="loggedIn" class="nav-item" @click="navigateToRoute('/membership/premium')">
              <svg class="nav-icon" width="45" height="45" viewBox="0 0 45 45" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M22.5 0C18.0499 0 13.6998 1.3196 9.99968 3.79193C6.29957 6.26426 3.41569 9.77828 1.71272 13.8896C0.0097489 18.001 -0.435826 22.525 0.432341 26.8895C1.30051 31.2541 3.44342 35.2632 6.59011 38.4099C9.73679 41.5566 13.7459 43.6995 18.1105 44.5677C22.475 45.4358 26.999 44.9902 31.1104 43.2873C35.2217 41.5843 38.7357 38.7004 41.2081 35.0003C43.6804 31.3002 45 26.9501 45 22.5C44.9937 16.5346 42.6211 10.8153 38.4029 6.59705C34.1847 2.37885 28.4654 0.00629961 22.5 0ZM32.3784 23.7245L25.4553 30.6476C25.1305 30.9724 24.6901 31.1548 24.2308 31.1548C23.7715 31.1548 23.331 30.9724 23.0063 30.6476C22.6815 30.3228 22.499 29.8824 22.499 29.4231C22.499 28.9638 22.6815 28.5233 23.0063 28.1985L26.9762 24.2308H13.8462C13.3871 24.2308 12.9469 24.0484 12.6223 23.7238C12.2977 23.3993 12.1154 22.959 12.1154 22.5C12.1154 22.041 12.2977 21.6007 12.6223 21.2762C12.9469 20.9516 13.3871 20.7692 13.8462 20.7692H26.9762L23.0063 16.8014C22.6815 16.4767 22.499 16.0362 22.499 15.5769C22.499 15.1176 22.6815 14.6772 23.0063 14.3524C23.331 14.0276 23.7715 13.8452 24.2308 13.8452C24.6901 13.8452 25.1305 14.0276 25.4553 14.3524L32.3784 21.2755C32.5393 21.4362 32.667 21.6271 32.754 21.8372C32.8411 22.0473 32.886 22.2725 32.886 22.5C32.886 22.7274 32.8411 22.9527 32.754 23.1628C32.667 23.3729 32.5393 23.5638 32.3784 23.7245Z" fill="#DA5761"/>
              </svg>
              <span class="nav-text"><CmsText pageKey="navigation" fieldKey="nav_premium_benefits" tag="span" :fallback="'プレミアム会員の特典'" /></span>
            </div>
            
            <div class="nav-item" @click="navigateToRoute('/news')">
              <svg class="nav-icon" width="45" height="45" viewBox="0 0 45 45" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M22.5 0C18.0499 0 13.6998 1.3196 9.99968 3.79193C6.29957 6.26426 3.41569 9.77828 1.71272 13.8896C0.0097489 18.001 -0.435826 22.525 0.432341 26.8895C1.30051 31.2541 3.44342 35.2632 6.59011 38.4099C9.73679 41.5566 13.7459 43.6995 18.1105 44.5677C22.475 45.4358 26.999 44.9902 31.1104 43.2873C35.2217 41.5843 38.7357 38.7004 41.2081 35.0003C43.6804 31.3002 45 26.9501 45 22.5C44.9937 16.5346 42.6211 10.8153 38.4029 6.59705C34.1847 2.37885 28.4654 0.00629961 22.5 0ZM32.3784 23.7245L25.4553 30.6476C25.1305 30.9724 24.6901 31.1548 24.2308 31.1548C23.7715 31.1548 23.331 30.9724 23.0063 30.6476C22.6815 30.3228 22.499 29.8824 22.499 29.4231C22.499 28.9638 22.6815 28.5233 23.0063 28.1985L26.9762 24.2308H13.8462C13.3871 24.2308 12.9469 24.0484 12.6223 23.7238C12.2977 23.3993 12.1154 22.959 12.1154 22.5C12.1154 22.041 12.2977 21.6007 12.6223 21.2762C12.9469 20.9516 13.3871 20.7692 13.8462 20.7692H26.9762L23.0063 16.8014C22.6815 16.4767 22.499 16.0362 22.499 15.5769C22.499 15.1176 22.6815 14.6772 23.0063 14.3524C23.331 14.0276 23.7715 13.8452 24.2308 13.8452C24.6901 13.8452 25.1305 14.0276 25.4553 14.3524L32.3784 21.2755C32.5393 21.4362 32.667 21.6271 32.754 21.8372C32.8411 22.0473 32.886 22.2725 32.886 22.5C32.886 22.7274 32.8411 22.9527 32.754 23.1628C32.667 23.3729 32.5393 23.5638 32.3784 23.7245Z" fill="#DA5761"/>
              </svg>
              <span class="nav-text"><CmsText pageKey="navigation" fieldKey="nav_news" tag="span" :fallback="'お知らせ'" /></span>
            </div>
            
            <div class="nav-item" @click="navigateToRoute('/company')">
              <svg class="nav-icon" width="45" height="45" viewBox="0 0 45 45" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M22.5 0C18.0499 0 13.6998 1.3196 9.99968 3.79193C6.29957 6.26426 3.41569 9.77828 1.71272 13.8896C0.0097489 18.001 -0.435826 22.525 0.432341 26.8895C1.30051 31.2541 3.44342 35.2632 6.59011 38.4099C9.73679 41.5566 13.7459 43.6995 18.1105 44.5677C22.475 45.4358 26.999 44.9902 31.1104 43.2873C35.2217 41.5843 38.7357 38.7004 41.2081 35.0003C43.6804 31.3002 45 26.9501 45 22.5C44.9937 16.5346 42.6211 10.8153 38.4029 6.59705C34.1847 2.37885 28.4654 0.00629961 22.5 0ZM32.3784 23.7245L25.4553 30.6476C25.1305 30.9724 24.6901 31.1548 24.2308 31.1548C23.7715 31.1548 23.331 30.9724 23.0063 30.6476C22.6815 30.3228 22.499 29.8824 22.499 29.4231C22.499 28.9638 22.6815 28.5233 23.0063 28.1985L26.9762 24.2308H13.8462C13.3871 24.2308 12.9469 24.0484 12.6223 23.7238C12.2977 23.3993 12.1154 22.959 12.1154 22.5C12.1154 22.041 12.2977 21.6007 12.6223 21.2762C12.9469 20.9516 13.3871 20.7692 13.8462 20.7692H26.9762L23.0063 16.8014C22.6815 16.4767 22.499 16.0362 22.499 15.5769C22.499 15.1176 22.6815 14.6772 23.0063 14.3524C23.331 14.0276 23.7715 13.8452 24.2308 13.8452C24.6901 13.8452 25.1305 14.0276 25.4553 14.3524L32.3784 21.2755C32.5393 21.4362 32.667 21.6271 32.754 21.8372C32.8411 22.0473 32.886 22.2725 32.886 22.5C32.886 22.7274 32.8411 22.9527 32.754 23.1628C32.667 23.3729 32.5393 23.5638 32.3784 23.7245Z" fill="#DA5761"/>
              </svg>
              <span class="nav-text"><CmsText pageKey="navigation" fieldKey="nav_company" tag="span" :fallback="'会社概要'" /></span>
            </div>
            
            <div class="nav-item" @click="navigateToRoute('/faq')">
              <svg class="nav-icon" width="45" height="45" viewBox="0 0 45 45" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M22.5 0C18.0499 0 13.6998 1.3196 9.99968 3.79193C6.29957 6.26426 3.41569 9.77828 1.71272 13.8896C0.0097489 18.001 -0.435826 22.525 0.432341 26.8895C1.30051 31.2541 3.44342 35.2632 6.59011 38.4099C9.73679 41.5566 13.7459 43.6995 18.1105 44.5677C22.475 45.4358 26.999 44.9902 31.1104 43.2873C35.2217 41.5843 38.7357 38.7004 41.2081 35.0003C43.6804 31.3002 45 26.9501 45 22.5C44.9937 16.5346 42.6211 10.8153 38.4029 6.59705C34.1847 2.37885 28.4654 0.00629961 22.5 0ZM32.3784 23.7245L25.4553 30.6476C25.1305 30.9724 24.6901 31.1548 24.2308 31.1548C23.7715 31.1548 23.331 30.9724 23.0063 30.6476C22.6815 30.3228 22.499 29.8824 22.499 29.4231C22.499 28.9638 22.6815 28.5233 23.0063 28.1985L26.9762 24.2308H13.8462C13.3871 24.2308 12.9469 24.0484 12.6223 23.7238C12.2977 23.3993 12.1154 22.959 12.1154 22.5C12.1154 22.041 12.2977 21.6007 12.6223 21.2762C12.9469 20.9516 13.3871 20.7692 13.8462 20.7692H26.9762L23.0063 16.8014C22.6815 16.4767 22.499 16.0362 22.499 15.5769C22.499 15.1176 22.6815 14.6772 23.0063 14.3524C23.331 14.0276 23.7715 13.8452 24.2308 13.8452C24.6901 13.8452 25.1305 14.0276 25.4553 14.3524L32.3784 21.2755C32.5393 21.4362 32.667 21.6271 32.754 21.8372C32.8411 22.0473 32.886 22.2725 32.886 22.5C32.886 22.7274 32.8411 22.9527 32.754 23.1628C32.667 23.3729 32.5393 23.5638 32.3784 23.7245Z" fill="#DA5761"/>
              </svg>
              <span class="nav-text"><CmsText pageKey="navigation" fieldKey="nav_faq" tag="span" :fallback="'よくある質問'" /></span>
            </div>
          </div>
        </div>

        <!-- Action Buttons -->
        <div class="action-buttons">
          <button class="login-button" @click="navigateToRoute(loggedIn ? '/myaccount' : '/login')">
            <span>{{ loggedIn ? 'マイページ' : 'ログイン' }}</span>
            <svg class="button-icon" width="32" height="33" viewBox="0 0 32 33" fill="none" xmlns="http://www.w3.org/2000/svg">
              <g clip-path="url(#clip0_196_92)">
                <path d="M28.8662 28C28.7784 28.152 28.6521 28.2783 28.5001 28.3661C28.348 28.4538 28.1755 28.5 28 28.5H3.99995C3.8245 28.4998 3.65219 28.4535 3.50031 28.3656C3.34843 28.2778 3.22234 28.1515 3.13469 27.9995C3.04705 27.8475 3.00093 27.6752 3.00098 27.4997C3.00102 27.3243 3.04723 27.1519 3.13495 27C5.0387 23.7087 7.97245 21.3487 11.3962 20.23C9.70266 19.2218 8.38688 17.6856 7.65094 15.8572C6.91499 14.0289 6.79956 12.0095 7.32238 10.1092C7.8452 8.20887 8.97735 6.53272 10.545 5.33814C12.1126 4.14355 14.029 3.49658 16 3.49658C17.9709 3.49658 19.8873 4.14355 21.4549 5.33814C23.0226 6.53272 24.1547 8.20887 24.6775 10.1092C25.2003 12.0095 25.0849 14.0289 24.349 15.8572C23.613 17.6856 22.2972 19.2218 20.6037 20.23C24.0275 21.3487 26.9612 23.7087 28.865 27C28.9529 27.1519 28.9993 27.3243 28.9996 27.4998C28.9998 27.6754 28.9538 27.8479 28.8662 28Z" fill="white"/>
              </g>
              <defs>
                <clipPath id="clip0_196_92">
                  <rect width="32" height="32" fill="white" transform="translate(0 0.5)"/>
                </clipPath>
              </defs>
            </svg>
          </button>
          
          <button class="contact-button" @click="navigateToRoute('/apply')">
            <span>お問い合わせ</span>
            <svg class="button-icon" width="32" height="33" viewBox="0 0 32 33" fill="none" xmlns="http://www.w3.org/2000/svg">
              <g clip-path="url(#clip0_196_102)">
                <path d="M22 2.5H10C9.20435 2.5 8.44129 2.81607 7.87868 3.37868C7.31607 3.94129 7 4.70435 7 5.5V27.5C7 28.2956 7.31607 29.0587 7.87868 29.6213C8.44129 30.1839 9.20435 30.5 10 30.5H22C22.7956 30.5 23.5587 30.1839 24.1213 29.6213C24.6839 29.0587 25 28.2956 25 27.5V5.5C25 4.70435 24.6839 3.94129 24.1213 3.37868C23.5587 2.81607 22.7956 2.5 22 2.5ZM10 4.5H22C22.2652 4.5 22.5196 4.60536 22.7071 4.79289C22.8946 4.98043 23 5.23478 23 5.5V6.5H9V5.5C9 5.23478 9.10536 4.98043 9.29289 4.79289C9.48043 4.60536 9.73478 4.5 10 4.5ZM22 28.5H10C9.73478 28.5 9.48043 28.3946 9.29289 28.2071C9.10536 28.0196 9 27.7652 9 27.5V26.5H23V27.5C23 27.7652 22.8946 28.0196 22.7071 28.2071C22.5196 28.3946 22.2652 28.5 22 28.5Z" fill="white"/>
              </g>
              <defs>
                <clipPath id="clip0_196_102">
                  <rect width="32" height="32" fill="white" transform="translate(0 0.5)"/>
                </clipPath>
              </defs>
            </svg>
          </button>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import CmsText from '@/components/CmsText.vue'
import ViewComponent from "./ViewComponent";
import Group1 from "./Group1";

export default {
  name: "PcMenuModal",
  components: {
    CmsText,
    ViewComponent,
    Group1,
  },
  props: {
    isOpen: {
      type: Boolean,
      default: false
    }
  },
  emits: ['close'],
  data() {
    return {
      navigationHeight: 'auto',
      activeDropdown: null,
      hideTimer: null
    };
  },
  
  mounted() {
    this.updateNavigationHeight();
    window.addEventListener('resize', this.updateNavigationHeight);
  },
  beforeDestroy() {
    window.removeEventListener('resize', this.updateNavigationHeight);
  },
  watch: {
    isOpen(newVal) {
      if (newVal) {
        // モーダルが開かれた時に高さを更新
        this.$nextTick(() => {
          this.updateNavigationHeight();
        });
      }
    }
  },
  computed: {
    loggedIn() {
      try {
        const token = localStorage.getItem('auth_token') || localStorage.getItem('memberToken')
        const user = localStorage.getItem('memberUser')
        return !!token && !!user
      } catch (e) {
        return false
      }
    }
  },
  methods: {
    closeModal() {
      this.$emit('close');
    },
    navigateToRoute(path) {
      this.$router.push(path);
      this.closeModal();
    },
    showDropdown(dropdownType) {
      // 既存のタイマーをクリア
      if (this.hideTimer) {
        clearTimeout(this.hideTimer);
        this.hideTimer = null;
      }
      this.activeDropdown = dropdownType;
    },
    hideDropdown(dropdownType) {
      // 少し遅延させてから非表示にする
      this.hideTimer = setTimeout(() => {
        if (this.activeDropdown === dropdownType) {
          this.activeDropdown = null;
        }
      }, 100);
    },
    updateNavigationHeight() {
      // Navigationコンポーネントの高さを取得
      const navigationElement = document.querySelector('.navigation');
      if (navigationElement) {
        this.navigationHeight = navigationElement.offsetHeight + 'px';
      }
    }
  }
};
</script>

<style scoped>
.pc-menu-modal {
  position: fixed;
  top: 0;
  left: 0;
  width: 100%;
  height: 100vh;
  background: rgba(0, 0, 0, 0.5);
  display: flex;
  justify-content: center;
  align-items: flex-start;
  z-index: 2147483647;
  overflow-y: auto;
  animation: fadeIn 0.3s ease-out;
}

@keyframes fadeIn {
  from {
    opacity: 0;
  }
  to {
    opacity: 1;
  }
}

.modal-container {
  width: 100%;
  min-height: 700px;
  background: #ffffff;
  display: flex;
  flex-direction: column;
  align-items: center;
  gap: 55px;
  padding-bottom: 55px;
  margin-top: 0;
  align-self: flex-start;
  z-index: 2147483647;
  animation: slideDown 0.3s ease-out;
}

@keyframes slideDown {
  from {
    transform: translateY(-100%);
    opacity: 0;
  }
  to {
    transform: translateY(0);
    opacity: 1;
  }
}

.modal-header {
  width: 100%;
  padding: 10px 20px;
  display: flex;
  justify-content: space-between;
  align-items: center;
  border-bottom: 1px solid #bebebe;
  flex-shrink: 0;
  transition: height 0.3s ease;
}

.logo-section {
  display: flex;
  align-items: center;
  gap: 20px;
  min-width: 0;
  flex-shrink: 1;
  transition: gap 0.3s ease;
  height: auto;
  min-height: auto;
}


.header-controls {
  display: flex;
  align-items: center;
  gap: 20px;
}


.close-button {
  width: 24px;
  height: 24px;
  display: flex;
  justify-content: center;
  align-items: center;
  background: none;
  border: none;
  cursor: pointer;
}

.navigation-section {
  min-height: 335px;
  display: flex;
  flex-direction: column;
  justify-content: center;
  align-items: center;
  gap: 50px;
  flex: 1;
  width: 100%;
  padding: 0 50px;
}

.nav-grid {
  width: 1000px;
  min-height: 210px;
  display: flex;
  flex-direction: column;
  justify-content: center;
  align-items: flex-start;
  gap: 20px;
}

.nav-row {
  width: 100%;
  min-height: 95px;
  display: flex;
  justify-content: flex-start;
  align-items: center;
  gap: 50px;
  flex-wrap: wrap;
}

.nav-item {
  width: 300px;
  flex: 0 0 auto;
  min-height: 95px;
  padding: 25px 0;
  display: flex;
  align-items: center;
  gap: 15px;
  border-bottom: 1px solid #bebebe;
  cursor: pointer;
  transition: all 0.3s ease;
}

.nav-item:hover {
  background-color: #f9f9f9;
}

.nav-item:hover .nav-icon path {
  fill: #9c3940;
}

.nav-item.dropdown {
  position: relative;
}

.dropdown-menu {
  position: absolute;
  top: 100%;
  left: 0;
  background: white;
  border: 1px solid #e0e0e0;
  border-radius: 8px;
  box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
  z-index: 1000;
  min-width: 200px;
  padding: 8px 0;
  margin-top: 5px;
}

.dropdown-item {
  padding: 12px 20px;
  color: #3f3f3f;
  font-family: Inter, -apple-system, Roboto, Helvetica, sans-serif;
  font-size: 16px;
  font-weight: 500;
  cursor: pointer;
  transition: background-color 0.2s ease;
}

.dropdown-item:hover {
  background-color: #f5f5f5;
  color: #DA5761;
}

.nav-icon {
  width: 45px;
  height: 45px;
  flex-shrink: 0;
}

.nav-text {
  color: #3f3f3f;
  font-family: Inter, -apple-system, Roboto, Helvetica, sans-serif;
  font-size: 20px;
  font-weight: 700;
  line-height: 150%;
}

.action-buttons {
  display: flex;
  align-items: center;
  gap: 20px;
  width: 100%;
  max-width: 538px;
  min-height: 75px;
  flex-wrap: wrap;
  justify-content: center;
}

.login-button,
.contact-button {
  min-width: 200px;
  max-width: 260px;
  flex: 1;
  min-height: 75px;
  padding: 20px 40px 20px 40px;
  display: flex;
  justify-content: space-between;
  align-items: center;
  border-radius: 100px;
  border: none;
  cursor: pointer;
  transition: all 0.3s ease;
}

.login-button {
  background: #da5761;
}

.contact-button {
  background: #9c3940;
}

.login-button:hover {
  background: #c44950;
}

.contact-button:hover {
  opacity: 0.8;
}

.login-button span,
.contact-button span {
  color: #ffffff;
  font-family: Inter, -apple-system, Roboto, Helvetica, sans-serif;
  font-size: 20px;
  font-weight: 700;
  line-height: 150%;
}

.button-icon {
  width: 32px;
  height: 32px;
  flex-shrink: 0;
}

/* タブレット対応 (768px - 1150px) */
@media (max-width: 1150px) {
  .modal-container {
    margin: 0;
    min-height: auto;
  }

  .nav-grid {
    width: 850px;
  }

  .modal-header {
    padding: 15px 30px;
  }
  
  .logo-section {
    gap: 12px;
    height: auto;
    min-height: auto;
  }

  .nav-text {
    font-size: 18px;
  }

  .nav-icon {
    width: 35px;
    height: 35px;
  }
  
  .nav-row {
    min-height: 80px;
  }
  
  .nav-item {
    width: 250px;
  }
  
  .action-buttons {
    max-width: 100%;
    gap: 15px;
  }
  
  .login-button span,
  .contact-button span {
    font-size: 18px;
  }
}

/* タブレット対応 (768px - 1024px) */
@media (max-width: 1024px) {
  .nav-grid {
    width: 600px;
  }
  
  .nav-grid {
    width: 550px;
  }
}


@media (max-width: 800px) {
  .modal-header {
    padding: 12px 15px;
  }
}

@media (max-width: 650px) {
  .nav-grid {
    width: 470px;
  }

  .nav-row {
    gap: 30px;
  }

  .nav-text {
    font-size: 16px;
  }

  .login-button span,
  .contact-button span {
    font-size: 16px;
  }

  .nav-icon {
    width: 30px;
    height: 30px;
  }

  .nav-item {
    width: 220px;
  }

  .modal-container {
    gap: 30px;
    padding-bottom: 30px;
  }

}

@media (max-width: 600px) {
  .modal-header {
    padding: 10px 10px;
  }
}

@media (max-width: 550px) {
  .nav-grid {
    width: 100%;
  }
  
  .nav-row {
    width: 100%;
    gap: 10px;
  }

  .navigation-section {
    margin-top: 10px;
  }

  .nav-item {
    width: 100%;
    padding: 20px 0;
    min-height: 0px;
  }

  .action-buttons {
    width: 100%;
    display: flex;
    flex-wrap: nowrap;
    gap: 10px;
  }
  
  .login-button,
  .contact-button {
    flex: 1;
    width: auto;
    min-width: 50%;
    padding: 10px 20px;
    min-height: 60px;
  }
}

/* 小さいモバイル対応 (480px以下) */
@media (max-width: 480px) {
  .modal-container {
    gap: 0px;
  }

  .navigation-section {
    gap: 30px;
    padding: 0 30px;
  }
  
  .modal-header {
    padding: 8px 8px;
  }
  
  .nav-row {
    gap: 10px;
  }
  
  .action-buttons {
    flex-direction: column;
    gap: 15px;
  }
  
  .login-button,
  .contact-button {
    width: 100%;
    flex: none;
    min-height: 50px;
    min-width: 100%;
  }
  
}
</style>
