document.addEventListener('DOMContentLoaded', function () {
    const slider = document.querySelector('.hclm-events-slider');
    const next = document.querySelector('.next-slide');
    const prev = document.querySelector('.prev-slide');

    next?.addEventListener('click', () => {
        slider.scrollBy({ left: 300, behavior: 'smooth' });
    });

    prev?.addEventListener('click', () => {
        slider.scrollBy({ left: -300, behavior: 'smooth' });
    });
});
