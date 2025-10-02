<template>
  <div class="application-form-page">
    <!-- Navigation -->
    <header class="navigation">
      <div class="company-logo">
        <img src="/img/ico-logo-1-1.svg" alt="Company Logo" class="logo-icon" />
        <div class="company-text">
          <div class="company-group">筑邦銀行グループ</div>
          <div class="company-name">株式会社ちくぎん地域経済研究所</div>
        </div>
      </div>
      <div class="nav-right">
        <div class="nav-top">
          <button class="phone-btn">
            <svg width="16" height="24" viewBox="0 0 16 24" fill="none" xmlns="http://www.w3.org/2000/svg">
              <path d="M13.3333 0H2.66667C1.95942 0 1.28115 0.270918 0.781049 0.753154C0.280952 1.23539 0 1.88944 0 2.57143V21.4286C0 22.1106 0.280952 22.7646 0.781049 23.2468C1.28115 23.7291 1.95942 24 2.66667 24H13.3333C14.0406 24 14.7189 23.7291 15.219 23.2468C15.719 22.7646 16 22.1106 16 21.4286V2.57143C16 1.88944 15.719 1.23539 15.219 0.753154C14.7189 0.270918 14.0406 0 13.3333 0ZM2.66667 1.71429H13.3333C13.5691 1.71429 13.7952 1.80459 13.9619 1.96534C14.1286 2.12608 14.2222 2.3441 14.2222 2.57143V3.42857H1.77778V2.57143C1.77778 2.3441 1.87143 2.12608 2.03813 1.96534C2.20483 1.80459 2.43092 1.71429 2.66667 1.71429ZM13.3333 22.2857H2.66667C2.43092 22.2857 2.20483 22.1954 2.03813 22.0347C1.87143 21.8739 1.77778 21.6559 1.77778 21.4286V20.5714H14.2222V21.4286C14.2222 21.6559 14.1286 21.8739 13.9619 22.0347C13.7952 22.1954 13.5691 22.2857 13.3333 22.2857Z" fill="white"/>
            </svg>
            <div class="phone-info">
              <div class="phone-number">0942-46-5081</div>
              <div class="phone-hours">（平日 9:00～17:00）</div>
            </div>
          </button>
          <button class="login-btn">ログイン</button>
          <button class="menu-btn">
            <svg width="26" height="24" viewBox="0 0 26 24" fill="none" xmlns="http://www.w3.org/2000/svg">
              <path d="M2 2H24" stroke="#1A1A1A" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"/>
              <path d="M2 12H24" stroke="#1A1A1A" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"/>
              <path d="M2 22H24" stroke="#1A1A1A" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"/>
            </svg>
          </button>
        </div>
        <nav class="nav-links">
          <router-link to="/" class="nav-link">トップ</router-link>
          <div class="nav-separator">|</div>
          <router-link to="/services" class="nav-link">会員サービスのご案内</router-link>
          <div class="nav-separator">|</div>
          <router-link to="/company" class="nav-link">会社概要</router-link>
          <div class="nav-separator">|</div>
          <router-link to="/news" class="nav-link">お知らせ</router-link>
          <div class="nav-separator">|</div>
          <router-link to="/faq" class="nav-link">よくあるご質問</router-link>
        </nav>
      </div>
    </header>

    <!-- Breadcrumb -->
    <div class="breadcrumb">
      <div class="breadcrumb-container">
        <span class="breadcrumb-item">トップ</span>
        <span class="breadcrumb-separator">></span>
        <span class="breadcrumb-item">お申込み</span>
      </div>
    </div>

    <!-- Hero Section -->
    <section class="hero-section">
      <div class="hero-overlay">
        <div class="hero-content">
          <div class="hero-title-en">APPLY</div>
          <div class="hero-title-jp">お申込み</div>
        </div>
      </div>
    </section>

    <!-- Form Section -->
    <section class="form-section">
      <div class="form-container">
        <div class="form-header">
          <h1 class="form-title">お申込み</h1>
          <div class="form-divider">
            <svg width="224" height="30" viewBox="0 0 224 30" fill="none" xmlns="http://www.w3.org/2000/svg">
              <path d="M0.5 15H69.5" stroke="#DA5761" stroke-width="2"/>
              <text fill="#DA5761" x="84.5" y="22.2727" font-family="Inter" font-size="20" font-weight="bold">apply</text>
              <path d="M154.5 15H223.5" stroke="#DA5761" stroke-width="2"/>
            </svg>
          </div>
          <div class="form-steps">
            <span class="step-active">①お客様情報の入力</span>
            <span class="step-inactive">- ② 記入内容のご確認</span>
            <span class="step-inactive">- ③ 完了</span>
          </div>
        </div>

        <form class="application-form" @submit.prevent="submitForm">
          <!-- Member Type -->
          <div class="form-field">
            <label class="field-label">会員タイプ</label>
            <div class="field-input">
              <select v-model="formData.memberType" class="select-field">
                <option value="">選択してください</option>
                <option value="standard">スタンダード会員</option>
                <option value="premium">プレミアムネット会員</option>
              </select>
              <svg class="select-arrow" width="24" height="25" viewBox="0 0 24 25" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M20.0312 10.0306L12.5312 17.5306C12.4616 17.6004 12.3788 17.6557 12.2878 17.6934C12.1967 17.7312 12.0992 17.7506 12.0006 17.7506C11.902 17.7506 11.8044 17.7312 11.7134 17.6934C11.6223 17.6557 11.5396 17.6004 11.47 17.5306L3.96997 10.0306C3.86496 9.92573 3.79343 9.79204 3.76444 9.64648C3.73546 9.50092 3.75031 9.35002 3.80712 9.21291C3.86394 9.07579 3.96016 8.95861 4.0836 8.87621C4.20705 8.79381 4.35217 8.74988 4.50059 8.75H19.5006C19.649 8.74988 19.7941 8.79381 19.9176 8.87621C20.041 8.95861 20.1372 9.07579 20.1941 9.21291C20.2509 9.35002 20.2657 9.50092 20.2367 9.64648C20.2078 9.79204 20.1362 9.92573 20.0312 10.0306Z" fill="#727272"/>
              </svg>
            </div>
          </div>

          <!-- Name -->
          <div class="form-field">
            <label class="field-label">お名前</label>
            <div class="field-input">
              <div class="name-inputs">
                <input v-model="formData.lastName" type="text" placeholder="性" class="text-input" />
                <input v-model="formData.firstName" type="text" placeholder="名" class="text-input" />
              </div>
            </div>
          </div>

          <!-- Furigana -->
          <div class="form-field">
            <label class="field-label">ふりがな（全角ひらがな）</label>
            <div class="field-input">
              <div class="name-inputs">
                <input v-model="formData.lastNameKana" type="text" placeholder="せい" class="text-input" />
                <input v-model="formData.firstNameKana" type="text" placeholder="めい" class="text-input" />
              </div>
            </div>
          </div>

          <!-- Company Name -->
          <div class="form-field">
            <label class="field-label">会社名</label>
            <div class="field-input">
              <input v-model="formData.companyName" type="text" class="text-input single" />
            </div>
          </div>

          <!-- Position -->
          <div class="form-field">
            <label class="field-label">役職</label>
            <div class="field-input">
              <input v-model="formData.position" type="text" class="text-input single" />
            </div>
          </div>

          <!-- Phone -->
          <div class="form-field">
            <label class="field-label">電話番号</label>
            <div class="field-input">
              <input v-model="formData.phone" type="tel" class="text-input single" />
            </div>
          </div>

          <!-- Email -->
          <div class="form-field">
            <label class="field-label">メールアドレス</label>
            <div class="field-input">
              <input v-model="formData.email" type="email" class="text-input single" />
            </div>
          </div>

          <!-- Notes -->
          <div class="form-field">
            <label class="field-label">備考</label>
            <div class="field-input">
              <textarea v-model="formData.notes" class="textarea-input"></textarea>
            </div>
          </div>

          <!-- Terms Section -->
          <div class="terms-section">
            <div class="terms-content">
              <div class="terms-article">
                <h3 class="terms-title">第1条(目的）</h3>
                <p class="terms-text">「ちくさん地域経済クラブ」（以下、「本会」という）は、株式会社ちくさん地域経済研究所（以下、「当社」という）が運営するサービスであり、産・食・学・金（金機関）のネットワーク菜や会員相互の交流等を通じて、企業務営等に役立つ様々な情報や機会提供により、会員企業等がともに発展し、ひいては地域の振興・発展に買することを目的とします。</p>
              </div>

              <div class="terms-article">
                <h3 class="terms-title">第2条(会員）</h3>
                <p class="terms-text">本規約を了承のうえ当社所定の形式により入会の手続きをされた法人およびそれに準ずる団体、個人事業主または個人のうち、当社が会員入会を承認した方を本会の会員とします（以下、会員入会を承認した法人およびそれに準ずる団体または個人事業主の方を「法人会員」、会員入会を承認した個人を「個人会員」という）。なお、法人会員は、複数口の入会が可能です。会員は、会員資格を第三者に利用させたり、貸与、譲渡、売買、質入等をすることはできないものとします。</p>
              </div>

              <div class="terms-article">
                <h3 class="terms-title">第3条(会員種別および会員サービス）</h3>
                <p class="terms-text">本会の会員は、スタンダード会員とプレミアムネット会員の2種類とし、その会員種別に応じた次のサービス（以下、「会員サービス」という）を利用できるものとします。</p>

                <h4 class="service-title">【スタンダード会員】</h4>
                <div class="service-item">
                  <span class="service-number">①</span>
                  <span class="service-text">機関誌「ちくぎん地域経済レポート」等、当社が発行する刊行物並びにダイレクトメール、E-Mail等による経済、産業、企業動向等、企業経営に役立つ情報サービス</span>
                </div>
                <div class="service-item">
                  <span class="service-number">②</span>
                  <span class="service-text">各種の経営相談に対する課題解決に向けた提案（相談の内容によっては有料）</span>
                </div>
                <div class="service-item">
                  <span class="service-number">③</span>
                  <span class="service-text">当社主催の各種セミナー、企画、イベント等の割引料金による案内</span>
                </div>
                <div class="service-item">
                  <span class="service-number">④</span>
                  <span class="service-text">当社が運営するインターネットサイト（スタンダード会員サイト）の利用</span>
                </div>

                <h4 class="service-title">【プレミアムネット会員】</h4>
                <p class="service-subtitle">スタンダード会員が利用できる上記①から④までのサービスに加えて</p>
                <div class="service-item">
                  <span class="service-number">⑤</span>
                  <span class="service-text">ブレミアムネット会員専用インターネットサイト（販路拡大等を目的としたビジネスマッチングサービスを含む）による企業経営に役当つ各種ビジネス情報の提供</span>
                </div>
                <div class="service-item">
                  <span class="service-number">⑥</span>
                  <span class="service-text">会員企業PR情報掲歳サービス</span>
                </div>
                <div class="service-item">
                  <span class="service-number">⑦</span>
                  <span class="service-text">日経BP発刊「日経トップリーダー」の送付。同社主催の「日経トップリーダー 経営セミナー」の案内</span>
                </div>
              </div>
            </div>

            <div class="terms-download">
              <a href="/pdf/chikugin_kiyaku2.pdf" class="download-link" target="_blank">
                会員規約をPDFでダウンロード
                <svg width="30" height="30" viewBox="0 0 30 30" fill="none" xmlns="http://www.w3.org/2000/svg">
                  <path d="M15 2.8125C12.5895 2.8125 10.2332 3.52728 8.22899 4.86646C6.22477 6.20564 4.66267 8.10907 3.74022 10.336C2.81778 12.563 2.57643 15.0135 3.04668 17.3777C3.51694 19.7418 4.67769 21.9134 6.38214 23.6179C8.08659 25.3223 10.2582 26.4831 12.6223 26.9533C14.9865 27.4236 17.437 27.1822 19.664 26.2598C21.8909 25.3373 23.7944 23.7752 25.1335 21.771C26.4727 19.7668 27.1875 17.4105 27.1875 15C27.1841 11.7687 25.899 8.67076 23.6141 6.3859C21.3292 4.10104 18.2313 2.81591 15 2.8125ZM18.4758 15.6633L13.7883 20.3508C13.7012 20.4379 13.5978 20.507 13.484 20.5541C13.3702 20.6013 13.2482 20.6255 13.125 20.6255C13.0018 20.6255 12.8798 20.6013 12.766 20.5541C12.6522 20.507 12.5488 20.4379 12.4617 20.3508C12.3746 20.2637 12.3055 20.1603 12.2584 20.0465C12.2112 19.9327 12.187 19.8107 12.187 19.6875C12.187 19.5643 12.2112 19.4423 12.2584 19.3285C12.3055 19.2147 12.3746 19.1113 12.4617 19.0242L16.4871 15L12.4617 10.9758C12.2858 10.7999 12.187 10.5613 12.187 10.3125C12.187 10.0637 12.2858 9.82513 12.4617 9.64922C12.6376 9.47331 12.8762 9.37448 13.125 9.37448C13.3738 9.37448 13.6124 9.47331 13.7883 9.64922L18.4758 14.3367C18.563 14.4238 18.6321 14.5272 18.6793 14.641C18.7265 14.7548 18.7507 14.8768 18.7507 15C18.7507 15.1232 18.7265 15.2452 18.6793 15.359C18.6321 15.4728 18.563 15.5762 18.4758 15.6633Z" fill="#DA5761"/>
                </svg>
              </a>
            </div>

            <div class="privacy-section">
              <p class="privacy-text">弊社のプライバシーポリシー（個人情報保護方針）に同意をされる場合は、下記のチェックボックスにチェックを入れてください。</p>
              <div class="privacy-checkbox">
                <input type="checkbox" id="privacy-agree" v-model="formData.privacyAgreement" />
                <label for="privacy-agree">個人情報保護方針に同意します</label>
              </div>
            </div>

            <div class="submit-section">
              <button type="submit" class="submit-btn" :disabled="!canSubmit">
                確認する
                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                  <rect x="0.5" y="0.5" width="23" height="23" rx="5" fill="white"/>
                  <path d="M18.0302 12.4415L13.5581 17.0412C13.4649 17.1371 13.3384 17.191 13.2066 17.191C13.0747 17.191 12.9482 17.1371 12.855 17.0412C12.7618 16.9453 12.7094 16.8152 12.7094 16.6796C12.7094 16.544 12.7618 16.4139 12.855 16.318L16.4793 12.5909L6.7469 12.5909C6.61511 12.5909 6.48872 12.5371 6.39554 12.4413C6.30235 12.3454 6.25 12.2154 6.25 12.0799C6.25 11.9443 6.30235 11.8143 6.39554 11.7185C6.48872 11.6226 6.61511 11.5688 6.7469 11.5688L16.4793 11.5688L12.855 7.84171C12.7618 7.74581 12.7094 7.61574 12.7094 7.48012C12.7094 7.34449 12.7618 7.21443 12.855 7.11853C12.9482 7.02263 13.0747 6.96875 13.2066 6.96875C13.3384 6.96875 13.4649 7.02263 13.5581 7.11853L18.0302 11.7183C18.0764 11.7657 18.113 11.8221 18.138 11.8841C18.1631 11.9462 18.1759 12.0127 18.1759 12.0799C18.1759 12.147 18.1631 12.2135 18.138 12.2756C18.113 12.3376 18.0764 12.394 18.0302 12.4415Z" fill="#1A1A1A"/>
                </svg>
              </button>
            </div>
          </div>
        </form>
      </div>
    </section>

    <!-- Floating Action Buttons -->
    <div class="floating-buttons">
      <button class="floating-btn login-floating">
        <svg width="26" height="25" viewBox="0 0 26 25" fill="none" xmlns="http://www.w3.org/2000/svg">
          <path d="M25.8666 24.5001C25.7789 24.6521 25.6526 24.7783 25.5005 24.8661C25.3484 24.9539 25.1759 25 25.0003 25H0.99903C0.82357 24.9998 0.651246 24.9535 0.499362 24.8657C0.347477 24.7778 0.221377 24.6516 0.133726 24.4996C0.0460738 24.3477 -4.50128e-05 24.1753 3.29665e-08 23.9999C4.50787e-05 23.8245 0.0462524 23.6521 0.133982 23.5002C2.03784 20.2094 4.97175 17.8497 8.39569 16.7311C6.70205 15.7231 5.3862 14.1871 4.65021 12.359C3.91422 10.5309 3.79879 8.51174 4.32164 6.6117C4.84448 4.71165 5.9767 3.03573 7.54442 1.84131C9.11214 0.646882 11.0287 0 12.9997 0C14.9707 0 16.8872 0.646882 18.455 1.84131C20.0227 3.03573 21.1549 4.71165 21.6777 6.6117C22.2006 8.51174 22.0852 10.5309 21.3492 12.359C20.6132 14.1871 19.2973 15.7231 17.6037 16.7311C21.0276 17.8497 23.9615 20.2094 25.8654 23.5002C25.9534 23.6521 25.9998 23.8245 26 24C26.0002 24.1755 25.9542 24.348 25.8666 24.5001Z" fill="white"/>
        </svg>
        <span>ログイン</span>
      </button>
      <button class="floating-btn contact-floating">
        <svg width="18" height="28" viewBox="0 0 18 28" fill="none" xmlns="http://www.w3.org/2000/svg">
          <path d="M15 0H3C2.20435 0 1.44129 0.316071 0.87868 0.87868C0.316071 1.44129 0 2.20435 0 3V25C0 25.7956 0.316071 26.5587 0.87868 27.1213C1.44129 27.6839 2.20435 28 3 28H15C15.7956 28 16.5587 27.6839 17.1213 27.1213C17.6839 26.5587 18 25.7956 18 25V3C18 2.20435 17.6839 1.44129 17.1213 0.87868C16.5587 0.316071 15.7956 0 15 0ZM3 2H15C15.2652 2 15.5196 2.10536 15.7071 2.29289C15.8946 2.48043 16 2.73478 16 3V4H2V3C2 2.73478 2.10536 2.48043 2.29289 2.29289C2.48043 2.10536 2.73478 2 3 2ZM15 26H3C2.73478 26 2.48043 25.8946 2.29289 25.7071C2.10536 25.5196 2 25.2652 2 25V24H16V25C16 25.2652 15.8946 25.5196 15.7071 25.7071C15.5196 25.8946 15.2652 26 15 26Z" fill="white"/>
        </svg>
        <span>お問い合わせ</span>
      </button>
    </div>

    <!-- Access Section -->
    <AccessSection />

    <!-- Footer -->
    <footer class="footer">
      <div class="footer-content">
        <div class="footer-nav">
          <div class="footer-column">
            <a href="/" class="footer-link">
              <svg width="10" height="18" viewBox="0 0 10 18" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M0.329269 15.3301C0.117926 15.5414 -0.000806808 15.8281 -0.000806808 16.127C-0.000806808 16.4258 0.117926 16.7125 0.329269 16.9238C0.540614 17.1352 0.827258 17.2539 1.12614 17.2539C1.42503 17.2539 1.71168 17.1352 1.92302 16.9238L9.42302 9.42383C9.5279 9.31931 9.61112 9.19512 9.6679 9.05837C9.72468 8.92163 9.75391 8.77502 9.75391 8.62695C9.75391 8.47889 9.72468 8.33228 9.6679 8.19553C9.61112 8.05878 9.5279 7.93459 9.42302 7.83008L1.92302 0.330076C1.71168 0.118732 1.42503 -4.45375e-09 1.12614 0C0.827258 4.45375e-09 0.540614 0.118732 0.329269 0.330077C0.117926 0.541421 -0.000806808 0.828065 -0.000806808 1.12695C-0.000806808 1.42584 0.117926 1.71248 0.329269 1.92383L7.03146 8.62601L0.329269 15.3301Z" fill="#DA5761"/>
              </svg>
              トップ
            </a>
            <a href="/company" class="footer-link">
              <svg width="10" height="18" viewBox="0 0 10 18" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M0.329269 15.3301C0.117926 15.5414 -0.000806808 15.8281 -0.000806808 16.127C-0.000806808 16.4258 0.117926 16.7125 0.329269 16.9238C0.540614 17.1352 0.827258 17.2539 1.12614 17.2539C1.42503 17.2539 1.71168 17.1352 1.92302 16.9238L9.42302 9.42383C9.5279 9.31931 9.61112 9.19512 9.6679 9.05837C9.72468 8.92163 9.75391 8.77502 9.75391 8.62695C9.75391 8.47889 9.72468 8.33228 9.6679 8.19553C9.61112 8.05878 9.5279 7.93459 9.42302 7.83008L1.92302 0.330076C1.71168 0.118732 1.42503 -4.45375e-09 1.12614 0C0.827258 4.45375e-09 0.540614 0.118732 0.329269 0.330077C0.117926 0.541421 -0.000806808 0.828065 -0.000806808 1.12695C-0.000806808 1.42584 0.117926 1.71248 0.329269 1.92383L7.03146 8.62601L0.329269 15.3301Z" fill="#DA5761"/>
              </svg>
              会社概要
            </a>
            <a href="/news" class="footer-link">
              <svg width="10" height="18" viewBox="0 0 10 18" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M0.329269 15.3301C0.117926 15.5414 -0.000806808 15.8281 -0.000806808 16.127C-0.000806808 16.4258 0.117926 16.7125 0.329269 16.9238C0.540614 17.1352 0.827258 17.2539 1.12614 17.2539C1.42503 17.2539 1.71168 17.1352 1.92302 16.9238L9.42302 9.42383C9.5279 9.31931 9.61112 9.19512 9.6679 9.05837C9.72468 8.92163 9.75391 8.77502 9.75391 8.62695C9.75391 8.47889 9.72468 8.33228 9.6679 8.19553C9.61112 8.05878 9.5279 7.93459 9.42302 7.83008L1.92302 0.330076C1.71168 0.118732 1.42503 -4.45375e-09 1.12614 0C0.827258 4.45375e-09 0.540614 0.118732 0.329269 0.330077C0.117926 0.541421 -0.000806808 0.828065 -0.000806808 1.12695C-0.000806808 1.42584 0.117926 1.71248 0.329269 1.92383L7.03146 8.62601L0.329269 15.3301Z" fill="#DA5761"/>
              </svg>
              お知らせ
            </a>
            <a href="/faq" class="footer-link">
              <svg width="10" height="18" viewBox="0 0 10 18" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M0.329269 15.3301C0.117926 15.5414 -0.000806808 15.8281 -0.000806808 16.127C-0.000806808 16.4258 0.117926 16.7125 0.329269 16.9238C0.540614 17.1352 0.827258 17.2539 1.12614 17.2539C1.42503 17.2539 1.71168 17.1352 1.92302 16.9238L9.42302 9.42383C9.5279 9.31931 9.61112 9.19512 9.6679 9.05837C9.72468 8.92163 9.75391 8.77502 9.75391 8.62695C9.75391 8.47889 9.72468 8.33228 9.6679 8.19553C9.61112 8.05878 9.5279 7.93459 9.42302 7.83008L1.92302 0.330076C1.71168 0.118732 1.42503 -4.45375e-09 1.12614 0C0.827258 4.45375e-09 0.540614 0.118732 0.329269 0.330077C0.117926 0.541421 -0.000806808 0.828065 -0.000806808 1.12695C-0.000806808 1.42584 0.117926 1.71248 0.329269 1.92383L7.03146 8.62601L0.329269 15.3301Z" fill="#DA5761"/>
              </svg>
              よくあるご質問
            </a>
            <a href="/contact" class="footer-link">
              <svg width="10" height="18" viewBox="0 0 10 18" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M0.329269 15.3301C0.117926 15.5414 -0.000806808 15.8281 -0.000806808 16.127C-0.000806808 16.4258 0.117926 16.7125 0.329269 16.9238C0.540614 17.1352 0.827258 17.2539 1.12614 17.2539C1.42503 17.2539 1.71168 17.1352 1.92302 16.9238L9.42302 9.42383C9.5279 9.31931 9.61112 9.19512 9.6679 9.05837C9.72468 8.92163 9.75391 8.77502 9.75391 8.62695C9.75391 8.47889 9.72468 8.33228 9.6679 8.19553C9.61112 8.05878 9.5279 7.93459 9.42302 7.83008L1.92302 0.330076C1.71168 0.118732 1.42503 -4.45375e-09 1.12614 0C0.827258 4.45375e-09 0.540614 0.118732 0.329269 0.330077C0.117926 0.541421 -0.000806808 0.828065 -0.000806808 1.12695C-0.000806808 1.42584 0.117926 1.71248 0.329269 1.92383L7.03146 8.62601L0.329269 15.3301Z" fill="#DA5761"/>
              </svg>
              お問い合わせ
            </a>
          </div>
          <!-- Add more footer columns as needed -->
        </div>
        <div class="footer-divider"></div>
        <div class="footer-bottom">
          <div class="company-info">
            <div class="company-logo-footer">
              <img src="/img/ico-logo-1-1.svg" alt="Company Logo" class="logo-icon-footer" />
              <div class="company-text-footer">
                <div class="company-name-footer">株式会社ちくぎん地域経済研究所</div>
                <div class="company-group-footer">筑邦銀行グループ</div>
              </div>
            </div>
            <div class="company-details">
              <div>〒839-0864　福岡県久留米市百年公園1番1号 久留米リサーチセンタービル6階</div>
              <div>電話番号. 0942-46-5081　営業時間. 平日9:00-17:00</div>
            </div>
          </div>
          <div class="copyright">
            Copyright (C) The Chikugin Reserch Institute For Regional Economy Co.,Ltd All rights reserved.
          </div>
        </div>
      </div>
    </footer>
  </div>
</template>

<script>
import AccessSection from './AccessSection.vue';

export default {
  name: 'ApplicationFormPage',
  components: {
    AccessSection
  },
  data() {
    return {
      formData: {
        memberType: '',
        lastName: '',
        firstName: '',
        lastNameKana: '',
        firstNameKana: '',
        companyName: '',
        position: '',
        phone: '',
        email: '',
        notes: '',
        privacyAgreement: false
      }
    };
  },
  computed: {
    canSubmit() {
      return this.formData.memberType &&
             this.formData.lastName &&
             this.formData.firstName &&
             this.formData.email &&
             this.formData.privacyAgreement;
    }
  },
  methods: {
    submitForm() {
      if (this.canSubmit) {
        // Handle form submission
        console.log('Form submitted:', this.formData);
        // Navigate to confirmation page or show success message
      }
    }
  }
};
</script>

<style scoped>
.application-form-page {
  background-color: #ECECEC;
  min-height: 100vh;
  font-family: 'Inter', -apple-system, Roboto, Helvetica, sans-serif;
}

/* Navigation */
.navigation {
  display: flex;
  justify-content: space-between;
  align-items: center;
  padding: 10px 20px;
  background: #FFF;
  box-shadow: 0 3px 20px rgba(0, 0, 0, 0.15);
  height: 88px;
}

.company-logo {
  display: flex;
  align-items: center;
  gap: 20px;
}

.logo-icon {
  width: 54px;
  height: 42px;
}

.company-text {
  display: flex;
  flex-direction: column;
  gap: -5px;
}

.company-group {
  font-size: 15px;
  font-weight: 700;
  color: #1A1A1A;
}

.company-name {
  font-size: 24px;
  font-weight: 700;
  color: #1A1A1A;
}

.nav-right {
  display: flex;
  flex-direction: column;
  gap: 10px;
  width: 541px;
}

.nav-top {
  display: flex;
  align-items: center;
  gap: 15px;
  justify-content: flex-end;
}

.phone-btn, .login-btn {
  display: flex;
  align-items: center;
  gap: 5px;
  padding: 0 20px;
  height: 40px;
  border-radius: 8px;
  background: #1A1A1A;
  color: #FFF;
  border: none;
  cursor: pointer;
  font-weight: 700;
  font-size: 13px;
}

.phone-info {
  display: flex;
  flex-direction: column;
}

.phone-number {
  font-size: 13px;
  font-weight: 700;
}

.phone-hours {
  font-size: 10px;
  font-weight: 700;
}

.menu-btn {
  width: 26px;
  height: 24px;
  background: none;
  border: none;
  cursor: pointer;
}

.nav-links {
  display: flex;
  align-items: center;
  gap: 15px;
}

.nav-link {
  font-size: 15px;
  font-weight: 700;
  color: #1A1A1A;
  text-decoration: none;
}

.nav-separator {
  color: #1A1A1A;
}

/* Breadcrumb */
.breadcrumb {
  padding: 24px 46px;
  height: 60px;
  display: flex;
  align-items: center;
}

.breadcrumb-container {
  display: flex;
  align-items: center;
  gap: 8px;
}

.breadcrumb-item {
  font-size: 12px;
  font-weight: 400;
  color: #3F3F3F;
}

.breadcrumb-separator {
  font-size: 12px;
  color: #3F3F3F;
}

/* Hero Section */
.hero-section {
  height: 396px;
  background: url('https://api.builder.io/api/v1/image/assets/TEMP/53cc5489ed3a3ad5de725cbc506b45ae898146f0?width=2880') lightgray 0px -131.583px / 100% 242.424% no-repeat;
  box-shadow: 0 4px 4px rgba(0, 0, 0, 0.25);
  position: relative;
}

.hero-overlay {
  width: 100%;
  height: 100%;
  background: rgba(77, 77, 77, 0.70);
  display: flex;
  align-items: flex-end;
  padding: 50px 30px;
}

.hero-content {
  display: flex;
  flex-direction: column;
  gap: 10px;
}

.hero-title-en {
  font-size: 24px;
  font-weight: 700;
  color: #FFF;
  letter-spacing: -0.48px;
}

.hero-title-jp {
  font-size: 40px;
  font-weight: 700;
  color: #FFF;
  letter-spacing: -0.8px;
}

/* Form Section */
.form-section {
  padding: 70px 50px 0;
}

.form-container {
  display: flex;
  flex-direction: column;
  gap: 40px;
  max-width: 1340px;
  margin: 0 auto;
}

.form-header {
  display: flex;
  flex-direction: column;
  align-items: center;
  gap: 29px;
}

.form-title {
  font-size: 36px;
  font-weight: 700;
  color: #1A1A1A;
  letter-spacing: -0.72px;
  text-align: center;
}

.form-divider {
  display: flex;
  justify-content: center;
  align-items: center;
}

.form-steps {
  text-align: center;
  font-size: 18px;
  letter-spacing: -0.36px;
  max-width: 1014px;
}

.step-active {
  font-weight: 700;
  color: #1A1A1A;
}

.step-inactive {
  font-weight: 400;
  color: #727272;
}

/* Form Fields */
.application-form {
  padding: 50px;
  border-radius: 20px;
  background: #FFF;
}

.form-field {
  display: flex;
  justify-content: space-between;
  align-items: center;
  padding: 20px 0;
  border-top: 0.5px dashed #727272;
  border-bottom: 0.5px dashed #727272;
}

.field-label {
  font-size: 18px;
  font-weight: 700;
  color: #3F3F3F;
  line-height: 150%;
  min-width: 320px;
}

.field-input {
  width: 920px;
  position: relative;
}

.select-field {
  width: 100%;
  padding: 15px;
  border-radius: 10px;
  background: #FFF;
  border: 1px solid #E0E0E0;
  font-size: 18px;
  color: #727272;
  appearance: none;
}

.select-arrow {
  position: absolute;
  right: 15px;
  top: 50%;
  transform: translateY(-50%);
  pointer-events: none;
}

.name-inputs {
  display: flex;
  flex-direction: column;
  gap: 10px;
}

.text-input {
  padding: 15px;
  border-radius: 10px;
  background: #FFF;
  border: 1px solid #E0E0E0;
  font-size: 18px;
  color: #1A1A1A;
}

.text-input::placeholder {
  color: #CFCFCF;
}

.text-input.single {
  width: 100%;
  height: 57px;
}

.textarea-input {
  width: 100%;
  height: 250px;
  padding: 15px;
  border-radius: 10px;
  background: #FFF;
  border: 1px solid #E0E0E0;
  font-size: 18px;
  color: #1A1A1A;
  resize: vertical;
  font-family: inherit;
}

/* Terms Section */
.terms-section {
  padding-top: 30px;
  display: flex;
  flex-direction: column;
  align-items: center;
  gap: 30px;
}

.terms-content {
  padding: 30px;
  border-radius: 20px;
  background: #CFCFCF;
  width: 100%;
  display: flex;
  flex-direction: column;
  gap: 30px;
  max-height: 400px;
  overflow-y: auto;
}

.terms-article {
  display: flex;
  flex-direction: column;
  gap: 10px;
}

.terms-title {
  font-size: 18px;
  font-weight: 700;
  color: #1A1A1A;
}

.terms-text {
  font-size: 18px;
  font-weight: 400;
  color: #1A1A1A;
  line-height: 1.6;
}

.service-title {
  font-size: 18px;
  font-weight: 700;
  color: #1A1A1A;
  margin-top: 10px;
}

.service-subtitle {
  font-size: 18px;
  font-weight: 400;
  color: #1A1A1A;
}

.service-item {
  display: flex;
  align-items: flex-start;
  gap: 5px;
  margin-top: 5px;
}

.service-number {
  font-size: 14px;
  font-weight: 400;
  color: #1A1A1A;
  min-width: 21px;
}

.service-text {
  font-size: 18px;
  font-weight: 400;
  color: #1A1A1A;
  flex: 1;
}

.terms-download {
  display: flex;
  align-items: flex-start;
  gap: 10px;
}

.download-link {
  display: flex;
  align-items: center;
  gap: 5px;
  padding-bottom: 5px;
  border-bottom: 1px solid #DA5761;
  color: #3F3F3F;
  text-decoration: none;
  font-size: 18px;
  font-weight: 400;
}

.privacy-section {
  display: flex;
  flex-direction: column;
  justify-content: center;
  align-items: center;
  gap: 10px;
  width: 100%;
}

.privacy-text {
  font-size: 18px;
  font-weight: 400;
  color: #1A1A1A;
  text-align: center;
}

.privacy-checkbox {
  display: flex;
  align-items: center;
  gap: 10px;
}

.privacy-checkbox input[type="checkbox"] {
  width: 16px;
  height: 16px;
}

.privacy-checkbox label {
  font-size: 18px;
  font-weight: 400;
  color: #1A1A1A;
}

.submit-section {
  padding-top: 30px;
  display: flex;
  flex-direction: column;
  align-items: flex-start;
  gap: 10px;
}

.submit-btn {
  display: flex;
  align-items: center;
  justify-content: center;
  gap: 20px;
  width: 500px;
  padding: 20px 100px;
  border-radius: 15px;
  background: #1A1A1A;
  color: #FFF;
  border: none;
  cursor: pointer;
  font-size: 20px;
  font-weight: 700;
  box-shadow: 0 1px 2px rgba(0, 0, 0, 0.05);
}

.submit-btn:disabled {
  opacity: 0.5;
  cursor: not-allowed;
}

/* Floating Action Buttons */
.floating-buttons {
  position: fixed;
  right: 20px;
  top: 50%;
  transform: translateY(-50%);
  display: flex;
  flex-direction: column;
  gap: 10px;
  z-index: 1000;
}

.floating-btn {
  width: 100px;
  height: 100px;
  border-radius: 50%;
  border: none;
  cursor: pointer;
  display: flex;
  flex-direction: column;
  align-items: center;
  justify-content: center;
  gap: 8px;
  font-size: 13px;
  font-weight: 700;
  color: #FFF;
}

.login-floating {
  background: #DA5761;
}

.contact-floating {
  background: #9C3940;
}

/* Access Section */

.access-container {
  display: flex;
  flex-direction: column;
  gap: 24px;
  max-width: 1340px;
  margin: 0 auto;
}

.access-line {
  position: absolute;
  left: 0;
  top: 18px;
  width: 91px;
  height: 1px;
  background: #DA5761;
}

.access-map {
  flex: 1;
}

.info-section {
  display: flex;
  flex-direction: column;
  gap: 10px;
}

.info-title {
  font-size: 20px;
  font-weight: 700;
  color: #DA5761;
}

.info-text {
  font-size: 16px;
  font-weight: 400;
  color: #1A1A1A;
  line-height: 200%;
}

/* Footer */
.footer {
  background: #CFCFCF;
  padding: 50px 100px;
}

.footer-content {
  display: flex;
  flex-direction: column;
  align-items: center;
  gap: 50px;
  max-width: 1240px;
  margin: 0 auto;
}

.footer-nav {
  display: flex;
  gap: 60px;
}

.footer-column {
  display: flex;
  flex-direction: column;
  gap: 20px;
}

.footer-link {
  display: flex;
  align-items: center;
  gap: 10px;
  color: #1A1A1A;
  text-decoration: none;
  font-size: 16px;
  font-weight: 400;
  padding: 0.746px 0;
}

.footer-divider {
  width: 100%;
  height: 1px;
  background: #B2B2B2;
}

.footer-bottom {
  display: flex;
  flex-direction: column;
  align-items: center;
  gap: 20px;
  width: 100%;
}

.company-info {
  display: flex;
  flex-direction: column;
  align-items: center;
  gap: 20px;
}

.company-logo-footer {
  display: flex;
  align-items: center;
  gap: 20px;
}

.logo-icon-footer {
  width: 54px;
  height: 42px;
}

.company-text-footer {
  display: flex;
  flex-direction: column;
  gap: -5px;
}

.company-name-footer {
  font-size: 24px;
  font-weight: 700;
  color: #1A1A1A;
}

.company-group-footer {
  font-size: 15px;
  font-weight: 700;
  color: #1A1A1A;
}

.company-details {
  text-align: center;
  font-size: 20px;
  font-weight: 400;
  color: #1A1A1A;
  line-height: 120%;
}

.copyright {
  font-size: 15px;
  font-weight: 400;
  color: #1A1A1A;
  text-align: center;
}

/* Responsive Design */
@media (max-width: 1200px) {
  .navigation {
    padding: 10px;
  }
  
  .form-section {
    padding: 50px 30px 0;
  }
  
  .application-form {
    padding: 30px;
  }
  
  .field-input {
    width: 100%;
  }
  
  .field-label {
    min-width: 200px;
  }

  .footer {
    padding: 50px 50px;
  }
}

@media (max-width: 768px) {
  .navigation {
    flex-direction: column;
    gap: 10px;
    height: auto;
    padding: 15px;
  }
  
  .nav-right {
    width: 100%;
  }
  
  .nav-links {
    flex-wrap: wrap;
  }
  
  .form-section {
    padding: 30px 20px 0;
  }
  
  .form-field {
    flex-direction: column;
    align-items: flex-start;
    gap: 15px;
  }
  
  .field-label {
    min-width: auto;
  }
  
  .submit-btn {
    width: 100%;
  }
  
  .floating-buttons {
    bottom: 20px;
    top: auto;
    transform: none;
    flex-direction: row;
    right: 50%;
    transform: translateX(50%);
  }
  
  .floating-btn {
    width: 80px;
    height: 80px;
    font-size: 11px;
  }

  .footer {
    padding: 30px 20px;
  }
  
  .footer-nav {
    flex-direction: column;
    gap: 30px;
  }
}
</style>
