document.addEventListener('DOMContentLoaded', function () {
    const menu = document.querySelector('.hclm-mobile-menu');
    const menuContent = document.querySelector('.hclm-mobile-menu-content');
    const btn = document.querySelector('.hclm-mobile-menu-btn');
    const subMenuToggles = document.querySelectorAll('.hclm-mobile-menu-list > li.menu-item-has-children > a');

    // Toggle the mobile menu visibility
    btn.addEventListener('click', function () {
        menu.classList.toggle('active');
    });

    // Close the menu when clicking outside of it
    document.addEventListener('click', function (e) {
        if (
            menu.classList.contains('active') &&
            !menuContent.contains(e.target) &&
            !btn.contains(e.target)
        ) {
            menu.classList.remove('active');
        }
    });

    // Close the menu when scrolling outside of it
    document.addEventListener('scroll', function (e) {
        if (
            menu.classList.contains('active') &&
            !menuContent.contains(e.target)
        ) {
            menu.classList.remove('active');
        }
    }, true);

    // Add click event to submenu toggles
    subMenuToggles.forEach(link => {
        link.addEventListener('click', function (e) {
            e.preventDefault();
            const parentLi = this.parentElement;
            parentLi.classList.toggle('open');
        });
    });

    // Add an icon to each submenu
    function createSubmenuIcon() {
        const svg = document.createElementNS("http://www.w3.org/2000/svg", "svg");
        svg.setAttribute("viewBox", "0 0 24 24");
        svg.setAttribute("fill", "none");
        svg.setAttribute("height", "22");
        svg.setAttribute("width", "22");
        svg.classList.add("hclm-submenu-icon");
        svg.innerHTML = '<path d="M7 10L12 15L17 10" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>';
        return svg;
    }
    subMenuToggles.forEach(link => {
        link.appendChild(createSubmenuIcon());
    });
});