<template>
  <AdminLayout>
    <div class="dashboard">
      <!-- ページヘッダー -->
      <div class="page-header">
        <div class="header-left">
          <button @click="goBack" class="back-btn">
            ← ダッシュボードに戻る
          </button>
          <h1 class="page-title">{{ isNew ? '新規ページ作成' : 'ページ編集' }}</h1>
        </div>
        <div class="header-actions">
          <button @click="handleLogout" class="logout-btn">ログアウト</button>
        </div>
      </div>

      <!-- フォームコンテナ -->
      <div class="form-container">
        <div v-if="loading" class="loading">読み込み中...</div>
        <div v-else-if="error && !isNew" class="error-container">
          <div class="error-message">{{ error }}</div>
        </div>
        <div v-else class="form-content">
          <form @submit.prevent="handleSubmit" class="edit-form">
            <!-- ページキー -->
            <div class="form-section">
              <div class="form-group">
                <label for="page_key" class="form-label">
                  ページキー <span class="required">*</span>
                </label>
                <input
                  id="page_key"
                  v-model="formData.page_key"
                  type="text"
                  :disabled="!isNew"
                  required
                  class="form-input"
                  :class="{ disabled: !isNew }"
                  placeholder="例: about, services, contact"
                />
                <p class="form-help">
                  {{ isNew ? 'URLの一部になります（例: /about）' : 'ページキーは編集できません' }}
                </p>
              </div>
            </div>

            <!-- タイトル -->
            <div class="form-section">
              <div class="form-group">
                <label for="title" class="form-label">
                  ページタイトル <span class="required">*</span>
                </label>
                <input
                  id="title"
                  v-model="formData.title"
                  type="text"
                  required
                  class="form-input"
                  placeholder="ページのタイトルを入力してください"
                />
              </div>
            </div>

            <!-- 画像管理セクションは削除（無効化） -->

            <!-- Company Profile: History repeater -->
            <div v-if="isCompanyProfile" class="form-group">
              <h3 class="section-title">沿革（history 配列）</h3>
              <div class="guide-actions">
                <button type="button" class="btn btn-primary" @click="addHistoryEntry">+ 年表を追加</button>
              </div>
              <div class="fields-editor">
                <div class="field-head">
                  <div>年</div><div>日付</div><div>本文（HTML可）</div><div>操作</div>
                </div>
                <div class="field-row" v-for="(h, hi) in (formData.content.history || [])" :key="`h-${hi}`">
                  <input v-model="h.year" type="text" class="form-input field-key" placeholder="例）2011" />
                  <input v-model="h.date" type="text" class="form-input field-key" placeholder="例）平成23年7月1日" />
                  <input v-model="h.body" type="text" class="form-input field-value" placeholder="本文（HTML可）" />
                  <div class="field-hint"><span class="counter">{{ (h.body||'').length }}</span></div>
                  <button type="button" class="btn btn-secondary small" @click="removeHistoryEntry(hi)">削除</button>
                </div>
              </div>
            </div>

            <!-- Financial Reports: Reports repeater with PDF upload -->
            <div v-if="isFinancialReports" class="form-group">
              <h3 class="section-title">決算報告（financial_reports 配列）</h3>
              <div class="guide-actions">
                <button type="button" class="btn btn-primary" @click="addReport">+ 年度を追加</button>
              </div>
              <div class="fields-editor" v-for="(r, ri) in (formData.content.financial_reports || [])" :key="`r-${ri}`" style="margin-bottom:16px;">
                <div class="field-row">
                  <input v-model="r.fiscal_year" type="text" class="form-input field-key" placeholder="例）2025年3月期" />
                  <input v-model="r.date_label" type="text" class="form-input field-value" placeholder="例）決算短信（2025年5月12日）" />
                  <button type="button" class="btn btn-secondary small" @click="removeReport(ri)">削除</button>
                </div>
                <div class="field-head" style="margin-top:8px"><div>項目ラベル</div><div>URL</div><div>操作</div></div>
                <div class="field-row" v-for="(it, ii) in (r.items || (r.items = []))" :key="`ri-${ri}-ii-${ii}`">
                  <input v-model="it.label" type="text" class="form-input field-key" placeholder="例）決算要旨（PDF：...）" />
                  <input v-model="it.url" type="text" class="form-input field-value" placeholder="/storage/media/xxx.pdf または https://..." />
                  <div class="field-hint">
                    <button type="button" class="btn btn-secondary small" @click="uploadReportItemPdf(ri, ii)">PDFアップ</button>
                  </div>
                  <button type="button" class="btn btn-secondary small" @click="removeReportItem(ri, ii)">削除</button>
                </div>
                <div class="guide-actions">
                  <button type="button" class="btn btn-primary" @click="addReportItem(ri)">+ 項目を追加</button>
                </div>
              </div>
            </div>

            <!-- コンテンツ -->
            <div class="form-section">
              <h3 class="section-title">コンテンツ</h3>
              <div class="mode-toggle">
                <!-- <label><input type="radio" value="wysiwyg" v-model="contentMode" /> エディタ（おすすめ）</label> -->
                <!-- <label><input type="radio" value="html" v-model="contentMode" /> HTML（全文編集）</label> -->
                <label><input type="radio" value="json" v-model="contentMode" /> JSON</label>
                <label><input type="radio" value="fields" v-model="contentMode" /> Fields（安全なテキスト上書き）</label>
                <!-- <label><input type="radio" value="inline" v-model="contentMode" /> ページ風プレビュー（直編集）</label> -->
                <!-- プレビュー（表示のみ）機能は無効化 -->
              </div>

              <!-- WYSIWYG editor -->
              <div v-if="contentMode==='wysiwyg'" class="form-group">
                <div class="editor-toolbar">
                  <button type="button" class="tb-btn" @click="execCmd('bold')"><b>B</b></button>
                  <button type="button" class="tb-btn" @click="execCmd('italic')"><i>I</i></button>
                  <button type="button" class="tb-btn" @click="execCmd('underline')"><u>U</u></button>
                  <span class="sep" />
                  <button type="button" class="tb-btn" @click="formatBlock('H1')">H1</button>
                  <button type="button" class="tb-btn" @click="formatBlock('H2')">H2</button>
                  <button type="button" class="tb-btn" @click="formatBlock('H3')">H3</button>
                  <span class="sep" />
                  <button type="button" class="tb-btn" @click="execCmd('insertUnorderedList')">• List</button>
                  <button type="button" class="tb-btn" @click="execCmd('insertOrderedList')">1. List</button>
                  <span class="sep" />
                  <button type="button" class="tb-btn" @click="insertLink()">Link</button>
                  <button type="button" class="tb-btn" @click="execCmd('removeFormat')">Clear</button>
                  <div class="flex-spacer" />
                  <label class="preview-toggle"><input type="checkbox" v-model="showPreview" /> プレビュー</label>
                </div>
                <div :class="['editor-split', showPreview ? 'with-preview' : '']">
                  <div 
                    class="wysiwyg-editor" 
                    ref="wysiwyg"
                    contenteditable
                    @input="onWysiwygInput"
                    v-html="contentHtml"
                  />
                  <div v-if="showPreview" class="wysiwyg-preview">
                    <div class="preview-inner" v-html="contentHtml"></div>
                  </div>
                </div>
                <p class="form-help">簡易エディタです。装飾は基本的な太字・見出し・リスト・リンクに対応しています。</p>
              </div>

              <div v-if="contentMode==='json'" class="form-group">
                <label for="content" class="form-label">
                  コンテンツ (JSON形式) <span class="required">*</span>
                </label>
                <div class="json-editor-container">
                  <textarea
                    id="content"
                    v-model="contentJson"
                    rows="15"
                    required
                    class="json-editor"
                    :class="{ error: jsonError }"
                    placeholder='{"key": "value", "title": "タイトル", "description": "説明文"}'
                  />
                  <div v-if="jsonError" class="json-error">
                    <svg width="16" height="16" viewBox="0 0 16 16" fill="currentColor">
                      <path d="M8 16A8 8 0 1 1 8 0a8 8 0 0 1 0 16zM7 3v4a1 1 0 0 0 2 0V3a1 1 0 0 0-2 0zm1 8a1 1 0 1 0 0-2 1 1 0 0 0 0 2z"/>
                    </svg>
                    {{ jsonError }}
                  </div>
                  <div v-else class="json-success">
                    <svg width="16" height="16" viewBox="0 0 16 16" fill="currentColor">
                      <path d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0zm-3.97-3.03a.75.75 0 0 0-1.08.022L7.477 9.417 5.384 7.323a.75.75 0 0 0-1.06 1.061L6.97 11.03a.75.75 0 0 0 1.079-.02l3.992-4.99a.75.75 0 0 0-.01-1.05z"/>
                    </svg>
                    JSONフォーマットが正しいです
                  </div>
                </div>
              </div>

              <div v-else-if="contentMode==='html'" class="form-group">
                <label for="content_html" class="form-label">
                  コンテンツ (HTML)
                </label>
                <textarea
                  id="content_html"
                  v-model="contentHtml"
                  rows="18"
                  class="html-editor"
                  placeholder="ここにHTMLを直接入力してください"
                />
                <label class="preview-inline"><input type="checkbox" v-model="showPreview" /> プレビューを表示</label>
                <div v-if="showPreview" class="html-preview" v-html="contentHtml"></div>
                <p class="form-help">危険なスクリプトは入れないでください（管理画面のみで使用、サニタイズは未実装）。</p>
              </div>

              <!-- Inline page-like editor -->
              <div v-else-if="contentMode==='inline'" class="form-group">
                <div class="inline-toolbar">
                  <button type="button" class="tb-btn" @click="execInline('bold')"><b>B</b></button>
                  <button type="button" class="tb-btn" @click="execInline('italic')"><i>I</i></button>
                  <button type="button" class="tb-btn" @click="execInline('underline')"><u>U</u></button>
                  <span class="sep" />
                  <button type="button" class="tb-btn" @click="formatInline('H1')">H1</button>
                  <button type="button" class="tb-btn" @click="formatInline('H2')">H2</button>
                  <button type="button" class="tb-btn" @click="formatInline('P')">P</button>
                  <span class="sep" />
                  <button type="button" class="tb-btn" @click="insertInlineLink()">Link</button>
                  <button type="button" class="tb-btn" @click="execInline('removeFormat')">Clear</button>
                </div>
                <div class="inline-surface">
                  <div class="inline-page">
                    <div class="inline-hero">ページヘッダー領域（プレビュー）</div>
                    <div class="inline-body cms-body">
                      <div 
                        ref="inlineEditor" 
                        class="inline-editor" 
                        contenteditable 
                        v-html="contentHtml"
                        @input="onInlineInput"
                      />
                    </div>
                    <div class="inline-footer">フッター領域（プレビュー）</div>
                  </div>
                </div>
                <p class="form-help">実際のサイト風にレイアウトして、本文をその場で編集できます。保存でこの内容が反映されます。</p>
              </div>
              <!-- Live page preview (iframe) は無効化 -->

              <!-- Fields mode: key-value editor for content.texts -->
              <div v-else class="form-group">
                <label class="form-label">
                  テキストフィールド（content.texts）
                </label>
                <div class="fields-guide">
                  <div class="guide-title">使い方（初心者向けの簡単ステップ）</div>
                  <ol>
                    <li>推奨キーを見て、不足があれば「不足キーを追加」を押す</li>
                    <li>各行の「値」に表示したい文言を入力（キーは基本そのままでOK）</li>
                    <li>右側の文字数カウンタを目安に短く整えるとレイアウトが安定します</li>
                  </ol>
                  <div class="guide-actions">
                    <button type="button" class="btn btn-secondary small" @click="reloadFieldsFromJson">JSON→Fieldsに再読込</button>
                  </div>
                </div>
                <div class="recommended-keys" v-if="recommendedKeys.length">
                  <div class="subtitle">推奨キー（クリックで追加/フォーカス）</div>
                  <div class="chips">
                    <button 
                      v-for="rk in recommendedKeys" 
                      :key="rk"
                      type="button"
                      :class="['chip', hasTextKey(rk) ? 'chip-ok' : 'chip-missing']"
                      @click="focusOrAddKey(rk)"
                    >
                      {{ rk }}
                    </button>
                  </div>
                </div>
                <!-- Device filter to clarify which device the text appears on -->
                <div class="device-filter">
                  <span class="df-label">表示対象</span>
                  <label class="df-item"><input type="radio" value="all" v-model="fieldDeviceFilter" /> 全て</label>
                  <label class="df-item"><input type="radio" value="desktop" v-model="fieldDeviceFilter" /> デスクトップ</label>
                  <label class="df-item"><input type="radio" value="mobile" v-model="fieldDeviceFilter" /> モバイル</label>
                </div>
                <div class="fields-editor">
                  <div class="field-head">
                    <div>キー</div>
                    <div>値</div>
                    <div>情報</div>
                    <div>操作</div>
                  </div>
                  <div 
                    v-for="(row, idx) in filteredTextRows" 
                    :key="row._id"
                    :class="['field-row', getKeyLimit(row.key) && (row.value || '').length > getKeyLimit(row.key) ? 'field-row-warning' : '']"
                  >
                    <input
                      v-model="row.key"
                      type="text"
                      class="form-input field-key"
                      :placeholder="getKeyPlaceholder(row.key) || 'キー（例: page_title, lead, cta_primary）'"
                    />
                    <input
                      v-model="row.value"
                      type="text"
                      class="form-input field-value"
                      :placeholder="getKeyPlaceholder(row.key) ? getKeyPlaceholder(row.key) : '値（表示テキスト）'"
                    />
                    <div class="field-hint">
                      <span class="device-badge" :class="'dev-' + getKeyDevice(row.key)">{{ getKeyDeviceLabel(row.key) }}</span>
                      <span v-if="getKeyLimit(row.key)" class="counter" style="margin-left:6px;">{{ (row.value || '').length }}/{{ getKeyLimit(row.key) }}</span>
                      <span v-else class="counter" style="margin-left:6px;">{{ (row.value || '').length }}</span>
                      <div v-if="getKeyLabel(row.key)" class="key-help">{{ getKeyLabel(row.key) }}：{{ getKeyDesc(row.key) }}</div>
                      <div v-if="getKeyLocation(row.key)" class="key-help">表示箇所：{{ getKeyLocation(row.key) }}</div>
                    </div>
                    <button type="button" class="btn btn-secondary small" @click="removeTextField(idx)">削除</button>
                  </div>
                  <button type="button" class="btn btn-primary" @click="addTextField">+ フィールドを追加</button>
                  <p class="form-help">推奨キー例: page_title, page_subtitle, lead, cta_primary, cta_secondary</p>
                </div>

                <!-- Live Preview (common placements) removed as requested -->

                <!-- Links subsection -->
                <label class="form-label" style="margin-top:20px;">
                  リンクフィールド（content.links）
                </label>
                <div class="fields-editor">
                  <div class="field-head">
                    <div>キー</div>
                    <div>URL（/ または http(s)://）</div>
                    <div>検証</div>
                    <div>操作</div>
                  </div>
                  <div 
                    v-for="(row, idx) in linksEditor" 
                    :key="row._id"
                    :class="['field-row', !isValidLink(row.value) && (row.value||'').length ? 'field-row-warning' : '']"
                  >
                    <input
                      v-model="row.key"
                      type="text"
                      class="form-input field-key"
                      placeholder="キー（例: cta_primary, cta_secondary）"
                    />
                    <input
                      v-model="row.value"
                      type="text"
                      class="form-input field-value"
                      placeholder="例）/contact または https://example.com"
                    />
                    <div class="field-hint">
                      <span class="counter" :style="{color: isValidLink(row.value) || !(row.value||'').length ? '#065f46' : '#b45309'}">{{ isValidLink(row.value) || !(row.value||'').length ? 'OK' : 'URL形式を確認してください' }}</span>
                    </div>
                    <button type="button" class="btn btn-secondary small" @click="removeLinkField(idx)">削除</button>
                  </div>
                  <div class="guide-actions">
                    <button type="button" class="btn btn-secondary small" @click="addMissingLinkKeys">不足キーを追加（cta系）</button>
                    <button type="button" class="btn btn-primary" @click="addLinkField">+ リンクを追加</button>
                  </div>
                  <p class="form-help">安全のため、`/` から始まるサイト内リンクか、`http(s)://` のみ許可</p>
                </div>
              </div>
            </div>

            <!-- メタデータ -->
            <div class="form-section">
              <h3 class="section-title">SEO設定</h3>
              
              <div class="form-group">
                <label for="meta_description" class="form-label">
                  メタディスクリプション
                </label>
                <textarea
                  id="meta_description"
                  v-model="formData.meta_description"
                  rows="3"
                  class="form-input"
                  placeholder="検索結果に表示される説明文（160文字以内推奨）"
                />
                <p class="form-help">
                  {{ formData.meta_description ? formData.meta_description.length : 0 }}/160文字
                </p>
              </div>

              <div class="form-group">
                <label for="meta_keywords" class="form-label">
                  メタキーワード
                </label>
                <input
                  id="meta_keywords"
                  v-model="formData.meta_keywords"
                  type="text"
                  class="form-input"
                  placeholder="キーワード1, キーワード2, キーワード3"
                />
                <p class="form-help">カンマ区切りでキーワードを入力してください</p>
              </div>
            </div>

            <!-- 公開設定 -->
            <div class="form-section">
              <div class="form-group">
                <label class="checkbox-label">
                  <input
                    v-model="formData.is_published"
                    type="checkbox"
                    class="checkbox-input"
                  />
                  <span class="checkbox-custom"></span>
                  <span class="checkbox-text">ページを公開する</span>
                </label>
                <p class="form-help">
                  チェックを外すと、このページは非公開になります
                </p>
              </div>
            </div>

            <!-- エラー・成功メッセージ -->
            <div v-if="submitError" class="message error-message">
              {{ submitError }}
            </div>

            <div v-if="successMessage" class="message success-message">
              {{ successMessage }}
            </div>

            <!-- アクションボタン -->
            <div class="form-actions">
              <button
                type="button"
                @click="goBack"
                class="btn btn-secondary"
              >
                キャンセル
              </button>
              <button
                type="submit"
                :disabled="loading || jsonError || submitLoading"
                class="btn btn-primary"
              >
                {{ submitLoading ? '保存中...' : '保存' }}
              </button>
            </div>
          </form>
        </div>
        
        <!-- Hidden iframe to auto-collect CMS keys (no UI) -->
        <div class="sr-only-hidden" aria-hidden="true">
          <iframe
            ref="hiddenFrame"
            :src="previewUrl"
            title="hidden-cms-preview"
            tabindex="-1"
          ></iframe>
        </div>
      </div>
    </div>
  </AdminLayout>
