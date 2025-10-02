# CMS Test Data Scripts

This directory contains scripts to add comprehensive test data to the CMS mock server and verify it displays correctly on HomePage.vue.

## Files Overview

1. **add-test-data.js** - Node.js/Browser script with comprehensive test data
2. **test-data-browser.html** - Browser-based test data manager (GUI)
3. **browser-console-script.js** - Simple browser console script
4. **verify-homepage-data.js** - Script to verify data display on HomePage
5. **README.md** - This documentation

## Test Data Included

### Seminars (6 items)
- **Ongoing (1)**: 採用力強化！経営・人事向け　面接官トレーニングセミナー
- **Scheduled (3)**: DX推進セミナー, 九州地域経済フォーラム2025, AI活用セミナー
- **Completed (2)**: 事業承継セミナー, 女性活躍推進セミナー

### Publications (7 items)
- **Monthly Reports (3)**: HOT Information Vol.325, 324, 323
- **Books (2)**: 経営参考BOOK vol.58, 57
- **Others (2)**: 九州経済白書2025, スタートアップ支援ガイド2025

### Notices (8 items)
- **Important (4)**: 年末年始休業, セミナーシステムメンテナンス, 夏季休業, etc.
- **Regular (4)**: ホームページリニューアル, フォーラム割引, 新刊発刊, etc.

### Other Data
- **Media files**: 3 items
- **Members**: 4 items  
- **Pages**: 2 items

## Usage Methods

### Method 1: Browser GUI (Recommended)

1. Open the test data manager HTML file:
   ```bash
   open /Users/kuwatataiga/modeitest/scripts/test-data-browser.html
   ```

2. Click "📊 Add Test Data" button
3. Click "🏠 Open HomePage" to view results
4. Use other buttons to view or clear data as needed

### Method 2: Browser Console Script

1. Go to your HomePage (http://localhost:8080)
2. Open browser developer tools (F12)
3. Go to Console tab
4. Copy and paste the contents of `browser-console-script.js`
5. Press Enter to execute
6. The page will automatically refresh after 3 seconds

### Method 3: Manual Console Commands

Open browser console on HomePage and run:

```javascript
// Add test data
const testData = { /* paste test data object here */ };
localStorage.setItem('cms_mock_data', JSON.stringify(testData));

// Reload page
window.location.reload();
```

## Verification

### Check Data Display

1. After adding test data, refresh HomePage (http://localhost:8080)
2. Run the verification script in browser console:
   ```javascript
   // Copy and paste contents of verify-homepage-data.js
   ```

### Expected Results on HomePage

1. **Information Section**: Should show latest 5 news items with updated dates and titles
2. **Seminar Counter**: Should show "セミナー(4件)" (4 upcoming seminars)
3. **Publications Counter**: Should show "刊行物(7件)"
4. **Notices Counter**: Should show important notices count

### Visual Indicators

- News items should have recent dates (2025-08-xx, 2025-09-xx, etc.)
- Titles should be realistic Japanese content
- Counters should reflect actual data quantities

## Troubleshooting

### Data Not Showing
1. Check browser console for errors
2. Verify localStorage contains data: `localStorage.getItem('cms_mock_data')`
3. Refresh the page after adding data
4. Check if mockServer.js is properly importing data

### Server Issues
1. Verify development server is running: `npm run start`
2. Check server is accessible at http://localhost:8080
3. Look for Vue.js errors in browser console

### Data Persistence
- Data is stored in browser localStorage
- Clearing browser data will remove test data
- Data persists across browser sessions
- Different browsers have separate localStorage

## Development Notes

### Mock Server Integration
The test data integrates with the mock server (`src/mockServer.js`) which:
- Uses localStorage with key 'cms_mock_data'
- Provides API-like methods (getSeminars, getPublications, etc.)
- Formats data for HomePage consumption via getAllNews()

### HomePage Data Flow
1. HomePage.vue imports mockServer
2. mounted() calls loadLatestData()
3. loadLatestData() fetches data via mockServer methods
4. Data is formatted and displayed in template

### Data Structure Requirements
- Seminars need: id, title, date, status ('ongoing'|'scheduled'|'completed')
- Publications need: id, title, publishedDate, category
- Notices need: id, title, date, isImportant boolean
- Dates should be in 'YYYY-MM-DD' format

## Files Location

All scripts are located in: `/Users/kuwatataiga/modeitest/scripts/`

The main application files are:
- HomePage: `/Users/kuwatataiga/modeitest/src/components/HomePage.vue`
- MockServer: `/Users/kuwatataiga/modeitest/src/mockServer.js`