document.addEventListener('DOMContentLoaded', () => {
    document.querySelectorAll('.newsletter-card').forEach(card => {
        const target = document.querySelector(card.dataset.target);

        card.addEventListener('click', () => {
            target.classList.remove('hidden');
        });

        target.querySelector('.popup-close').addEventListener('click', () => {
            target.classList.add('hidden');
        });

        target.querySelector('.popup-overlay').addEventListener('click', () => {
            target.classList.add('hidden');
        });
    });
});
