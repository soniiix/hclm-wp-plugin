document.addEventListener('DOMContentLoaded', function () {

    /* SEARCH POPUP */
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


    /* KEYWORDS TAGBOX */
    const tagbox = document.getElementById('hclm-keywords-tagbox');
    const input = document.getElementById('hclm-keywords-input');
    const hidden = document.getElementById('hclm-keywords-hidden');
    let tags = [];

    function updateHidden() {
        hidden.value = tags.join(',');
    }

    function renderTags() {
        tagbox.querySelectorAll('.hclm-keywords-tag').forEach(tag => tag.remove());
        tags.forEach((tag, idx) => {
            const tagEl = document.createElement('span');
            tagEl.className = 'hclm-keywords-tag';
            tagEl.textContent = tag;

            const remove = document.createElement('span');
            remove.className = 'hclm-keywords-remove-tag';
            remove.textContent = '×';
            remove.onclick = function () {
                tags.splice(idx, 1);
                renderTags();
                updateHidden();
            };
            tagEl.appendChild(remove);
            tagbox.insertBefore(tagEl, input);
        });
        updateHidden();
    }

    input.addEventListener('keydown', function (e) {
        if ((e.key === 'Enter' || e.key === ',') && input.value.trim() !== '') {
            e.preventDefault();
            const value = input.value.trim();
            if (value && !tags.includes(value)) {
                tags.push(value);
                renderTags();
            }
            input.value = '';
        } else if (e.key === 'Backspace' && input.value === '' && tags.length) {
            tags.pop();
            renderTags();
        }
    });
});
