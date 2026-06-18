/* ============================================
   SportVenue — Core JavaScript
   ============================================ */

// Dark mode persistence
(function() {
    const stored = localStorage.getItem('darkMode');
    if (stored === 'true') {
        document.documentElement.classList.add('dark');
    }
})();
