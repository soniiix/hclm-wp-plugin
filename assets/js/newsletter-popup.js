document.addEventListener('DOMContentLoaded', function () {
    const cards = document.querySelectorAll('.newsletter-card');
    const popup = document.getElementById('newsletter-popup');
    const viewer = document.getElementById('newsletter-viewer');
    const closeBtn = document.querySelector('.popup-close');
    const overlay = document.querySelector('.popup-overlay');

    cards.forEach(card => {
        card.addEventListener('click', () => {
            const pdfSrc = card.getAttribute('data-pdf');
            viewer.setAttribute('source', pdfSrc);
            popup.classList.remove('hidden');

            // Si le plugin DFLIP est utilisé, il faut le recharger manuellement :
            if (typeof dFlipLocation !== 'undefined' && typeof dFlipBooks !== 'undefined') {
                if (dFlipBooks.length) {
                    dFlipBooks.forEach(book => book.destroy());
                }
                dFlipInit(); // Recharge les viewers
            }
        });
    });

    const closePopup = () => {
        popup.classList.add('hidden');
        viewer.setAttribute('source', '');

        if (typeof dFlipLocation !== 'undefined' && typeof dFlipBooks !== 'undefined') {
            if (dFlipBooks.length) {
                dFlipBooks.forEach(book => book.destroy());
            }
        }
    };

    closeBtn.addEventListener('click', closePopup);
    overlay.addEventListener('click', closePopup);
});
