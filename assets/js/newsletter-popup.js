document.addEventListener('DOMContentLoaded', function () {
    const cards = document.querySelectorAll('.newsletter-card');
    const popup = document.getElementById('newsletter-popup');
    const closeBtn = document.querySelector('.popup-close');
    const overlay = document.querySelector('.popup-overlay');
    const title = document.getElementById('newsletter-title');

    const canvas1 = document.getElementById('pdf-page1');
    const canvas2 = document.getElementById('pdf-page2');

    pdfjsLib.GlobalWorkerOptions.workerSrc = 'https://cdnjs.cloudflare.com/ajax/libs/pdf.js/3.11.174/pdf.worker.min.js';

    cards.forEach(card => {
        card.addEventListener('click', () => {
            const summaryUrl = card.getAttribute('data-summary-url');
            const newsletterNumber = card.getAttribute('data-newsletter-number');
            title.textContent = `Bulletin n°${newsletterNumber}`;
            popup.classList.remove('hidden');
            
            pdfjsLib.getDocument(summaryUrl).promise.then(pdf => {
                const renderPage = (pageNum, canvas) => {
                    pdf.getPage(pageNum).then(page => {
                        const context = canvas.getContext('2d');
                        const viewport = page.getViewport({ scale: 1.5 });

                        canvas.width = viewport.width;
                        canvas.height = viewport.height;

                        page.render({ canvasContext: context, viewport: viewport });
                    });
                };

                renderPage(1, canvas1);
                renderPage(2, canvas2);
            });
        });
    });

    const closePopup = () => {
        popup.classList.add('hidden');
        canvas1.getContext('2d').clearRect(0, 0, canvas1.width, canvas1.height);
        canvas2.getContext('2d').clearRect(0, 0, canvas2.width, canvas2.height);
    };

    closeBtn.addEventListener('click', closePopup);
    overlay.addEventListener('click', closePopup);
});
