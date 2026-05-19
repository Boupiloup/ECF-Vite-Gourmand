const filterButton = document.getElementById('filterToggle');
const filtersMenu = document.getElementById('filterForm');

filterButton.addEventListener('click', function () {
    filtersMenu.classList.toggle('active');
});