</template>

<script>
import AdminLayout from './AdminLayout.vue'
import mockServer from '@/mockServer'
import axios from 'axios'
import apiClient from '@/services/apiClient.js'

// 推奨キー（ページキーごと）
const RECOMMENDED_KEYS = {
  home: ['page_title', 'lead', 'cta_primary', 'cta_secondary'],
  'economic-statistics': ['page_title', 'page_subtitle', 'cta_primary', 'cta_secondary'],
  publications: ['page_title', 'page_subtitle', 'cta_primary', 'cta_secondary'],
  'economic-indicators': ['page_title', 'page_subtitle'],
  news: ['page_title', 'page_subtitle'],
  // Aboutページ：ご挨拶の本文/署名までCMS化
  about: [
    'page_title','page_subtitle',
    'mission_title','mission_body',
    'message_title','message_subtitle','message_body','message_signature',
    'cta_primary',
    // CTA block
    'cta_block_title','cta_block_body','cta_block_button',
    // Company table
    'company_label_name','company_value_name',
    'company_label_established','company_value_established',
    'company_label_capital','company_value_capital',
    'company_label_address','company_value_address',
    'company_label_tel','company_value_tel',
    'company_label_business_hours','company_value_business_hours',
    // History controls
    'history_more'
  ],
  // CompanyProfile：ご挨拶、ミッション、会社概要、所員紹介（スタッフカードの文言も含む）
  'company-profile': [
    'page_title', 'page_subtitle',
    'philosophy_title', 'philosophy_subtitle',
    'message_title', 'message_subtitle', 'message_label', 'message_signature',
    'mission_title', 'mission_body',
    'profile_title', 'profile_subtitle',
    // 所員紹介 見出し
    'staff_title', 'staff_subtitle',
    // 所員：森田
    'staff_morita_position','staff_morita_name','staff_morita_reading','staff_morita_note',
    // 所員：溝上
    'staff_mizokami_position','staff_mizokami_name','staff_mizokami_reading',
    // 所員：空閑
    'staff_kuga_position','staff_kuga_name','staff_kuga_reading',
    // 所員：髙田
    'staff_takada_position','staff_takada_name','staff_takada_reading',
    // 所員：中村
    'staff_nakamura_position','staff_nakamura_name','staff_nakamura_reading'
  ],
  // Consulting：主要セクションをCMS化
  consulting: ['page_title', 'page_subtitle', 'what_title', 'what_subtitle', 'what_content_subtitle', 'what_content_heading', 'what_content_body', 'duties_title', 'duties_subtitle', 'duties_intro', 'duties_label', 'duties_heading', 'duties_list', 'support_title', 'support_subtitle_en', 'support_intro', 'support_free_title', 'support_paid_title', 'achievements_title', 'achievements_subtitle', 'cta_primary', 'cta_secondary'],
  // About Institute：導入とサービス概要
  'about-institute': ['page_title', 'page_subtitle', 'about_title', 'about_subtitle', 'about_body', 'service_title', 'service_subtitle', 'service1_title', 'service1_desc', 'service1_list', 'service2_title', 'service2_desc', 'service2_list', 'service3_title', 'service3_desc', 'service3_list', 'cta_primary', 'cta_secondary'],
  terms: [
    'page_title', 'page_subtitle', 'intro',
    'copyright_title', 'copyright_body',
    'link_title', 'link_body',
    'disclaimer_title', 'disclaimer_body',
    'security_title', 'security_body',
    'cookie_title', 'cookie_body',
    'environment_title', 'environment_body',
    'prohibited_title', 'prohibited_body',
    'article8_title', 'article8_body'
  ],
  privacy: [
    'page_title','page_subtitle',
    'intro',
    'collection_title','collection_body',
    'purpose_title','purpose_intro','purpose_list',
    'disclosure_title','disclosure_list',
    'correction_title','correction_body',
    'disclaimer_title','disclaimer_body1','disclaimer_body2','disclaimer_body3',
    'changes_title','changes_body'
  ],
  faq: ['page_title', 'page_subtitle'],
  'transaction-law': [
    'page_title','page_subtitle',
    'seller_label','seller_value',
    'rep_label','rep_value',
    'addr_label','addr_value',
    'tel_label','tel_value',
    'fax_label','fax_value',
    'mail_label','mail_value','contact_cta',
    'fee_label','fee_desc','fee_section_title','fee_standard_label','fee_standard_amount','fee_premium_label','fee_premium_amount',
    'payment_label','payment_body',
    'otherfees_label','otherfees_value',
    'service_time_label','service_time_value',
    'cancel_label','cancel_value',
    'refund_label','refund_body',
    'terms_note',
    'article1_title','article1_body',
    'article2_title','article2_body',
    'article3_title','article3_body',
    'download_terms'
  ]
}

