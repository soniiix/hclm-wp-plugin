document.addEventListener('DOMContentLoaded', function () {
    const tabs = document.querySelectorAll('.sidebar li');
    const sections = document.querySelectorAll('.tab-content');

    tabs.forEach(tab => {
        tab.addEventListener('click', () => {
            const target = tab.getAttribute('data-tab');

            tabs.forEach(tab => {
                tab.classList.toggle('active', tab.getAttribute('data-tab') === target);
            });

            sections.forEach(section => {
                section.classList.toggle('active', section.id === target);
            });
        });
    });
});
