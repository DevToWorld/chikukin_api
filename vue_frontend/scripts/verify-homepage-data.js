// Run this script in browser console on HomePage to verify data display
// This script checks what data is actually being shown on the HomePage

console.log('🔍 HomePage Data Verification Script');
console.log('====================================');

// Check localStorage data
const STORAGE_KEY = 'cms_mock_data';
const storedData = localStorage.getItem(STORAGE_KEY);

if (storedData) {
    const data = JSON.parse(storedData);
    console.log('📦 LocalStorage Data Status:');
    console.log(`- Seminars: ${data.seminars?.length || 0}`);
    console.log(`- Publications: ${data.publications?.length || 0}`);
    console.log(`- Notices: ${data.notices?.length || 0}`);
    console.log('');
} else {
    console.log('❌ No data found in localStorage');
    console.log('');
}

// Check what's displayed on the HomePage
console.log('🖥️ HomePage Display Status:');

// Check seminar counter
const seminarElement = document.querySelector('.seminar');
if (seminarElement) {
    console.log(`- Seminar text: "${seminarElement.textContent.trim()}"`);
} else {
    console.log('- Seminar element not found');
}

// Check publications counter  
const publicationsElement = document.querySelector('.publications');
if (publicationsElement) {
    console.log(`- Publications text: "${publicationsElement.textContent.trim()}"`);
} else {
    console.log('- Publications element not found');
}

// Check information/notices counter
const InformationElement = document.querySelector('.Information');
if (InformationElement) {
    console.log(`- Information text: "${InformationElement.textContent.trim()}"`);
} else {
    console.log('- Information element not found');
}

// Check news/information items in the Information section
console.log('');
console.log('📰 News Items Displayed:');

// Look for date and title elements in the information section
const dateElements = document.querySelectorAll('.date');
const hotInfoElements = document.querySelectorAll('.hot-info, .text-58');

if (dateElements.length > 0) {
    console.log(`- Found ${dateElements.length} date elements:`);
    dateElements.forEach((el, index) => {
        const dateText = el.textContent.trim();
        
        // Try to find corresponding title element
        const parent = el.closest('.frame-13');
        let titleText = '';
        if (parent) {
            const titleEl = parent.querySelector('.hot-info, .text-58');
            titleText = titleEl ? titleEl.textContent.trim() : 'No title found';
        }
        
        console.log(`  ${index + 1}. Date: "${dateText}" | Title: "${titleText}"`);
    });
} else {
    console.log('- No date elements found');
}

console.log('');
console.log('🎯 Verification Summary:');
console.log('- If you see updated dates and titles above, the data is working!');
console.log('- The HomePage should show the latest news items from the mock server');
console.log('- Counter texts should reflect the number of items in each category');
console.log('');

// Check if Vue app instance is available and try to trigger data reload
if (window.Vue) {
    console.log('🔄 Vue detected - attempting to trigger data reload...');
    // This will depend on the Vue app structure
} else {
    console.log('ℹ️  Vue not detected in global scope');
}

console.log('Run this script after refreshing the page to see updated data!');