// ソフト上限（超過時に警告）
const TEXT_LIMITS = {
  page_title: 40,
  page_subtitle: 60,
  lead: 120,
  cta_primary: 30,
  cta_secondary: 30,
  mission_title: 40,
  mission_body: 200,
  // 所員紹介：控えめに上限（レイアウト保護の目安）
  staff_morita_position: 40,
  staff_morita_name: 20,
  staff_morita_reading: 24,
  staff_morita_note: 40,
  staff_mizokami_position: 40,
  staff_mizokami_name: 20,
  staff_mizokami_reading: 24,
  staff_kuga_position: 40,
  staff_kuga_name: 20,
  staff_kuga_reading: 24,
  staff_takada_position: 40,
  staff_takada_name: 20,
  staff_takada_reading: 24,
  staff_nakamura_position: 40,
  staff_nakamura_name: 20,
  staff_nakamura_reading: 24,
}

// 初心者向けのキー説明
const KEY_HELP = {
  page_title: { label: 'ページタイトル', desc: 'ページの大見出しに表示されます', placeholder: '例）経済・調査統計' },
  page_subtitle: { label: 'サブタイトル（英字など）', desc: 'タイトル下に小さく英字等で表示', placeholder: '例）economic statistics' },
  lead: { label: 'リード文', desc: 'ページ冒頭の説明文（任意）', placeholder: 'ページの概要を1〜2文で書きます' },
  cta_primary: { label: 'メインボタン文言', desc: '主ボタンのテキスト', placeholder: '例）お問い合わせはこちら' },
  cta_secondary: { label: 'サブボタン文言', desc: '副ボタンのテキスト', placeholder: '例）入会はこちら' },
  form_title: { label: 'フォーム見出し', desc: 'お問い合わせフォーム上部の見出し', placeholder: 'お問い合わせ' },
  mission_title: { label: 'ミッション見出し', desc: '会社/団体紹介の小見出し', placeholder: '産学官金のネットワークを活かした地域創生' },
  mission_body: { label: 'ミッション本文', desc: '紹介の本文', placeholder: '私たちは、〜〜〜' },
  // ご挨拶（共通）
  message_title: { label: 'ご挨拶見出し', desc: 'セクション見出し（例：ご挨拶）', placeholder: 'ご挨拶' },
  message_subtitle: { label: 'ご挨拶英字', desc: '英字の小見出し（例：MESSAGE）', placeholder: 'MESSAGE' },
  message_label: { label: '左側ラベル', desc: 'セクション左側の大きなラベル', placeholder: 'MESSAGE' },
  message_body: { label: 'ご挨拶本文（HTML可）', desc: '複数段落・リンク等も可（安全にサニタイズ表示）', placeholder: '本文を入力' },
  message_signature: { label: 'ご挨拶の署名', desc: '末尾の署名（役職・氏名など）', placeholder: '株式会社〜 代表取締役〜' },
  mission_label: { label: 'ミッション英字', desc: 'ミッションの英字ラベル', placeholder: 'OUR MISSION' },
}

