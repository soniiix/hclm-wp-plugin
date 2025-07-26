document.addEventListener('DOMContentLoaded', function () {
    const slider = document.querySelector('.hclm-events-slider');
    const cards = slider.querySelectorAll('.event-card');
    const prevBtn = document.querySelector('.prev-slide');
    const nextBtn = document.querySelector('.next-slide');
    const bulletsContainer = document.querySelector('.slider-bullets');

    if (!slider || cards.length === 0) return;

    let currentIndex = 0;
    const cardWidth = cards[0].offsetWidth + 16;

    // Bullets
    cards.forEach((_, i) => {
        const bullet = document.createElement('span');
        bullet.className = 'bullet' + (i === 0 ? ' active' : '');
        bullet.addEventListener('click', () => goToSlide(i));
        bulletsContainer.appendChild(bullet);
    });

    function updateBullets(idx) {
        bulletsContainer.querySelectorAll('.bullet').forEach((b, i) => {
            b.classList.toggle('active', i === idx);
        });
    }

    function goToSlide(idx) {
        currentIndex = Math.max(0, Math.min(idx, cards.length - 1));
        slider.scrollTo({ left: currentIndex * cardWidth, behavior: 'smooth' });
        updateBullets(currentIndex);
    }

    nextBtn.addEventListener('click', function () {
        if (currentIndex < cards.length - 1) {
            goToSlide(currentIndex + 1);
        }
    });

    prevBtn.addEventListener('click', function () {
        if (currentIndex > 0) {
            goToSlide(currentIndex - 1);
        }
    });

    // Update bullets on scroll (for manual scroll)
    slider.addEventListener('scroll', function () {
        const idx = Math.round(slider.scrollLeft / cardWidth);
        if (idx !== currentIndex) {
            currentIndex = idx;
            updateBullets(currentIndex);
        }
    });

    let isDown = false;
    let startX;
    let scrollLeft;
    slider.addEventListener('mousedown', (e) => {
        isDown = true;
        slider.classList.add('dragging');
        startX = e.pageX - slider.offsetLeft;
        scrollLeft = slider.scrollLeft;
    });
    slider.addEventListener('mouseleave', () => {
        isDown = false;
        slider.classList.remove('dragging');
    });
    slider.addEventListener('mouseup', () => {
        isDown = false;
        slider.classList.remove('dragging');
    });
    slider.addEventListener('mousemove', (e) => {
        if (!isDown) return;
        e.preventDefault();
        const x = e.pageX - slider.offsetLeft;
        const walk = (x - startX);
        slider.scrollLeft = scrollLeft - walk;
    });

    slider.addEventListener('touchstart', (e) => {
        isDown = true;
        startX = e.touches[0].pageX - slider.offsetLeft;
        scrollLeft = slider.scrollLeft;
    });
    slider.addEventListener('touchend', () => {
        isDown = false;
    });
    slider.addEventListener('touchmove', (e) => {
        if (!isDown) return;
        const x = e.touches[0].pageX - slider.offsetLeft;
        const walk = (x - startX);
        slider.scrollLeft = scrollLeft - walk;
    });
});
