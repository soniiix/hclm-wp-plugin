document.addEventListener('DOMContentLoaded', () => {
    const searchInput = document.querySelector('.hclm-products-searchbar');
    const productCards = Array.from(document.querySelectorAll('.product-card')).map(card => ({
        element: card,
        title: card.querySelector('.product-title').textContent.toLowerCase(),
        price: card.querySelector('.product-price').textContent.toLowerCase()
    }));

    searchInput.addEventListener('input', e => {
        const searchTerm = e.target.value.toLowerCase();

        productCards.forEach(({ element, title, price }) => {
            if (title.includes(searchTerm) || price.includes(searchTerm)) {
                element.style.display = '';
            } else {
                element.style.display = 'none';
            }
        });
    });
});
