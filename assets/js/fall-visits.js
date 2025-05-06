document.addEventListener('DOMContentLoaded', () => {
    const searchInput = document.querySelector('.hclm-visits-searchbar');
    const visitCards = Array.from(document.querySelectorAll('.visit-card')).map(card => ({
        element: card,
        title: card.querySelector('.visit-title').textContent.toLowerCase(),
        date: card.querySelector('.visit-date').textContent.toLowerCase()
    }));

    searchInput.addEventListener('input', e => {
        const searchTerm = e.target.value.toLowerCase();

        visitCards.forEach(({ element, title, date }) => {
            if (title.includes(searchTerm) || date.includes(searchTerm)) {
                element.style.display = '';
            } else {
                element.style.display = 'none';
            }
        });
    });
});