// キーの表示箇所（ページ別）
const KEY_LOCATIONS = {
  'home': {
    page_title: 'ヒーロー大見出し（HomePage）',
    page_subtitle: 'ヒーロー小見出し（HomePage）',
    lead: 'ヒーロー下の説明文（HomePage）',
    cta_primary: '最下部の主ボタン文言（HomePage）',
    cta_secondary: '最下部の副ボタン文言（HomePage）',
  },
  'economic-statistics': {
    page_title: 'ヒーロー・パンくず・見出し（EconomicStatisticsPage）',
    page_subtitle: '英字のサブ見出し（EconomicStatisticsPage）',
    cta_primary: '最下部の主ボタン文言（EconomicStatisticsPage）',
    cta_secondary: '最下部の副ボタン文言（EconomicStatisticsPage）',
  },
  'publications': {
    page_title: 'ヒーロー・パンくず・見出し（PublicationsPublic/Detail）',
    page_subtitle: '英字のサブ見出し（PublicationsPublic/Detail）',
    cta_primary: '下部の主ボタン文言（Detail等）',
    cta_secondary: '下部の副ボタン文言（Detail等）',
  },
  'economic-indicators': {
    page_title: 'ヒーロー・パンくず・見出し（EconomicIndicatorsPage）',
    page_subtitle: '英字のサブ見出し（EconomicIndicatorsPage）',
  },
  'news': {
    page_title: 'ヒーロー・パンくず・見出し（NewsPage）',
    page_subtitle: '英字のサブ見出し（NewsPage）',
  },
  'contact': {
    page_title: 'ヒーロー・パンくず（ContactFormPage）',
    page_subtitle: '英字のサブ見出し（ContactFormPage）',
    form_title: 'フォームの大見出し（ContactFormPage）',
  },
  'company-profile': {
    page_title: 'ヒーロー・パンくず（CompanyProfile）',
    page_subtitle: '英字のサブ見出し（CompanyProfile）',
    philosophy_title: '哲学セクション見出し（CompanyProfile）',
    philosophy_subtitle: '哲学セクション英字（CompanyProfile）',
    message_title: 'ご挨拶セクション見出し（CompanyProfile）',
    message_subtitle: 'ご挨拶セクション英字（CompanyProfile）',
    message_label: '左側ラベル「MESSAGE」',
    message_signature: 'ご挨拶の署名',
    profile_title: '会社概要セクション見出し',
    profile_subtitle: '会社概要セクション英字',
    mission_label: 'ミッション英字（OUR MISSION）',
    mission_title: 'ミッションの見出し',
    mission_body: 'ミッションの本文（HTML可）',
    history_title: '沿革セクション見出し',
    history_subtitle: '沿革セクション英字',
    nav_philosophy: 'ページ内ナビ：経営理念',
    nav_message: 'ページ内ナビ：ご挨拶',
    nav_profile: 'ページ内ナビ：企業概要',
    nav_history: 'ページ内ナビ：沿革',
    nav_staff: 'ページ内ナビ：所員紹介',
    nav_financial: 'ページ内ナビ：決算報告',
    nav_access: 'ページ内ナビ：アクセス',
    // 所員紹介 見出し
    staff_title: '所員紹介セクション見出し',
    staff_subtitle: '所員紹介セクション英字',
    // 所員カード（森田）
    staff_morita_position: '所員紹介：森田 役職',
    staff_morita_name: '所員紹介：森田 氏名',
    staff_morita_reading: '所員紹介：森田 ふりがな',
    staff_morita_note: '所員紹介：森田 注記',
    // 所員カード（溝上）
    staff_mizokami_position: '所員紹介：溝上 役職',
    staff_mizokami_name: '所員紹介：溝上 氏名',
    staff_mizokami_reading: '所員紹介：溝上 ふりがな',
    // 所員カード（空閑）
    staff_kuga_position: '所員紹介：空閑 役職',
    staff_kuga_name: '所員紹介：空閑 氏名',
    staff_kuga_reading: '所員紹介：空閑 ふりがな',
    // 所員カード（髙田）
    staff_takada_position: '所員紹介：髙田 役職',
    staff_takada_name: '所員紹介：髙田 氏名',
    staff_takada_reading: '所員紹介：髙田 ふりがな',
    // 所員カード（中村）
    staff_nakamura_position: '所員紹介：中村 役職（空可）',
    staff_nakamura_name: '所員紹介：中村 氏名',
    staff_nakamura_reading: '所員紹介：中村 ふりがな',
  },
  'privacy': {
    page_title: 'ヒーロー・パンくず・見出し（PrivacyPolicyPage）',
    page_subtitle: '英字のサブ見出し（PrivacyPolicyPage）',
    intro: '導入文',
    collection_title: '見出し：適切な取得',
    collection_body: '本文：適切な取得',
    purpose_title: '見出し：収集・利用目的',
    purpose_intro: '本文：目的の導入',
    purpose_list: '本文：目的リスト（HTML可）',
    disclosure_title: '見出し：第三者開示',
    disclosure_list: '本文：第三者開示リスト（HTML可）',
    correction_title: '見出し：訂正・削除',
    correction_body: '本文：訂正・削除（HTML可）',
    disclaimer_title: '見出し：免責事項',
    disclaimer_body1: '本文：免責事項（1）',
    disclaimer_body2: '本文：免責事項（2）',
    disclaimer_body3: '本文：免責事項（3）',
    changes_title: '見出し：方針の変更',
    changes_body: '本文：方針の変更',
  },
  'faq': {
    page_title: 'ヒーロー・パンくず・見出し（FaqPage）',
    page_subtitle: '英字のサブ見出し（FaqPage）',
  },
  'terms': {
    page_title: 'ヒーロー・パンくず・見出し（TermsOfServicePage）',
    page_subtitle: '英字のサブ見出し（TermsOfServicePage）',
    intro: '導入文（ページ冒頭の説明）',
    copyright_title: 'セクション見出し：著作権等について',
    copyright_body: '本文：著作権等について（HTML可）',
    link_title: 'セクション見出し：リンクについて',
    link_body: '本文：リンクについて（HTML可）',
    disclaimer_title: 'セクション見出し：免責事項',
    disclaimer_body: '本文：免責事項（HTML可）',
    security_title: 'セクション見出し：セキュリティについて',
    security_body: '本文：セキュリティについて（HTML可）',
    cookie_title: 'セクション見出し：クッキー(Cookie)について',
    cookie_body: '本文：クッキー(Cookie)について（HTML可）',
    environment_title: 'セクション見出し：ご利用環境について',
    environment_body: '本文：ご利用環境について（HTML可）',
    prohibited_title: 'セクション見出し：禁止される行為',
    prohibited_body: '本文：禁止される行為（HTML可）',
    article8_title: 'セクション見出し：第8条（利用規約の変更）',
    article8_body: '本文：第8条（利用規約の変更）（HTML可）',
  },
  'transaction-law': {
    page_title: 'ヒーロー・パンくず・見出し（TransactionLawPage）',
    page_subtitle: '英字のサブ見出し（TransactionLawPage）',
    seller_label: 'ラベル：販売業者', seller_value: '値：販売業者',
    rep_label: 'ラベル：代表者名', rep_value: '値：代表者名',
    addr_label: 'ラベル：住所', addr_value: '値：住所（HTML可）',
    tel_label: 'ラベル：電話番号', tel_value: '値：電話番号',
    fax_label: 'ラベル：FAX番号', fax_value: '値：FAX番号',
    mail_label: 'ラベル：メール', mail_value: '値：メール', contact_cta: 'ボタン：お問い合わせへ',
    fee_label: 'ラベル：料金', fee_desc: '本文：料金の説明（HTML可）', fee_section_title: '見出し：料金セクション',
    fee_standard_label: '料金：スタンダード見出し', fee_standard_amount: '料金：スタンダード金額',
    fee_premium_label: '料金：プレミアム見出し', fee_premium_amount: '料金：プレミアム金額',
    payment_label: 'ラベル：支払い時期および方法', payment_body: '本文：支払い時期および方法（HTML可）',
    otherfees_label: 'ラベル：その他料金', otherfees_value: '値：その他料金',
    service_time_label: 'ラベル：提供時間', service_time_value: '値：提供時間',
    cancel_label: 'ラベル：退会について', cancel_value: '値：退会について',
    refund_label: 'ラベル：返金について', refund_body: '本文：返金について（HTML可）',
    terms_note: '注記：会員規約の確認',
    article1_title: '規約：第1条 見出し', article1_body: '規約：第1条 本文（HTML可）',
    article2_title: '規約：第2条 見出し', article2_body: '規約：第2条 本文（HTML可）',
    article3_title: '規約：第3条 見出し', article3_body: '規約：第3条 本文（HTML可）',
    download_terms: 'リンク文言：会員規約PDFダウンロード',
  },
  'sitemap': {
    page_title: 'ヒーロー・パンくず・見出し（SitemapPage）',
    page_subtitle: '英字のサブ見出し（SitemapPage）',
    cta_primary: '下部ボタン（お問い合わせ）',
    cta_secondary: '下部ボタン（入会）',
  },
  'consulting': {
    page_title: 'ヒーロー・パンくず（CRIConsultingPage）',
    page_subtitle: '英字のサブ見出し（CRIConsultingPage）',
    what_title: '見出し：CRI 経営コンサルティングとは？',
    what_subtitle: '英字：consulting',
    what_content_subtitle: '小見出し（英字）',
    what_content_heading: '小見出し（日本語）',
    what_content_body: '導入本文（HTML可）',
    duties_title: '見出し：主な業務',
    duties_subtitle: '英字：main duties',
    duties_intro: '主な業務の導入（HTML）',
    duties_label: '左側ラベル（MAIN DUTIES）',
    duties_heading: '主な業務の見出し',
    duties_list: '主な業務のリスト（HTML可）',
    support_title: '見出し：サポート内容と費用',
    support_subtitle_en: '英字：contents and costs',
    support_intro: '導入（HTML可）',
    support_free_title: '無料枠のタイトル',
    support_paid_title: '有料枠のタイトル',
    achievements_title: '見出し：実績紹介',
    achievements_subtitle: '英字：achievements',
    cta_primary: '最下部ボタン（お問い合わせ）',
    cta_secondary: '最下部ボタン（入会）',
  },
  'about-institute': {
    page_title: 'ヒーロー・パンくず（AboutInstitutePage）',
    page_subtitle: '英字のサブ見出し（AboutInstitutePage）',
    about_title: '導入セクション見出し',
    about_subtitle: '導入セクション英字',
    about_body: '導入セクション本文（HTML可）',
    service_title: 'サービス概要見出し',
    service_subtitle: 'サービス概要英字',
    service1_title: 'カード1 見出し',
    service1_desc: 'カード1 説明文',
    service1_list: 'カード1 詳細（HTML可）',
    service2_title: 'カード2 見出し',
    service2_desc: 'カード2 説明文',
    service3_title: 'カード3 見出し',
    service3_desc: 'カード3 説明文',
    cta_primary: '最下部ボタン（お問い合わせ）',
    cta_secondary: '最下部ボタン（入会）',
  },
  'about': {
    page_title: 'ヒーロー見出し（AboutPage）',
    page_subtitle: 'ヒーロー英字（AboutPage）',
    mission_title: '経営理念セクションの見出し',
    mission_body: '経営理念セクションの本文',
    message_title: 'ご挨拶セクション見出し',
    message_subtitle: 'ご挨拶セクション英字',
    message_signature: 'ご挨拶の署名',
    cta_block_title: 'CTAブロック見出し',
    cta_block_body: 'CTAブロック本文',
    cta_block_button: 'CTAボタン文言',
    company_label_name: '会社概要テーブル：項目名（会社名）',
    company_value_name: '会社概要テーブル：値（会社名）',
    company_label_established: '会社概要テーブル：項目名（設立）',
    company_value_established: '会社概要テーブル：値（設立）',
    company_label_capital: '会社概要テーブル：項目名（資本金）',
    company_value_capital: '会社概要テーブル：値（資本金）',
    company_label_address: '会社概要テーブル：項目名（所在地）',
    company_value_address: '会社概要テーブル：値（所在地・HTML可）',
    company_label_tel: '会社概要テーブル：項目名（電話番号）',
    company_value_tel: '会社概要テーブル：値（電話番号）',
    company_label_business_hours: '会社概要テーブル：項目名（営業時間）',
    company_value_business_hours: '会社概要テーブル：値（営業時間）',
    history_more: '沿革：さらに表示ボタン',
  },
  'membership': {
    page_title: 'ヒーロー・パンくず（MembershipPage）',
    page_subtitle: '英字のサブ見出し（MembershipPage）',
    cta_primary: '下部ボタン（お問い合わせ）',
    cta_secondary: '下部ボタン（入会）',
  },
  'seminars-current': {
    page_title: 'ヒーロー・パンくず（CurrentSeminarsPage）',
    page_subtitle: '英字のサブ見出し（CurrentSeminarsPage）',
  }
}

// Optional device mapping per page/key to clarify where text is used.
// Values: 'desktop' | 'mobile' | 'both' (default when unspecified)
// Keep this sparse; unspecified keys are treated as 'both'.
const KEY_DEVICES = {
  // Example structure (uncomment and adjust as needed):
  // 'aboutus': { page_title: 'both', page_subtitle: 'both' },
  // 'membership': { page_title: 'both' },
}

