/* SIDEBAR HANDLING */
document.addEventListener('DOMContentLoaded', function () {
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
            
            // Apply 80vh height for reports and suggestions tabs if they are active
            if (target === 'reports' || target === 'suggestions') {
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

                if ((target === 'reports' || target === 'suggestions') && !headerVisible) {
                    sidebar.classList.add('sidebar-90vh');
                } else {
                    sidebar.classList.remove('sidebar-90vh');
                }
            });
        });
    });
});

/* EDITABLE FIELDS (PROFILE SECTION) */
document.addEventListener('DOMContentLoaded', () => {
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
});

function showReports(){
    const tabs = document.querySelectorAll('.sidebar li');
    const sections = document.querySelectorAll('.tab-content');

    tabs.forEach(tab => {
        tab.classList.toggle('active', tab.getAttribute('data-tab') === "reports");
    });

    sections.forEach(section => {
        section.classList.toggle('active', section.id === "reports");
    });
}

function showProfile(){
    const tabs = document.querySelectorAll('.sidebar li');
    const sections = document.querySelectorAll('.tab-content');

    tabs.forEach(tab => {
        tab.classList.toggle('active', tab.getAttribute('data-tab') === "profile");
    });

    sections.forEach(section => {
        section.classList.toggle('active', section.id === "profile");
    });
}

/* REPORTS FILTERING AND SORTING */
document.addEventListener('DOMContentLoaded', () => {
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
});

document.addEventListener("DOMContentLoaded", function () {
    /* PROFILE UPDATE MESSAGE */
    const message = document.querySelector(".update-message");
    if (message) {
        showProfile();
        setTimeout(() => {
            message.classList.add("fade-out");
            setTimeout(() => {
                message.remove();
            }, 600);
        }, 5000);
    }

    // Handle profile picture upload
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

    /* MEMBERSHIP PAYMENT HISTORY */
    const subscriptionTh = document.querySelector('.pms-payment-subscription-plan');
    if (subscriptionTh) {
        subscriptionTh.textContent = 'Intitulé';
    }
});