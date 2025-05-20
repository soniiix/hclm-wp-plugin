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
    function setupTagBox(tagboxId, inputId, hiddenId) {
        const tagbox = document.getElementById(tagboxId);
        const input = document.getElementById(inputId);
        const hidden = document.getElementById(hiddenId);
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
            }
        });
    }

    setupTagBox('hclm-keywords-tagbox', 'hclm-keywords-input', 'hclm-keywords-hidden');
    setupTagBox('hclm-exclude-tagbox', 'hclm-exclude-input', 'hclm-exclude-hidden');
});