export default {
  name: 'NewPageEditForm',
  components: {
    AdminLayout
  },
  data() {
    return {
      formData: {
        page_key: '',
        title: '',
        content: {},
        meta_description: '',
        meta_keywords: '',
        is_published: false
      },
      contentMode: 'wysiwyg',
      contentJson: '',
      contentHtml: '',
      showPreview: true,
      textsEditor: [],
      linksEditor: [],
      jsonError: '',
      loading: false,
      submitLoading: false,
      error: '',
      submitError: '',
      successMessage: '',
      // ライブプレビューのデバイス幅切替
      previewDevice: 'desktop',
      collectedTextKeys: [],
      collectedTextDefaults: {},
      // Fields view: filter rows by device for clarity
      fieldDeviceFilter: 'all' // 'all' | 'desktop' | 'mobile'
    }
  },
  computed: {
    isNew() {
      return this.$route.params.pageKey === 'new'
    },
    pageKey() {
      return this.$route.params.pageKey
    },
    previewPath() {
      // ルーティングの別名・旧キーも吸収し、誤ったページが開かないよう包括的に対応
      const key = (this.formData.page_key || this.pageKey || '').trim()
      const map = {
        // Top/Home
        'home': '/#/',
        'top': '/#/',
        // Company/About
        'about': '/#/company',
        'company': '/#/company',
        'company-profile': '/#/company',
        // About institute (aboutus)
        'aboutus': '/#/aboutus',
        'about-institute': '/#/aboutus',
        // Static info pages
        'sitemap': '/#/sitemap',
        'privacy': '/#/privacy',
        'terms': '/#/terms',
        'legal': '/#/legal',
        'transaction-law': '/#/legal',
        // Contact
        'contact': '/#/contact',
        // Consulting
        'consulting': '/#/cri-consulting',
        'cri-consulting': '/#/cri-consulting',
        // Services
        'services': '/#/services',
        // Membership
        'membership': '/#/membership',
        'register': '/#/membership',
        'premium-membership': '/#/premium-membership',
        'membership-premium': '/#/premium-membership',
        // Glossary
        'glossary': '/#/glossary',
        // Economics sections
        'economic-statistics': '/#/economic-research',
        'economic-research': '/#/economic-research',
        'economic-indicators': '/#/economic-indicators',
        // Seminars
        'seminars': '/#/seminar',
        'seminars-current': '/#/seminars/current',
        // Publications / News
        'publications': '/#/publications-public',
        'news': '/#/news',
        // FAQ
        'faq': '/#/faq',
        // Financial reports
      }
      return map[key] || '/#/'
    },
    previewUrl() {
      const base = this.previewPath
      const sep = base.includes('?') ? '&' : '?'
      // cmsEdit を付けずにプレビュー（閲覧のみ）
      return `${base}${sep}cmsPreview=1`
    },
    recommendedKeys() {
      const key = this.formData.page_key || this.pageKey || ''
      return RECOMMENDED_KEYS[key] || []
    },
    // Filter text rows by device selection (default: all)
    filteredTextRows() {
      const f = this.fieldDeviceFilter
      if (f === 'all') return this.textsEditor
      return this.textsEditor.filter(r => {
        const d = this.getKeyDevice(r.key)
        return d === f || d === 'both'
      })
    },
    previewWidthPx() {
      switch (this.previewDevice) {
        case 'mobile': return 375
        case 'tablet': return 900
        case 'desktop':
        default: return 1200
      }
    },
    isCompanyProfile() { const k = (this.formData.page_key || this.pageKey || '').trim(); return k === 'company-profile' },
    // Show Financial Reports editor for Company Profile and dedicated Financial Reports page
    isFinancialReports() {
      const k = (this.formData.page_key || this.pageKey || '').trim()
      return k === 'company-profile' || k === 'financial-reports'
    },
  },
  watch: {
    contentJson(newValue) {
      this.jsonError = ''
      try {
        this.formData.content = JSON.parse(newValue)
      } catch (e) {
        this.jsonError = 'JSONの形式が正しくありません: ' + e.message
      }
      this.postToPreview()
    },
    contentMode(newMode, oldMode) {
      if ((newMode === 'html' || newMode === 'wysiwyg') && oldMode !== newMode) {
        this.contentHtml = this.extractHtmlFromContent()
      }
      if (newMode === 'json') {
        this.contentJson = JSON.stringify(this.formData.content || {}, null, 2)
      }
      if (newMode === 'fields') {
        // Ask preview to list keys then preset
        this.requestPreviewKeys()
        this.ensureFieldsPreset()
      }
      if (newMode === 'live') {
        this.$nextTick(() => { this.postToPreview(); this.requestPreviewKeysDeferred() })
      }
    }
  },
  async mounted() {
    const token = localStorage.getItem('admin_token')
    
    if (!token) {
      this.$router.push('/admin')
      return
    }

    if (!this.isNew) {
      await this.fetchPageData()
    } else {
      const initial = {
        title: '新しいページ',
        description: 'ページの説明をここに入力してください'
      }
      this.formData.content = initial
      this.contentJson = JSON.stringify(initial, null, 2)
      this.contentHtml = ''
    }
    // listen for keys reported from preview
    try {
      window.addEventListener('message', (ev) => {
        const data = ev?.data || {}
        if (data && data.type === 'cms-key' && data.pageKey === this.pageKey) {
          const k = (data.fieldKey || '').trim()
          if (k && !this.collectedTextKeys.includes(k)) {
            this.collectedTextKeys.push(k)
            if (this.contentMode === 'fields') this.ensureFieldsPreset()
          }
          if (k && typeof data.value === 'string' && data.value.length >= 0) {
            this.collectedTextDefaults[k] = data.value
          }
        }
      })
    } catch(_) {}

    this.$nextTick(() => { this.postToPreview(); this.requestPreviewKeysDeferred() })
  },
  methods: {
    syncContentJson() { try { this.contentJson = JSON.stringify(this.formData.content || {}, null, 2) } catch(_) {} },
    // History editor
    addHistoryEntry() {
      if (!this.formData.content) this.formData.content = {}
      if (!Array.isArray(this.formData.content.history)) this.formData.content.history = []
      this.formData.content.history.push({ year: '', date: '', body: '' })
      this.syncContentJson()
    },
    removeHistoryEntry(index) {
      try { this.formData.content.history.splice(index, 1) } catch(_) {}
      this.syncContentJson()
    },
    // Reports editor
    addReport() {
      if (!this.formData.content) this.formData.content = {}
      if (!Array.isArray(this.formData.content.financial_reports)) this.formData.content.financial_reports = []
      this.formData.content.financial_reports.push({ fiscal_year: '', date_label: '', items: [] })
      this.syncContentJson()
    },
    removeReport(ri) {
      try { this.formData.content.financial_reports.splice(ri, 1) } catch(_) {}
      this.syncContentJson()
    },
    addReportItem(ri) {
      const r = (this.formData.content.financial_reports || [])[ri]
      if (!r) return
      if (!Array.isArray(r.items)) r.items = []
      r.items.push({ label: '', url: '' })
      this.syncContentJson()
    },
    removeReportItem(ri, ii) {
      const r = (this.formData.content.financial_reports || [])[ri]
      if (!r || !Array.isArray(r.items)) return
      r.items.splice(ii, 1)
      this.syncContentJson()
    },
    async uploadReportItemPdf(ri, ii) {
      try {
        const input = document.createElement('input')
        input.type = 'file'
        input.accept = 'application/pdf'
        input.onchange = async (e) => {
          const file = e.target.files[0]
          if (!file) return
          const fd = new FormData()
          fd.append('file', file)
          fd.append('directory', 'public/media/uploads')
          const api = (await import('@/services/apiClient.js')).default
          const res = await api.upload('/api/admin/media/upload', fd)
          const url = res?.file?.url || res?.data?.file?.url || res?.data?.url || ''
          if (url) {
            const r = (this.formData.content.financial_reports || [])[ri]
            if (r && Array.isArray(r.items) && r.items[ii]) { r.items[ii].url = url }
            this.syncContentJson()
            alert('PDFをアップロードしました')
          } else {
            alert(res?.message || 'アップロードに失敗しました')
          }
        }
        input.click()
      } catch (e) {
        console.error(e)
        alert('アップロードに失敗しました')
      }
    },
    requestPreviewKeys() {
      try {
        const f = this.$refs.hiddenFrame || this.$refs.liveFrame
        if (f && f.contentWindow) f.contentWindow.postMessage({ type: 'cms-list-keys', pageKey: this.pageKey }, '*')
      } catch(_) {}
    },
    requestPreviewKeysDeferred() { setTimeout(() => this.requestPreviewKeys(), 400) },
    text(k) {
      const key = (k || '').trim()
      const row = this.textsEditor.find(r => (r.key||'').trim() === key)
      return row ? row.value : ''
    },
    getExistingTextValue(k) {
      const key = (k || '').trim()
      try {
        const c = this.formData?.content
        if (c && typeof c === 'object' && c.texts && typeof c.texts === 'object') {
          const v = c.texts[key]
          if (typeof v === 'string') return v
        }
      } catch(_) {}
      // Heuristic from current HTML preview
      try {
        const html = this.extractHtmlFromContent()
        if (key === 'page_title') {
          const h1 = (html.match(/<h1[^>]*>([\s\S]*?)<\/h1>/i) || [null, ''])[1]
          if (h1) return h1.replace(/<[^>]+>/g, '').trim()
        }
        if (key === 'lead') {
          const p = (html.match(/<p[^>]*>([\s\S]*?)<\/p>/i) || [null, ''])[1]
          if (p) return p.replace(/<[^>]+>/g, '').trim()
        }
      } catch(_) {}
      // Fallback to collected display text from preview
      if (this.collectedTextDefaults[key]) return this.collectedTextDefaults[key]
      return ''
    },
    focusOrAddKey(k) {
      const key = (k || '').trim()
      const idx = this.textsEditor.findIndex(r => (r.key||'').trim() === key)
      if (idx === -1) {
        const preset = this.getExistingTextValue(key)
        this.textsEditor.push({ _id: `rk-${key}-${Date.now()}-${Math.random()}`, key, value: preset })
        this.$nextTick(() => {
          const inputs = this.$el.querySelectorAll('.field-row .field-key')
          if (inputs && inputs.length) inputs[inputs.length-1].focus()
        })
      } else {
        this.$nextTick(() => {
          const inputs = this.$el.querySelectorAll('.field-row .field-key')
          if (inputs && inputs[idx]) inputs[idx].focus()
        })
      }
    },
    ensureFieldsPreset() {
      // 既存content.textsやHTMLから値を推定しつつ推奨キーをプリセット
      const existing = {}
      try {
        const c = this.formData?.content
        if (c && typeof c === 'object' && c.texts && typeof c.texts === 'object') {
          Object.assign(existing, c.texts)
        }
      } catch (_) {}

      // HTMLからの簡易抽出（h1, p）
      try {
        const html = this.extractHtmlFromContent()
        if (html) {
          const h1 = (html.match(/<h1[^>]*>([\s\S]*?)<\/h1>/i) || [null, ''])[1]
          const p = (html.match(/<p[^>]*>([\s\S]*?)<\/p>/i) || [null, ''])[1]
          if (h1 && !existing.page_title) existing.page_title = h1.replace(/<[^>]+>/g, '').trim()
          if (p && !existing.lead) existing.lead = p.replace(/<[^>]+>/g, '').trim()
        }
      } catch (_) {}

      const keySet = new Set([...(this.recommendedKeys || []), ...(this.collectedTextKeys || [])])
      const toAdd = Array.from(keySet).filter(k => !this.hasTextKey(k))
      for (const k of toAdd) {
        const v = existing[k] || this.collectedTextDefaults[k] || ''
        this.textsEditor.push({ _id: `rk-${k}-${Date.now()}-${Math.random()}`, key: k, value: v })
      }
    },
    // Inline editor helpers
    execInline(cmd) {
      try { document.execCommand(cmd, false, null) } catch (_) {}
      this.captureInline()
    },
    formatInline(tag) {
      try { document.execCommand('formatBlock', false, tag) } catch (_) {}
      this.captureInline()
    },
    insertInlineLink() {
      const url = prompt('リンクURLを入力してください（https:// または / で始まる）', 'https://')
      if (!url) return
      try { document.execCommand('createLink', false, url) } catch (_) {}
      this.captureInline()
    },
    onInlineInput() { this.captureInline() },
    captureInline() {
      const el = this.$refs.inlineEditor
      if (el) this.contentHtml = el.innerHTML
    },
    postToPreview() {
      let html = this.contentHtml || ''
      if (!html) {
        try {
          const base = this.contentJson ? JSON.parse(this.contentJson) : {}
          if (typeof base.html === 'string') html = base.html
          if (!html && base.texts) {
            html = Object.keys(base.texts).map(k => `<p>${base.texts[k]}</p>`).join('')
          }
        } catch(_) {}
      }
      try { localStorage.setItem(`cms_preview_${this.pageKey}`, html || '') } catch(_) {}
      const frame = this.$refs.hiddenFrame || this.$refs.liveFrame
      if (frame && frame.contentWindow) { frame.contentWindow.postMessage({ type: 'cms-preview', pageKey: this.pageKey, html }, '*') }
    },
    refreshPreview() {
      const frame = this.$refs.hiddenFrame || this.$refs.liveFrame
      if (frame) frame.src = this.previewUrl
      setTimeout(() => this.postToPreview(), 300)
    },
    extractHtmlFromContent() {
      const c = this.formData?.content
      if (!c) return this.contentHtml || ''
      if (typeof c === 'string') return c
      if (typeof c.html === 'string') return c.html
      if (c.texts && typeof c.texts === 'object') {
        const title = c.texts.page_title || c.title || ''
        const lead = c.texts.lead || ''
        let html = ''
        if (title) html += `<h1>${title}</h1>`
        if (lead) html += `<p>${lead}</p>`
        return html
      }
      return this.contentHtml || ''
    },
    execCmd(cmd) {
      document.execCommand(cmd, false, null)
      this.onWysiwygInput()
    },
    formatBlock(block) {
      document.execCommand('formatBlock', false, block)
      this.onWysiwygInput()
    },
    insertLink() {
      const url = prompt('リンクURLを入力してください (https:// または / から開始)')
      if (!url) return
      document.execCommand('createLink', false, url)
      this.onWysiwygInput()
    },
    onWysiwygInput() {
      const el = this.$refs.wysiwyg
      if (el) {
        this.contentHtml = el.innerHTML
      }
    },
    async fetchPageData() {
      this.loading = true
      this.error = ''

      try {
        // 管理APIから取得（未公開でも取得可能）
        // Add cache-buster to avoid stale admin GET after updates
        const res = await apiClient.request('GET', `/api/admin/pages/${this.pageKey}`, null, { silent: true, params: { _t: Date.now() } })
        const body = res?.data || res
        const page = body?.page || body?.data?.page || body
        if (!page) throw new Error('no page data')

        this.formData = {
          page_key: page.page_key || this.pageKey,
          title: page.title || '',
          content: page.content || {},
          meta_description: page.meta_description || '',
          meta_keywords: page.meta_keywords || '',
          is_published: !!page.is_published
        }
        this.contentJson = JSON.stringify(this.formData.content || {}, null, 2)
        // HTML/WYSIWYG 初期値
        try { this.contentHtml = this.extractHtmlFromContent() } catch (_) { this.contentHtml = '' }
        // Fields初期化
        const texts = (this.formData.content && this.formData.content.texts) ? this.formData.content.texts : {}
        this.textsEditor = Object.keys(texts).map((k) => ({ _id: `${k}-${Date.now()}-${Math.random()}`, key: k, value: texts[k] }))
        const links = (this.formData.content && this.formData.content.links) ? this.formData.content.links : {}
        this.linksEditor = Object.keys(links).map((k) => ({ _id: `l-${k}-${Date.now()}-${Math.random()}`, key: k, value: links[k] }))
        if (this.contentMode === 'fields') this.ensureFieldsPreset()
      } catch (err) {
        this.error = 'ページデータの取得に失敗しました'
        console.error(err)
      } finally {
        this.loading = false
      }
    },
    
    // 画像編集機能は無効化しました
    addTextField() {
      this.textsEditor.push({ _id: `new-${Date.now()}-${Math.random()}`, key: '', value: '' })
    },
    removeTextField(index) {
      this.textsEditor.splice(index, 1)
    },
    addLinkField() {
      this.linksEditor.push({ _id: `ln-${Date.now()}-${Math.random()}`, key: '', value: '' })
    },
    removeLinkField(index) {
      this.linksEditor.splice(index, 1)
    },
    hasTextKey(k) {
      const key = (k || '').trim()
      return this.textsEditor.some(r => (r.key || '').trim() === key)
    },
    addMissingRecommendedKeys() {
      const toAdd = (this.recommendedKeys || []).filter(k => !this.hasTextKey(k))
      for (const k of toAdd) {
        this.textsEditor.push({ _id: `rk-${k}-${Date.now()}-${Math.random()}`, key: k, value: '' })
      }
    },
    getKeyLimit(k) {
      const key = (k || '').trim()
      return TEXT_LIMITS[key] || null
    },
    getKeyLabel(k) {
      const key = (k || '').trim()
      return KEY_HELP[key]?.label || ''
    },
    getKeyDesc(k) {
      const key = (k || '').trim()
      return KEY_HELP[key]?.desc || ''
    },
    getKeyPlaceholder(k) {
      const key = (k || '').trim()
      return KEY_HELP[key]?.placeholder || ''
    },
    getKeyLocation(k) {
      const page = (this.formData.page_key || this.pageKey || '').trim()
      const key = (k || '').trim()
      return (KEY_LOCATIONS[page] && KEY_LOCATIONS[page][key]) ? KEY_LOCATIONS[page][key] : ''
    },
    getKeyDevice(k) {
      const page = (this.formData.page_key || this.pageKey || '').trim()
      const key = (k || '').trim()
      const map = KEY_DEVICES[page] || {}
      const v = map[key]
      return v === 'desktop' || v === 'mobile' ? v : 'both'
    },
    getKeyDeviceLabel(k) {
      const d = this.getKeyDevice(k)
      return d === 'desktop' ? 'デスクトップ' : d === 'mobile' ? 'モバイル' : '両方'
    },
    reloadFieldsFromJson() {
      try {
        const base = this.contentJson ? JSON.parse(this.contentJson) : {}
        const texts = base?.texts || {}
        const links = base?.links || {}
        this.textsEditor = Object.keys(texts).map((k) => ({ _id: `${k}-${Date.now()}-${Math.random()}`, key: k, value: texts[k] }))
        this.linksEditor = Object.keys(links).map((k) => ({ _id: `l-${k}-${Date.now()}-${Math.random()}`, key: k, value: links[k] }))
      } catch(e) {
        this.submitError = 'JSONを読み込めませんでした: ' + e.message
      }
    },
    isValidLink(url) {
      if (!url) return true
      return /^\//.test(url) || /^https?:\/\//i.test(url)
    },
    addMissingLinkKeys() {
      const defaults = ['cta_primary', 'cta_secondary']
      const exist = new Set(this.linksEditor.map(r => (r.key||'').trim()))
      defaults.forEach(k => {
        if (!exist.has(k)) {
          let v = ''
          try { v = (this.formData?.content?.links?.[k] || '') } catch(_) {}
          this.linksEditor.push({ _id: `ln-${k}-${Date.now()}-${Math.random()}`, key: k, value: v })
        }
      })
    },
    collectTextWarnings() {
      const warnings = []
      for (const row of this.textsEditor) {
        const key = (row.key || '').trim()
        const limit = this.getKeyLimit(key)
        const len = (row.value || '').length
        if (limit && len > limit) {
          warnings.push(`${key}: ${len}/${limit}`)
        }
      }
      return warnings
    },
    async handleSubmit() {
      this.submitLoading = true
      this.submitError = ''
      this.successMessage = ''

      try {
        // In live-preview mode, ask iframe to save all inline edits first
        if (this.contentMode === 'live') {
          try {
            const frame = this.$refs.liveFrame
            if (frame && frame.contentWindow) {
              frame.contentWindow.postMessage({ type: 'cms-save-all' }, '*')
              // Give a moment for inline PUTs to complete before our own save
              await new Promise(res => setTimeout(res, 500))
            }
          } catch (_) { /* noop */ }
        }

        // Fieldsモードの長文ガード（ソフト）
        if (this.contentMode === 'fields') {
          const warns = this.collectTextWarnings()
          if (warns.length) {
            const proceed = confirm(`一部のフィールドが推奨文字数を超えています:\n\n${warns.join('\n')}\n\nこのまま保存しますか？`)
            if (!proceed) {
              this.submitLoading = false
              return
            }
          }
        }
        // コンテンツのモードに応じて content を設定
        if (this.contentMode === 'html' || this.contentMode === 'wysiwyg' || this.contentMode === 'inline' || this.contentMode === 'live') {
          // フルHTML本文として保存。互換性のため単体 'html' と 'htmls.body' 両方に格納
          // liveモードでは直近のプレビューHTMLを優先して保存
          let html = this.contentHtml || ''
          if (this.contentMode === 'live') {
            try {
              const previewKey = `cms_preview_${this.pageKey}`
              const stored = localStorage.getItem(previewKey)
              if (stored && typeof stored === 'string') html = stored
              if (!html) {
                // fallback: 推定可能なHTMLを抽出
                html = this.extractHtmlFromContent()
              }
            } catch (_) { /* noop */ }
          }
          let base = {}
          try { base = this.contentJson ? JSON.parse(this.contentJson) : {} } catch(e) { base = {} }
          const htmls = { ...(base.htmls || {}) }
          htmls.body = html
          this.formData.content = { ...(base || {}), html, htmls }
        } else if (this.contentMode === 'fields') {
          // Merge with existing JSON if valid, else new object
          let base = {}
          try { base = this.contentJson ? JSON.parse(this.contentJson) : {} } catch(e) { base = {} }
          const texts = {}
          for (const row of this.textsEditor) {
            const k = (row.key || '').trim()
            if (!k) continue
            texts[k] = row.value || ''
          }
          const links = {}
          for (const row of this.linksEditor) {
            const k = (row.key || '').trim()
            if (!k) continue
            if (row.value && !this.isValidLink(row.value)) {
              // 軽いガード：許可形式以外は無視
              continue
            }
            links[k] = row.value || ''
          }
          this.formData.content = { ...(base || {}), texts, links }
        } else {
          try {
            this.formData.content = JSON.parse(this.contentJson)
          } catch (e) {
            this.submitError = 'JSONの形式が正しくありません: ' + e.message
            this.submitLoading = false
            return
          }
        }
        
        // 送信データの準備
        const submitData = { ...this.formData }
        
        if (this.isNew) {
          // 新規作成機能
          if (!submitData.page_key) {
            this.submitError = 'ページキーは必須です'
            this.submitLoading = false
            return
          }
          
          try {
            const res = await apiClient.request('POST', '/api/admin/pages', submitData)
            if (!(res && (res.success || res.id || res.page || res.data))) {
              throw new Error(res?.message || '作成に失敗しました')
            }
            this.successMessage = 'ページを作成しました'
            setTimeout(() => {
              this.$router.push('/admin/pages')
            }, 1500)
          } catch (e) {
            this.submitError = '新規ページ作成に失敗しました: ' + e.message
          }
        } else {
          // 既存ページの更新
          const key = this.formData.page_key || this.pageKey
          const res = await apiClient.request('PUT', `/api/admin/pages/${key}`, submitData)
          if (!(res && (res.success || res.id || res.page || res.data))) {
            throw new Error(res?.message || '更新に失敗しました')
          }
          this.successMessage = 'ページを更新しました'
          
          // 画像に関する付加メッセージは不要
          
          // 更新後に最新データを再取得
          setTimeout(() => {
            this.fetchPageData()
            // ライブプレビューの場合はiframeも更新
            if (this.contentMode === 'live') {
              this.refreshPreview()
            }
          }, 1000)
        }
      } catch (err) {
        if (err.response?.data?.errors) {
          this.submitError = Object.values(err.response.data.errors).flat().join(', ')
        } else {
          this.submitError = this.isNew ? 'ページの作成に失敗しました' : 'ページの更新に失敗しました'
        }
        console.error(err)
      } finally {
        this.submitLoading = false
      }
    },
    goBack() {
      this.$router.push('/admin/pages')
    },
    handleLogout() {
      localStorage.removeItem('admin_token')
      localStorage.removeItem('adminUser')
      delete axios.defaults.headers.common['Authorization']
      this.$router.push('/admin')
    }
  }
}
</script>

