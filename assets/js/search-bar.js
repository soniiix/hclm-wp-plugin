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
            // Remove all existing tags
            tagbox.querySelectorAll('.hclm-keywords-tag').forEach(tag => tag.remove());
            tags.forEach((tag, idx) => {
                const tagEl = document.createElement('span');
                tagEl.className = 'hclm-keywords-tag';
                tagEl.textContent = tag;
                // Add the remove cross
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
            checkFormFields(); // Update submit button state
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


    /* SEARCH FORM VALIDATION */
    const form = document.querySelector('#hclm-search-popup form');
    const submitBtn = form?.querySelector('button[type="submit"]');
    const main = form?.querySelector('input[name="s"]');
    const keywords = form?.querySelector('input[name="keywords"]');
    const exclude = form?.querySelector('input[name="exclude"]');

    // Check if the form fields are filled and enable/disable the submit button accordingly
    function checkFormFields() {
        const hasMain = main && main.value.trim() !== '';
        const hasKeywords = keywords && keywords.value.trim() !== '';
        const hasExclude = exclude && exclude.value.trim() !== '';
        if (hasMain || hasKeywords || hasExclude) {
            submitBtn.removeAttribute('disabled');
            submitBtn.classList.remove('disabled');
        } else {
            submitBtn.setAttribute('disabled', 'disabled');
            submitBtn.classList.add('disabled');
        }
    }

    [main, keywords, exclude].forEach(field => {
        if (field) {
            field.addEventListener('input', checkFormFields);
            field.addEventListener('change', checkFormFields);
        }
    });

    checkFormFields();

    if (form) {
        form.addEventListener('submit', function (e) {
            // Fallback to prevent form submission if the submit button is disabled
            if (submitBtn.hasAttribute('disabled')) {
                e.preventDefault();
            }
        });
    }
});
