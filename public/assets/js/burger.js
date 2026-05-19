const burgerButton = document.getElementById('burgerButton');
const siteNav = document.getElementById('site-nav');

burgerButton.addEventListener('click', () => {
    siteNav.classList.toggle('active');
});