<style scoped>
.sr-only-hidden { position: absolute; width: 0; height: 0; overflow: hidden; opacity: 0; pointer-events: none; }
.sr-only-hidden iframe { width: 0; height: 0; border: 0; }
.dashboard {
  background-color: white;
  border-radius: 8px;
  overflow: hidden;
  min-height: calc(100vh - 120px);
}

.page-header {
  display: flex;
  justify-content: space-between;
  align-items: center;
  padding: 20px 24px;
  border-bottom: 1px solid #e5e5e5;
  background-color: #f8f9fa;
}

.header-left {
  display: flex;
  align-items: center;
  gap: 16px;
}

.back-btn {
  background: none;
  border: none;
  color: #0066cc;
  font-size: 14px;
  cursor: pointer;
  padding: 8px 0;
  transition: color 0.2s;
}

.back-btn:hover {
  color: #0052a3;
  text-decoration: underline;
}

.page-title {
  font-size: 24px;
  font-weight: 600;
  color: #1A1A1A;
  margin: 0;
}

.header-actions {
  display: flex;
  gap: 12px;
}

.logout-btn {
  background-color: #1A1A1A;
  color: white;
  border: none;
  padding: 8px 16px;
  border-radius: 4px;
  font-size: 14px;
  cursor: pointer;
  transition: background-color 0.2s;
}

