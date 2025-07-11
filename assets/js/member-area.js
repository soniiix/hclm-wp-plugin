/**
 * Shows the specified section in the member area. Switches the active tab and displays the corresponding content section.
 * @param {string} sectionId - The ID of the section to show (e.g., 'profile', 'reports', 'membership').
 */
function showSection(sectionId) {
    const tabs = document.querySelectorAll('.sidebar li');
    const sections = document.querySelectorAll('.tab-content');

    tabs.forEach(tab => {
        tab.classList.toggle('active', tab.getAttribute('data-tab') === sectionId);
    });

    sections.forEach(section => {
        section.classList.toggle('active', section.id === sectionId);
    });
}



document.addEventListener('DOMContentLoaded', function () {
    /* =========================== */
    /*          SIDEBAR            */
    /* =========================== */

    /* SIDEBAR TOGGLE */
    const tabs = document.querySelectorAll('.sidebar li');
    const sections = document.querySelectorAll('.tab-content');

    tabs.forEach(tab => {
        tab.addEventListener('click', () => {
            const target = tab.getAttribute('data-tab');

            tabs.forEach(tab => {
                tab.classList.toggle('active', tab.getAttribute('data-tab') === target);
            });

            sections.forEach(section => {
                section.classList.toggle('active', section.id === target);
            });

            const sidebar = document.querySelector('.sidebar');

            const paymentsRows = document.querySelectorAll('tr');
            
            // Apply 80vh height for reports and suggestions tabs if they are active, and if membership tab is active and there are more than 4 rows in payments table
            if (target === 'reports' || target === 'suggestions' || (target === 'membership' && (paymentsRows && paymentsRows.length > 4))) {
                sidebar.classList.add('sidebar-80vh');
            } else {
                sidebar.classList.remove('sidebar-80vh');
            }

            // Adjust sidebar height based on scroll position and header visibility
            document.addEventListener('scroll', () => {
                const header = document.querySelector('header');
                const headerVisible = header
                    ? header.getBoundingClientRect().bottom > 0
                    : false;

                if ((target === 'reports' || target === 'suggestions' || (target === 'membership' && (paymentsRows && paymentsRows.length > 4))) && !headerVisible) {
                    sidebar.classList.add('sidebar-90vh');
                } else {
                    sidebar.classList.remove('sidebar-90vh');
                }
            });
        });
    });



    /* =========================== */
    /*          PROFILE            */
    /* =========================== */

    /* ENABLE EDITING FORM INPUTS */
    document.querySelectorAll('.edit-icon').forEach(icon => {
        icon.addEventListener('click', () => {
            const input = icon.previousElementSibling;
            input.disabled = false;
            input.focus();
            const val = input.value;
            input.value = '';
            input.value = val;
            icon.style.display = 'none';
        });
    });

    /* PROFILE UPDATE MESSAGE */
    const message = document.querySelector("#profile-update-message");
    if (message) {
        showSection('profile');
        setTimeout(() => {
            message.classList.add("fade-out");
            setTimeout(() => {
                message.remove();
            }, 600);
        }, 8000);
    }

    /* PROFILE PICTURE UPLOAD */
    const profilePictureInput = document.querySelector('.profile-picture-input');
    const editPictureBtn = document.querySelector('.edit-picture-btn');
    const profilePicture = document.querySelector('.profile-picture');

    if (profilePictureInput && editPictureBtn) {
        editPictureBtn.addEventListener('click', () => {
            profilePictureInput.click();
        });

        profilePictureInput.addEventListener('change', (event) => {
            const file = event.target.files[0];
            const reader = new FileReader();

            console.log(reader);
            reader.readAsDataURL(file);

            console.log(profilePicture);
            reader.onloadend = () => {
                profilePicture.src = reader.result;
            }
        });
    }



    /* =========================== */
    /*          REPORTS            */
    /* =========================== */

    /* REPORTS FILTERING AND SORTING */
    const noResultsMessage = document.getElementById('no-results-message');
    const yearSelect = document.getElementById('filter-year');
    const typeSelect = document.getElementById('filter-type');
    const searchInput = document.getElementById('search-input');
    const sortSelect = document.getElementById('sort-date');
    const reportCards = Array.from(document.querySelectorAll('.report-card'));
    const reportsContainer = document.querySelector('.reports-list');

    // Populate date filter options
    const years = new Set();
    reportCards.forEach(card => {
        const year = card.dataset.year;
        if (year) years.add(year);
    });

    [...years].sort((a, b) => b - a).forEach(year => {
        const option = document.createElement('option');
        option.value = year;
        option.textContent = year;
        yearSelect?.appendChild(option);
    });

    function filterAndSortReports() {
        const selectedYear = yearSelect?.value || '';
        const selectedType = typeSelect?.value || '';
        const searchQuery = searchInput?.value.toLowerCase() || '';
        const sortOrder = sortSelect?.value || 'desc';

        let visibleCards = reportCards.filter(card => {
            const year = card.dataset.year;
            const type = card.dataset.type;
            const date = card.dataset.date;
            const title = card.querySelector('.report-title')?.textContent.toLowerCase() || '';

            const matchYear = !selectedYear || year === selectedYear;
            const matchType = !selectedType || type === selectedType;

            const normalizedQuery = searchQuery.trim().toLowerCase();
            const matchSearch =
                !normalizedQuery ||
                title.includes(normalizedQuery) ||
                (date && date.includes(normalizedQuery));

            return matchYear && matchType && matchSearch;
        });

        visibleCards.sort((a, b) => {
            const dateA = new Date(a.dataset.date);
            const dateB = new Date(b.dataset.date);
            return sortOrder === 'asc' ? dateA - dateB : dateB - dateA;
        });

        if (!reportsContainer) return;

        reportsContainer.innerHTML = '';
        visibleCards.forEach(card => reportsContainer.appendChild(card));

        reportCards.forEach(card => {
            card.style.display = visibleCards.includes(card) ? '' : 'none';
        });

        noResultsMessage.style.display = visibleCards.length === 0 ? 'block' : 'none';
    }

    [yearSelect, typeSelect, searchInput, sortSelect].forEach(el => {
        if (el) el.addEventListener('input', filterAndSortReports);
    });

    filterAndSortReports();



    /* =========================== */
    /*         MEMBERSHIP          */
    /* =========================== */
    
    /* CUSTOMIZE PAYMENT TABLE */
    const subscriptionTh = document.querySelector('.pms-payment-subscription-plan');
    if (subscriptionTh) {
        subscriptionTh.textContent = 'Intitulé';
    }
    
    /* MEMBERSHIP PMS ACTION HANDLING */
    const params = new URLSearchParams(window.location.search);
    if (params.has("pms-action")) {
        const pmsPopupOverlay = document.querySelector('.pms-action-popup-overlay');

        window.addEventListener('click', (e) => {
            if (e.target === pmsPopupOverlay) {
                pmsPopupOverlay.style.display = 'none';
            }
        });

        // Customize pms-account output
        const pmsForm = document.querySelector('.pms-form');
        const pmsFormParagraph = document.querySelector('.pms-form p');
        const pmsFormTitle = document.querySelector('.pms-field-type-heading h3');
        const pmsStripeWrapper = document.querySelector('#pms-stripe-connect');
        const pmsRedirectBackButton = document.getElementsByName('pms_redirect_back')[0];
        const pmsSuccessMessage = document.querySelector('.pms_success-messages-wrapper');
        const pmsErrorMessage = document.querySelector('.pms-payment-error');
        const pmsSubscriptionDuration = document.querySelector('.pms-subscription-plan-duration');
        const pmsSubscriptionPlan = document.querySelector('.pms-subscription-plan');
        const pmsSubscriptionAutoRenew = document.querySelector('.pms-subscription-plan-auto-renew');
        if (pmsSuccessMessage) pmsSuccessMessage.style.display = 'none';
        if (pmsErrorMessage) {
            errorIcon = document.createElement('i');
            errorIcon.classList.add('far', 'fa-times-circle');
            pmsErrorMessageParagraph = pmsErrorMessage.querySelector('p:first-child');
            pmsErrorMessageParagraph.insertBefore(errorIcon, pmsErrorMessageParagraph.firstChild);
            if (!pmsForm) {
                const closeBtn = document.createElement('div');
                closeBtn.className = ('pms-action-popup-close-btn pms-error');
                closeBtn.textContent = 'Fermer';
                closeBtn.addEventListener('click', (e) => {
                    e.preventDefault();
                    pmsPopupOverlay.style.display = 'none';
                });
                document.querySelector('.pms-action-popup').appendChild(closeBtn);
            }
        }
        if (pmsSubscriptionDuration) pmsSubscriptionDuration.style.setProperty('display', 'none', 'important');
        if (pmsSubscriptionPlan) {
            pmsSubscriptionPlan.style.marginBottom = '13px';
            popupSpan = document.querySelector('.pms-action-popup div');
            if (popupSpan) pmsSubscriptionPlan.appendChild(popupSpan);
        }
        if (pmsSubscriptionAutoRenew) pmsSubscriptionAutoRenew.style.marginBottom = '2em';
        let pmsSubmitButton = null;

        if (pmsForm) {
            // Show the popup
            pmsForm.style.display = 'block';
            pmsForm.style.marginBottom = '5px';

            // Remove the default styles
            if (pmsFormParagraph) pmsFormParagraph.style.display = 'none';
            if (pmsFormTitle) pmsFormTitle.style.display = 'none';
            if (pmsStripeWrapper) {
                pmsStripeWrapper.style.border = 'none';
                pmsStripeWrapper.style.setProperty('padding', '0', 'important');
                pmsStripeWrapper.style.boxShadow = 'none';
                pmsStripeWrapper.style.margin = '-15px 0px 0px 0px';
            }

            switch (params.get("pms-action")) {
                case "update_payment_method":
                    pmsSubmitButton = document.getElementsByName('pms_update_payment_method')[0];
                    if (pmsSubmitButton) pmsSubmitButton.value = 'Mettre à jour le moyen de paiement';
                    break;
                case "cancel_subscription":
                    pmsSubmitButton = document.getElementsByName('pms_confirm_cancel_subscription')[0];
                    break;
                case "renew_subscription":
                    pmsSubmitButton = document.getElementsByName('pms_renew_subscription')[0];
                    if (pmsSubmitButton) pmsSubmitButton.value = "Renouveler l'adhésion";
                    break;
            }

            // Customize the buttons : change the text and style
            if (pmsRedirectBackButton) {
                pmsRedirectBackButton.value = 'Annuler';
                pmsRedirectBackButton.classList.add('pms-action-popup-close-btn');

                // Create a container for the buttons
                actionButtonsContainer = document.createElement('div');
                actionButtonsContainer.classList.add('hclm-popup-action-row');
                pmsForm.appendChild(actionButtonsContainer);
                actionButtonsContainer.appendChild(pmsRedirectBackButton);
                actionButtonsContainer.appendChild(pmsSubmitButton);
                pmsRedirectBackButton.addEventListener('click', (e) => {
                    e.preventDefault();
                    pmsPopupOverlay.style.display = 'none';
                });
            }
        }
        showSection('membership');
    }

    if (params.has('pmsscsmsg')) {
        showSection('membership');
    }

    const membershipMessage = document.querySelector("#membership-update-message");
    if (membershipMessage) {
        showSection('membership');
        setTimeout(() => {
            membershipMessage.classList.add("fade-out");
            setTimeout(() => {
                membershipMessage.remove();
            }, 600);
        }, 8000);
    }
});