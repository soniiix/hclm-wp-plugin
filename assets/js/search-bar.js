document.addEventListener('DOMContentLoaded', function () {
    const searchBar = document.querySelector('.hclm-search-bar-wrapper');
    const popupSearchBar = document.querySelector('.hclm-popup-search-bar');
    const popup = document.getElementById('hclm-search-popup');
    const closeBtn = document.querySelector('.hclm-popup-close-btn');

    searchBar?.addEventListener('click', () => {
        popup.style.display = 'flex';
        popupSearchBar.focus();
    });

    closeBtn?.addEventListener('click', () => {
        popup.style.display = 'none';
    });

    window.addEventListener('click', (e) => {
        if (e.target === popup) {
            popup.style.display = 'none';
        }
    });
});
