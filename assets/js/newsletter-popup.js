document.addEventListener('DOMContentLoaded', function () {
    const cards = document.querySelectorAll('.newsletter-card');
    const popup = document.getElementById('newsletter-popup');
    const viewer = document.getElementById('newsletter-viewer');
    const closeBtn = document.querySelector('.popup-close');
    const overlay = document.querySelector('.popup-overlay');
    const title = document.getElementById('newsletter-title');

    cards.forEach(card => {
        card.addEventListener('click', () => {
            const pdfSrc = card.getAttribute('data-pdf');
            const bulletinNumber = card.getAttribute('data-bulletin');
            viewer.setAttribute('src', pdfSrc);
            title.textContent = `Bulletin n°${bulletinNumber} - Table des matières ci-dessous`;
            popup.classList.remove('hidden');
        });
    });

    const closePopup = () => {
        popup.classList.add('hidden');
        viewer.setAttribute('src', '');
    };

    closeBtn.addEventListener('click', closePopup);
    overlay.addEventListener('click', closePopup);
});
