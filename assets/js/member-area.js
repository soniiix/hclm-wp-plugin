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
        });
    });
});

document.addEventListener('DOMContentLoaded', () => {
    document.querySelectorAll('.edit-icon').forEach(icon => {
        icon.addEventListener('click', () => {
            const input = icon.previousElementSibling;
            input.disabled = false;
            input.focus();
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