.logout-btn:hover {
  background-color: #555;
}

.form-container {
  padding: 24px;
  max-width: 800px;
  margin: 0 auto;
}

.loading {
  text-align: center;
  padding: 60px 20px;
  color: #666;
  font-size: 16px;
}

.error-container {
  padding: 20px;
  text-align: center;
}

.error-message {
  background-color: #fee2e2;
  border: 1px solid #fca5a5;
  color: #dc2626;
  padding: 16px;
  border-radius: 8px;
  font-size: 14px;
}

.form-section {
  margin-bottom: 32px;
}

.section-title {
  font-size: 18px;
  font-weight: 600;
  color: #1A1A1A;
  margin: 0 0 16px 0;
  padding-bottom: 8px;
  border-bottom: 2px solid #da5761;
}

.form-group {
  margin-bottom: 24px;
}

.form-label {
  display: block;
  font-size: 14px;
  font-weight: 600;
  color: #1A1A1A;
  margin-bottom: 8px;
}

.required {
  color: #dc2626;
  font-weight: 500;
}

.form-input {
  width: 100%;
  padding: 12px 16px;
  border: 2px solid #e5e5e5;
  border-radius: 6px;
  font-size: 14px;
  transition: border-color 0.2s;
  box-sizing: border-box;
  font-family: inherit;
}

.form-input:focus {
  outline: none;
  border-color: #da5761;
}

.form-input.disabled {
  background-color: #f5f5f5;
  color: #666;
  cursor: not-allowed;
}

.form-help {
  font-size: 12px;
  color: #666;
  margin-top: 4px;
  margin-bottom: 0;
}

.json-editor-container {
  position: relative;
}

.json-editor {
  width: 100%;
  padding: 16px;
  border: 2px solid #e5e5e5;
  border-radius: 6px;
  font-size: 13px;
  font-family: 'Monaco', 'Menlo', 'Ubuntu Mono', monospace;
  line-height: 1.5;
  resize: vertical;
  min-height: 300px;
  box-sizing: border-box;
  transition: border-color 0.2s;
}

