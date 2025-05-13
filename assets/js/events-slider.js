document.addEventListener('DOMContentLoaded', function () {
    const slider = document.querySelector('.hclm-events-slider');
    const cardWidth = document.querySelector('.event-card')?.offsetWidth + 16;

    document.querySelector('.next-slide').addEventListener('click', function () {
        slider.scrollBy({ left: cardWidth, behavior: 'smooth' });
    });

    document.querySelector('.prev-slide').addEventListener('click', function () {
        slider.scrollBy({ left: -cardWidth, behavior: 'smooth' });
    });
});
