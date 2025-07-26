document.addEventListener('DOMContentLoaded', function () {
    const swiper = new Swiper('.hclm-events-slider', {
        slidesPerView: 1,
        spaceBetween: 16,
        loop: false,
        navigation: {
            nextEl: '.swiper-button-next',
            prevEl: '.swiper-button-prev',
        },
        pagination: {
            el: '.swiper-pagination',
            clickable: true,
            bulletClass: 'swiper-pagination-bullet',
            bulletActiveClass: 'swiper-pagination-bullet-active',
        },
        breakpoints: {
            600: {
                slidesPerView: 2,
            },
            1024: {
                slidesPerView: 3,
            }
        }
    });
});