/* HTML/WYSIWYG editor styles */
.html-editor {
  width: 100%;
  border: 2px solid #e5e5e5;
  border-radius: 6px;
  padding: 12px;
  font-family: ui-monospace, SFMono-Regular, Menlo, Monaco, Consolas, 'Liberation Mono', 'Courier New', monospace;
  font-size: 13px;
}
.html-preview {
  margin-top: 12px;
  border: 1px solid #eee;
  border-radius: 6px;
  padding: 16px;
  background: #fafafa;
}
.editor-toolbar {
  display: flex;
  align-items: center;
  gap: 8px;
  border: 1px solid #d0d0d0;
  border-bottom: none;
  border-radius: 6px 6px 0 0;
  padding: 8px;
  background: #f7f7f7;
}
.tb-btn {
  border: 1px solid #ccc;
  background: white;
  border-radius: 4px;
  padding: 4px 8px;
  font-size: 13px;
  cursor: pointer;
}
.tb-btn:hover { background: #f0f0f0; }
.sep { width: 1px; height: 18px; background: #ddd; display: inline-block; margin: 0 4px; }
.flex-spacer { flex: 1; }
.preview-toggle, .preview-inline { font-size: 13px; color: #555; }
.editor-split {
  display: flex;
  border: 1px solid #d0d0d0;
  border-radius: 0 0 6px 6px;
  min-height: 240px;
}
.editor-split.with-preview .wysiwyg-editor { width: 50%; }
.editor-split.with-preview .wysiwyg-preview { width: 50%; display: block; }
.wysiwyg-editor {
  width: 100%;
  padding: 12px;
  min-height: 240px;
  outline: none;
  background: white;
}
.wysiwyg-preview {
  display: none;
  border-left: 1px solid #eee;
  background: #fafafa;
}
.preview-inner { padding: 12px; }

/* Live preview (iframe) */
.preview-toolbar {
  display: flex;
  align-items: center;
  gap: 12px;
  margin: 8px 0 12px 0;
  font-size: 13px;
  color: #555;
}
.device-toggle { display: flex; align-items: center; gap: 8px; margin-left: auto; }
.device-select { padding: 6px 8px; border: 1px solid #d0d0d0; border-radius: 4px; font-size: 13px; }
.live-preview-wrap {
  overflow-x: auto;
  padding-bottom: 8px;
}
.live-preview-frame {
  margin: 0 auto;
}
.live-preview {
  width: 100%;
  min-height: 700px;
  border: 1px solid #e5e5e5;
  border-radius: 6px;
  background: #fff;
}

/* Inline page-like editor */
.inline-toolbar {
  display: flex;
  align-items: center;
  gap: 8px;
  border: 1px solid #d0d0d0;
  border-bottom: none;
  border-radius: 6px 6px 0 0;
  padding: 8px;
  background: #f7f7f7;
  margin-top: 10px;
}
.inline-surface {
  border: 1px solid #e5e7eb;
  border-radius: 8px;
  background: #f3f4f6;
  padding: 12px;
}
.inline-page {
  background: white;
  border-radius: 8px;
  overflow: hidden;
  box-shadow: 0 2px 8px rgba(0,0,0,0.06);
}
.inline-hero {
  background: #ececec;
  padding: 24px;
  text-align: center;
  color: #666;
  font-weight: 600;
}
.inline-body {
  padding: 24px;
  min-height: 260px;
}
.inline-editor {
  min-height: 220px;
  outline: none;
}
.inline-footer {
  background: #f7f7f7;
  padding: 16px;
  text-align: center;
  color: #777;
}

/* Fields mode styles */
.fields-editor {
  display: flex;
  flex-direction: column;
  gap: 10px;
}

.field-head {
  display: grid;
  grid-template-columns: 1.2fr 2fr auto auto;
  gap: 8px;
  font-size: 12px;
  color: #666;
  padding: 4px 2px;
}

.field-row {
  display: grid;
  grid-template-columns: 1.2fr 2fr auto auto;
  gap: 8px;
  align-items: center;
}

.field-row-warning .field-value {
  border-color: #f59e0b; /* amber-500 */
}

.field-key { }
.field-value { }

.field-hint {
  font-size: 12px;
  color: #666;
}

/* Device indicator and filter */
.device-filter {
  display: flex;
  align-items: center;
  gap: 12px;
  margin: 8px 0 6px 0;
  font-size: 13px;
  color: #555;
}
.df-label { font-weight: 600; color: #374151; }
.df-item { display: inline-flex; align-items: center; gap: 6px; }

.device-badge {
  display: inline-block;
  font-size: 11px;
  line-height: 1;
  padding: 4px 6px;
  border-radius: 9999px;
  border: 1px solid transparent;
}
.dev-both { background: #eef2ff; border-color: #c7d2fe; color: #3730a3; }
.dev-desktop { background: #ecfeff; border-color: #a5f3fc; color: #155e75; }
.dev-mobile { background: #fef3c7; border-color: #fcd34d; color: #92400e; }

.counter {
  background: #f3f4f6;
  border: 1px solid #e5e7eb;
  padding: 2px 6px;
  border-radius: 4px;
}

.key-help {
  margin-top: 4px;
  color: #6b7280;
}

.fields-guide {
  background: #f8fafc;
  border: 1px solid #e5e7eb;
  border-radius: 6px;
  padding: 12px;
  margin-bottom: 10px;
}

.fields-guide .guide-title {
  font-size: 13px;
  font-weight: 600;
  margin-bottom: 6px;
}

.fields-guide ol {
  margin: 0 0 8px 18px;
  padding: 0;
  font-size: 12px;
  color: #4b5563;
}

.fields-guide .guide-actions {
  display: flex;
  gap: 8px;
}

.recommended-keys {
  display: flex;
  flex-direction: column;
  gap: 8px;
  margin-bottom: 10px;
}

.recommended-keys .subtitle {
  font-size: 13px;
  color: #666;
}

.recommended-keys .chips {
  display: flex;
  flex-wrap: wrap;
  gap: 6px;
}

.chip {
  font-size: 12px;
  padding: 4px 8px;
  border-radius: 12px;
  border: 1px solid #e5e5e5;
}

.chip-ok {
  background: #e8f5e9; /* green-50 */
  border-color: #a7f3d0; /* green-200 */
}

.chip-missing {
  background: #fff7ed; /* orange-50 */
  border-color: #fdba74; /* orange-300 */
}

.json-editor:focus {
  outline: none;
  border-color: #da5761;
}

.json-editor.error {
  border-color: #dc2626;
}

.json-error {
  display: flex;
  align-items: center;
  gap: 8px;
  margin-top: 8px;
  padding: 8px 12px;
  background-color: #fee2e2;
  border: 1px solid #fca5a5;
  color: #dc2626;
  border-radius: 4px;
  font-size: 12px;
}

.json-success {
  display: flex;
  align-items: center;
  gap: 8px;
  margin-top: 8px;
  padding: 8px 12px;
  background-color: #d1fae5;
  border: 1px solid #6ee7b7;
  color: #065f46;
  border-radius: 4px;
  font-size: 12px;
}

/* Live preview for Fields mode */
.fields-preview {
  margin: 20px 0;
  border: 1px dashed #d9d9d9;
  border-radius: 8px;
  background: #fafafa;
}
.preview-hero {
  background: linear-gradient(180deg,#f5f5f5,#fff);
  padding: 16px;
  border-radius: 8px;
}
.pv-title { font-size: 20px; margin: 0 0 4px 0; color:#1A1A1A; }
.pv-subtitle { font-size: 12px; color:#9c9c9c; margin-bottom: 8px; }
.pv-lead { font-size: 13px; color:#555; margin: 0 0 12px 0; }
.pv-cta button { font-size: 12px; }

.checkbox-label {
  display: flex;
  align-items: center;
  gap: 12px;
  cursor: pointer;
  padding: 16px;
  border: 2px solid #e5e5e5;
  border-radius: 6px;
  transition: border-color 0.2s;
}

.checkbox-label:hover {
  border-color: #da5761;
}

.checkbox-input {
  display: none;
}

.checkbox-custom {
  width: 20px;
  height: 20px;
  border: 2px solid #d1d5db;
  border-radius: 4px;
  position: relative;
  transition: all 0.2s;
}

.checkbox-input:checked + .checkbox-custom {
  background-color: #da5761;
  border-color: #da5761;
}

.checkbox-input:checked + .checkbox-custom::after {
  content: '';
  position: absolute;
  top: 2px;
  left: 6px;
  width: 4px;
  height: 8px;
  border: 2px solid white;
  border-left: none;
  border-top: none;
  transform: rotate(45deg);
}

.checkbox-text {
  font-size: 14px;
  font-weight: 500;
  color: #1A1A1A;
}

.message {
  padding: 16px;
  border-radius: 6px;
  margin-bottom: 24px;
  font-size: 14px;
}

.success-message {
  background-color: #d1fae5;
  border: 1px solid #6ee7b7;
  color: #065f46;
}

.form-actions {
  display: flex;
  gap: 12px;
  justify-content: flex-end;
  padding-top: 24px;
  border-top: 1px solid #e5e5e5;
}

.btn {
  padding: 12px 24px;
  border: none;
  border-radius: 6px;
  font-size: 14px;
  font-weight: 500;
  cursor: pointer;
  transition: all 0.2s;
  min-width: 120px;
}

.btn-secondary {
  background-color: #f5f5f5;
  color: #1A1A1A;
  border: 1px solid #d1d5db;
}

.btn-secondary:hover {
  background-color: #e5e5e5;
}

.btn-primary {
  background-color: #da5761;
  color: white;
}

.btn-primary:hover:not(:disabled) {
  background-color: #c44853;
}

.btn:disabled {
  opacity: 0.6;
  cursor: not-allowed;
}

/* 画像管理スタイル */
.image-management {
  margin-top: 16px;
}

.image-list {
  display: grid;
  grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
  gap: 20px;
  margin-bottom: 24px;
}

.image-item {
  border: 1px solid #e5e5e5;
  border-radius: 8px;
  overflow: hidden;
  box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
  background-color: white;
}

.image-preview {
  height: 160px;
  overflow: hidden;
  background-color: #f5f5f5;
}

.preview-img {
  width: 100%;
  height: 100%;
  object-fit: cover;
}

.image-details {
  padding: 12px;
}

.image-info {
  margin-bottom: 12px;
}

.image-name {
  font-weight: 500;
  font-size: 14px;
  color: #1A1A1A;
  margin-bottom: 4px;
}

.image-path {
  font-size: 12px;
  color: #666;
  word-break: break-all;
}

.image-actions {
  display: flex;
  gap: 8px;
}

.image-btn {
  flex: 1;
  padding: 8px;
  border-radius: 4px;
  font-size: 12px;
  cursor: pointer;
  border: none;
  transition: all 0.2s;
}

.edit-image-btn {
  background-color: #f5f5f5;
  color: #1A1A1A;
  border: 1px solid #d1d5db;
}

.edit-image-btn:hover {
  background-color: #e5e5e5;
}

.delete-image-btn {
  background-color: #fee2e2;
  color: #dc2626;
  border: 1px solid #fca5a5;
}

.delete-image-btn:hover {
  background-color: #fecaca;
}

.no-images {
  padding: 32px;
  text-align: center;
  background-color: #f9fafb;
  border: 1px dashed #d1d5db;
  border-radius: 8px;
  color: #666;
  margin-bottom: 24px;
}

.add-image-section {
  margin-bottom: 24px;
}

.add-image-btn {
  display: flex;
  align-items: center;
  gap: 8px;
  padding: 12px 16px;
  background-color: #f9fafb;
  border: 1px dashed #d1d5db;
  border-radius: 8px;
  color: #1A1A1A;
  font-size: 14px;
  cursor: pointer;
  transition: all 0.2s;
  width: 100%;
  justify-content: center;
}

.add-image-btn:hover {
  background-color: #f3f4f6;
  border-color: #da5761;
  color: #da5761;
}

.add-image-btn svg {
  transition: all 0.2s;
}

.add-image-btn:hover svg {
  color: #da5761;
}

/* モーダル */
.modal-overlay {
  position: fixed;
  top: 0;
  left: 0;
  right: 0;
  bottom: 0;
  background-color: rgba(0, 0, 0, 0.5);
  display: flex;
  align-items: center;
  justify-content: center;
  z-index: 1000;
}

.modal-container {
  background-color: white;
  border-radius: 8px;
  box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
  width: 100%;
  max-width: 600px;
  max-height: 90vh;
  overflow-y: auto;
}

.modal-header {
  display: flex;
  justify-content: space-between;
  align-items: center;
  padding: 16px 24px;
  border-bottom: 1px solid #e5e5e5;
}

.modal-header h3 {
  margin: 0;
  font-size: 18px;
  font-weight: 600;
  color: #1A1A1A;
}

.close-modal {
  background: none;
  border: none;
  font-size: 24px;
  color: #666;
  cursor: pointer;
  padding: 0;
  line-height: 1;
}

.modal-body {
  padding: 24px;
}

.modal-footer {
  display: flex;
  justify-content: flex-end;
  gap: 12px;
  padding: 16px 24px;
  border-top: 1px solid #e5e5e5;
}

.file-input {
  padding: 12px;
  border: 1px solid #e5e5e5;
  border-radius: 6px;
  width: 100%;
  background-color: #f9fafb;
}

.image-preview-container {
  margin: 16px 0;
  text-align: center;
}

.image-preview-large {
  max-width: 100%;
  max-height: 300px;
  border: 1px solid #e5e5e5;
  border-radius: 4px;
}

/* レスポンシブ対応 */
@media (max-width: 768px) {
  .page-header {
    flex-direction: column;
    gap: 16px;
    align-items: stretch;
  }
  
  .header-left {
    flex-direction: column;
    align-items: flex-start;
    gap: 8px;
  }
  
  .form-container {
    padding: 16px;
  }
  
  .page-title {
    font-size: 20px;
  }
  
  .form-actions {
    flex-direction: column-reverse;
  }
  
  .btn {
    width: 100%;
  }
  
  .json-editor {
    font-size: 12px;
    min-height: 250px;
  }
}

@media (max-width: 480px) {
  .page-header {
    padding: 16px;
  }
  
  .form-container {
    padding: 12px;
  }
  
  .page-title {
    font-size: 18px;
  }
  
  .form-section {
    margin-bottom: 24px;
  }
  
  .json-editor {
    min-height: 200px;
  }
}
</style>
